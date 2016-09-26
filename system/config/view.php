<?php
return [
	//模板目录（只对路由调用有效）
	'path'      => 'view',
	//模板文件扩展名
	'prefix'    => '.php',
	//标签
	'tags'      => [ ],
	//消息模板
	'message'   => 'resource/view/message.php',
	//有确定提示的模板页面
	'confirm'   => 'resource/view/confirm.php',
	//404页面
	'404'       => 'resource/view/404.php',
	//错误提示页面
	'bug'       => 'resource/view/bug.php',
	//左标签
	'tag_left'  => '<',
	//右标签
	'tag_right' => '>',
	//blade 模板功能开关
	'blade'     => TRUE
];