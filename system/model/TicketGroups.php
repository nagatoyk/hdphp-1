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
 * 卡券使用会员组
 * Class TicketGroups
 * @package system\model
 * @author 向军
 */
class TicketGroups extends Model {
	protected $table = 'ticket_groups';
	protected $auto
	                 = [
			[ 'siteid', 'autoSiteid', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	protected function autoSiteid() {
		return v( 'site.siteid' );
	}

	/**
	 * 获取指定卡券允许使用的用户组
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketGroupIds( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}

		return $this->where( 'siteid', v( 'site.siteid' ) )->where( 'tid', $tid )->lists( 'group_id' );
	}
}