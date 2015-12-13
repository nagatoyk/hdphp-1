<?php namespace hdphp\kernel;

use ReflectionClass;
use Hdphp\Kernel\ServiceProviders;

class App extends Container
{
    //应用已启动
    protected $booted = false;

    //服务配置
    protected $config = array();

    //外观别名
    protected $facades = array();

    //延迟加载服务提供者
    protected $deferProviders = array();

    //已加载服务提供者
    protected $serviceProviders = array();

    // 构造函数
    public function run ()
    {
        //引入服务配置
        $this->config = require ROOT_PATH.'/config/service.php';
        //自动加载
        Loader::register (array($this, 'autoload'));
        //绑定核心服务提供者
        $this->bindServiceProvider ();
        //添加初始实例
        $this->instance ('App', $this);
        //设置外观基类APP属性
        \hdphp\kernel\ServiceFacade::setFacadeApplication ($this);
        //导入类库别名
        Loader::addMap (Config::get ('app.alias'));
        //启动服务
        $this->boot ();
        //执行请求
        $this->exe ();
    }

    // 执行请求
    public function exe ()
    {
        //命令模式
        if ($_SERVER['SCRIPT_NAME'] == 'hd')
        {
            require_once HDPHP_PATH.'/cli/Cli.php';
            \hdphp\cli\Cli::run ();
            exit;
        }
        header ("Content-type:text/html;charset=".Config::get ('app.charset'));
        date_default_timezone_set (Config::get ('app.timezone'));
        Route::dispatch ();
        //导入钓子
        \hdphp\hook\Hook::import (Config::get ('hook'));
        Log::save ();
    }

    // 自动加载外观
    public function autoload ($class)
    {
        $file   = str_replace ('\\', '/', $class);
        $facade = basename ($file);
        if (isset($this->config['facades'][$facade]))
        {
            //加载facade类
            return class_alias ($this->config['facades'][$facade], $class);
        }
    }

    // 系统启动
    public function boot ()
    {
        if ($this->booted)
        {
            return;
        }

        foreach ($this->serviceProviders as $p)
        {
            $this->bootProvider ($p);
        }
        $this->booted = true;
    }

    // 服务加载处理
    public function bindServiceProvider ()
    {
        foreach ($this->config['providers'] as $provider)
        {
            $reflectionClass = new ReflectionClass($provider);
            $properties      = $reflectionClass->getDefaultProperties ();

            //获取服务延迟属性
            if (isset($properties['defer']) && $properties['defer'])
            {
                $alias = substr ($reflectionClass->getShortName (), 0, -8);

                //延迟加载服务
                $this->deferProviders[$alias] = $provider;
            }
            else
            {
                //立即加载服务
                $this->register (new $provider($this));
            }
        }

    }

    /**
     * 获取服务对象
     *
     * @param $name 服务名
     * @param bool|false $force 是否单例
     *
     * @return Object
     */
    public function make ($name, $force = false)
    {
        if (isset($this->deferProviders[$name]))
        {
            $this->register (new $this->deferProviders[$name]($this));
            unset($this->deferProviders[$name]);
        }

        return parent::make ($name, $force);
    }

    /**
     * 注册服务
     * @param $provider 服务名
     *
     * @return object
     */
    public function register ($provider)
    {
        //服务对象已经注册过时直接返回
        if ($registered = $this->getProvider ($provider))
        {
            return $registered;
        }

        if (is_string ($provider))
        {
            $provider = new $provider($this);
        }

        $provider->register ($this);

        //记录服务
        $this->serviceProviders[] = $provider;

        if ($this->booted)
        {
            $this->bootProvider ($provider);
        }
    }

    /**
     * 运行服务提供者的boot方法
     *
     * @param object $provider
     */
    protected function bootProvider ($provider)
    {
        if (method_exists ($provider, 'boot'))
        {
            $provider->boot ();
        }
    }

    /**
     * 获取已经注册的服务
     *
     * @param  string $provider 服务名
     *
     * @return object
     */
    protected function getProvider ($provider)
    {
        $class = is_object ($provider) ? get_class ($provider) : $provider;

        foreach ($this->serviceProviders as $value)
        {
            if ($value instanceof $class)
            {
                return $value;
            }
        }
    }
}