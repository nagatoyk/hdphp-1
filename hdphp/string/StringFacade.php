<?php namespace hdphp\string;

use hdphp\kernel\ServiceFacade;

class StringFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'String';
	}
}