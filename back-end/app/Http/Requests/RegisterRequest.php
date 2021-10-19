<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['bail', 'required', 'string'],
            'email' => ['bail', 'required', 'email', 'unique:users'],
            'password' => ['bail', 'required', 'string', 'min:8'],
        ];
    }
}
