<?php
return [
	//引擎:file,mysql,memcache,redis
	'driver'   => 'mysql',
	//session_name
	'name'     => 'hdcmsid',
	//域名
	'domain'   => '',
	//过期时间
	'expire'   => 0,
	#File
	'file'     => [
		'path' => 'storage/session',
	],
	#Mysql
	'mysql'    => [
		'table' => 'hd_core_session',
	],
	#Memcache
	'memcache' => [
		'host' => 'localhost',
		'port' => 11211,
	],
	#Redis
	'redis'    => [
		'host'     => 'localhost',
		'port'     => 11211,
		'password' => '',
		'database' => 0,
	]
];