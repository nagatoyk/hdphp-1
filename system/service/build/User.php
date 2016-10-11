<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\service\build;

use system\model\SiteUser;

/**
 * 用户管理服务
 * Class User
 * @package system\service\build
 */
class User extends \system\model\User {
	/**
	 * 系统临时关闭显示提示信息
	 * @return mixed
	 */
	public function checkSystemClose() {
		/**
		 * 站点关闭检测,系统管理员忽略检测
		 */
		if ( ! $this->isSuperUser() && v( 'config.site.is_open' ) == 0 ) {
			\Session::flush();

			die( view( 'resource/view/site_close.php' ) );
		}
	}

	/**
	 * 超级管理员检测
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function isSuperUser( $uid = 0 ) {
		$uid  = $uid ?: v( "user.info.uid" );
		$user = Db::table( 'user' )->find( $uid );

		return $user && $user['groupid'] == 0;
	}

	/**
	 * 是否为站长
	 * 当前帐号是否拥有站长权限(网站所有者,系统管理员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool
	 */
	public function isOwner( $siteId = 0, $uid = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->where( 'role', 'owner' )->get() ? TRUE : FALSE;
	}

	/**
	 * 是否拥有管理员权限
	 * 是否拥有管理员及以上权限(网站所有者,系统管理员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isManage( $siteId = NULL, $uid = NULL ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [ 'manage', 'owner' ] )->get();
	}


	/**
	 * 是否拥有操作员权限
	 * 是否拥有操作员及以上权限(网站所有者,系统管理员,操作员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isOperate( $siteId = 0, $uid = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid, 'return' ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [ 'owner', 'manage', 'operate' ] )->get();
	}

	/**
	 * 用户登录
	 *
	 * @param array $data 登录数据
	 * @param bool $process 直接处理
	 *
	 * @return bool|array
	 */
	public function login( array $data, $process = TRUE ) {
		$user = Db::table( 'user' )->where( 'username', $data['username'] )->first();
		if ( ! $this->checkPassword( $data['password'], $user['username'] ) ) {
			if ( $process ) {
				message( '密码输入错误', 'back', 'error' );
			} else {
				return [ 'valid' => 0, 'message' => '密码输入错误' ];
			}
		}

		if ( ! $user['status'] ) {
			if ( $process ) {
				message( '您的帐号正在审核中', 'back', 'error' );
			} else {
				return [ 'valid' => 0, 'message' => '您的帐号正在审核中' ];
			}
		}
		//更新登录状态
		$data             = [ ];
		$data['lastip']   = Request::ip();
		$data['lasttime'] = time();
		Db::table( 'user' )->where( 'uid', $user['uid'] )->update( $data );
		Session::set( "admin_uid", $user['uid'] );
		$this->initUserInfo();

		return TRUE;
	}

	//初始用户信息
	public function initUserInfo() {
		//前台访问
		if ( Session::get( "admin_uid" ) ) {
			$user                         = [ ];
			$user['info']                 = Db::table( 'user' )->find( $_SESSION['admin_uid'] );
			$group                        = Db::table( 'user_group' )->where( 'id', $user['info']['groupid'] )->first();
			$user['group']                = $group ?: [ ];
			$user['system']['super_user'] = $user['group']['id'] == 0;
			$user['system']['user_type']  = 'admin';
			v( 'user', $user );
		}
	}

	/**
	 * 验证密码是否正确
	 *
	 * @param string $password 登录密码
	 * @param $username 用户名
	 *
	 * @return bool
	 */
	public function checkPassword( $password, $username ) {
		$user = Db::table( 'user' )->where( 'username', $username )->first();

		return $user && $user['password'] == md5( $password . $user['security'] );
	}

	/**
	 * 登录验证
	 * 没有登录异步请求会返回json数据否则直接跳转到登录页
	 * @return bool
	 */
	public function loginAuth() {
		if ( Session::get( 'admin_uid' ) ) {
			return TRUE;
		}
		message( '请登录后进行操作', u( 'system/entry/login' ), 'error' );
	}

	/**
	 * 超级管理员验证
	 * 异步请求会返回json数据否则直接回调页面
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function superUserAuth( $uid = 0 ) {
		if ( ! $this->isSuperUser( $uid ) ) {
			message( '您不是管理员,无法进行操作!', 'back', 'error' );
		}

		return TRUE;
	}

	/**
	 * 根据标识验证模块的访问权限
	 *
	 * @param string $identify 权限标识
	 * @param string $type system 系统模块 / 插件模块的名称
	 *
	 * @return bool
	 */
	public function auth( $identify, $type ) {
		$permission = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', v( "user.info.uid" ) )->get();
		if ( empty( $permission ) ) {
			return TRUE;
		}
		foreach ( $permission as $v ) {
			if ( $v['type'] == $type && in_array( $identify, explode( '|', $v['permission'] ) ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * 根据权限标识验证访问权限
	 *
	 * @param string $identify 验证标识
	 * @param string $module 类型 system系统模块或模块英文标识
	 *
	 * @return bool
	 */
	public function verify( $identify, $module = NULL ) {
		$module     = $module ?: 'system';
		$permission = Db::table( 'user_permission' )
		                ->where( 'uid', Session::get( 'user.uid' ) )
		                ->where( 'siteid', SITEID )
		                ->where( 'type', $module )
		                ->pluck( 'permission' );
		if ( empty( $permission ) || in_array( $identify, explode( '|', $permission ) ) ) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 获取站长信息
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteOwner( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'role', 'owner' )->pluck( 'uid' );
		if ( $uid ) {
			return Db::table( 'user' )->find( $uid );
		}
	}


	/**
	 * 获取用户所在站点角色
	 * 系统管理员和网站所有者返回owner
	 * @return bool
	 */
	//	public function role() {
	//		$siteid = SITEID;
	//		$uid    = v( 'user.info.uid' );
	//		if ( empty( $siteid ) || empty( $uid ) ) {
	//			return FALSE;
	//		}
	//		//系统管理员拥有同站长一样的角色
	//		if ( Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' ) == 0 ) {
	//			return 'owner';
	//		} else {
	//			$sql = "SELECT su.role FROM " . tablename( 'user' ) . " u " . "JOIN " . tablename( 'site_user' ) . " su ON u.uid=su.uid " . "WHERE u.uid={$uid} AND su.siteid={$siteid}";
	//			$res = Db::select( $sql );
	//
	//			return $res ? $res[0]['role'] : FALSE;
	//		}
	//	}

	/**
	 * 获取用户拥有的站点数量
	 *
	 * @param $uid
	 *
	 * @return mixed
	 */
	public function siteNums( $uid = 0 ) {
		$uid = $uid ?: v( "user.info.uid" );

		return Db::table( 'site_user' )->where( 'uid', $uid )->where( 'role', 'owner' )->count() * 1;
	}

	/**
	 * 检测当前帐号是否能添加站点
	 * @return bool
	 */
	public function hasAddSite( $uid = 0 ) {
		$uid  = $uid ?: v( 'user.info.uid' );
		$user = Db::table( 'user' )->find( $uid );
		if ( $user ) {
			//系统管理员不受限制
			if ( $this->isSuperUser( $user['uid'] ) ) {
				return TRUE;
			} else {
				//普通用户检查
				$maxsite = Db::table( 'user_group' )->where( 'id', $user['groupid'] )->pluck( 'maxsite' );

				return $maxsite - $this->siteNums( $uid ) > 0 ? TRUE : FALSE;
			}
		}
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
		if ( $this->isSuperUser( $uid ) ) {
			return TRUE;
		}
		$SiteUser           = new SiteUser();
		$SiteUser['siteid'] = $siteid;
		$SiteUser['role']   = 'owner';
		$SiteUser['uid']    = $uid;

		return $SiteUser->save();
	}

	/**
	 * 获取用户的用户组信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserGroup( $uid = 0 ) {
		$uid      = $uid ?: v( 'user.info.uid' );
		$group_id = Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' );

		return $this->find( $group_id );
	}

	/**
	 * 获取用户在站点的权限分配
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserAtSiteAccess( $siteId = 0, $uid = 0 ) {
		$siteId     = $siteId ?: SITEID;
		$uid        = $uid ?: v( 'user.info.uid' );
		$permission = model( 'UserPermission' )->where( 'siteid', $siteId )->where( 'uid', $uid )->lists( 'type,permission' ) ?: [ ];
		foreach ( $permission as $m => $p ) {
			$permission[ $m ] = explode( '|', $p );
		}

		return $permission;
	}
}