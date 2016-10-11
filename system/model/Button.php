<?php namespace system\model;

use hdphp\model\Model;

/**
 * 微信菜单管理
 * Class Button
 * @package system\model
 * @author 向军
 */
class Button extends Model {
	protected $table = 'button';
	protected $validate
	                 = [
			[ 'title', 'required', '标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ]

		];
	protected $auto
	                 = [
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'status', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'siteid', SITEID, 'string', self::EMPTY_AUTO, self::MODEL_BOTH ],
		];
}