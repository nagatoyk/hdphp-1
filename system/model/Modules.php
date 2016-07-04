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
		];
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
	 * 获取站点模块数据
	 * 包括站点套餐内模块和为站点独立添加的模块
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getSiteAllModules( $siteid = NULL, $cache = TRUE ) {
		$siteid = $siteid ?: SITEID;
		if ( empty( $siteid ) ) {
			throw new \Exception( '$siteid 参数错误' );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//读取缓存
		if ( $cache ) {
			if ( $data = d( "modules:{$siteid}" ) ) {
				return $data;
			}
		}
		//获取站点可使用的所有套餐
		$package = ( new Package() )->getSiteAllPackageData( $siteid );
		$modules = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$modules = $this->get();
		} else {
			$moduleNames = [ ];
			foreach ( $package as $p ) {
				$moduleNames = array_merge( $moduleNames, $p['modules'] );
			}
			$moduleNames = array_merge( $moduleNames, ( new SiteModules() )->getSiteExtModulesName( $siteid ) );
			if ( ! empty( $moduleNames ) ) {
				$modules = $this->whereIn( 'name', $moduleNames )->get();
			}
		}
		//加入系统模块
		$modules = array_merge( $modules, $this->where( 'is_system', 1 )->get() );
		foreach ( $modules as $k => $m ) {
			$m['subscribes']  = unserialize( $m['subscribes'] );
			$m['processors']  = unserialize( $m['processors'] );
			$m['permissions'] = unserialize( $m['permissions'] );
			$binds            = Db::table( 'modules_bindings' )->where( 'module', $m['name'] )->get();
			foreach ( $binds as $b ) {
				$m['budings'][ $b['entry'] ][] = $b;
			}
			$modules[ $k ] = $m;
		}

		d( "modules:{$siteid}", $modules );

		return $cache[ $siteid ] = $modules;
	}

	/**
	 * 删除模块
	 *
	 * @param string $module 模块名称
	 * @param int $delRule
	 *
	 * @return bool
	 */
	public function remove( $module, $delRule = 0 ) {
		//删除封面关键词数据
		if ( $delRule ) {
			//删除模块封面数据
			if ( $coverRids = Db::table( 'reply_cover' )->where( 'module', $_GET['module'] )->lists( 'rid' ) ) {
				Db::table( 'rule' )->whereIn( 'rid', $coverRids )->delete();
				Db::table( 'rule_keyword' )->whereIn( 'rid', $coverRids )->delete();
				Db::table( 'reply_cover' )->where( 'module', $_GET['module'] )->delete();
			}
			//删除模块回复规则列表
			Db::table( 'rule' )->where( 'module', $module )->delete();
			Db::table( 'rule_keyword' )->where( 'module', $module )->delete();
		}
		//删除模块数据
		$this->where( 'name', $module )->delete();
		//删除站点模块
		Db::table( 'site_modules' )->where( 'module', $module )->delete();
		//模块设置
		Db::table( 'module_setting' )->where( 'module', $module )->delete();
		//代金券使用的模块
		Db::table( 'ticket_module' )->where( 'module', $module )->delete();
		//删除模块动作数据
		Db::table( 'modules_bindings' )->where( 'module', $module )->delete();
		//更新套餐数据
		$package = Db::table( 'package' )->get();
		foreach ( $package as $p ) {
			$p['modules'] = unserialize( $p['modules'] );
			if ( $k = array_search( $_GET['module'], $p['modules'] ) ) {
				unset( $p['modules'][ $k ] );
			}
			$p['modules'] = serialize( $p['modules'] );
			Db::table( 'package' )->where( 'id', $p['id'] )->update( $p );
		}
		//更新站点缓存
		$siteids   = Db::table( 'site' )->lists( 'siteid' );
		$siteModel = new Site();
		foreach ( $siteids as $siteid ) {
			$siteModel->updateSiteCache( $siteid );
		}

		return TRUE;
	}

	/**
	 * 按行业获取模块列表
	 *
	 * @param array $modules 限定模块(只有这些模块获取)
	 *
	 * @return array
	 */
	public function getModulesByIndustry( $modules = [ ] ) {
		$data = [ ];
		foreach ( v( 'modules' ) as $m ) {
			if ( ! empty( $modules ) && ! in_array( $m['name'], $modules ) || $m['is_system'] == 1 ) {
				continue;
			}
			$data[ $this->industry[ $m['industry'] ] ][] = [
				'title' => $m['title'],
				'name'  => $m['name']
			];
		}

		return $data;
	}
}