<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class ErrorFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Error';
    }
}