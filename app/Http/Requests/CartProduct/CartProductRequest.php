<?php

namespace App\Http\Requests\CartProduct;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'cart_id' => ['required', 'numeric', 'exists:carts,id'],
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ];

		if ($this->isMethod('post')) {
            $rules['quantity'][] = function ($attribute, $value, $fail) {
                $product = Product::find($this->product_id);

                if ($product->stock === 0) {
                    $fail('El producto está agotado');
                    return;
                }

                if ($product->stock < $value) {
                    $fail('La cantidad solicitada supera la cantidad disponible del producto');
                }
            };
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cart_id.required' => 'El ID del carrito es requerido',
            'cart_id.numeric' => 'El ID del carrito debe ser un número',
            'cart_id.exists' => 'El carrito seleccionado no existe',
            'product_id.required' => 'El ID del producto es requerido',
            'product_id.numeric' => 'El ID del producto debe ser un número',
            'product_id.exists' => 'El producto seleccionado no existe',
            'quantity.required' => 'La cantidad es requerida',
            'quantity.numeric' => 'La cantidad debe ser un número',
            'quantity.min' => 'La cantidad debe ser al menos 1',
        ];
    }
}
