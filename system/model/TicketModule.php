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
 * 卡券使用模块
 * Class TicketModule
 * @package system\model
 * @author 向军
 */
class TicketModule extends Model {
	protected $table = 'ticket_module';
	protected $auto
	                 = [
			[ 'siteid', 'autoSiteid', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	protected function autoSiteid() {
		return v( 'site.siteid' );
	}

	/**
	 * 获取指定卡券允许使用的模块
	 *
	 * @param int $tmid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketModules( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}
		$module = $this->where( 'siteid', Session::get( 'siteid' ) )->where( 'tid', $tid )->lists( 'module' );

		return $module ? Db::table( 'modules' )->whereIn( 'name', $module )->get() : [ ];
	}
}