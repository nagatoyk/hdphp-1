<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\service;

/**
 * 服务工具类
 * Class Util
 * @package server
 */
class Util {

	/**
	 * 实例子服务器
	 *
	 * @param string $name 服务名
	 *
	 * @return mixed
	 */
	public function instance( $name ) {
		static $instances = [ ];
		if ( isset( $instances[ $name ] ) ) {
			return $instances[ $name ];
		}
		$class = '\system\service\build\\' . ucfirst($name);

		return $instances[ $name ] = new $class;
	}
}