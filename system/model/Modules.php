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
 * 模块管理模型
 * Class Modules
 * @package system\model
 */
class Modules extends Model {
	protected $table            = 'modules';
	protected $denyInsertFields = [ 'mid' ];
	protected $validate
	                            = [
			[ 'name', 'regexp:/^\w+$/', '模块标识符, 对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能由字母数字下划线组成', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'industry', 'required', '模块类型不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'title', 'maxlen:10', '模块名称不能超过10个字符', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'version', 'regexp:/^\d+\.?\d*$/', '版本只能为数字', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'resume', 'required', '模块简述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'detail', 'required', '详细介绍不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'url', 'required', '发布url不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'thumb', 'required', '模块缩略图不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'cover', 'required', '模块封面图片不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
	                            = [
			[ 'name', 'strtolower', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'is_system', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'subscribes', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'processors', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'setting', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'rule', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'permissions', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'locality', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		];

	/**
	 * 删除模块
	 *
	 * @param string $module 模块名称
	 * @param int $removeData 删除模块数据
	 *
	 * @return bool
	 */
	public function remove( $module, $removeData = 0 ) {
		//删除封面关键词数据
		if ( $removeData ) {
			//本地安装的模块删除处理
			$xmlFile = 'addons/' . $module . '/manifest.xml';
			if ( is_file( $xmlFile ) ) {
				$manifest = Xml::toArray( file_get_contents( $xmlFile ) );
				//卸载数据
				$installSql = trim( $manifest['manifest']['uninstall']['@cdata'] );
				if ( ! empty( $installSql ) ) {
					if ( preg_match( '/.php$/', $installSql ) ) {
						$file = 'addons/' . $module . '/' . $installSql;
						if ( ! is_file( $file ) ) {
							$this->error = '卸载文件:' . $file . '不存在';

							return;
						}
						require $file;
					} else {
						\Schema::sql( $installSql );
					}
				}
			}
			//删除模块回复规则列表
			$rids = Db::table( 'rule' )->where( 'module', $module )->lists( 'rid' ) ?: [ ];
			foreach ( $rids as $rid ) {
				service( 'WeChat' )->removeRule( $rid );
			}
			//删除站点模块
			Db::table( 'site_modules' )->where( 'module', $module )->delete();
			//模块设置
			Db::table( 'module_setting' )->where( 'module', $module )->delete();
			//代金券使用的模块
			Db::table( 'ticket_module' )->where( 'module', $module )->delete();
		}
		//删除模块数据
		$this->where( 'name', $module )->delete();
		//删除模块动作数据
		Db::table( 'modules_bindings' )->where( 'module', $module )->delete();
		//更新套餐数据
		$package = Db::table( 'package' )->get();
		if ( $package ) {
			foreach ( $package as $p ) {
				$p['modules'] = unserialize( $p['modules'] ) ?: [ ];
				if ( $k = array_search( $_GET['module'], $p['modules'] ) ) {
					unset( $p['modules'][ $k ] );
				}
				$p['modules'] = serialize( $p['modules'] );
				Db::table( 'package' )->where( 'id', $p['id'] )->update( $p );
			}
		}
		//更新所有站点缓存
		service( 'site' )->updateAllCache();

		return TRUE;
	}
}