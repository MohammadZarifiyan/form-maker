<?php

namespace App\Http\Requests;

use App\Rules\SqlParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QueryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sql' => ['bail', 'required', 'string', 'min:6'],
            'type' => ['bail', 'required', 'string', 'in:table,chart'],
            'parameters' => ['bail', 'nullable', 'array'],
            'parameters.*.key' => [
                'bail',
                'required',
                'string',
                'between:2,20',
                'regex:/(?!\d|_)([\w\d]+)/',
                'distinct',
                new SqlParameter('sql'),
            ],
            'parameters.*.title' => ['bail', 'required', 'string', 'between:2,20', 'distinct'],
            'parameters.*.type' => ['bail', 'required', 'string', 'in:string,boolean,integer'],
            'button_title' => ['bail', 'required', 'string', 'between:3,10'],
            'connection_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('connections', 'id')->where(fn ($query) => $query->where('user_id', '=', $this->user()->id)),
            ],
        ];
    }
}
