<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class QueryRunner extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \App\Classes\QueryRunner::class;
    }
}
