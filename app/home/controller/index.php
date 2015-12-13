<?php namespace home\controller;

//初始控制器
class Index
{
    public function __construct ()
    {
        //中间件
        Middleware::set ('auth', array('only' => array('index')));
    }

    public function index ()
    {
        View::make ();
    }
}