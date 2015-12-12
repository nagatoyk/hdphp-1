<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class ImageFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Image';
	}
}