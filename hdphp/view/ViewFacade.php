<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class ViewFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'View';
    }
}