<?php namespace Home\Model;

use Hdphp\Model\Model;

class User extends Model
{

    //数据表
    protected $table = "user";

    //完整表名
    protected $full = false;

    //自动验证
    protected $validate = array(
        //字段名: 表单字段名
        //验证方法: 函数或模型方法
        //验证条件: 1有字段时验证(默认)	2值不为空时验证  	3必须验证
        //验证时间: 1 插入时验证		2更新时空时验证 	3全部情况验证 (默认)
        //array('字段名','验证方法','提示信息',验证条件,验证时间),
        array('username', 'checkUser', '用户名长度错误', 3, 3)
    );

    public function checkUser($field, $value, $params, $data)
    {
        //返回true，为验证通过
        if (mb_strlen($value, 'utf-8') > 5) {
            return true;
        }
    }

    //自动完成
    protected $auto = array(
        //字段名: 表单字段名
        //处理方法: 函数或模型方法
        //方法类型: string(字符串 默认)  function(函数)  method(模型方法)
        //验证条件: 1有字段时处理(默认)	2值不为空时 3必须处理
        //处理时间: 1 插入时  2更新时 3全部情况 (默认)
        //array('字段名','处理方法','方法类型',验证条件,验证时间),
    );

    //时间操作
    protected $timestamps = false;

    //禁止插入的字段
    protected $denyInsertFields = array('');

    //禁止更新的字段
    protected $denyUpdateFields = array('');

    public function store()
    {
        if ($this->create()) {
            return $this->add();
        } else {
            return $this->getError();
        }
    }

    public function edit()
    {

        if ($this->create()) {
            $this->save();
        } else {
            return $this->error;
        }
    }

    //前置方法 比如: _before_add 为添加前执行的方法
    protected function _before_add()
    {
    }

    protected function _before_delete()
    {
    }

    protected function _before_save(&$data)
    {
    }

    protected function _after_add()
    {
    }

    protected function _after_delete()
    {
    }

    protected function _after_save()
    {
    }

}