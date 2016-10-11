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
 * 菜单管理
 * Class Menu
 * @package system\model
 */
class Menu extends Model {
	protected $table = 'menu';

	protected $validate
		= [
			[ 'title', 'required', '标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'permission', 'unique', '权限标识已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'url', 'unique', '菜单链接已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'append_url', 'unique', '右侧图标链接已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序数字为0~255', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'is_display', 'num:0,1', '[显示]字段参数错误', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
		= [
			[ 'is_display', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'mark', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'is_system', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'icon', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'append_url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'permission', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'pid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];
}