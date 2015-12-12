<?php namespace hdphp\kernel;

class Boot
{

    public static function bootstrap ()
    {
        define ('IS_CGI', substr (PHP_SAPI, 0, 3) == 'cgi' ? true : false);
        define ('IS_WIN', strstr (PHP_OS, 'WIN') ? true : false);
        define ('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        define ('DS', DIRECTORY_SEPARATOR);
        define ('IS_GET', $_SERVER['REQUEST_METHOD'] == 'GET');
        define ('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST');
        define ('IS_DELETE', $_SERVER['REQUEST_METHOD'] == 'DELETE' ?: (isset($_POST['_method']) && $_POST['_method'] == 'DELETE'));
        define ('IS_PUT', $_SERVER['REQUEST_METHOD'] == 'PUT' ?: (isset($_POST['_method']) && $_POST['_method'] == 'PUT'));
        define (
        'IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower ($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        );

        define ('NOW', $_SERVER['REQUEST_TIME']);
        define ('NOW_MICROTIME', microtime (true));
        // 系统信息
        @ini_set ('memory_limit', '128M');
        @ini_set ('register_globals', 'off');
        define ('__ROOT__', rtrim ('http://'.$_SERVER['HTTP_HOST'].preg_replace ('@\w+\.php$@i', '', $_SERVER['SCRIPT_NAME']), '/'));
        define ('__URL__', 'http://'.$_SERVER['HTTP_HOST'].'/'.trim ($_SERVER['REQUEST_URI'], '/'));
        define ("__HISTORY__", isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null);
        if (version_compare (PHP_VERSION, '5.4.0', '<'))
        {
            //对外部引入文件禁止加转义符
            ini_set ('magic_quotes_runtime', 0);
            //删除系统自动加的转义符号
            if (get_magic_quotes_gpc ())
            {
                self::unaddslashes ($_POST);
                self::unaddslashes ($_COOKIE);
                self::unaddslashes ($_GET);
            }
        }
    }

    //反转义$_POST
    private static function unaddslashes (&$data)
    {
        foreach ((array)$data as $k => $v)
        {
            if (is_numeric ($v))
            {
                $data[$k] = $v;
            }
            else
            {
                $data[$k] = is_array ($v) ? self::unaddslashes ($v) : stripslashes ($v);
            }
        }

        return $data;
    }

}