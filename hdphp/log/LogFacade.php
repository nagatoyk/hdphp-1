<?php namespace hdphp\log;

use hdphp\kernel\ServiceFacade;

class LogFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Log';
    }
}