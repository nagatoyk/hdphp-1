<?php namespace Hdphp\lang;

use hdphp\kernel\ServiceFacade;

class LangFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Lang';
	}
}