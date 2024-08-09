<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Http\Requests\CartProduct\CartProductRequest;

class CartProductController extends Controller
{
	public function checkCartItem(Request $request)
    {
        $item = CartProduct::where('cart_id', $request->cart_id)
                            ->where('product_id', $request->product_id)
                            ->first();
        return response()->json(['item' => $item]);
    }

    public function store(CartProductRequest $request)
	{
	    $cartProduct = new CartProduct($request->all());
	    $cartProduct->save();
	    $product = Product::find($cartProduct->product_id);
	    if ($product) {
	        $product->stock -= $cartProduct->quantity;
	        $product->save();
	        $totalAmount = $product->price * $cartProduct->quantity;
	        $cart = Cart::where('user_id', auth()->id())->first();
	        if ($cart) {
	            $cart->total_amount += $totalAmount;
	            $cart->save();
	        }
	    }
	    return response()->json(['cartProduct' => $cartProduct], 201);
	}

    public function update(CartProductRequest $request, CartProduct $cartProduct)
	{
	    $oldQuantity = $cartProduct->quantity;
	    $newQuantity = $request->quantity;
	    $product = Product::find($cartProduct->product_id);
	    if (!$product) {
	        return response()->json(['error' => 'Producto no encontrado.'], 404);
	    }
	    $quantityDifference = $newQuantity - $oldQuantity;
	    if ($quantityDifference > 0) {
	        if ($product->stock < $quantityDifference) {
	            return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
	        }
	        $product->stock -= $quantityDifference;
	    } else {
	        $product->stock -= $quantityDifference;
	    }
	    $product->save();
	    $cart = Cart::where('user_id', auth()->id())->first();
	    if (!$cart) {
	        return response()->json(['error' => 'Carrito no encontrado.'], 404);
	    }
	    $totalAmountChange = $product->price * $quantityDifference;
	    $cart->total_amount += $totalAmountChange;
	    $cart->save();
	    $cartProduct->update($request->all());
	    return response()->json([], 204);
	}

    public function destroy(CartProduct $cartProduct)
	{
	    $product = $cartProduct->product;
	    $cart = Cart::where('user_id', auth()->id())->first();
	    if (!$cart) {
	        return response()->json(['error' => 'Carrito no encontrado.'], 404);
	    }
	    $totalAmountToRemove = $product->price * $cartProduct->quantity;
	    $cart->total_amount -= $totalAmountToRemove;
	    $cart->save();
	    $cartProduct->delete();
	    return response()->json([], 204);
	}
}
