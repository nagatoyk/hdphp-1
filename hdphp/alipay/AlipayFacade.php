<?php namespace hdphp\alipay;

use hdphp\kernel\ServiceFacade;

class AlipayFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Alipay';
	}
}