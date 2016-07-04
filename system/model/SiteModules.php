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
 * 站点扩展模块管理
 * Class SiteModules
 * @package system\model
 */
class SiteModules extends Model {
	protected $table = 'site_modules';
	protected $validate
	                 = [
			[ 'siteid', 'required', '站点编号不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'module', 'required', '模块编号不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
		];

	/**
	 * 获取站点扩展模块数据
	 *
	 * @param $siteid 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtModules( $siteid ) {
		$module = $this->where( 'siteid', $siteid )->lists( 'module' );

		return $module ? Db::table( 'modules' )->whereIn( 'name', $module )->get() : [ ];
	}

	/**
	 * 获取站点扩展模块名称列表
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtModulesName( $siteid ) {
		return $this->where( 'siteid', $siteid )->lists( 'module' );
	}
}