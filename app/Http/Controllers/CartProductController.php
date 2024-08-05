<?php

namespace App\Http\Controllers;

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

        // Decrementar el stock del producto
        $product = Product::find($cartProduct->product_id);
        if ($product) {
            $product->stock -= $cartProduct->quantity;
            $product->save();
        }

        return response()->json(['cartProduct' => $cartProduct], 201);
    }

    public function update(CartProductRequest $request, CartProduct $cartProduct)
    {
        $oldQuantity = $cartProduct->quantity;
        $product = Product::find($cartProduct->product_id);
        $quantityDifference = $oldQuantity - $request->quantity;

        if ($quantityDifference > 0){
            $product->stock += $quantityDifference;
            $product->save();
        } elseif ($quantityDifference < 0) {
            if ($product->stock === 0){
                return response()->json(['error' => 'El producto está agotado.'], 400);
            } else {
                $product->stock += $quantityDifference;
                $product->save();
            }
        }

        $cartProduct->update($request->all());

        return response()->json([], 204);
    }

    public function destroy(Request $request, CartProduct $cartProduct)
    {
        $cartProduct->delete();

        if (!$request->ajax()) return view();
        return response()->json([], 204);
    }
}
