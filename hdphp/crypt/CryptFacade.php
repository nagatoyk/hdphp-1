<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class CryptFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Crypt';
    }
}