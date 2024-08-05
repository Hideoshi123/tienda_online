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
	    // Obtener el producto y calcular el monto total
	    $product = Product::find($cartProduct->product_id);
	    if ($product) {
	        $product->stock -= $cartProduct->quantity;
	        $product->save();
	        // Calcular el total amount
	        $totalAmount = $product->price * $cartProduct->quantity;
	        // Obtener el carrito del usuario autenticado
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
	    // Calcular la diferencia en cantidad
	    $quantityDifference = $newQuantity - $oldQuantity;
	    // Ajustar el stock del producto
	    if ($quantityDifference > 0) {
	        if ($product->stock < $quantityDifference) {
	            return response()->json(['error' => 'No hay suficiente stock disponible.'], 400);
	        }
	        $product->stock -= $quantityDifference;
	    } else {
	        $product->stock -= $quantityDifference; // quantityDifference es negativo aquí, incrementando el stock
	    }
	    $product->save();
	    // Obtener el carrito del usuario autenticado
	    $cart = Cart::where('user_id', auth()->id())->first();
	    if (!$cart) {
	        return response()->json(['error' => 'Carrito no encontrado.'], 404);
	    }
	    // Calcular el cambio en el monto total del carrito
	    $totalAmountChange = $product->price * $quantityDifference;
	    $cart->total_amount += $totalAmountChange;
	    $cart->save();
	    // Actualizar el producto en el carrito
	    $cartProduct->update($request->all());
	    return response()->json([], 204);
	}

    public function destroy(Request $request, CartProduct $cartProduct)
	{
	    // Obtener el producto asociado
	    $product = $cartProduct->product; // Utiliza la relación en lugar de buscarlo de nuevo
	    // Obtener el carrito del usuario autenticado
	    $cart = Cart::where('user_id', auth()->id())->first();
	    if (!$cart) {
	        return response()->json(['error' => 'Carrito no encontrado.'], 404);
	    }
	    // Calcular el monto total del producto a eliminar
	    $totalAmountToRemove = $product->price * $cartProduct->quantity;
	    // Ajustar el monto total del carrito
	    $cart->total_amount -= $totalAmountToRemove;
	    $cart->save();
	    // Eliminar el registro del producto del carrito
	    $cartProduct->delete();
	    if (!$request->ajax()) {
	        return view();
	    }
	    return response()->json([], 204);
	}
}
