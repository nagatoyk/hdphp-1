<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class ResponseFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Response';
    }
}