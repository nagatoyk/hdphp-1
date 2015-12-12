<?php namespace hdphp\facade;

use hdphp\kernel\ServiceFacade;

class MailFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Mail';
	}
}