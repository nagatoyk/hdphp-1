<?php namespace hdphp\xml;

use hdphp\kernel\ServiceProvider;

class XmlProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Xml',
            function ()
            {
                return new Xml();
            }
        );
    }
}