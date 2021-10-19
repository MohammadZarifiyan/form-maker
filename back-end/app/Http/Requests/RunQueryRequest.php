<?php

namespace App\Http\Requests;

use App\Models\Query;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RunQueryRequest extends FormRequest
{
    public function __construct(public $runnable_query)
    {
        //
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        /*
         * Final result is like:
         * [
         *  'query_id' => [...]
         *  'values.key1' => [...]
         *  'values.key2' => [...]
         *  ...
         * ]
         */
        return array_merge(
            [
                'values' => ['bail', 'nullable', 'array'],
            ],
            $this->getSqlValuesRules(),
        );
    }

    /*
     * Rules for values that match in sql.
     */
    public function getSqlValuesRules(): array
    {
        $rules = [];

        foreach ($this->runnable_query->parameters as $parameter) {
            $name = 'values.' . $parameter['key'];

            $rules[$name] = [
                'bail',
                'required',
                $parameter['type'],
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->runnable_query->parameters as $parameter) {
            $name = 'values.' . $parameter['key'];

            $attributes[$name] = $parameter['title'];
        }

        return $attributes;
    }
}
