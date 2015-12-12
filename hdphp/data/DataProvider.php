<?php namespace hdphp\data;

use hdphp\kernel\ServiceProvider;

class DataProvider extends ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{
	}

	public function register()
	{
		$this->app->single('Data',function($app)
		{
			return new Data($app);
		});
	}
}