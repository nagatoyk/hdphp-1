<?php namespace hdphp\route;

class RouteFacade extends \hdphp\kernel\ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Route';
	}
}