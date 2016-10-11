<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

use hdphp\model\Model;

/**
 * 用户站点操作权限
 * Class UserPermission
 * @package system\model
 * @author 向军
 */
class UserPermission extends Model {
	protected $table = 'user_permission';
	protected $validate
	                 = [
			[ 'uid', 'required', '用户编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'siteid', 'required', '站点编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'type', 'required', '模块类型不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'permission', 'required', '权限内容不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
}