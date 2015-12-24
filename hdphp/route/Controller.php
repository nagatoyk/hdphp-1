<?php namespace hdphp\route;

/**
 * GET模式处理
 * Class getMode
 * @package kernel\route
 */


use Exception;
use ReflectionMethod;

class Controller
{
    public static function run ()
    {
        //URL结构处理
        $param = array();
        if (isset($_GET[c ('http.url_var')]))
        {
            $param = explode ('/', $_GET[c ('http.url_var')]);
            unset($_GET[c ('http.url_var')]);
        }
        switch (count ($param))
        {
            case 3:
                define ('MODULE', array_shift ($param));
                define ('CONTROLLER', array_shift ($param));
                define ('ACTION', array_shift ($param));
                break;
            case 2:
                define ('CONTROLLER', array_shift ($param));
                define ('ACTION', array_shift ($param));
                break;
            case 1:
                define ('ACTION', array_shift ($param));
                break;
        }

        if ( ! defined ('MODULE'))
        {
            define ('MODULE', defined ('BIND_MODULE') ? BIND_MODULE : C ('http.default_module'));
        }
        if ( ! defined ('CONTROLLER'))
        {
            define ('CONTROLLER', C ('http.default_controller'));
        }
        if ( ! defined ('ACTION'))
        {
            define ('ACTION', C ('http.default_action'));
        }
        //模块目录
        defined ('MODULE_PATH') or define ('MODULE_PATH', APP_PATH.'/'.MODULE);
        //模板目录
        defined ('VIEW_PATH') or define ('VIEW_PATH', defined ('MODULE_PATH') ? MODULE_PATH.'/View' : C ('view.path'));
        //公共目录
        defined ("__PUBLIC__") or define ('__PUBLIC__', __ROOT__.'/Public');
        //模板url
        defined ("__VIEW__") or define ('__VIEW__', __ROOT__.'/'.rtrim (VIEW_PATH, '/'));

        self::action ();
    }

    //执行动作
    private static function action ()
    {
        //禁止使用模块检测
        if (in_array (MODULE, C ('http.deny_module')))
        {
            throw new Exception(MODULE.'模块禁止使用');
        }
        $class = MODULE.'\\controller\\'.CONTROLLER;

        //控制器不存在
        if ( ! class_exists ($class))
        {
            throw new Exception("{$class} 不存在");
        }

        $controller = Route::$app->make ($class, true);

        //执行中间件
        Middleware::run ();

        //执行动作
        try
        {
            $action = new ReflectionMethod($controller, ACTION);

            if ($action->isPublic ())
            {
                //控制器前置钩子
                \hdphp\hook\Hook::listen ('controller_begin');

                //执行动作
                $result = call_user_func_array (array($controller, ACTION), array());
                if (IS_AJAX)
                {
                    Response::ajax ($result);
                }
                else
                {
                    die($result);
                }
            }
            else
            {
                throw new ReflectionException('动作不存在');
            }

        } catch (ReflectionException $e)
        {
            $action = new ReflectionMethod($controller, '__call');
            $action->invokeArgs ($controller, array(ACTION, ''));
        }
    }
}