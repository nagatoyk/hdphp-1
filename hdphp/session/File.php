<?php namespace hdphp\session;

class File implements AbSession
{
    //执行SESSION控制
    public function make()
    {
        //创建目录
        if ( ! is_dir('Storage/session'))
        {
            mkdir('Storage/session', 0755, true);
        }

        //设置session保存目录
        session_save_path('Storage/session');
        //开启session
        session_start();
    }
}