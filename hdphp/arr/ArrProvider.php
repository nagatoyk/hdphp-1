<?php namespace hdphp\arr;

use hdphp\kernel\ServiceProvider;

class ArrProvider extends ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{

	}

	public function register()
	{
		$this->app->single('Arr',function($app)
		{
			return new Arr($app);
		});
	}
}