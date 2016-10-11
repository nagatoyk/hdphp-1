<?php namespace system\service\build;
/**
 * 站点后台菜单管理服务
 * Class Menu
 * @package system\service\build
 */
class Menu extends \system\model\Menu {
	/**
	 * 获取菜单组合的父子级多维数组
	 * @return mixed
	 */
	public function getLevelMenuLists() {
		$menu = Db::table( 'menu' )->get() ?: [ ];

		return Data::channelLevel( $menu ?: [ ], 0, '', 'id', 'pid' );
	}

	/**
	 * 获取当前用户在站点后台可以使用的系统菜单
	 * @return mixed
	 */
	public function menus() {
		/**
		 * 系统管理
		 * 1 移除系统菜单
		 * 2 将没有三级或二级菜单的菜单移除
		 */
		//移除用户没有使用权限的系统菜单
		$permission = Db::table( 'user_permission' )
		                ->where( 'siteid', SITEID )
		                ->where( 'uid', v( 'user.info.uid' ) )
		                ->where( 'type', 'system' )
		                ->pluck( 'permission' );
		$menus      = Db::table( 'menu' )->get();
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
			//二级菜单为空时删除些菜单
			foreach ( $t['_data'] as $n => $d ) {
				if ( empty( $d['_data'] ) ) {
					unset( $menus[ $k ]['_data'][ $n ] );
				}
			}
			//一级菜单没有子菜单时移除
			if ( empty( $menus[ $k ]['_data'] ) ) {
				unset( $menus[ $k ] );
			}
		}

		return $menus;
	}

	/**
	 * 分配菜单数据到模板
	 * @return mixed
	 */
	public function assign() {
		$links = [
			//系统菜单数据
			'menus'       => $this->menus(),
			//当前模块
			'module'      => service( 'module' )->currentUseModule(),
			//用户在站点可以使用的模块列表
			'moduleLists' => service( 'module' )->getBySiteUser()
		];
		View::with( [ '_LINKS_' => $links ] );
	}
}