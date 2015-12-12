<?php namespace hdphp\backup;

use hdphp\kernel\ServiceProvider;

/**
 * 数据库备份服务
 * Class BackupProvider
 * @package hdphp\backup
 * @author  向军 <2300071698@qq.com>
 */
class BackupProvider extends ServiceProvider
{

    //延迟加载
    public $defer = true;

    public function boot ()
    {
    }

    public function register ()
    {
        $this->app->single (
            'Backup',
            function ($app)
            {
                return new Backup($app);
            }
        );
    }
}