<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)->get();

        return view('admin.product.index', compact('products'));
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.product.edit', compact('product'));
    }


    public function add()
    {
        return view('admin.product.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($request->id) {
            $product = Product::find($request->id);
        } else {
            $product = new Product();
            $product->shop_id = Auth::user()->shop_id;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $file = $request->file('image');
            $path = 'images/' . $product->id;
            $path = Storage::disk('public')->put($path, $file);
            $product->image = $path;
        }

        $product->save();

        return redirect()->route('product.edit', ['id' => $product->id]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $product = Product::findOrFail($request->id);
        $product->delete();
        return redirect()->route('product.index');
    }
}
