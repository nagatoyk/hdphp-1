<?php
//版本号
define ('HDPHP_VERSION', '2015-12-18');

//框架目录
define ('HDPHP_PATH', __DIR__);

//项目根目录
define ('ROOT_PATH', dirname (HDPHP_PATH));

//应用目录
defined ('APP_PATH') or define ('APP_PATH', ROOT_PATH.'/app');

//调试模式
defined ("DEBUG") or define ("DEBUG", false);

//命令行模式
if ($_SERVER['SCRIPT_NAME'] == 'hd')
{
    require_once HDPHP_PATH.'/cli/Cli.php';
    Hdphp\Cli\Cli::run ();
    exit;
}
require HDPHP_PATH.'/kernel/Boot.php';
require HDPHP_PATH.'/kernel/Container.php';
require HDPHP_PATH.'/kernel/App.php';
require HDPHP_PATH.'/kernel/Functions.php';

hdphp\kernel\Boot::bootstrap ();

$app = new hdphp\kernel\App();

new hdphp\kernel\Kernel($app);
