<?php
define ('HDPHP_VERSION', '2.0.0');
define ('HDPHP_PATH', __DIR__);
define ('ROOT_PATH', dirname (HDPHP_PATH));
defined ('APP_PATH') or define ('APP_PATH', ROOT_PATH.'/app');
defined ("DEBUG") or define ("DEBUG", false);
define ('IS_CGI', substr (PHP_SAPI, 0, 3) == 'cgi' ? true : false);
define ('IS_WIN', strstr (PHP_OS, 'WIN') ? true : false);
define ('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define ('DS', DIRECTORY_SEPARATOR);
define ('IS_GET', $_SERVER['REQUEST_METHOD'] == 'GET');
define ('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST');
define ('IS_DELETE', $_SERVER['REQUEST_METHOD'] == 'DELETE' ?: (isset($_POST['_method']) && $_POST['_method'] == 'DELETE'));
define ('IS_PUT', $_SERVER['REQUEST_METHOD'] == 'PUT' ?: (isset($_POST['_method']) && $_POST['_method'] == 'PUT'));
define ('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower ($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
define ('NOW', $_SERVER['REQUEST_TIME']);
define ('NOW_MICROTIME', microtime (true));
define ('__ROOT__', rtrim ('http://'.$_SERVER['HTTP_HOST'].preg_replace ('@\w+\.php$@i', '', $_SERVER['SCRIPT_NAME']), '/'));
define ('__URL__', 'http://'.$_SERVER['HTTP_HOST'].'/'.trim ($_SERVER['REQUEST_URI'], '/'));
define ("__HISTORY__", isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null);
@ini_set ('memory_limit', '128M');
@ini_set ('register_globals', 'off');
require HDPHP_PATH.'/kernel/Functions.php';
require HDPHP_PATH.'/kernel/Loader.php';

if (is_file (__DIR__.'/../vendor/autoload.php'))
{
    require __DIR__.'/../vendor/autoload.php';
}
if (version_compare (PHP_VERSION, '5.4.0', '<'))
{
    ini_set ('magic_quotes_runtime', 0);
}
\hdphp\kernel\Loader::register ();
$app = new \hdphp\kernel\App();
$app->run ();
