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
            $order->shop_id = $user->shop_id;
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

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required'],
        ]);

        $user = $request->user();
        $product = Product::find($request->product_id);
        $statusInCart = Status::where('name', 'In Cart')->first();

        $order = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->with('products')
            ->first();

        $order->products()->detach($product->id);

        $order->total -= $product->price * $product->quantity;
        $order->save();

        $order = Order::with('products')->find($order->id);

        return response()->json(compact('order'));
    }

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

    public function checkout(Request $request)
    {
        $user = $request->user();
        $status = Status::all();
        $statusToBeProcessed = $status->where('name', 'To Be Process')->first();
        $statusInCart = $status->where('name', 'In Cart')->first();

        $order = Order::where('user_id', $user->id)
            ->where('status_id', $statusInCart->id)
            ->first();
        $order->status_id = $statusToBeProcessed;
        $order->save();

        return response()->json(compact('orders'));
    }

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
