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
 * 积分记录
 * Class CreditsRecord
 * @package system\model
 * @author 向军
 */
class CreditsRecord extends Model {
	protected $table = 'credits_record';
	protected $validate
	                 = [
		];
	protected $auto
	                 = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'module', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
			[ 'operator', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
			[ 'createtime', 'time', 'function', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
			[ 'remark', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
		];

}