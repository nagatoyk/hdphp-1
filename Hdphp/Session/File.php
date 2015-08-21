<?php namespace Hdphp\Session;

class File implements AbSession
{
    public function __construct()
    {
        //创建目录
        if ( ! is_dir('Storage/session'))
        {
            mkdir('Storage/session',0755,true);
        }
    }

    //执行SESSION控制
    public function make()
    {
        session_save_path('Storage/session');
    }
}