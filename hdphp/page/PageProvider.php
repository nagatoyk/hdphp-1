<?php namespace hdphp\page;

use hdphp\kernel\ServiceProvider;

class PageProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Page',
            function ($app)
            {
                return new Page($app);
            },
            true
        );
    }
}