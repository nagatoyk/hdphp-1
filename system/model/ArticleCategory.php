<?php namespace system\model;

use hdphp\model\Model;

/**
 * 文章栏目管理
 * Class ArticleCategory
 * @package system\model
 * @author 向军
 */
class ArticleCategory extends Model {
	protected $table = 'article_category';
	protected $validate
	                 = [
			[ 'title', 'required', '栏目标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序只能为0~255', self::MUST_VALIDATE, self::MODEL_BOTH ]
		];
	protected $auto
	                 = [
			[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'template', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ]
		];

	//删除栏目
	public function remove( $id ) {
		//删除文章
		Db::table( 'article' )->where( 'cid', $id )->delete();

		return $this->where( 'id', $id )->delete();
	}
}