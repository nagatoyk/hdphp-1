<?php namespace hdphp\zip;

use hdphp\kernel\ServiceFacade;

class ZipFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Zip';
    }
}