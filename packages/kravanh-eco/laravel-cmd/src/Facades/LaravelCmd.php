<?php

namespace KravanhEco\LaravelCmd\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelCmd extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-cmd';
    }
}
