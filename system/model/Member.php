<?php namespace system\model;

use hdphp\model\Model;

//会员管理
class Member extends Model {
	protected $table = 'member';
	protected $auto
	                 = [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'mobile', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'email', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'icon', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'credit1', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit2', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit3', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit4', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit5', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'qq', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'nickname', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'realname', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'telephone', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'vip', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'address', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'zipcode', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'alipay', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'msn', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'taobao', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'site', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'nationality', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'introduce', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'gender', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'graduateschool', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'height', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'weight', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'bloodtype', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'birthyear', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'birthmonth', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'birthday', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'resideprovince', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'residecity', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'residedist', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'access_token', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		];
	protected $validate
	                 = [
			[ 'password', 'required', '密码不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'email', 'unique', '邮箱已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'email', 'email', '邮箱格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'phone', '手机号格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'uid', 'checkUid', '当前用户不属于当前站点', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'group_id', 'required', '用户组不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ]
		];

	public function checkUid( $field, $value, $params, $data ) {
		return Db::table( $this->table )->where( 'uid', $value )->where( 'siteid', SITEID )->first() ? TRUE : FALSE;
	}

	/**
	 * 根据密码获取密钥与加密后的密码数据及确认密码
	 *
	 * @return array
	 */
	public function getPasswordAndSecurity() {
		$data             = [ ];
		$data['security'] = substr( md5( time() ), 0, 10 );
		$data['password'] = md5( Request::post( 'password' ) . $data['security'] );

		return $data;
	}
}