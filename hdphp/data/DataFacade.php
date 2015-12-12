<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class DataFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Data';
    }
}