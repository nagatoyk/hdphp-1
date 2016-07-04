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
 * 站点扩展套餐管理
 * Class SitePackage
 * @package system\model
 */
class SitePackage extends Model {
	protected $table = 'site_package';
	protected $validate
	                 = [
			[ 'siteid', 'required', '站点编号不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'package_id', 'required', '套餐编号不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
		];

	/**
	 * 获取为站点自定义扩展套餐编号
	 * 套餐由用户组套餐+站点扩展套餐构成
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtPackageIds( $siteid ) {
		return $this->where( 'siteid', '=', $siteid )->lists( 'package_id' ) ?: [ ];
	}
}