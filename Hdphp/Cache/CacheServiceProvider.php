<?php namespace Hdphp\Cache;

use Hdphp\Cache\Cache;
use Hdphp\Kernel\ServiceProvider;

class CacheServiceProvider extends ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{
	}

	public function register()
	{
		$this->app->single('Cache',function($app)
		{
			return new Cache($app);
		});
	}
}