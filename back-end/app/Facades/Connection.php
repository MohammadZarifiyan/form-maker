<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Connection extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \App\Classes\Connection::class;
    }
}
