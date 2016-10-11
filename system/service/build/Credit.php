<?php namespace system\service\build;

/**
 * 积分管理服务
 * Class Credit
 * @package system\service\build
 */
class Credit {
	/**
	 * 会员积分中文名称
	 *
	 * @param string $creditType 积分类型
	 *
	 * @return string
	 */
	public function getCreditTitle( $creditType ) {
		return v( 'site.setting.creditnames.' . $creditType . '.title' );
	}

	/**
	 * 更改会员积分或余额
	 *
	 * @param array $data
	 * array(
	 *  'uid'=>会员编号,
	 *  'credittype'=>积分类型,如credit1
	 *  'num'=>数量,负数为减少
	 *  'module'=>变动积分的模块
	 *  'remark'=>说明
	 * );
	 *
	 * @return bool
	 */
	public function changeCredit( array $data ) {
		if ( empty( $data['uid'] ) || empty( $data['credittype'] ) || empty( $data['num'] ) || empty( $data['remark'] ) ) {
			message( '积分变动参数错误', 'back', 'error' );
		}
		$Member         = Db::table( 'member' );
		$data['module'] = isset( $data['module'] ) ? $data['module'] : '';
		//检测兑换数量
		$user = $Member->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->first();
		if ( empty( $user ) ) {
			message( '当前站点不存在该用户', 'back', 'error' );
		}
		if ( empty( $user[ $data['credittype'] ] ) ) {
			message( '积分类型不存在', 'back', 'error' );
		}
		//动作增加或减少
		$action = $data['num'] > 0 ? 'increment' : 'decrement';
		//用户积分数量
		$userTickNum = $user[ $data['credittype'] ];
		//减少时不能小于用户现有积分
		if ( $action == 'decrement' && $userTickNum < $data['num'] ) {
			$error = $this->getCreditTitle( $data['credittype'] ) . ' 数量不够';
			message( $error, 'back', 'error' );
		}
		$num = $data['num'] > 0 ? $data['num'] : abs( $data['num'] );
		if ( ! $Member->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->$action( $data['credittype'], $num ) ) {
			$error = '修改会员 ' . $this->getCreditTitle( $data['credittype'] ) . " 失败";
			message( $error, 'back', 'error' );
		}
		//记录变量日志
		model( 'CreditsRecord' )->save( $data );
		//系统设置根据积分变动用户组时变更之...
		$user = $Member->find( $data['uid'] );
		//用户积分大于组积分的组
		$group = Db::table( 'member_group' )->where( 'credit', '<=', $user['credit1'] )->orderBy( 'credit', 'DESC' )->first();
		switch ( v( 'site.setting.grouplevel' ) ) {
			case 2:
				//根据总积分多少自动升降
				if ( $group ) {
					$Member->where( 'uid', $user['uid'] )->update( [ 'group_id' => $group['id'] ] );
				}
				break;
			case 3:
				//当前用户所在组的积分
				$userGroupCredit = Db::table( 'member_group' )->where( 'id', $user['group_id'] )->pluck( 'credit' );
				if ( $group && $group['credit'] > $userGroupCredit ) {
					$Member->where( 'uid', $user['uid'] )->update( [ 'group_id' => $group['id'] ] );
				}
				break;
		}

		return TRUE;
	}

	/**
	 * 根据积分字段获取中文描述
	 *
	 * @param $name
	 * <code>
	 *  api('credit')->getTitle('credit1');
	 * </code>
	 *
	 * @return string 积分中文描述
	 */
	public function getTitle( $name ) {
		return v( 'setting.creditnames.' . $name . '.title' );
	}
}