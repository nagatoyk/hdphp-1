<?php namespace hdphp\upload;

use hdphp\kernel\ServiceProvider;

class UploadProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Upload',
            function ($app)
            {
                return new Upload($app);
            }
        );
    }
}