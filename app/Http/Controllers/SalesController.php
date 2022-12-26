<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index($slug = null)
    {
        if ($slug === null) return redirect(route('sales.index', ['slug' => 'daily']));

        $status = Status::all();
        $orders = Order::all();
        return view('admin.sales', compact('status', 'orders'));
    }
}
