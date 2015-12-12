<?php namespace hdphp\session;

use hdphp\kernel\ServiceFacade;

class SessionFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Session';
    }
}