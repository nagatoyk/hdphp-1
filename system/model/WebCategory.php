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
 * 官网栏目管理
 * Class WebCategory
 * @package system\model
 * @author 向军
 */
class WebCategory extends Model {
	protected $table            = 'web_category';
	protected $denyInsertFields = [ 'cid' ];
	protected $validate
	                            = [
			[ 'title', 'required', '栏目标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序数字为0~255之间的字符', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'status', 'num:0,1', '栏目状态为0或1', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'pid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'orderby', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'icontype', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'template_tid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'linkurl', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'ishomepage', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'css', 'json_encode', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'isnav', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'web_id', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];
}