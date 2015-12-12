<?php namespace hdphp\qq;

use hdphp\kernel\ServiceProvider;

class QqProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Qq',
            function ()
            {
                return new Qq();
            }
        );
    }
}