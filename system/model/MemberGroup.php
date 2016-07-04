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

//前台用户组
class MemberGroup extends Model {
	protected $table = 'member_group';
	protected $validate
	                 = [
			[ 'siteid', 'required', '站点编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'title', 'required', '组名不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'is_system', 'exists', '不允许设置is_system字段', self::EXIST_VALIDATE, self::MODEL_UPDATE ],
			[ 'rank', 'num:0,255', '排序数字为0~255', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                 = [
			[ 'siteid', 'autoSiteid', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'credit', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'rank', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'isdefault', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'is_system', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];

	protected function autoSiteid() {
		return v( "site.siteid" );
	}

	/**
	 * 获取站点默认组
	 * @return mixed
	 */
	public function getDefaultGroup() {
		return $this->where( 'siteid', v( 'site.siteid' ) )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	/**
	 * 获取站点所有组
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteGroups( $siteid = NULL ) {
		$siteid = $siteid ?: SITEID;

		return $this->where( 'siteid', $siteid )->get() ?: [ ];
	}
}