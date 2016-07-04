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
 * 图文回复
 * Class ReplyNews
 * @package system\model
 * @author 向军
 */
class ReplyNews extends Model {
	protected $table            = 'reply_news';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'rid', 'required', '回复规则编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'title', 'required', '标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'thumb', 'required', '图文消息图片不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'pid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'author', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'content', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'rank', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'rank', 'time', 'function', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
		];

}