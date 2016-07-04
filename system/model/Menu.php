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
 * 菜单管理
 * Class Menu
 * @package system\model
 */
class Menu extends Model {
	protected $table = 'menu';

	protected $validate
		= [
			[ 'title', 'required', '标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'permission', 'unique', '权限标识已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'url', 'unique', '菜单链接已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'append_url', 'unique', '右侧图标链接已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'orderby', 'num:0,255', '排序数字为0~255', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'is_display', 'num:0,1', '[显示]字段参数错误', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
		= [
			[ 'is_display', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'mark', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'is_system', 0, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'icon', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'append_url', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'permission', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'pid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];

	/**
	 * 获取菜单组合的父子级多维数组
	 * @return mixed
	 */
	public function getLevelMenuLists() {
		return Data::channelLevel( $this->get(), 0, '', 'id', 'pid' );
	}

	/**
	 * 获取当前帐号后台访问菜单
	 * @return mixed
	 */
	public function getMenus( $show = TRUE ) {
		//移除用户没有使用权限的菜单
		$permission = Db::table( 'user_permission' )
		                ->where( 'siteid', SITEID )
		                ->where( 'uid', Session::get( 'user.uid' ) )
		                ->where( 'type', 'system' )
		                ->pluck( 'permission' );
		$menus      = $this->get();
		if ( $permission ) {
			$permission = explode( '|', $permission );
			$tmp        = $menus;
			foreach ( $tmp as $k => $m ) {
				if ( $m['permission'] != '' && ! in_array( $m['permission'], $permission ) ) {
					unset( $menus[ $k ] );
				}
			}
		}
		$menus = Data::channelLevel( $menus, 0, '', 'id', 'pid' );
		//移除没有三级菜单的一级与二级菜单
		$tmp = $menus;
		foreach ( $tmp as $k => $t ) {
			foreach ( $t['_data'] as $n => $d ) {
				if ( empty( $d['_data'] ) ) {
					unset( $menus[ $k ]['_data'][ $n ] );
				}
			}
			if ( empty( $menus[ $k ]['_data'] ) ) {
				unset( $menus[ $k ] );
			}
		}
		//插件模块列表
		$allowModules = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', Session::get( 'user.uid' ) )->lists( 'type' );
		//获取模块按行业类型
		$modules = ( new Modules() )->getModulesByIndustry( $allowModules );
		//模块菜单
		foreach ( v( 'modules' ) as $v ) {
			if ( $v['name'] == v( 'module.name' ) ) {
				$moduleLinks = $v;
				break;
			}
		}
		if ( $show ) {
			View::with( '_site_menu_', $menus );
			View::with( '_site_menu_modules_', $modules );
			View::with( '_site_modules_menu_', $moduleLinks );
		}

		return $menus;
	}

}