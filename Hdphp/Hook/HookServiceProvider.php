<?php namespace Hdphp\Hook;

use Hdphp\Hook\Hook;
use Hdphp\Kernel\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
	
	//延迟加载
	public $defer=false;

	public function boot()
	{
		
	}

	public function register()
	{
		$this->app->single('Hook',function ($app){
			return new Hook($app);
		},true);
	}
}