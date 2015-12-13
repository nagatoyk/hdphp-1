<?php namespace hdphp\db;

use hdphp\kernel\ServiceProvider;

class DbProvider extends ServiceProvider
{
    //延迟加载
    public $defer = false;

    public function boot ()
    {
        //将公共数据库配置合并到 write 与 read 中
        $config = Config::get ('database');
        foreach ($config as $key => $value)
        {
            if ( ! in_array ($key, array('read', 'write')))
            {
                if (empty($config['write'][$key]))
                {
                    $config['write'][$key] = $value;
                }
                if (empty($config['read'][$key]))
                {
                    $config['read'][$key] = $value;
                }
            }
        }

        Config::set ('database', $config);
    }

    public function register ()
    {
        $this->app->bind (
            'Db',
            function ($app)
            {echo 111;
                return new Db($app);
            },
            true
        );
    }
}