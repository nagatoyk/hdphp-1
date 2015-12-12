<?php namespace hdphp\cache;

use hdphp\kernel\ServiceFacade;

class Cache extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Cache';
	}
}