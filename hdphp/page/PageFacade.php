<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class PageFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Page';
	}
}