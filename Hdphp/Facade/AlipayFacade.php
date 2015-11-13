<?php namespace Hdphp\Facade;

use Hdphp\Kernel\Facade;

class AlipayFacade extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'Alipay';
	}
}