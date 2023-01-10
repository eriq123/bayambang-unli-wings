<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderByStatus($shopSlug, $statusSlug)
    {
        $status = Status::where('slug', $statusSlug)->first();
        $shop = Shop::where('slug', $shopSlug)->first();

        if (!isset($status) || !isset($shop)) return redirect('/');

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
            'status_id' => 'required',
        ]);
        $order = Order::findOrFail($id);
        $order->status_id = $request->status_id;
        $order->save();

        return redirect()->back()->withSuccess('Order has been updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $order = Order::findOrFail($request->id);
        $order->delete();
        return redirect()->back()->withSuccess('Order deleted.');
    }
}
