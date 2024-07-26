<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartProduct\CartProductRequest;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class CartProductController extends Controller
{
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

        if (!$request->ajax()) return view();
        return response()->json(['cartProduct' => $cartProduct], 201);
    }

	public function update(CartProductRequest $request, CartProduct $cartProduct)
	{
	    // Obtener la cantidad anterior
	    $oldQuantity = $cartProduct->quantity;

	    // Obtener el producto
	    $product = Product::find($cartProduct->product_id);

	    // Calcular la diferencia en cantidad
	    $quantityDifference = $oldQuantity - $request->quantity;

		if ($quantityDifference > 0){
			$product->stock += $quantityDifference;
	        $product->save();
		} elseif ($quantityDifference < 0) {
			if ($product->stock === 0){
	            // Si el stock es cero y se intenta restar productos, devolver error
	            return response()->json(['error' => 'El producto está agotado.'], 400);
	        } else {
	            $product->stock += $quantityDifference;
	            $product->save();
	        }
		}

	    // Actualizar el cartProduct con la nueva cantidad
	    $cartProduct->update($request->all());

	    if (!$request->ajax()) return view();
	    return response()->json([], 204);
	}

    public function destroy(Request $request, CartProduct $cartProduct)
    {
        $cartProduct->delete();

        if (!$request->ajax()) return view();
        return response()->json([], 204);
    }
}
