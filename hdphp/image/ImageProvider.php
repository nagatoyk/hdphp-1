<?php namespace hdphp\image;

use hdphp\kernel\ServiceProvider;

class ImageProvider extends ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{
	}

	public function register()
	{
		$this->app->single('Image',function($app)
		{
			return new \Hdphp\Image\Image($app);
		});
	}
}