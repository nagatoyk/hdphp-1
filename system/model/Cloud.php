<?php namespace system\model;

use hdphp\model\Model;

/**
 * 后盾云
 * Class Cloud
 * @package system\model
 * @author 向军
 */
class Cloud extends Model {
	protected $table = 'cloud';
	protected $validate
	                 = [
		];
	protected $auto
	                 = [
			[ 'uid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'username', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'AppID', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'AppSecret', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'versionCode', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'releaseCode', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		];
}