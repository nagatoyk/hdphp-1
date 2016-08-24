<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace web\home\controller;

class Index{
    //动作
	public function add($id,$cid){
		p($id);
		p($cid);
		echo 'ok22';
		//此处书写代码...
	}

	public function getIndex()
	{
		echo 'index';
	}
	public function getAdd()
	{
		echo 'add';
	}
	public function postEdit()
	{
		echo 'edit';
	}
}
