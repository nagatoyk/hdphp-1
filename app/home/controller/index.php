<?php namespace home\controller;

use hdphp\kernel\Controller;

class b{
    function a(){
        echo 33;
    }
}
//初始控制器
class Index extends Controller
{
    public function __construct ()
    {
        App::bind('bb',new b);
    }

    public function index ()
    {
        App::make('bb')->a();
    }
}