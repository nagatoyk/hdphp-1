<?php namespace hdphp\xml;

use hdphp\kernel\ServiceFacade;

class XmlFacade extends ServiceFacade
{
    public static function getFacadeAccessor ()
    {
        return 'Xml';
    }
}