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

class ModuleSetting extends Model {
	protected $table            = 'module_setting';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'setting', 'required', '网站配置不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ]
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'setting', 'serialize', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'module', 'autoGetModule', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	protected function autoGetModule() {
		return v( 'module.name' );
	}

	/**
	 * 获取模块配置
	 *
	 * @param string $module 模块名称
	 *
	 * @return array
	 */
	public function getModuleConfig( $module = NULL ) {
		$module  = $module ?: v( 'module.name' );
		$setting = $this->where( 'siteid', SITEID )->where( 'module', $module )->pluck( 'setting' );

		return $setting ? unserialize( $setting ) : [ ];
	}
}