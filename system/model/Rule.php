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
 * 微信回复规则
 * Class Rule
 * @package system\model
 * @author 向军
 */
class Rule extends Model {
	protected $table            = 'rule';
	protected $denyInsertFields = [ 'rid' ];
	protected $validate
	                            = [
			[ 'rank', 'num:0,255', '排序数字在0~255之间', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'name', 'required', '规则名称不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'module', 'required', 'module字段不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'rid', 'validateRid', '回复规则不属于本网站', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];

	protected function validateRid( $field, $val ) {
		$rule = Db::table( 'rule' )->where( 'rid', $val )->first();
		if ( ! empty( $rule ) && $rule['siteid'] != SITEID ) {
			return FALSE;
		}

		return TRUE;
	}

	protected $filter
		= [
			[ 'rid', self::EMPTY_FILTER, self::MODEL_BOTH ],
		];

	protected $auto
		= [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'rank', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ]
		];
}