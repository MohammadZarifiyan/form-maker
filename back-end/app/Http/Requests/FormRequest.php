<?php

namespace App\Http\Requests;

use App\Models\Query;
use Illuminate\Foundation\Http\FormRequest as FormBaseRequest;
use Illuminate\Validation\Rule;

class FormRequest extends FormBaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['bail', 'required', 'string', 'between:3,10'],
            'autoload' => ['bail', 'required', 'boolean'],
            'queries' => [
                'bail',
                'required',
                'array',
                'between:1,5',
                fn () => Query::whereRelation('connection', 'user_id', $this->user()->id)
                    ->when(
                        $this->boolean('autoload'),
                        fn ($query) => $query->whereNull('parameters')
                    )
                    ->exists(),
            ],
            'queries.*' => ['bail', 'integer'],
            'project_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('projects', 'id')->where(fn ($query) => $query->where('user_id', '=', $this->user()->id)),
            ],
        ];
    }
}
