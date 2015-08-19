<?php namespace Hdphp\Kernel;

use Exception;
use ReflectionMethod;

class Kernel
{

    public function __construct()
    {
        //设置字符集
        header("Content-type:text/html;charset=" . Config::get('app.charset'));

        //时区
        date_default_timezone_set(Config::get('app.timezone'));

        //路由处理
        $this->ParseRoute();

        //导入钓子
        Hook::import(Config::get('hook'));

        //定义常量
        $this->DefineConsts();

        //应用开始钓子
        Hook::listen("app_begin");

        $this->ExecuteAction();

        //应用结束钩子
        Hook::listen("app_end");

        //保存日志
        Log::save();
    }

    /**
     * 解析路由
     *
     * @return bool
     */
    private function parseRoute()
    {
        //导入路由
        require APP_PATH . '/routes.php';

        //分析处理
        return Route::dispatch();
    }

    /**
     * 定义常量
     */
    private function DefineConsts()
    {
        //禁止使用模块检测
        if (in_array(MODULE, C('http.deny_module')))
        {
            throw new Exception(MODULE . '模块禁止使用');
        }
        define('__WEB__', C('http.rewrite') ? __ROOT__ : __ROOT__ . '/' . basename($_SERVER['SCRIPT_FILENAME']));

        define('MODULE_PATH', APP_PATH . '/' . MODULE);
        //模板目录常量
        defined('VIEW_PATH') or define(
        'VIEW_PATH', strstr(C('view.path'), '/') ? C('view.path') : MODULE_PATH . '/View'
        );
        defined("__PUBLIC__") or define('__PUBLIC__', __ROOT__ . '/public');
        defined("__VIEW__") or define('__VIEW__', __ROOT__ . '/' . rtrim(VIEW_PATH, '/'));
    }

    //执行动作
    private function ExecuteAction()
    {
        $class = ucfirst(MODULE) . '\\Controller\\' . ucfirst(CONTROLLER) . 'Controller';
        //控制器不存在
        if ( ! class_exists($class))
        {
            throw new Exception("{$class} 不存在");
        }

        $controller = new $class;

        //执行动作
        try
        {
            $action = new ReflectionMethod($controller, ACTION);

            if ($action->isPublic())
            {
                call_user_func_array(array($controller, ACTION), Route::getArg());
            }
            else
            {
                throw new ReflectionException('动作不存在');
            }

        }
        catch (ReflectionException $e)
        {
            $action = new ReflectionMethod($controller, '__call');
            $action->invokeArgs($controller, array(ACTION, ''));
        }
    }
}