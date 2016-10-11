<?php namespace system\service\build;

use system\model\Modules;

/**
 * 模块管理服务
 * Class Module
 * @package system\service\build
 */
class Module extends Modules {
	protected $industry
		= [
			'business'  => '主要业务',
			'customer'  => '客户关系',
			'marketing' => '营销与活动',
			'tools'     => '常用服务与工具',
			'industry'  => '行业解决方案',
			'other'     => '其他'
		];

	/**
	 * 验证当前用户在当前站点
	 * 能否使用当前模块
	 * 具体模块动作需要使用权限标识独立验证
	 * @return bool
	 * @throws \Exception
	 */
	public function verifyModuleAccess() {
		//操作员验证
		if ( ! service( 'user' )->isOperate() ) {
			return FALSE;
		}
		if ( v( "module.is_system" ) == 1 ) {
			return TRUE;
		} else {
			//站点是否含有模块
			if ( ! $this->hasModule( SITEID, v( 'module.name' ) ) ) {
				return FALSE;
			}
			//插件模块
			$allowModules = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', Session::get( 'user.uid' ) )->lists( 'type' );
			if ( ! empty( $allowModules ) ) {
				return in_array( v( 'module.name' ), $allowModules );
			}

			return TRUE;
		}
	}

	/**
	 * 调用模块方法
	 *
	 * @param string $module 模块.方法
	 * @param array $params 方法参数
	 *
	 * @return mixed
	 */
	function api( $module, $params ) {
		static $instance = [ ];
		$info = explode( '.', $module );
		if ( ! isset( $instance[ $module ] ) ) {
			$data                = Db::table( 'modules' )->where( 'name', $info[0] )->first();
			$class               = 'addons\\' . $data['name'] . '\api';
			$instance[ $module ] = new $class;
		}

		return call_user_func_array( [ $instance[ $module ], $info[1] ], $params );
	}

	/**
	 * 验证站点是否拥有模块
	 *
	 * @param string $siteid 站点编号
	 * @param string $module 模块名称
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function hasModule( $siteid = NULL, $module = NULL ) {
		$siteid = $siteid ?: SITEID;
		$module = $module ?: v( 'module.name' );
		if ( empty( $siteid ) || empty( $module ) ) {
			return FALSE;
		}
		$modules = $this->getSiteAllModules( $siteid );
		foreach ( $modules as $m ) {
			if ( strtolower( $module ) == strtolower( $m['name'] ) ) {
				return TRUE;
			}
		}
	}

	/**
	 * 获取站点模块数据
	 * 包括站点套餐内模块和为站点独立添加的模块
	 *
	 * @param int $siteId 站点编号
	 * @param bool $readFromCache
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function getSiteAllModules( $siteId = 0, $readFromCache = TRUE ) {
		$siteId = $siteId ?: SITEID;
		if ( empty( $siteId ) ) {
			throw new \Exception( '$siteid 参数错误' );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		//读取缓存
		if ( $readFromCache ) {
			if ( $data = d( "modules:{$siteId}" ) ) {
				return $data;
			}
		}
		//获取站点可使用的所有套餐
		$package = service( 'package' )->getSiteAllPackageData( $siteId );
		$modules = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$modules = Db::table( 'modules' )->get() ?: [ ];
		} else {
			$moduleNames = [ ];
			foreach ( $package as $p ) {
				$moduleNames = array_merge( $moduleNames, $p['modules'] );
			}
			$moduleNames = array_merge( $moduleNames, $this->getSiteExtModulesName( $siteId ) );
			if ( ! empty( $moduleNames ) ) {
				$res     = Db::table( 'modules' )->whereIn( 'name', $moduleNames )->get();
				$modules = $res ?: [ ];
			}
		}
		//加入系统模块
		$modules = array_merge( $modules, Db::table( 'modules' )->where( 'is_system', 1 )->get() );
		foreach ( $modules as $k => $m ) {
			$m['subscribes']  = unserialize( $m['subscribes'] ) ?: [ ];
			$m['processors']  = unserialize( $m['processors'] ) ?: [ ];
			$m['permissions'] = array_filter( unserialize( $m['permissions'] ) ?: [ ] );
			$res              = Db::table( 'modules_bindings' )->where( 'module', $m['name'] )->get();
			$binds            = $res ?: [ ];
			foreach ( $binds as $b ) {
				$m['budings'][ $b['entry'] ][] = $b;
			}
			$modules[ $k ] = $m;
		}
		d( "modules:{$siteId}", $modules );

		return $cache[ $siteId ] = $modules;
	}

	/**
	 * 当前使用的非系统模块
	 * @return array
	 */
	public function currentUseModule() {
		$menus = [ ];
		foreach ( v( 'site.modules' ) as $v ) {
			if ( $v['name'] == v( 'module.name' ) ) {
				$menus = $v;
				break;
			}
		}

		return $menus;
	}

	/**
	 * 用户在站点使用的模块列表
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getBySiteUser( $siteId = 0, $uid = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( 'user.info.uid' );
		/**
		 * 插件模块列表
		 */
		$modules = Db::table( 'user_permission' )
		             ->where( 'type', '<>', 'system' )
		             ->where( 'siteid', $siteId )
		             ->where( 'uid', $uid )
		             ->lists( 'type' ) ?: [ ];

		//获取模块按行业类型
		return $this->getModulesByIndustry( $modules );
	}

	/**
	 * 按行业获取当前站点的模块列表
	 * 根据当前使用站点拥有的模块获取
	 * 系统管理员获取所有模块
	 *
	 * @param array $modules 限定模块(只有这些模块获取)
	 *
	 * @return array
	 */
	public function getModulesByIndustry( $modules = [ ] ) {
		$data = [ ];
		foreach ( (array) v( 'site.modules' ) as $m ) {
			if ( ( empty( $modules ) || in_array( $m['name'], $modules ) ) && $m['is_system'] == 0 ) {
				$data[ $this->industry[ $m['industry'] ] ][] = [
					'title' => $m['title'],
					'name'  => $m['name']
				];
			}
		}

		return $data;
	}

	/**
	 * 模块标题列表
	 * @return array
	 */
	public function getModuleTitles() {
		return [
			'business'  => '主要业务',
			'customer'  => '客户关系',
			'marketing' => '营销与活动',
			'tools'     => '常用服务与工具',
			'industry'  => '行业解决方案',
			'other'     => '其他'
		];
	}

	/**
	 * 获取模块配置
	 *
	 * @param string $module 模块名称
	 *
	 * @return array
	 */
	public function getModuleConfig( $module = '' ) {
		$module  = $module ?: v( 'module.name' );
		$setting = Db::table( 'module_setting' )->where( 'siteid', SITEID )->where( 'module', $module )->pluck( 'setting' );

		return $setting ? unserialize( $setting ) : [ ];
	}

	/**
	 * 获取站点扩展模块数据
	 *
	 * @param $siteid 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtModules( $siteid ) {
		$module = Db::table( 'site_modules' )->where( 'siteid', $siteid )->lists( 'module' );

		return $module ? Db::table( 'modules' )->whereIn( 'name', $module )->get() : [ ];
	}

	/**
	 * 获取站点扩展模块名称列表
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtModulesName( $siteId ) {
		return Db::table( 'site_modules' )->where( 'siteid', $siteId )->lists( 'module' ) ?: [ ];
	}
}