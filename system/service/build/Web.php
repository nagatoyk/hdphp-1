<?php namespace system\service\build;
/**
 * 微站管理服务
 * Class Web
 * @package system\service\build
 */
class Web {
	/**
	 * 获取站点的所有官网数据
	 * @return array
	 */
	public function getSiteWebs() {
		return Db::table( 'web' )->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->get();
	}

	/**
	 * 检测当前站点中是否存在官网
	 *
	 * @param int $id 站点编号
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return Db::table( 'web' )->where( 'siteid', SITEID )->where( 'id', $id )->first() ? TRUE : FALSE;
	}

	/**
	 * 获取默认站点
	 * @return array
	 */
	public function getDefaultWeb() {
		return Db::table( 'web' )->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->first();
	}

	/**
	 * 删除文章站点
	 * @param $webId 站点编号
	 *
	 * @return bool
	 */
	public function del( $webId ) {
		//删除栏目
		Db::table( 'web_category' )->where( 'web_id', $webId )->delete();
		//删除文章
		Db::table( 'web_article' )->where( 'web_id', $webId )->delete();
		//删除站点
		Db::table( 'web' )->where( 'id', $webId )->delete();
		//删除回复规则
		$rid = Db::table( 'reply_cover' )->where( 'web_id', $webId )->pluck( 'rid' );
		service( 'WeChat' )->removeRule( $rid );

		return TRUE;
	}
}