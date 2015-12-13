<?php namespace hdphp\cache;

use hdphp\kernel\ServiceProvider;

/**
 * 缓存服务提供者
 * Class CacheServiceProvider
 *
 * @package Hdphp\Cache
 * @author  向军 <2300071698@qq.com>
 */
class CacheProvider extends ServiceProvider
{

    //延迟加载
    public $defer = false;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->single('Cache', function ($app)
        {
            return new Cache($app);
        }
        );
    }
}