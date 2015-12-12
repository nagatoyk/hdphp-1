<?php namespace hdphp\config;

use hdphp\kernel\ServiceFacade;

class ConfigFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Config';
	}
}