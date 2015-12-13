<?php namespace hdphp\middleware;

class Middleware
{
    protected $app;

    protected static $run = array();

    public function __construct ($app)
    {
        $this->app = $app;
        self::$run = Config::get ('middleware.global');
    }

    /**
     * 添加控制器执行的中间件
     *
     * @param $name 中间件名称
     * @param $mold array 类型
     *  ['only'=>array('a','b')] 仅执行a,b控制器动作
     *  ['except']=>array('a','b')], 除了a,b控制器动作
     */
    public function set ($name, $mod = array())
    {
        if ($mod)
        {
            foreach ($mod as $type => $data)
            {
                switch ($type)
                {
                    case 'only':
                        if (in_array (ACTION, $data))
                        {
                            self::$run[] = Config::get ('middleware.middleware.'.$name);
                        }
                        break;
                    case 'except':
                        if (!in_array (ACTION, $data))
                        {
                            self::$run[] = Config::get ('middleware.middleware.'.$name);
                        }
                        break;
                }
            }
        }
    }

    //执行控制器
    public function run ()
    {
        foreach (self::$run as $class)
        {
            if (class_exists ($class))
            {
                $obj = $this->app->make ($class);
                if (method_exists ($obj, 'run'))
                {
                    $obj->run ();
                }
            }
        }
    }
}