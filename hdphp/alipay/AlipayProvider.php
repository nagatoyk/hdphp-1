<?php namespace hdphp\alipay;

class AlipayProvider extends \hdphp\kernel\ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{

	}

	public function register()
	{
		$this->app->single('Alipay',function($app)
		{
			return new Alipay($app);
		});
	}
}