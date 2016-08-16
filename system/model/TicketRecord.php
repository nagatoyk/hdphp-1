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
 * 卡券使用管理
 * Class TicketRecord
 * @package system\model
 * @author 向军
 */
class TicketRecord extends Model {
	protected $table = 'ticket_record';

	/**
	 * 核销卡券
	 *
	 * @param string $id 编号
	 *
	 * @return bool
	 */
	public function verification( $id ) {
		$data['id']     = $id;
		$data['status'] = 2;

		return $this->save( $data );
	}

	/**
	 * 获取会员卡券兑换数量
	 *
	 * @param int $tid 卡券编号
	 * @param int $uid 会员编号
	 *
	 * @return mixed
	 */
	public function getNumByTid( $tid, $uid ) {
		return $this->where( 'tid', $tid )->where( 'uid', $uid )->count();
	}
}