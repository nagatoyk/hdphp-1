<?php namespace Hdphp\Zip;

use Hdphp\Zip\Zip;
use Hdphp\Kernel\ServiceProvider;

class ZipServiceProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->single('Zip', function (){
            return new \Hdphp\Zip\Zip();
        });
    }
}