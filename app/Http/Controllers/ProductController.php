<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)->get();

        return view('admin.product.index', compact('products'));
    }
}
