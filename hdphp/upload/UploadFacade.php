<?php namespace hdphp\upload;

use hdphp\kernel\ServiceFacade;

class UploadFacade extends ServiceFacade
{
	public static function getFacadeAccessor()
	{
		return 'Upload';
	}
}