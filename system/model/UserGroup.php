<?php namespace system\model;

use hdphp\model\Model;

//管理员组
class UserGroup extends Model {
	protected $table = 'user_group';
	protected $validate
	                 = [
			[ 'name', 'required', '用户组称不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'maxsite', 'regexp:/^[1-9]\d*$/', '站点数量不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'daylimit', 'regexp:/^[1-9]\d*$/', '有效期限不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                 = [
			[ 'package', 'serialize', 'function', self::MUST_AUTO, self::MODEL_BOTH ]
		];

	/**
	 * 获取默认组
	 */
	protected function getDefaultGroup() {
		return 1;
	}

	/**
	 * 获取用户的用户组信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserGroup( $uid ) {
		$group_id = Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' );

		return $this->find( $group_id );
	}
}