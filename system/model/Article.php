<?php namespace system\model;

use hdphp\model\Model;

/**
 * 文章栏目管理
 * Class ArticleCategory
 * @package system\model
 * @author 向军
 */
class Article extends Model {
	protected $table     = 'article';
	protected $allowFill = [ '*' ];
	protected $validate
	                     = [
			[ 'title', 'required', '栏目标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序只能为0~255', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'cid', 'required', '栏目不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'content', 'required', '文章内容不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'url', 'http', '链接格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                     = [
			[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'click', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'thumb', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'content', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'template', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		];

	public function lists() {
		return $this->join( 'article_category', 'article.cid', '=', 'article_category.id' )
		            ->field( 'article.*,article_category.title cat_title' )
		            ->get();
	}
}