<?php namespace Hdphp\Cache;

interface InterfaceCache{
	public function connect();
	public function set($name,$value,$expire);
	public function get($name);
	public function del($name);
	public function flush();
}