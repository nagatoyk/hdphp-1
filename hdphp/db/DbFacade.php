<?php namespace hdphp\db;

use hdphp\kernel\ServiceFacade;

class DbFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Db';
    }
}