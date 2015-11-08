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
        $db = M('User');
        $_POST['usernadme'] = 'abcdefg';

        p(Validate::make(
            [
                ['username','required','用户名不能为空'],
            ]
        )->message());
        exit;
        View::make();
    }

    public function code()
    {
        Code::make();
    }
}
