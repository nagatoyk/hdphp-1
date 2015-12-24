<?php namespace hdphp\kernel;

use hdphp\kernel\ServiceFacade;

class AppFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'App';
    }
}