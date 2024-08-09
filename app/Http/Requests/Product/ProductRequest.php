<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    protected $rules = [
        'category_id' => ['numeric', 'exists:categories,id'],
        'name' => ['required', 'string'],
        'description' => ['required', 'string'],
        'stock' => ['required', 'numeric', 'min:1'],
        'file' => ['required', 'image'],
        'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'] // Asegura que el precio sea decimal con 2 decimales
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = $this->rules;
        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'La categoría seleccionada no existe',
			'category_id.numeric' => 'La categoría es requerida',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'name.unique' => 'El nombre del producto ya existe',
            'description.required' => 'La descripción es requerida',
            'description.string' => 'La descripción debe ser una cadena de caracteres',
            'stock.required' => 'La cantidad es requerida',
            'stock.numeric' => 'La cantidad debe ser un número',
            'stock.min' => 'La cantidad debe ser al menos 1',
            'file.required' => 'La imagen es requerida.',
            'file.image' => 'El archivo debe de ser una imagen valida',
            'price.required' => 'El precio es requerido',
            'price.numeric' => 'El precio debe ser un número',
            'price.regex' => 'El precio debe positivo tener hasta 2 decimales',
        ];
    }
}
