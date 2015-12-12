<?php namespace hdphp\view;

use hdphp\kernel\ServiceProvider;

class ViewProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->bind (
            'View',
            function ($app)
            {
                return new View($app);
            },
            true
        );
    }
}