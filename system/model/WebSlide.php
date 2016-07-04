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
 * 幻灯片管理
 * Class WebSlide
 * @package system\model
 * @author 向军
 */
class WebSlide extends Model {
	protected $table            = 'web_slide';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'title', 'required', '标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'url', 'required', '链接不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'thumb', 'required', '图片不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'displayorder', 'num:0,255', '排序只能为0~255', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
		];
}