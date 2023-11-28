<?php

namespace Keasy9\Localize\Facades;

use Illuminate\Support\Facades\Facade;

class Localize extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Keasy9\Localize\Services\Localize::class;
    }
}
