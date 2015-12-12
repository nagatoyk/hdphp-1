<?php namespace Hdphp\Cart;

use hdphp\kernel\ServiceFacade;

class CartFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Cart';
	}
}