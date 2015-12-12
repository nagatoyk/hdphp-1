<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class QqFacade extends ServiceFacade
{
    public static function getFacadeAccessor()
    {
        return 'Qq';
    }
}