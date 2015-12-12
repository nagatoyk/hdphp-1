<?php namespace hdphp\url;

use hdphp\kernel\ServiceFacade;

class UrlFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Url';
	}
}