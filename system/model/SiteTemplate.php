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
 * 站点扩展模板管理
 * Class SiteTemplate
 * @package system\model
 */
class SiteTemplate extends Model {
	protected $table    = 'site_template';
	protected $validate = [ ];

	/**
	 * 获取站点扩展模板数据
	 *
	 * @param $siteid 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplates( $siteid ) {
		$template = $this->where( 'siteid', $siteid )->lists( 'template' );

		return $template ? Db::table( 'template' )->whereIn( 'name', $template )->get() : [ ];
	}

	/**
	 * 获取站点扩展模板
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplateName( $siteid ) {
		return $this->where( 'siteid', $siteid )->lists( 'template' );
	}
}