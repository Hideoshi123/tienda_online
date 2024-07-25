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
			'address' => ['required', 'string'],
            'phone_number' => ['required', 'numeric', 'digits_between:10,15'],
			'password' => ['confirmed', 'string', 'min:8'],
		];

		if ($this->method() == 'POST') {
			array_push($rules['number_id'], 'unique:users,number_id');
			array_push($rules['email'], 'unique:users,email');
			array_push($rules['password'], 'required');
			array_push($rules['address'], 'required');
			array_push($rules['phone_number'], 'required');
		} else {
			array_push($rules['number_id'], 'unique:users,number_id,' . $this->user->id);
			array_push($rules['email'], 'unique:users,email,' . $this->user->id);
			array_push($rules['password'], 'nullable');
		}

		/*if ($this->path() != 'api/register') {
			$rules['role'] = ['required', 'string', 'in:user,admin,librarian'];
		}*/

		return $rules;
	}

	public function messages()
	{
		return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de caracteres',
            'last_name.required' => 'El apellido es requerido',
            'last_name.string' => 'El apellido debe ser una cadena de caracteres',
            'number_id.required' => 'El número de identificación es requerido',
            'number_id.numeric' => 'El número de identificación debe ser numérico',
            'number_id.unique' => 'El número de identificación ya está en uso',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'El correo electrónico ya está en uso',
            'address.required' => 'La dirección es requerida',
            'address.string' => 'La dirección debe ser una cadena de caracteres',
            'phone_number.required' => 'El número de teléfono es requerido',
            'phone_number.numeric' => 'El número de teléfono debe ser numérico',
            'phone_number.digits_between' => 'El número de teléfono debe tener entre 10 y 15 dígitos',
            'password.confirmed' => 'La contraseña no coincide con la confirmación',
            'password.string' => 'La contraseña debe ser una cadena de caracteres',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.required' => 'La contraseña es requerida en el registro',
            'password.nullable' => 'La contraseña es opcional en la actualización',
        ];
	}
}
