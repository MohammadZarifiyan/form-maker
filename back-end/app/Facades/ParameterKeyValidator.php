<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ParameterKeyValidator extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \App\Classes\ParameterKeyValidator::class;
    }
}
