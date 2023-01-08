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
            'user_id' => ['required'],
            'product_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $user = User::find($request->user_id);
        $order = Order::where('user_id', $user->id)->where('isActive', 0)->with('products')->first();
        $statusInCart = Status::where('name', 'In Cart')->first();
        $product = Product::find($request->product_id);
        $total = $request->quantity * $product->price;

        if (!isset($order)) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = $user->shop_id;
            $order->status_id = $statusInCart->id;
            $order->total = 0;
            $order->save();
            $order->with('products');
        }

        $order->products()->attach($product->id, [
            'quantity' => $request->quantity,
            'price' => $product->price,
            'total' => $total
        ]);

        $order->total += $total;
        $order->save();

        return response()->json(compact('order'));
    }

    public function viewOrders(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
        ]);
        $user = User::find($request->user_id);
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
        $request->validate([
            'user_id' => ['required'],
        ]);
        $user = User::find($request->user_id);

        $statusToBeProcessed = Status::where('name', 'To Be Process')->first();
        $order = Order::where('user_id', $user->id)->where('isActive', 0)->first();
        $order->status_id = $statusToBeProcessed;
        $order->save();

        return response()->json(compact('orders'));
    }
}
