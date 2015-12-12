<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class ValidateFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Validate';
    }
}