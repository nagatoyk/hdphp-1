<?php
namespace Hdphp\Controller;

class Controller
{
    //事件参数
    protected $options = array();

    public function __construct()
    {
        \Hdphp\Hook\Hook::listen('controller_begin', $this->options);

        if (method_exists($this, '__init'))
        {
            $this->__init();
        }
    }

    /**
     * 通过魔术方法设置变量
     *
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function __set($name, $value)
    {
        $this->assign($name, $value);
    }

    protected function success($message = '操作成功', $url = null, $time = 1)
    {
        View::success($message, $url, $time);
    }

    protected function error($message = '操作失败', $url = null, $time = 1)
    {
        View::error($message, $url, $time);
    }

    public function __destruct()
    {
        \Hdphp\Hook\Hook::listen('controller_end', $this->options);
    }

    public function __call($method, $params)
    {

    }
}