<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'host' => ['bail', 'required', 'ipv4'],
            'port' => ['bail', 'required', 'integer', 'between:1,16777215'],
            'database' => ['bail', 'required', 'string'],
            'username' => ['bail', 'required', 'string'],
            'password' => ['bail', 'nullable', 'string'],
        ];
    }
}
