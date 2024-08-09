<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;

class CartController extends Controller
{
	public function show()
    {
        return view('carts.index');
    }

	public function edit($cartId)
    {
        $cart = CartProduct::with(['product.file'])
                       ->where('cart_id', $cartId)
                       ->get();
    	return response()->json($cart);
    }

	public function getCartQuantity($cartId)
    {
        $quantity = CartProduct::where('cart_id', $cartId)->sum('quantity');
        return response()->json(['quantity' => $quantity]);
    }
}
