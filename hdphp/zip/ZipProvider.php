<?php namespace hdphp\zip;

use hdphp\kernel\ServiceProvider;

class ZipProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function register ()
    {
        $this->app->single (
            'Zip',
            function ()
            {
                return new \Hdphp\Zip\PclZip();
            }
        );
    }
}