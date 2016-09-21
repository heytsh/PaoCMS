<?php

namespace PAO\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Cookie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cookie';
    }
}
