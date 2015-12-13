<?php namespace hdphp\db;

//配置项处理
class Db{
	
	protected $link=array();
	
	public function __construct($app)
	{
	}

	/**
	 * @param $method
	 * @param $params
	 *
	 * @return mixed
	 */
	public function __call($method,$params)
	{

		$driver ='\hdphp\db\\'.ucfirst(Config::get('database.read.driver'));
		
		$instance = new $driver;
		
		return call_user_func_array(array($instance,$method), $params);
	}
	
}