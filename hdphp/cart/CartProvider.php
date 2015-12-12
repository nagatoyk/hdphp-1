<?php namespace hdphp\cart;

use hdphp\kernel\ServiceProvider;

class CartProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Cart',
            function ($app)
            {
                return new Cart($app);
            }
        );
    }
}