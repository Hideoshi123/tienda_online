<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'number_id' => ['required', 'numeric'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['confirmed', 'string', 'min:8'],
        ];

        if ($this->method() == 'POST') {
            $rules['number_id'][] = 'unique:users,number_id';
            $rules['email'][] = 'unique:users,email';
            $rules['password'][] = 'required';
        } else {
            $rules['number_id'][] = 'unique:users,number_id,' . $this->user->id;
            $rules['email'][] = 'unique:users,email,' . $this->user->id;
            $rules['password'][] = 'nullable';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser válido',
            'last_name.required' => 'El apellido es requerido',
            'last_name.string' => 'El apellido debe ser válido',
            'number_id.required' => 'El número de identificación es requerido',
            'number_id.numeric' => 'El número de identificación debe ser numérico',
            'number_id.unique' => 'El número de identificación ya está en uso',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'El correo electrónico ya está en uso',
            'password.confirmed' => 'La contraseña no coincide con la confirmación',
            'password.string' => 'La contraseña debe ser válida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.required' => 'La contraseña es requerida en el registro',
            'password.nullable' => 'La contraseña es opcional en la actualización',
        ];
    }
}
