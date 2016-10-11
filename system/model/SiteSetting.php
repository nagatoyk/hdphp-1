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
 * 站点配置管理
 * Class SiteSetting
 * @package system\model
 */
class SiteSetting extends Model {
	protected $table     = 'site_setting';
	protected $allowFill = [ '*' ];
	protected $validate  = [ ];
	protected $auto
	                     = [
			[ 'grouplevel', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'default_template', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'welcome', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'default_message', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'smtp', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'smtp', 'serialize', 'function', self::EXIST_AUTO, self::MODEL_UPDATE ],
			[ 'pay', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'pay', 'serialize', 'function', self::EXIST_AUTO, self::MODEL_UPDATE ],
			[ 'creditnames', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'creditnames', 'serialize', 'function', self::EXIST_AUTO, self::MODEL_UPDATE ],
			[ 'creditbehaviors', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'creditbehaviors', 'serialize', 'function', self::EXIST_AUTO, self::MODEL_UPDATE ],
			[ 'register', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'register', 'serialize', 'function', self::EXIST_AUTO, self::MODEL_UPDATE ],
		];
}