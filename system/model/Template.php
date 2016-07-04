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
	 *
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 * @throws \Exception
	 */
	/**
	 * 获取站点所有模板
	 *
	 * @param int $siteid 站点编号
	 * @param string $type 模板类型
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getSiteAllTemplate( $siteid = NULL, $type = NULL ) {
		$siteid = $siteid ?: Session::get( 'siteid' );
		if ( empty( $siteid ) ) {
			throw new \Exception( '$siteid 参数错误' );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//获取站点可使用的所有套餐
		$package   = ( new Package() )->getSiteAllPackageData( $siteid );
		$templates = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$templates = $this->get();
		} else {
			$templateNames = [ ];
			foreach ( $package as $p ) {
				$templateNames = array_merge( $templateNames, $p['template'] );
			}
			$templateNames = array_merge( $templateNames, ( new SiteTemplate() )->getSiteExtTemplateName( $siteid ) );
			if ( ! empty( $templateNames ) ) {
				if ( $type ) {
					$this->where( 'type', $type );
				}
				$templates = $this->whereIn( 'name', $templateNames )->get();
			}
		}

		return $cache[ $siteid ] = $templates;
	}

	/**
	 * 获取模板位置数据
	 *
	 * @param $tid 模板编号
	 *
	 * @return array
	 * array(
	 *  1=>'位置1',
	 *  2=>'位置2',
	 * )
	 */
	public function getPositionData( $tid ) {
		$position = $this->where( 'tid', $tid )->pluck( 'position' );
		$data     = [ ];
		if ( $position ) {
			for ( $i = 1;$i <= $position;$i ++ ) {
				$data[ $i ] = '位置' . $i;
			}
		}

		return $data;
	}

	/**
	 * 删除模板
	 * @param $name 模板标识
	 *
	 * @return bool
	 */
	public function remove( $name ) {
		//删除模板数据
		$this->where( 'name', $name )->delete();
		//更新套餐数据
		$package = Db::table( 'package' )->get();
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
			$siteModel->updateSiteCache( $siteid );
		}

		return TRUE;
	}

}