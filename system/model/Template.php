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
 * 模板
 * Class Template
 * @package system\model
 * @author 向军
 */
class Template extends Model {
	protected $table = 'template';

	/**
	 * 删除模板
	 *
	 * @param $name 模板标识
	 *
	 * @return bool
	 */
	public function remove( $name ) {
		//删除模板数据
		$this->where( 'name', $name )->delete();
		//更新套餐数据
		$package = Db::table( 'package' )->get()?:[];
		foreach ( $package as $p ) {
			$p['template'] = unserialize( $p['template'] );
			if ( $k = array_search( $name, $p['template'] ) ) {
				unset( $p['template'][ $k ] );
			}
			$p['template'] = serialize( $p['template'] );
			Db::table( 'package' )->where( 'id', $p['id'] )->update( $p );
		}
		//更新站点缓存
		$siteids   = Db::table( 'site' )->lists( 'siteid' );
		$siteModel = new Site();
		foreach ( $siteids as $siteid ) {
			service('site')->updateCache( $siteid );
		}

		return TRUE;
	}
}