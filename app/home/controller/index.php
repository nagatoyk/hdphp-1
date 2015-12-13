<?php namespace home\controller;

use hdphp\kernel\Controller;

//初始控制器
class Index extends Controller
{
    public function index ()
    {
        View::make ();
    }
}