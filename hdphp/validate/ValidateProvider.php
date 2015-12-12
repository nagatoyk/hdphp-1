<?php namespace hdphp\validate;

use hdphp\kernel\ServiceProvider;

class ValidateProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
        $_SESSION['_validate'] = '';
    }

    public function register ()
    {
        $this->app->single (
            'Validate',
            function ()
            {
                return new Validate();
            }
        );
    }
}