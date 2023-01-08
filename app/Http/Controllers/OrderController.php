<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderByStatus($shopSlug, $statusSlug)
    {
        $status = Status::where('slug', $statusSlug)->first();
        $shop = Shop::where('slug', $shopSlug)->first();

        $orders = Order::with(['products', 'status', 'user', 'shop'])
            ->where('shop_id', $shop->id)
            ->where('status_id', $status->id)
            ->get();

        $status = (new SalesController)->getStatusExceptInCart();
        $salesFilter = (new SalesController)->salesFilter;

        return view('index', compact('orders', 'status', 'salesFilter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_id' => ['required'],
        ]);
        $order = Order::findOrFail($id);
        $order->status_id = $request->status_id;
        $order->save();

        return redirect()->back()->withSuccess('Order has been updated successfully.');
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required'],
        ]);
        $order = Order::findOrFail($request->id);
        $order->delete();
        return redirect()->back()->withSuccess('Order deleted.');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'product_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $user = User::find($request->user_id);
        $order = Order::where('user_id', $user->id)->where('isActive', 0)->first();
        $statusInCart = Status::where('name', 'In Cart')->first();
        $product = Product::find($request->product_id);

        if (!isset($order)) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = $user->shop_id;
            $order->status_id = $statusInCart->id;
            $order->save();
        }

        $order->attach($product->id, [
            'quantity' => $request->quantity,
            'price' => $product->price,
            'total' => $request->quantity * $product->price
        ]);

        return response()->json();
    }
}
