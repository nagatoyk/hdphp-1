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
 * 微站导航菜单
 * Class WebNav
 * @package system\model
 * @author 向军
 */
class WebNav extends Model {
	protected $table            = 'web_nav';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'name', 'required', '导航标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'url', 'required', '链接不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序只能为0~255之间的数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'entry', 'required', '导航类型不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'web_id', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'module', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'css', 'json_encode', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'category_cid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'position', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'position', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'orderby', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'icontype', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'entry', 'strtolower', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
		];

	/**
	 * 获取菜单类型的中文标题
	 *
	 * @param string $entry 类型标识
	 *
	 * @return mixed
	 */
	public function getEntryTitle( $entry ) {
		$menu = [ 'home' => '微站首页导航', 'profile' => '手机会员中心导航', 'member' => '桌面会员中心导航' ];

		return $menu[ $entry ];
	}
}