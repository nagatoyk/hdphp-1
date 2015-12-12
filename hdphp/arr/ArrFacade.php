<?php namespace hdphp\arr;

use hdphp\kernel\ServiceFacade;

class ArrFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Arr';
	}
}