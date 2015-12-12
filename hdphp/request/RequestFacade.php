<?php namespace hdphp\request;

use hdphp\kernel\ServiceFacade;

class RequestFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Request';
    }
}