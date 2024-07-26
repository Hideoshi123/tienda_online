<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::get();
		if (!$request->ajax()) return view();
		return response()->json(['category' => $products], 200);
    }


    public function create()
    {
        // view
    }


    public function store(ProductRequest $request)
    {
        $product = new Product($request->all());
		$product->save();
		//if (!$request->ajax()) return back()->with('success', 'User created');
		return response()->json(['status' => 'Product created', 'product' => $product], 201);
    }


    /*public function show(Request $request, User $user)
    {
        if (!$request->ajax()) return view();
		return response()->json([], 204);
    }


    public function edit(Request $request, User $user)
    {
        if (!$request->ajax()) return view();
		return response()->json(['user' => $user], 200);
    }*/


    public function update(ProductRequest $request, Product $product)
    {
		$product->update($request->all());
		//$user->syncRoles([$request->role]);
		if (!$request->ajax()) return view();
		return response()->json([], 204);
    }


    public function destroy(Request $request, Product $product)
    {
        $product->delete();
		if (!$request->ajax()) return back()->with('success', 'User deleted');
		return response()->json([], 204);
    }
}
