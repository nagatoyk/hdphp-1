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
			[ 'password', 'autoPassword', 'method', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'group_id', 'autoGroupId', 'method', self::MUST_AUTO, self::MODEL_INSERT ],
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
		];

	//获取默认组
	public function autoGroupId() {
		return Db::table( 'member_group' )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	//密码字段处理
	public function autoPassword( $password, &$data ) {
		$data['security']  = substr( md5( time() ), 0, 10 );
		$data['password2'] = md5( $data['password2'] . $data['security'] );

		return md5( $password . $data['security'] );
	}

	protected $validate
		= [
			[ 'email', 'unique', '邮箱已经被使用', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'email', 'email', '邮箱格式错误', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被使用', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'mobile', 'phone', '手机号格式错误', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'uid', 'checkUid', '当前用户不属于当前站点', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'password', 'confirm:password2', '两次密码不一致', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'group_id', 'required', '用户组不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ]
		];

	public function checkUid( $field, $value, $params, $data ) {
		return Db::table( $this->table )->where( 'uid', $value )->where( 'siteid', SITEID )->first() ? TRUE : FALSE;
	}

	//获取会员组
	public function getGroupName( $uid ) {
		$sql = "SELECT title,id FROM " . tablename( 'member' ) . " m JOIN " . tablename( 'member_group' ) . " g ON m.group_id = g.id WHERE m.uid={$uid}";
		$d   = Db::query( $sql );

		return $d ? $d[0] : NULL;
	}

	//检测会员登录&微信端开启自动登录时使用openid登录
	public function isLogin() {
		return Session::get( 'member' ) ? TRUE : FALSE;
	}

	//会员登录
	public function login() {
		$user = Db::table( 'member' )->where( 'email', $_POST['username'] )->orWhere( 'mobile', $_POST['username'] )->first();
		if ( empty( $user ) ) {
			$this->error = '帐号不存在';

			return FALSE;
		}
		if ( md5( $_POST['password'] . $user['security'] ) != $user['password'] ) {
			$this->error = '密码输入错误';

			return FALSE;
		}
		Session::set( 'member', $user );

		return TRUE;
	}


	/**
	 * 使用openid自动登录
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
	 * 更新会员SESSION信息
	 */
	public function updateUserSessionData() {
		$user = Db::table( 'member' )->where( 'uid', Session::get( 'member.uid' ) )->first();

		return Session::set( 'member', $user );
	}

	/**
	 * 判断当前uid的用户是否在当前站点中存在
	 *
	 * @param $uid 会员编号
	 *
	 * @return bool
	 */
	public function hasUser( $uid ) {
		return $this->where( 'siteid', v( 'site.siteid' ) )->where( 'uid', $uid )->get() ? TRUE : FALSE;
	}

	/**
	 * 会员积分中文名称
	 *
	 * @param string $creditType 积分类型
	 *
	 * @return string
	 */
	public function getCreditTitle( $creditType ) {
		return v( 'setting.creditnames.' . $creditType . '.title' );
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
			$this->error = '参数错误';

			return FALSE;
		}
		$data['module'] = isset( $data['module'] ) ? $data['module'] : '';
		//检测兑换数量
		$userTickNum = $this->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->pluck( $data['credittype'] );
		if ( $userTickNum < $data['num'] ) {
			$this->error = $this->getCreditTitle( $data['credittype'] ) . ' 数量不够';

			return FALSE;
		}
		//执行
		$action      = $data['num'] > 0 ? 'increment' : 'decrement';
		$data['num'] = $data['num'] > 0 ? $data['num'] : abs( $data['num'] );
		if ( ! $this->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->$action( $data['credittype'], $data['num'] ) ) {
			$this->error = '修改会员 ' . $this->getCreditTitle( $data['credittype'] ) . " 失败";

			return FALSE;
		}
		//记录变量日志
		$RecordModel = new CreditsRecord();
		if ( ! $RecordModel->add( $data ) ) {
			$this->error = $RecordModel->getError();

			return FALSE;
		}

		return TRUE;
	}
}