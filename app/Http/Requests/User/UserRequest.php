<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

	protected $rules = [
		'number_id' => ['required', 'numeric'],
		'name' => ['required', 'string'],
		'last_name' => ['required', 'string'],
		'email' => ['required', 'email'],
		'password' => ['required', 'confirmed', 'string', 'min:8'],
		'file' => ['required', 'image'],
		'role' => ['required', 'string', 'exists:roles,name'],
	];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->Method() == 'POST') {
            // Reglas para creación
            $this->rules['number_id'][] = 'unique:users,number_id';
            $this->rules['email'][] = 'unique:users,email';
        } else {
            // Reglas para actualización
            $this->rules['number_id'][] = 'unique:users,number_id,' . $this->user->id;
            $this->rules['email'][] = 'unique:users,email,' . $this->user->id;
            $this->rules['password'] = ['nullable', 'confirmed', 'string', 'min:8'] ;
			$this->rules['role'] = ['nullable', 'string', 'exists:roles,name'];
            $this->rules['file'] = ['nullable', 'image'];
        }

        return $this->rules;
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
            'file.required' => 'La imagen es requerida.',
            'file.image' => 'El archivo debe ser una imagen válida',
            'role.required' => 'El rol es requerido',
            'role.string' => 'El rol debe ser una cadena válida',
            'role.exists' => 'El rol seleccionado no existe',
        ];
    }
}
