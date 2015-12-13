<?php namespace System\Service\Form;

use Hdphp\Kernel\ServiceProvider;

class FormServiceProvider extends ServiceProvider{

    //延迟加载
    public $defer=true;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->single('Form',function($app)
        {
            return new \System\Service\Form\Form($app);
        });
    }
}