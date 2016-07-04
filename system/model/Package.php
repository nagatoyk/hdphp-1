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

//站点套餐管理
class Package extends Model {
	protected $table = "package";
	protected $validate
	                 = [
			[ 'name', 'required', '套餐名不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                 = [
			[ 'modules', 'serialize', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'template', 'serialize', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	/**
	 * 获取站点拥有的套餐数据
	 * 包括站长默认套餐+为站点独立设置的扩展套餐
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteAllPackageData( $siteid ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		$ids      = $this->getSiteAllPackageIds( $siteid );
		$packages = $ids ? $this->whereIn( 'id', $ids )->get() : [ ];
		foreach ( $packages as $k => $v ) {
			$packages[ $k ]['modules']  = unserialize( $v['modules'] );
			$packages[ $k ]['template'] = unserialize( $v['template'] );
		}
		//含有[所有服务]套餐时
		if ( in_array( - 1, $ids ) ) {
			array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => [ ], 'template' => [ ] ] );
		}

		return $cache[ $siteid ] = $packages;
	}

	/**
	 * 获取系统所有套餐
	 * @return array
	 */
	public function getSystemAllPackageData() {
		static $cache = [ ];
		if ( ! empty( $cache ) ) {
			return $cache;
		}
		$packages = $this->get();
		foreach ( $packages as $k => $v ) {
			$packages[ $k ]['modules']  = $v['modules'] ? Db::table( 'modules' )->whereIn( 'name', unserialize( $v['modules'] ) )->get() : [ ];
			$packages[ $k ]['template'] = $v['template'] ? Db::table( 'template' )->whereIn( 'name', unserialize( $v['template'] ) )->get() : [ ];
		}

		//		array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => [ ], 'template' => [ ] ] );

		return $cache = $packages;
	}

	/**
	 * 站点所有套餐列表编号
	 * 包括站点默认套餐+为站点独立设置的扩展套餐
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array 数组中包含-1套餐表示可以使用所有模块
	 */
	public function getSiteAllPackageIds( $siteid ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//获取站长拥有的套餐
		$default = $this->getSiteDefaultPackageIds( $siteid );
		//站点自定义扩展的套餐
		$ext = ( new SitePackage() )->getSiteExtPackageIds( $siteid );

		return $cache[ $siteid ] = array_merge( $default, $ext );
	}

	/**
	 * 根据站长用户组获取站点套餐
	 * 不含为站点自定义的扩展套餐
	 * 套餐由用户组套餐+站点扩展套餐构成
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteDefaultPackageIds( $siteid ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//获取站长拥有的套餐
		$sql = "SELECT ug.package FROM " . tablename( 'user' ) . " u " . "JOIN " . tablename( 'site_user' ) . " su ON u.uid=su.uid ";
		$sql .= "JOIN " . tablename( 'user_group' ) . " ug ON u.groupid=ug.id " . "WHERE su.siteid={$siteid} AND su.role='owner'";
		$res = Db::query( $sql );

		return $cache[ $siteid ] = $res ? ( empty( $res[0]['package'] ) ? [ ] : unserialize( $res[0]['package'] ) ) : [ ];
	}

	/**
	 * 根据会员组编号获取该会员组的所有套餐信息
	 *
	 * @param int $groupId 会员组编号
	 *
	 * @return array
	 */
	public function getUserGroupPackageLists( $groupId ) {
		$group = Db::table( 'user_group' )->where( 'id', $groupId )->first();
		//用户套餐
		$packageIds = unserialize( $group['package'] );
		$packages = $group['package'] ? $this->whereIn( 'id', $packageIds )->get() : [ ];
		foreach ( $packages as $k => $p ) {
			$packages[ $k ]['modules']  = $p['modules'] ? Db::table( 'modules' )->whereIn( 'name', unserialize( $p['modules'] ) )->get() : [ ];
			$packages[ $k ]['template'] = $p['template'] ? Db::table( 'template' )->whereIn( 'name', unserialize( $p['template'] ) )->get() : [ ];
		}
		if ( in_array( - 1, $packageIds ) ) {
			array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => '', 'template' => '' ] );
		}

		return $packages;
	}
}