<?php namespace Home\Controller;

use Hdphp\Controller\Controller;

//测试控制器
class IndexController extends Controller
{

    //构造函数
    public function __init()
    {
    }

    //动作
    public function index()
    {
        $db = new \Home\Model\User;
        $_POST['id'] = 1;
        $_POST['username'] = 'aaa';
        $_POST['password'] = 'mima';
        $_POST['age'] = 1332;
        if ($db->edit()) {
            echo $db->getError();
        }


//       View::make();
    }
}
