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
 * 站点角色管理
 * Class SiteUser
 * @package system\model
 */
class SiteUser extends Model {
	protected $table = 'site_user';
	protected $validate
	                 = [
			[ 'uid', 'required', '用户编号不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'siteid', 'required', '网站编号不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'role', 'required', '角色类型role不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'role', 'validateRole', '角色类型为角色类型：owner(所有者),manage(管理员),operate(操作员)其中之一', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];

	protected function validateRole( $field, $value, $params, $data ) {
		return in_array( $value, [ 'owner', 'manage', 'operate' ] ) ? TRUE : FALSE;
	}

	/**
	 * 获取用户在站点角色中文描述
	 *
	 * @param $siteid
	 * @param $uid
	 *
	 * @return string
	 */
	public function getRoleTitle( $siteid, $uid ) {
		if ( ( new User() )->isSuperUser() ) {
			return '系统管理员';
		}
		$role = $this->where( 'siteid', $siteid )->where( 'uid', $uid )->pluck( 'role' );
		$data = [ 'owner' => '所有者', 'manage' => '管理员', 'operate' => '操作员' ];

		return $role ? $data[ $role ] : '';
	}

	/**
	 * 设置站点的站长
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return int 自增主键
	 */
	public function setSiteOwner( $siteid, $uid ) {
		//系统管理员不添加数据
		if ( ( new User() )->isSuperUser( $uid ) ) {
			return TRUE;
		}
		$data = [
			'siteid' => $siteid,
			'role'   => 'owner',
			'uid'    => $uid
		];

		return $this->add( $data );
	}

	/**
	 * 获取站长用户信息
	 *
	 * @param int $siteid
	 *
	 * @return array
	 */
	public function getSiteOwner( $siteid ) {
		//站长编号,没有站长时为系统管理员创建
		$uid = $this->where( 'siteid', $siteid )->where( 'role', 'owner' )->pluck( 'uid' );
		if ( $uid ) {
			return Db::table( 'user' )->find( $uid ) ?: [ ];
		}
	}
}