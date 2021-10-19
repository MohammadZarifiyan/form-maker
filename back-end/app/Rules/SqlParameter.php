<?php

namespace App\Rules;

use App\Facades\ParameterKeyValidator;
use Illuminate\Contracts\Validation\Rule;

class SqlParameter implements Rule
{
    public function __construct(public string $sqlAttributeName)
    {
        //
    }

    public function passes($attribute, $value)
    {
        return ParameterKeyValidator::setSql(
            request($this->sqlAttributeName)
        )->verifyParameterKey($value);
    }

    public function message()
    {
        return trans('validation.sql-parameter');
    }
}
