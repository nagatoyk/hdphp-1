<?php namespace system\service\build;

use system\model\MemberFields;
use system\model\MemberGroup;
use system\model\SiteSetting;

/**
 * 站点管理服务
 * Class Site
 * @package system\service\build
 */
class Site extends \system\model\Site {
	/**
	 * 加载当前请求的站点缓存
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool|void
	 */
	public function loadSite( $siteId ) {
		//缓存存在时不获取
		if ( v( 'site' ) || empty( $siteId ) ) {
			return;
		}

		//站点信息
		v( 'site.info', d( "site:" . $siteId ) );
		//站点设置
		v( 'site.setting', d( "setting:" . $siteId ) );
		//微信帐号
		v( 'site.wechat', d( "wechat:" . $siteId ) );
		//加载模块
		v( 'site.modules', d( "modules:" . $siteId ) );
		//设置微信配置
		$config = [
			"token"          => v( 'site.wechat.token' ),
			"encodingaeskey" => v( 'site.wechat.encodingaeskey' ),
			"appid"          => v( 'site.wechat.appid' ),
			"appsecret"      => v( 'site.wechat.appsecret' ),
			"mch_id"         => v( 'site.setting.pay.weichat.mch_id' ),
			"key"            => v( 'site.setting.pay.weichat.key' ),
			"apiclient_cert" => v( 'site.setting.pay.weichat.apiclient_cert' ),
			"apiclient_key"  => v( 'site.setting.pay.weichat.apiclient_key' ),
			"rootca"         => v( 'site.setting.pay.weichat.rootca' ),
			"back_url"       => '',
		];
		//设置微信通信数据配置
		c( 'weixin', array_merge( c( 'weixin' ), $config ) );
		//设置邮箱配置
		c( 'mail', v( 'setting.smtp' ) );

		return TRUE;
	}

	/**
	 * 站点是否存在
	 *
	 * @param $siteId
	 *
	 * @return bool
	 */
	public function has( $siteId ) {
		return $this->where( 'siteid', $siteId )->get() ? TRUE : FALSE;
	}

	/**
	 * 初始化站点的会员字段信息数据
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool
	 */
	public function InitializationSiteTableData( $siteId ) {
		$SiteSetting                    = new SiteSetting();
		$SiteSetting['siteid']          = $siteId;
		$SiteSetting['creditnames']     = [
			'credit1' => [ 'title' => '积分', 'status' => 1 ],
			'credit2' => [ 'title' => '余额', 'status' => 1 ],
			'credit3' => [ 'title' => '', 'status' => 0 ],
			'credit4' => [ 'title' => '', 'status' => 0 ],
			'credit5' => [ 'title' => '', 'status' => 0 ],
		];
		$SiteSetting['register']        = [
			'focusreg' => 0,
			'item'     => 2
		];
		$SiteSetting['creditbehaviors'] = [
			'activity' => 'credit1',
			'currency' => 'credit2'
		];
		$SiteSetting->save();
		//添加默认会员组
		$MemberGroup              = new MemberGroup();
		$MemberGroup['siteid']    = $siteId;
		$MemberGroup['title']     = '会员';
		$MemberGroup['isdefault'] = 1;
		$MemberGroup['is_system'] = 1;
		$MemberGroup->save();

		//创建用户字段表数据
		$memberField = new MemberFields();
		$memberField->where( 'siteid', $siteId )->delete();
		$profile_fields = Db::table( 'profile_fields' )->get();
		foreach ( $profile_fields as $f ) {
			$d['siteid']  = $siteId;
			$d['field']   = $f['field'];
			$d['title']   = $f['title'];
			$d['orderby'] = $f['orderby'];
			$d['status']  = $f['status'];
			$memberField->insert( $d );
		}

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
	 * 更新站点数据缓存
	 *
	 * @param int $siteId 网站编号
	 *
	 * @return bool
	 */
	public function updateCache( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		//站点微信信息缓存
		$wechat         = Db::table( 'site_wechat' )->where( 'siteid', $siteId )->first();
		$data['wechat'] = $wechat ?: [ ];
		//站点信息缓存
		$site         = Db::table( 'site' )->where( 'siteid', $siteId )->first();
		$data['site'] = $site ?: [ ];
		//站点设置缓存
		$setting                     = Db::table( 'site_setting' )->where( 'siteid', $siteId )->first();
		$setting                     = $setting ?: [ ];
		$setting ['creditnames']     = unserialize( $setting['creditnames'] );
		$setting ['creditbehaviors'] = unserialize( $setting['creditbehaviors'] );
		$setting ['register']        = unserialize( $setting['register'] );
		$setting ['smtp']            = unserialize( $setting['smtp'] );
		$setting ['pay']             = unserialize( $setting['pay'] );
		$data['setting']             = $setting;
		//站点模块
		$data['modules'] = service( 'module' )->getSiteAllModules( $siteId, FALSE );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteId}", $value );
		}

		return TRUE;
	}

	/**
	 * 更新所有站点缓存
	 * @return bool
	 */
	public function updateAllCache() {
		foreach ( $this->lists( 'siteid' ) as $siteid ) {
			$this->updateCache( $siteid );
		}

		return TRUE;
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

		return Db::table( 'member_group' )->where( 'siteid', $siteid )->get() ?: [ ];
	}

	/**
	 * 初始化站点的微信
	 *
	 * @param $siteid
	 *
	 * @return 微信表新增主键编号
	 */
	public function initSiteWeChat( $siteid ) {
		$data = [
			'siteid'     => $siteid,
			'wename'     => '',
			'account'    => '',
			'original'   => '',
			'level'      => 1,
			'appid'      => '',
			'appsecret'  => '',
			'qrcode'     => '',
			'icon'       => '',
			'is_connect' => 0,
		];

		return $this->insertGetId( $data );
	}
}