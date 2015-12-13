<?php namespace hdphp\view;

use hdphp\kernel\ServiceFacade;

class ViewFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'View';
    }
}