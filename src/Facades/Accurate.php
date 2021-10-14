<?php

namespace Buatin\Accurate\Facades;

use Illuminate\Support\Facades\Facade;

class Accurate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'accurate';
    }
}
