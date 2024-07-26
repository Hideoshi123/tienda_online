<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'category_id' => ['numeric', 'exists:categories,id'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'stock' => ['required', 'numeric'],
        ];

        if ($this->method() == 'POST') {
            array_push($rules['name'], 'unique:products,name');
        } else {
            array_push($rules['name'], 'unique:products,name,' . $this->product->id);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.numeric' => 'La categoría debe ser un número',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'name.unique' => 'El nombre del producto ya existe',
            'description.required' => 'La descripción es requerida',
            'description.string' => 'La descripción debe ser una cadena de caracteres',

            'stock.required' => 'El stock es requerido',
            'stock.numeric' => 'El stock debe ser un número',
        ];
    }
}
