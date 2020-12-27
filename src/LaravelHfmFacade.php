<?php

namespace AnatolieGhebea\LaravelHfm;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AnatolieGhebea\LaravelHfm\LaravelHfm
 */
class LaravelHfmFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-hfm';
    }
}
