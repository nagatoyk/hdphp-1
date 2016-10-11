<?php namespace system\service\build;

/**
 * 卡券管理服务
 * Class Ticket
 * @package system\service\build
 */
class Ticket {
	/**
	 * 获取卡券标题
	 *
	 * @param int $type 卡券类型
	 *
	 * @return string
	 */
	public function getTitleByType( $type ) {
		$names = [ 1 => '折扣券', 2 => '代金券' ];

		return $names[ $type ];
	}

	/**
	 * 获取指定类型的卡券列表
	 *
	 * @param int $type 卡券类型
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getTicketListsByType( $type, $siteid = NULL ) {
		$siteid = $siteid ?: SITEID;

		return $this->where( 'siteid', $siteid )->where( 'type', intval( $type ) )->get();
	}

	/**
	 * 卡券兑换
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return bool
	 */
	public function convert( $tid ) {
		Db::lock( 'ticket,ticket_record,member,credits_record' );
		//会员已经兑换的数量
		$TicketRecord = new TicketRecord();
		$count        = $TicketRecord->getNumByTid( $tid, Session::get( 'member.uid' ) );
		//卡券信息
		$ticket      = $this->where( 'tid', $tid )->where( 'siteid', SITEID )->first();
		$ticketTitle = $this->getTitleByType( $ticket['type'] );
		if ( $count >= $ticket['limit'] ) {
			$this->error = '只能兑换' . $ticket['limit'] . '个' . $ticketTitle . ',你已经全部兑换完毕';
			Db::unlock();

			return FALSE;
		}
		//减掉会员积分
		$Member             = new Member();
		$data               = [ ];
		$data['uid']        = Session::get( 'member.uid' );
		$data['credittype'] = $ticket['credittype'];
		$data['num']        = - 1 * $ticket['credit'];
		$data['module']     = v( 'module.name' );
		$data['remark']     = '兑换' . $ticketTitle . ':' . $ticket['title'] . ',消耗' . $ticket['credit'] . $Member->getCreditTitle( $ticket['credittype'] );
		if ( ! $Member->changeCredit( $data ) ) {
			$this->error = $Member->getError();
			Db::unlock();

			return FALSE;
		}
		$this->where( 'tid', $tid )->decrement( 'amount', 1 );

		//记录卡券兑换日志
		$record             = new TicketRecord();
		$data               = [ ];
		$data['uid']        = Session::get( 'member.uid' );
		$data['createtime'] = time();
		$data['usetime']    = 0;//使用时间
		$data['status']     = 1;
		$data['siteid']     = SITEID;
		$data['tid']        = $tid;
		$data['manage']     = 0;//核销员编号
		$data['module']     = v( 'module.name' );
		$data['remark']     = '兑换' . $ticketTitle . ':' . $ticket['title'] . ',消耗' . $ticket['credit'] . $Member->getCreditTitle( $ticket['credittype'] );//核销员编号
		$record->add( $data );
		Db::unlock();

		return TRUE;
	}

	/**
	 * 获取指定卡券允许使用的用户组
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketGroupIds( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}

		return Db::table('ticket_groups')->where( 'siteid', SITEID )->where( 'tid', $tid )->lists( 'group_id' );
	}

	/**
	 * 获取指定卡券允许使用的模块
	 *
	 * @param int $tmid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketModules( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}
		$module = Db::table( 'ticket_module' )->where( 'siteid', SITEID )->where( 'tid', $tid )->lists( 'module' );

		return $module ? Db::table( 'modules' )->whereIn( 'name', $module )->get() : [ ];
	}

	/**
	 * 核销卡券
	 *
	 * @param string $id 编号
	 *
	 * @return bool
	 */
	public function verification( $id ) {
		$data['id']     = $id;
		$data['status'] = 2;

		return $this->save( $data );
	}

	/**
	 * 获取会员卡券兑换数量
	 *
	 * @param int $tid 卡券编号
	 * @param int $uid 会员编号
	 *
	 * @return mixed
	 */
	public function getNumByTid( $tid, $uid ) {
		return $this->where( 'tid', $tid )->where( 'uid', $uid )->count();
	}
}