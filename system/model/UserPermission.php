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
	protected $auto
	                 = [
			[ 'permission', 'autoPermission', 'method', self::MUST_AUTO, self::MODEL_BOTH ]
		];

	protected function autoPermission( $val ) {
		return implode( '|', $val );
	}


	/**
	 * 获取用户在站点的权限分配
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserAtSiteAccess( $siteid = NULL, $uid = NULL ) {
		$siteid     = $siteid ?: Session::get( 'siteid' );
		$uid        = $uid ?: Session::get( "user.uid" );
		$permission = $this->where( 'siteid', $siteid )->where( 'uid', $uid )->lists( 'type,permission' );
		foreach ( $permission as $m => $p ) {
			$permission[ $m ] = explode( '|', $p );
		}

		return $permission;
	}

}