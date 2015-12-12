<?php namespace hdphp\url;

use hdphp\kernel\ServiceProvider;

class UrlProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot ()
    {

    }

    public function register ()
    {
        $this->app->single (
            'Url',
            function ($app)
            {
                return new Url($app);
            },
            true
        );
    }
}