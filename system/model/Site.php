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

//站点服务
use hdphp\model\Model;

class Site extends Model {
	protected $table = 'site';
	protected $validate
	                 = [
			[ 'name', 'required', '站点名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'name', 'unique', '站点名称已经存在', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
	                 = [
			[ 'icp', '', 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'weid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'allfilesize', 200, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		];

	//加载站点缓存
	public function loadSite() {
		$siteid = \Session::get( 'siteid' );
		if ( empty( $siteid ) ) {
			return FALSE;
		}
		//缓存存在时不进行获取
		if ( v( 'site' ) ) {
			return TRUE;
		}
		//微信帐号
		v( 'wechat', d( "wechat:{$siteid}" ) );
		//站点信息
		v( 'site', d( "site:{$siteid}" ) );
		//站点设置
		v( 'setting', d( "setting:{$siteid}" ) );
		//加载模块
		v( 'modules', d( "modules:{$siteid}" ) );
		//设置微信配置
		$config = [
			"token"          => v( 'wechat.token' ),
			"encodingaeskey" => v( 'wechat.encodingaeskey' ),
			"appid"          => v( 'wechat.appid' ),
			"appsecret"      => v( 'wechat.appsecret' ),
			"mch_id"         => v( 'setting.pay.weichat.mch_id' ),
			"key"            => v( 'setting.pay.weichat.key' ),
			"apiclient_cert" => v( 'setting.pay.weichat.apiclient_cert' ),
			"apiclient_key"  => v( 'setting.pay.weichat.apiclient_key' ),
			"rootca"         => v( 'setting.pay.weichat.rootca' ),
			"back_url"       => '',
		];
		c( 'weixin', array_merge( c( 'weixin' ), $config ) );

		return TRUE;
	}

	/**
	 * 更新站点数据缓存
	 *
	 * @param int $siteid 网站编号
	 *
	 * @return bool
	 */
	public function updateSiteCache( $siteid = NULL ) {
		$siteid = $siteid ?: Session::get( 'siteid' );
		//站点微信信息缓存
		$data['wechat'] = Db::table( 'site_wechat' )->where( 'siteid', '=', $siteid )->first();
		//站点信息缓存
		$data['site'] = Db::table( 'site' )->where( 'siteid', '=', $siteid )->first();
		//站点设置缓存
		$setting                     = Db::table( 'site_setting' )->where( 'siteid', '=', $siteid )->first() ?: [ ];
		$setting ['creditnames']     = unserialize( $setting['creditnames'] );
		$setting ['creditbehaviors'] = unserialize( $setting['creditbehaviors'] );
		$setting ['register']        = unserialize( $setting['register'] );
		$setting ['smtp']            = unserialize( $setting['smtp'] );
		$setting ['pay']             = unserialize( $setting['pay'] );
		$data['setting']             = $setting;
		//站点模块
		$data['modules'] = ( new Modules() )->getSiteAllModules( $siteid, FALSE );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteid}", $value );
		}

		return TRUE;
	}

	/**
	 * 更新所有站点缓存
	 */
	public function updateAllSiteCache() {
		foreach ( $this->lists( 'siteid' ) as $siteid ) {
			$this->updateSiteCache( $siteid );
		}
	}

	/**
	 * 站点是否存在
	 *
	 * @param $siteid
	 *
	 * @return bool
	 */
	public function isSite( $siteid ) {
		return $this->where( 'siteid', $siteid )->get() ? TRUE : FALSE;
	}

	/**
	 * 获取站点关联表
	 *
	 * @param int $siteid 站点编号
	 */
	public function getSiteRelationTables() {
		$tables = Db::getAllTableInfo();
		p( $tables );
	}

	/**
	 * 删除(注销)站点
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return bool
	 */
	public function remove( $siteid ) {
		//站点关联表(删除站点时使用)
		$tables = [
			'balance',//会员余额
			'core_attachment',//附件字段
			'credits_record',//积分变动记录
			'member',//会员表
			'member_address',
			'member_fields',
			'member_group',
			'module_setting',
			'pay',
			'profile_fields',
			'reply_cover',
			'rule',
			'rule_keyword',
			'site',//站点表
			'site_modules',//站点模块
			'site_package',
			'site_setting',//站点设置
			'site_template',
			'site_user',//站点操作员
			'site_wechat',//微信
			'ticket',
			'ticket_groups',
			'ticket_module',
			'ticket_record',
			'user_permission',
			'web',
			'web_article',//文章
			'web_category',//栏目
			'web_nav',//导航
			'web_page',//微官网页面(快捷导航/专题页面)
			'web_slide',//站点幻灯图
			'user_permission',//用户权限分配
		];
		foreach ( $tables as $t ) {
			Db::table( $t )->where( 'siteid', $siteid )->delete();
		}
		//删除缓存
		$keys = [ 'access', 'setting', 'wechat', 'site', 'modules', 'module_binding' ];
		foreach ( $keys as $key ) {
			d( "{$key}:{$siteid}", '[del]' );
		}
		\Session::del( 'siteid' );

		return TRUE;
	}

	/**
	 * 获取用户管理的所有站点信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return array 站点列表
	 */
	public function getUserAllSite( $uid ) {
		return $this->join( 'site_user', 'site.siteid', '=', 'site_user.siteid' )->where( 'site_user.uid', $uid )->get();
	}

	/**
	 * 验证站点是否拥有模块
	 *
	 * @param string $siteid 站点编号
	 * @param string $module 模块名称
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function hasModule( $siteid = NULL, $module = NULL ) {
		$siteid = $siteid ?: SITEID;
		$module = $module ?: v( 'module.name' );
		if ( empty( $siteid ) || empty( $module ) ) {
			return FALSE;
		}
		$modules = ( new Modules() )->getSiteAllModules( $siteid );
		foreach ( $modules as $m ) {
			if ( strtolower( $module ) == strtolower( $m['name'] ) ) {
				return TRUE;
			}
		}
	}
}