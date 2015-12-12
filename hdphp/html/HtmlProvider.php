<?php namespace hdphp\html;

use hdphp\kernel\ServiceProvider;

class HtmlProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Html',
            function ($app)
            {
                return new Html($app);
            }
        );
    }
}