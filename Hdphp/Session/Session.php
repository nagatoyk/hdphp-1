<?php namespace Hdphp\Session;

//URL处理类
class Session
{
	public function __construct()
	{
		$this->init();
		$driver = '\Hdphp\Session\\'.ucfirst(Config::get('session.driver'));
		$this->driver = new $driver();
	}

	//session初始
	private function init()
	{
		session_name(Config::get('session.name'));

		if($domain = Config::get('session.domain'))
		{
			ini_set('session.cookie_domain', $domain);
		}

		if($expire = Config::get('session.expire'))
		{
            session_set_cookie_params($expire);
		}
		//post存在session时使用post做为sessionid值
		if($session_id = Q(session_name()))
		{
			session_id($session_id);
		}
	}

	public function has($name)
	{
		return isset($_SESSION[$name]);
	}

	public function set($name,$value)
    {
    	return $_SESSION[$name]=$value;
    }

    public function get($name)
    {
    	return isset($_SESSION[$name])?$_SESSION[$name]:null;
    }

    public function del($name)
    {
    	if(isset($_SESSION[$name]))
    	{
    		unset($_SESSION[$name]);
    	}
    	return true;
    }

    public function all()
    {
    	return $_SESSION;
    }
    
    public function flush()
    {
    	session_unset();
    	session_destroy();
    }
    
	public function __call($method,$params)
	{
		return call_user_func_array(array(new $this->driver,$method), $params);
	}
	
}