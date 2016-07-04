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

class Web extends Model {
	protected $table            = 'web';
	protected $denyInsertFields = [ 'id' ];
	protected $validate
	                            = [
			[ 'title', 'required', '网站标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'template_tid', 'required', '请选择网站模板风格', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'domain', 'http', '域名格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'status', 'num:0,1', '网站状态只能为1或9', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                            = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'domain', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];

	/**
	 * 获取站点的模板数据
	 *
	 * @param int $webid 站点编号
	 *
	 * @return array 模板数据
	 */
	public function getTemplateData( $webid ) {
		$template_tid = $this->where( 'siteid', SITEID )->where( 'id', $webid )->pluck( 'template_tid' );

		return Db::table( 'template' )->where( 'tid', $template_tid )->first();
	}

	/**
	 * 获取站点的所有官网数据
	 * @return array
	 */
	public function getSiteWebs() {
		$web = $this->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->get();

		return Arr::string_to_int( $web );
	}

	/**
	 * 检测当前站点中是否存在官网
	 *
	 * @param int $id 站点编号
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return $this->where( 'siteid', SITEID )->where( 'id', $id )->first() ? TRUE : FALSE;
	}


	/**
	 * 获取默认站点
	 * @return array
	 */
	public function getDefaultWeb() {
		return $this->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->first();
	}

	//删除站点
	public function remove( $webid ) {
		//删除栏目
		Db::table( 'web_category' )->where( 'web_id', $webid )->where( 'siteid', SITEID )->delete();
		//删除文章
		Db::table( 'web_article' )->where( 'web_id', $webid )->delete();
		//删除站点
		Db::table( 'web' )->where( 'id', $webid )->delete();

		return TRUE;
	}
}