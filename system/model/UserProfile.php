<?php namespace system\model;

use hdphp\model\Model;

class UserProfile extends Model {
	protected $table = 'user_profile';
	protected $auto
	                 = [
			[ 'status', 'setPassword', 'method', 3, 3 ]
		];
}