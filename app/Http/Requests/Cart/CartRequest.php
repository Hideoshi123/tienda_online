<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
        ];

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
        ];
    }
}
