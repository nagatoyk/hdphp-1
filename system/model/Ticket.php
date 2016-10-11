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
 * 卡券管理
 * Class Ticket
 * @package system\model
 * @author 向军
 */
class Ticket extends Model {
	protected $table = 'ticket';
	protected $validate
	                 = [
			[ 'title', 'required', '名称不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'title', 'unique', '名称已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'type', 'num:1,3', '卡券类型不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'condition', 'regexp:/^[0-9\.]+$/', '"满多少钱可打折或使用条件" 只能为数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'discount', 'regexp:/^[0-9\.]+$/', '"折扣或代金券面额" 只能为数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'thumb', 'required', '封面图片不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'credit', 'regexp:/^[0-9\.]+$/', '兑换方式的"积分数量"只能为数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'limit', 'regexp:/^\d+$/', '每人可使用的数量不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'amount', 'regexp:/^[0-9\.]+$/', '卡券兑换的"券总数量"不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'limit', 'regexp:/^\d+$/', '"每人可使用数量"只能为数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'limit', 'validateLimit', '"每人可使用数量" 不能大于 "卡券总数量"', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'amount', 'regexp:/^\d+$/', '"折扣券总数量"只能为数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'starttime', 'required', '使用时间不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'endtime', 'required', '使用时间不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'credittype', 'required', '积分类型不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'description', 'required', '卡券说明 不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];

	protected function validateLimit( $field, $value, $params, $data ) {
		return $data['amount'] > $data['limit'] ? TRUE : FALSE;
	}

	protected $auto
		= [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'sn', 'autoSn', 'method', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'starttime', 'autoStarttime', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'endtime', 'autoEndtime', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
		];

	protected function autoStarttime() {
		$time = preg_split( '/\s+至\s+/', $_POST['times'] );

		return strtotime( trim( $time[0] ) );
	}

	protected function autoEndtime() {
		$time = preg_split( '/\s+至\s+/', $_POST['times'] );

		return strtotime( trim( $time[1] ) );
	}

	protected function autoSn() {
		while ( TRUE ) {
			$sn = 'HD' . strtoupper( substr( md5( time() ), 0, 15 ) );
			if ( ! Db::table( 'ticket' )->where( 'sn', $sn )->get() ) {
				return $sn;
			}
		}
	}
}