<?php namespace Hdphp\hook;

use hdphp\kernel\ServiceProvider;

class HookProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot ()
    {
        $this->app['Error']->bootstrap ();
    }

    public function register ()
    {
        $this->app->single (
            'Hook',
            function ($app)
            {
                return new Hook($app);
            },
            true
        );
    }
}