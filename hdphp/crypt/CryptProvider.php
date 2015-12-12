<?php namespace hdphp\crypt;

use hdphp\kernel\ServiceProvider;

class CryptProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Crypt',
            function ($app)
            {
                return new Crypt($app);
            },
            true
        );
    }
}