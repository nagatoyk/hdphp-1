<?php namespace Hdphp\Backup;

use Hdphp\Kernel\ServiceProvider;

class BackupServiceProvider extends ServiceProvider{
	
	//延迟加载
	public $defer=true;

	public function boot()
	{
	}

	public function register()
	{
		$this->app->single('Backup',function($app)
		{
			return new \Hdphp\Backup\Backup($app);
		});
	}
}