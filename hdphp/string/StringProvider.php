<?php namespace hdphp\string;

use hdphp\kernel\ServiceProvider;

class StringProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'String',
            function ($app)
            {
                return new String($app);
            }
        );
    }
}