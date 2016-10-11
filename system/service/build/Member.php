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
/**
 * 会员接口
 * Class Member
 * @package server
 * @author 向军
 */
class Member {
	protected $db;

	public function __construct() {
		$this->db = new \system\model\Member();
	}

	//检测用户登录
	public function isLogin() {
		if ( ! Session::get( "member_uid" ) ) {
			message( '请登录后操作', web_url( 'reg/login', [ ], 'uc' ), 'error' );
		}

		return TRUE;
	}

	//初始用户信息
	public function initUserInfo() {
		if ( Session::get( "member_uid" ) ) {
			$user                        = [ ];
			$user['member']              = Db::table( 'member' )->find( $_SESSION['member_uid'] );
			$group                       = Db::table( 'member_group' )->where( 'id', $user['info']['group_id'] )->first();
			$user['group']               = $group ?: [ ];
			$user['system']['user_type'] = 'member';
			v( 'user', $user );
		}
	}

	//获取会员组
	public function getGroupName( $uid = 0 ) {
		$uid = $uid ?: v( 'user.uid' );
		$sql = "SELECT title,id FROM " . tablename( 'member' ) . " m JOIN " . tablename( 'member_group' ) . " g ON m.group_id = g.id WHERE m.uid={$uid}";
		$d   = Db::query( $sql );

		return $d ? $d[0] : NULL;
	}

	/**
	 * 判断当前uid的用户是否在当前站点中存在
	 *
	 * @param $uid 会员编号
	 *
	 * @return bool
	 */
	public function hasUser( $uid ) {
		if ( ! Db::table( 'member' )->where( 'siteid', SITEID )->where( 'uid', $uid )->get() ) {
			message( '当前站点中不存在此用户', 'back', 'error' );
		}

		return TRUE;
	}

	//微信自动登录
	public function weixinLogin() {
		if ( IS_WEIXIN && v( 'site.wechat.level' ) >= 3 ) {
			//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
			if ( $info = \Weixin::instance( 'oauth' )->snsapiUserinfo() ) {
				$user = $this->db->where( 'openid', $info['openid'] )->first();
				if ( ! $user ) {
					//帐号不存在时使用openid添加帐号
					$this->db['openid']   = $info['openid'];
					$this->db['nickname'] = $info['nickname'];
					$this->db['icon']     = $info['headimgurl'];
					$uid                  = $this->db->save();
					$user                 = $this->db->find( $uid );
				}
				//更新access_token
				$user['access_token'] = md5( $user['username'] . $user['password'] . c( 'app.key' ) );
				$user->save();
				Session::set( 'member_uid', $user['uid'] );

				return TRUE;
			}
		}
	}

	//根据access_token获取用户信息
	public function getUserInfoByAccessToken( $access_token ) {
		$res = $this->db->where( 'access_token', $access_token )->first();

		return $res ? [ 'valid' => 1, 'data' => $res ] : [ 'valid' => 0, 'message' => '用户不存在' ];
	}

	//会员登录
	public function login( $data ) {
		$member = new \system\model\Member();
		$user   = $member->where( 'email', $data['username'] )->orWhere( 'mobile', $data['username'] )->first();
		if ( empty( $user ) ) {
			message( '帐号不存在', 'back', 'error' );
		}
		if ( md5( $data['password'] . $user['security'] ) != $user['password'] ) {
			message( '密码输入错误', 'back', 'error' );
		}
		Session::set( 'member_uid', $user['uid'] );

		return TRUE;
	}

	//注册页面
	public function register( $data ) {
		$member             = new \system\model\Member();
		$member['password'] = $data['password'];
		$member['group_id'] = $this->defaultGruopId();
		$info               = $member->getPasswordAndSecurity();
		$member['password'] = $info['password'];
		$member['security'] = $info['security'];
		switch ( v( 'site.setting.register.item' ) ) {
			case 1:
				//手机号注册
				if ( ! preg_match( '/^\d{11}$/', $data['username'] ) ) {
					message( '请输入手机号', 'back', 'error' );
				}
				$member['mobile'] = $data['username'];
				break;
			case 2:
				//邮箱注册
				if ( ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱', 'back', 'error' );
				}
				$member['email'] = $data['username'];
				break;
			case 3:
				//二者都行
				if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) && ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱或手机号', 'back', 'error' );
				} else if ( preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
					$member['mobile'] = $data['username'];
				} else {
					$member['email'] = $data['username'];
				}
		}
		if ( ! empty( $member['mobile'] ) ) {
			if ( $member->where( 'mobile', $data['mobile'] )->get() ) {
				message( '手机号已经存在', '', 'error' );
			}
		}
		if ( ! empty( $data['email'] ) ) {
			if ( $member->where( 'mobile', $data['email'] )->get() ) {
				message( '邮箱已经存在', '', 'error' );
			}
		}

		$member->save( $data );

		return TRUE;
	}

	/**
	 * 获取当前登录会员默认地址
	 * @return mixed
	 */
	public function getDefaultAddress() {
		return $this->where( 'uid', Session::get( 'member.uid' ) )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->first();
	}

	/**
	 * 默认会员组编号
	 * @return mixed
	 */
	public function defaultGruopId() {
		return Db::table( 'member_group' )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->pluck( 'id' );
	}
}