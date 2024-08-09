<?php

namespace App\Http\Requests\User;

use App\Http\Requests\User\UserRequest;

class UserRegisterRequest extends UserRequest
{
    public function rules()
    {
        $this->rules['role'] = ['nullable', 'string', 'exists:roles,name'];
        return $this->rules;
    }
}
