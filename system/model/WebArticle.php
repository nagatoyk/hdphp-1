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
 * 文章管理
 * Class WebArticle
 * @package system\model
 * @author 向军
 */
class WebArticle extends Model {
	protected $table            = 'web_article';
	protected $denyInsertFields = [ 'aid' ];
	protected $validate
	                            = [
			[ 'title', 'required', '文章标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'category_cid', 'required', '请选择文章栏目', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'content', 'required', '文章内容不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序只能是0~255之间的数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'rid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'iscommend', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'ishot', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'template_tid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'source', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'author', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'orderby', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'linkurl', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'orderby', 'intval', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'click', 'intval', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'thumb', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];
}