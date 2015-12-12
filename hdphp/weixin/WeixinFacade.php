<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class WeixinFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Weixin';
    }
}