<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *      path="/cart/add",
     *      operationId="addToCart",
     *      tags={"Cart"},
     *      security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={
     *                  "product_id",
     *                  "quantity",
     *              },
     *              @OA\Property(property="product_id", type="integer", format="integer", example="1"),
     *              @OA\Property(property="quantity", type="integer", format="integer", example="1"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="order", type="object", description="Order object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $user = $request->user();
        $statusInCart = Status::where('name', 'In Cart')->first();

        $order = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->with('products')
            ->first();

        $product = Product::find($request->product_id);
        $total = $request->quantity * $product->price;

        if (!$order) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = $product->shop_id;
            $order->status_id = $statusInCart->id;
            $order->total = 0;
            $order->save();
        }

        if ($order->products()->where('product_id', $product->id)->exists()) {
            $this->removeFromCart($request);
        }

        $order->products()->attach($product->id, [
            'quantity' => $request->quantity,
            'price' => $product->price,
            'total' => $total
        ]);

        $order->total += $total;
        $order->save();
        $order = Order::with('products')->find($order->id);

        return response()->json(compact('order'));
    }

    /**
     * @OA\Post(
     *      path="/cart/remove",
     *      operationId="removeFromCart",
     *      tags={"Cart"},
     *      security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={
     *                  "product_id",
     *              },
     *              @OA\Property(property="product_id", type="integer", format="integer", example="1"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="order", type="object", description="Order object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required'],
        ]);

        $user = $request->user();
        $statusInCart = Status::where('name', 'In Cart')->first();

        $order = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->with('products')
            ->first();

        $product = $order->products()->find($request->product_id);

        $order->products()->detach($product->id);
        $order->total -= $product->pivot->total;
        $order->save();

        $order = Order::with('products')->find($order->id);
        return response()->json(compact('order'));
    }

    /**
     * @OA\Post(
     *      path="/orders/view",
     *      operationId="viewOrders",
     *      tags={"Order"},
     *      security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="delivered", type="boolean", format="boolean", example="true"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="orders", type="object", description="Order object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function viewOrders(Request $request)
    {
        $user = $request->user();
        $status = Status::where('name', '!=', 'In Cart')->get();

        if ($request->delivered) {
            $status = $status->where('name', 'Delivered');
        } else {
            $status = $status->where('name', '!=', 'Delivered');
        }

        $orders = Order::where('user_id', $user->id)
            ->whereIn('status_id', $status->pluck('id'))
            ->with('products')
            ->get();

        return response()->json(compact('orders'));
    }

    /**
     * @OA\Post(
     *      path="/cart/checkout",
     *      operationId="checkoutOrder",
     *      tags={"Cart"},
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="orders", type="object", description="Array of orders."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function checkout(Request $request)
    {
        $user = $request->user();
        $status = Status::all();
        $statusToBeProcessed = $status->where('name', 'To Be Process')->first();
        $statusInCart = $status->where('name', 'In Cart')->first();

        $cartOrder = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->first();

        $productsByShop = $cartOrder->products->groupBy('shop_id');

        foreach ($productsByShop as $k => $shop) {
            $newOrder = new Order();
            $newOrder->user_id = $user->id;
            $newOrder->shop_id = $shop[0]->shop_id;
            $newOrder->status_id = $statusToBeProcessed->id;
            $newOrder->total = 0;
            $newOrder->save();

            foreach ($shop as $product) {
                $newOrder->products()->attach($product->id, [
                    'shop_id' => $product->shop_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'total' => $product->total
                ]);
                $newOrder->total += $product->total;
                $newOrder->save();

                $cartOrder->products()->detach($product->id);
                $cartOrder->total -= $product->total;
                $cartOrder->save();
            }
        }

        $orders = Order::where('user_id', $user->id)->get();
        return response()->json(compact('orders'));
    }

    /**
     * @OA\Get(
     *      path="/cart/view",
     *      operationId="viewCart",
     *      tags={"Cart"},
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="order", type="object", description="Order object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function viewCart(Request $request)
    {
        $user = $request->user();
        $statusInCart = Status::where('name', 'In Cart')->first();

        $order = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->with('products')
            ->get();

        return response()->json(compact('order'));
    }
}
