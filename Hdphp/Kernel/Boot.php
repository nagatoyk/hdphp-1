<?php namespace Hdphp\Kernel;

class Boot
{

    private static $binded = false;

    public static function bootstrap()
    {
        //设置系统常量
        self::setConsts();

        //创建初始目录
        self::mkDirs();

        //创建初始文件
        self::createInitFile();

        //创建编译文件
        self::createRuntimeFile();
    }

    /**
     * 设置初始常量
     */
    public static function setConsts()
    {
        define('IS_CGI', substr(PHP_SAPI, 0, 3) == 'cgi' ? true : false);
        define('IS_WIN', strstr(PHP_OS, 'WIN') ? true : false);
        define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        define('DS', DIRECTORY_SEPARATOR);
        define('IS_GET', $_SERVER['REQUEST_METHOD'] == 'GET');
        define('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST');
        define('IS_DELETE', $_SERVER['REQUEST_METHOD'] == 'DELETE' ?: (isset($_POST['_method']) && $_POST['_method'] == 'DELETE'));
        define('IS_PUT', $_SERVER['REQUEST_METHOD'] == 'PUT' ?: (isset($_POST['_method']) && $_POST['_method'] == 'PUT'));
        define(
        'IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        );

        define('__ROOT__', rtrim('http://' . $_SERVER['HTTP_HOST'] . preg_replace('@\w+\.php$@i', '', $_SERVER['SCRIPT_NAME']), '/'));
        define("__HISTORY__", isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null);
        define('__URL__', 'http://' . $_SERVER['HTTP_HOST'].'/'.trim($_SERVER['REQUEST_URI'],'/'));

        define('NOW', $_SERVER['REQUEST_TIME']);
        define('NOW_MICROTIME', microtime(true));
        // 系统信息
        @ini_set('memory_limit', '128M');
        @ini_set('register_globals', 'off');
        if (version_compare(PHP_VERSION, '5.4.0', '<'))
        {
            //对外部引入文件禁止加转义符
            ini_set('magic_quotes_runtime', 0);
            //删除系统自动加的转义符号
            if (get_magic_quotes_gpc())
            {
                self::unaddslashes($_POST);
                self::unaddslashes($_COOKIE);
                self::unaddslashes($_GET);
            }
        }
    }

    //反转义$_POST
    private static function unaddslashes(&$data)
    {
        foreach ((array)$data as $k => $v)
        {
            if (is_numeric($v))
            {
                $data[$k] = $v;
            }
            else
            {
                $data[$k] = is_array($v) ? self::unaddslashes($v) : stripslashes($v);
            }
        }

        return $data;
    }

    /**
     * 初次运行框时创建基础目录
     *
     * @return [type] [description]
     */
    public static function mkDirs()
    {
        if (is_dir(APP_PATH))
        {
            self::$binded = true;

            return;
        }


        $dirs = array(
            APP_PATH . '/Common/Service', APP_PATH . '/Common/Provider', APP_PATH . '/Common/Common', APP_PATH . '/Common/Lang', APP_PATH . '/Common/Tag', APP_PATH . '/Common/Hook', APP_PATH . '/Home/Controller', APP_PATH . '/Home/Model', APP_PATH . '/Home/Api', APP_PATH . '/Home/View/Index', 'config', 'public'
        );

        foreach ($dirs as $dir)
        {
            is_dir($dir) or mkdir($dir, 0755, true);
        }
    }

    /**
     * 创建初始文件
     *
     * @return [type] [description]
     */
    public static function createInitFile()
    {
        if (self::$binded)
        {
            return;
        }

        $files = array(
            HDPHP_PATH . '/View/index.php' => APP_PATH . '/Home/View/Index/index.php', HDPHP_PATH . '/View/success.php' => 'public/success.php', HDPHP_PATH . '/View/error.php' => 'public/error.php', HDPHP_PATH . '/View/tag.php' => APP_PATH . '/Common/Tag/Common.php', HDPHP_PATH . '/Controller/IndexController.php' => APP_PATH . '/Home/Controller/IndexController.php', HDPHP_PATH . '/Lang/zh.php' => APP_PATH . '/Common/Lang/zh.php', HDPHP_PATH . '/Route/routes.php' => APP_PATH . '/routes.php',
        );

        foreach ($files as $key => $value)
        {
            if ( ! is_file($value))
            {
                copy($key, $value);
            }
        }

        //复制配置文件
        foreach (glob(HDPHP_PATH . '/Config/Config/*') as $file)
        {
            if ( ! is_file('config/' . basename($file)))
            {
                copy($file, 'config/' . basename($file));
            }
        }
    }

    /**
     * 生成编译文件
     *
     * @return [type] [description]
     */
    private static function createRuntimeFile()
    {
        //调试模式下不生成编译文件
        if (DEBUG == true || is_file('storage/~runtime.php'))
        {
            return;
        }

        //核心文件
        $core = array(
            HDPHP_PATH . '/Kernel/Boot', HDPHP_PATH . '/Kernel/Container', HDPHP_PATH . '/Kernel/Application', HDPHP_PATH . '/Kernel/Facade', HDPHP_PATH . '/Kernel/Kernel', HDPHP_PATH . '/Kernel/ServiceProvider',
        );

        //服务与facade文件列表
        $config = require 'config/service.php';

        $files = array_merge($core, $config['provider'], $config['facade']);

        $compile = '';

        foreach ($files as $file)
        {
            $file = str_replace('\\', DS, $file);
            $compile .= substr(rtrim(file_get_contents($file . '.php')), 5) . "\n";
        }
        if(!is_dir('storage'))
        {
            mkdir('storage',0755,true);
        }
        //保存文件
        file_put_contents('storage/~runtime.php', '<?php ' . $compile);
    }

}