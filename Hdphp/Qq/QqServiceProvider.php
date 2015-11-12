<?php namespace Hdphp\Qq;

use Hdphp\Kernel\ServiceProvider;

class QqServiceProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->single('Qq', function () {
            return new \Hdphp\Qq\Qq();
        });
    }
}