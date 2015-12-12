<?php namespace hdphp\dir;

use hdphp\kernel\ServiceFacade;

class DirFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Dir';
    }
}