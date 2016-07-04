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
 * 封面回复
 * Class ReplyCover
 * @package system\model
 * @author 向军
 */
class ReplyCover extends Model {
	protected $table            = 'reply_cover';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'rid', 'required', '回复规则编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'title', 'required', 'reply_cover表的title字段不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'description', 'required', '描述不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'thumb', 'required', '图片不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'web_id', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'module', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'do', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];

}