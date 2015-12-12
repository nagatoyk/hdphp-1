<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class HtmlFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Html';
    }
}