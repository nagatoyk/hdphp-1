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
 * 会员字段信息表
 * 用于标识用户个人信息字段的中文标识与排序/显示
 * Class MemberFields
 * @package system\model
 */
class MemberFields extends Model {
	protected $table = 'member_fields';

	/**
	 * 初始化站点的会员字段信息数据
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return bool
	 */
	public function InitializationSiteTableData( $siteid ) {
		$this->where( 'siteid', $siteid )->delete();
		$profile_fields = Db::table( 'profile_fields' )->get();
		foreach ( $profile_fields as $f ) {
			$d['siteid']  = $siteid;
			$d['field']   = $f['field'];
			$d['title']   = $f['title'];
			$d['orderby'] = $f['orderby'];
			$d['status']  = $f['status'];
			$this->add( $d );
		}

		return TRUE;
	}
}