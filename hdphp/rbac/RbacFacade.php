<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class RbacFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Rbac';
	}
}