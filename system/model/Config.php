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

class Config extends Model {
	protected $table = 'config';
	protected $auto
	                 = [
			[ 'id', 'autoId', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	protected function autoId() {
		if ( ! $this->where( 'id', 1 )->get() ) {
			$this->insert( [ 'site' => '', 'upload' => '', 'register' => '' ] );
		}

		return 1;
	}

	/**
	 * 获取配置项
	 *
	 * @param string $name 名称
	 * @template m('Config')->getByName('site');
	 * @source m('Config')->getByName('site');
	 * @return array|string
	 */
	public function getByName( $name ) {
		$val = $this->where( 'id', 1 )->pluck( $name );

		return $val ? json_decode( $val, TRUE ) : [ ];
	}
}