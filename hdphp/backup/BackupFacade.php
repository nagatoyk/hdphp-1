<?php namespace hdphp\backup;

use hdphp\kernel\ServiceFacade;

class BackupFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Backup'; 
	}
}