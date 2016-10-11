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
 * 站点模型
 * Class Site
 * @package system\model
 */
class Site extends Model {
	protected $table = 'site';
	protected $validate
	                 = [
			[ 'name', 'required', '站点名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'name', 'unique', '站点名称已经存在', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'description', 'required', '网站描述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
	                 = [
			[ 'icp', '', 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'weid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'allfilesize', 200, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'description', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'domain', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'module', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		];

	/**
	 * 删除(注销)站点
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return bool
	 */
	public function remove( $siteid ) {
		//站点关联表(删除站点时使用)
		$tables = [
			'balance',//会员余额
			'core_attachment',//附件字段
			'credits_record',//积分变动记录
			'member',//会员表
			'member_address',
			'member_fields',
			'member_group',
			'module_setting',
			'pay',
			'rule',
			'rule_keyword',
			'reply_cover',
			'reply_basic',
			'reply_image',
			'reply_news',
			'site',//站点表
			'site_modules',//站点模块
			'site_package',
			'site_setting',//站点设置
			'site_template',
			'site_user',//站点操作员
			'site_wechat',//微信
			'ticket',
			'ticket_groups',
			'ticket_module',
			'ticket_record',
			'user_permission',
			'web',
			'web_article',//文章
			'web_category',//栏目
			'web_nav',//导航
			'web_page',//微官网页面(快捷导航/专题页面)
			'web_slide',//站点幻灯图
			'user_permission',//用户权限分配
		];
		foreach ( $tables as $t ) {
			Db::table( $t )->where( 'siteid', $siteid )->delete();
		}
		//删除缓存
		$keys = [ 'access', 'setting', 'wechat', 'site', 'modules', 'module_binding' ];
		foreach ( $keys as $key ) {
			d( "{$key}:{$siteid}", '[del]' );
		}

		return TRUE;
	}
}