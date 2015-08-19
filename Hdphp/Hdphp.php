<?php

define('HDPHP_VERSION', 	'2015-7-1');
defined("DEBUG")        	or define("DEBUG", false);//调试模式
defined("DEBUG_TOOL")   	or define("DEBUG_TOOL", false);//Trace调试面板
defined('APP_PATH') 		or define('APP_PATH', 'app');//应用目录
defined('APP') 		        or define('APP', basename(APP_PATH));//应用
defined('HDPHP_PATH')		or define('HDPHP_PATH',__DIR__);//框架目录

//命令行模式
if($_SERVER['SCRIPT_NAME']=='hd')
{
	require_once 'hdphp/Cli/Cli.php';
	Hdphp\Cli\Cli::run();
	exit;
}
else if (!DEBUG && is_file('storage/~runtime.php'))
{
	//加载核心编译文件
	require_once 'storage/~runtime.php';
}
else
{
	require_once HDPHP_PATH.'/Kernel/Container.php';
	require_once HDPHP_PATH.'/Kernel/Application.php';
	require_once HDPHP_PATH.'/Kernel/Boot.php';
}

require_once HDPHP_PATH.'/Kernel/Functions.php';
Hdphp\Kernel\Boot::bootstrap();
new Hdphp\Kernel\Application();
new Hdphp\Kernel\Kernel();
