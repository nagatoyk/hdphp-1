<?php namespace system\service\build;

use system\model\Rule;
use system\model\RuleKeyword;

/**
 * 微信管理服务
 * Class WeChat
 * @package system\service
 */
class WeChat {
	/**
	 * 使用微信openid自动登录
	 */
	public function loginByOpenid() {
		//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
		if ( $info = \Weixin::instance( 'oauth' )->snsapiUserinfo() ) {
			$user = $this->where( 'openid', $info['openid'] )->first();
			if ( ! $user ) {
				//帐号不存在时使用openid添加帐号
				$data['openid']   = $info['openid'];
				$data['nickname'] = $info['nickname'];
				$data['icon']     = $info['headimgurl'];
				if ( $uid = ! $this->add( $data ) ) {
					message( $this->getError(), 'back', 'error' );
				}
				$user = Db::table( 'member' )->where( 'uid', $uid )->first();
			}
			Session::set( 'member', $user );

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 获取公众号类型
	 *
	 * @param $level 公众号编号
	 *
	 * @return mixed
	 */
	public function chatNameBylevel( $level ) {
		$data = [ 1 => '普通订阅号', 2 => '普通服务号', 3 => '认证订阅号', 4 => '认证服务号/认证媒体/政府订阅号' ];

		return $data[ $level ];
	}

	/**
	 * 保存回复规则
	 *
	 * @param $data =[
	 *  name=>'规则名称',
	 *  module=>'模块名称',
	 *  rank=>'排序',
	 *  status=>'是否开启'
	 *  keywords=>[
	 *      module=>'模块名称',
	 *      content=>'关键词内容'
	 *      type=>'关键词类型 1: 完全匹配  2:包含  3:正则 4:直接托管',
	 *      rank=>'排序',
	 *      status=>'是否开启'
	 *  ]
	 * ];
	 *
	 * @return bool
	 */
	public function rule( $data ) {
		$Rule = new Rule();
		foreach ( $data as $field => $v ) {
			$Rule[ $field ] = $v;
		}
		$insertRid = $Rule->save();
		$rid       = empty( $data['rid'] ) ? $insertRid : $data['rid'];
		/**
		 * 添加回复关键词
		 * 先删除旧的回复规则
		 */
		Db::table( 'rule_keyword' )->where( 'rid', $rid )->delete();
		if ( isset( $data['keywords'] ) ) {
			foreach ( $data['keywords'] as $keyword ) {
				$Keyword = new RuleKeyword();
				foreach ( $keyword as $field => $value ) {
					$Keyword[ $field ] = $value;
				}
				$Keyword['rid'] = $rid;
				$Keyword->save();
			}
		}

		return $rid;
	}

	/**
	 * 删除回复规则与回复关键字
	 *
	 * @param $rid
	 *
	 * @return bool
	 */
	public function removeRule( $rid ) {
		$tables = [
			'rule',
			'rule_keyword',
			'reply_cover',
			'reply_basic',
			'reply_image',
			'reply_news'
		];
		if ( $rid ) {
			foreach ( $tables as $tab ) {
				Db::table( $tab )->where( 'rid', $rid )->delete();
			}
		}

		return TRUE;
	}
}