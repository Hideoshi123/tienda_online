<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
	public function show()
    {
        return view('carts.index');
    }

	public function edit($cartId)
    {
        $cart = CartProduct::with(['product.file']) // Incluye la relación product y file
                       ->where('cart_id', $cartId)
                       ->get();
    	return response()->json($cart);
    }

	public function getCartQuantity($cartId)
    {
        // Lógica para obtener la cantidad total de productos en el carrito con el ID proporcionado
        $quantity = CartProduct::where('cart_id', $cartId)->sum('quantity');
        return response()->json(['quantity' => $quantity]);
    }
}
