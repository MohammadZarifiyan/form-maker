<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Database extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \App\Classes\Database::class;
    }
}
