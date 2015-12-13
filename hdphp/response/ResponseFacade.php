<?php namespace hdphp\response;

use hdphp\kernel\ServiceFacade;

class ResponseFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Response';
    }
}