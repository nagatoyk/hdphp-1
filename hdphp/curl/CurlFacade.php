<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class CurlFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Curl';
	}
}