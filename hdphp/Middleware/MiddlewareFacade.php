<?php namespace hdphp\middleware;

use hdphp\kernel\ServiceFacade;

class MiddlewareFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Middleware';
	}
}