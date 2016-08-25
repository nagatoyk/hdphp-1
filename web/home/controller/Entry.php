<?php namespace web\home\controller;
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
		$data = [
			[ 'name' => '小明', 'age' => 33 ],
			[ 'name' => '李四', 'age' => 13 ],
			[ 'name' => '小六', 'age' => 28 ],
			[ 'name' => '爱华', 'age' => 33 ],
			[ 'name' => '张三', 'age' => 23 ]
		];
		View::with( 'data', $data );
		View::make();
	}
}