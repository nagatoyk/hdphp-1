<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class CookieFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Cookie';
    }
}