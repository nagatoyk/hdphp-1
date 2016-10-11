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
 * 套餐模型
 * Class Package
 * @package system\model
 */
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
	 * @param $id
	 */
	public function remove( $id ) {
		return $this->delete( $id );
	}
}