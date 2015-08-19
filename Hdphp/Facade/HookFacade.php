<?php namespace Hdphp\Facade;

use Hdphp\Kernel\Facade;

class HookFacade extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'Hook';
	}
}