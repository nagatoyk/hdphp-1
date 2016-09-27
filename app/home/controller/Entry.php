<?php namespace app\home\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
class Entry {

	//首页
	public function index() {
		$d = Image::thumb('4.jpg', '4a.jpg', 50, 300, 5);
		dd($d);

		return view();
	}
}