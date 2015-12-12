<?php namespace hdphp\cookie;

class Cookie
{
    public function get($name)
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
    }

    public function all()
    {
        return $_COOKIE;
    }

    public function set($name, $value, $expire = 0, $path = '/', $domain = null)
    {
        $expire = $expire ? time() + $expire : $expire;
        setcookie($name, $value, $expire, $path, $domain);
    }

    public function del($name)
    {
        return setcookie($name, '', 1);
    }

    public function has($name)
    {
        return isset($_COOKIE[$name]);
    }

    public function flush()
    {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, '', 1);
        }

    }
}