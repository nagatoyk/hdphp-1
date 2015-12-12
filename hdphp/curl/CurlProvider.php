<?php namespace hdphp\curl;

use hdphp\kernel\ServiceProvider;

class CurlProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Curl',
            function ($app)
            {
                return new Curl($app);
            },
            true
        );
    }
}