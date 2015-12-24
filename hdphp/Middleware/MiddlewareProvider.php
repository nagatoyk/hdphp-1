<?php namespace hdphp\middleware;

use hdphp\kernel\ServiceProvider;

class MiddlewareProvider extends ServiceProvider
{
    //延迟加载
    public $defer = false;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Middleware',
            function ($app)
            {
                return new Middleware($app);
            },
            true
        );
    }
}