<?php namespace system\service\build;
/**
 * 模板管理服务
 * Class Template
 * @package system\service\build
 */
class Template {
	/**
	 * 获取站点扩展模板数据
	 *
	 * @param $siteid 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplates( $siteid ) {
		$template = model( 'SiteTemplate' )->where( 'siteid', $siteid )->lists( 'template' );

		return $template ? model( 'template' )->whereIn( 'name', $template )->get() : [ ];
	}

	/**
	 * 获取站点扩展模板
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplateName( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;

		return Db::table( 'site_template' )->where( 'siteid', $siteId )->lists( 'template' );
	}

	/**
	 * 获取站点所有模板
	 *
	 * @param int $siteId 站点编号
	 * @param string $industry 模板类型
	 * @param string $module 模块
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function getSiteAllTemplate( $siteId = 0, $industry = '', $module = '' ) {
		$siteId = $siteId ?: SITEID;
		if ( empty( $siteId ) ) {
			throw new \Exception( '$siteid 参数错误' );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		$db = Db::table( 'template' );
		//获取站点可使用的所有套餐
		$package   = service( 'package' )->getSiteAllPackageData( $siteId );
		$templates = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐时可以使用模板
			if ( $industry ) {
				$db->where( 'industry', $industry );
			}
			if ( $module ) {
				$db->where( 'module', $module );
			}
			$templates = $db->get();
		} else {
			$templateNames = [ ];
			foreach ( $package as $p ) {
				$templateNames = array_merge( $templateNames, $p['template'] );
			}
			$templateNames = array_merge( $templateNames, $this->getSiteExtTemplateName( $siteId ) );
			if ( ! empty( $templateNames ) ) {
				if ( $industry ) {
					$db->where( 'industry', $industry );
				}
				if ( $module ) {
					$db->where( 'module', $module );
				}
				$templates = $db->whereIn( 'name', $templateNames )->get();
			}
		}

		return $cache[ $siteId ] = $templates;
	}

	/**
	 * 获取模板位置数据
	 *
	 * @param $tid 模板编号
	 *
	 * @return array
	 * array(
	 *  1=>'位置1',
	 *  2=>'位置2',
	 * )
	 */
	public function getPositionData( $tid ) {
		$position = Db::table( 'template' )->where( 'tid', $tid )->pluck( 'position' );
		$data     = [ ];
		if ( $position ) {
			for ( $i = 1;$i <= $position;$i ++ ) {
				$data[ $i ] = '位置' . $i;
			}
		}

		return $data;
	}

	/**
	 * 获取模板类型
	 * @return array
	 */
	public function getTitleLists() {
		return [
			'often'       => '常用模板',
			'rummery'     => '酒店',
			'car'         => '汽车',
			'tourism'     => '旅游',
			'drink'       => '餐饮',
			'realty'      => '房地产',
			'medical'     => '医疗保健',
			'education'   => '教育',
			'cosmetology' => '健身美容',
			'shoot'       => '婚纱摄影',
			'other'       => '其他'
		];
	}

	/**
	 * 获取站点的模板数据
	 *
	 * @param int $webid 站点编号
	 *
	 * @return array 模板数据
	 */
	public function getTemplateData( $webid ) {
		$name = Db::table( 'web' )->where( 'siteid', SITEID )->where( 'id', $webid )->pluck( 'template_name' );
		if ( $name ) {
			return Db::table( 'template' )->where( 'name', $name )->first();
		}
	}
}