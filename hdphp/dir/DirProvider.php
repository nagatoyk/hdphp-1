<?php namespace hdphp\dir;

use hdphp\kernel\ServiceProvider;

class DirProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Dir',
            function ($app)
            {
                return new Dir($app);
            }
        );
    }
}