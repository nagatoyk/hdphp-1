<?php
//中间件配置
return array(
    //全局中间件
    'global'     => array(),

    //路由中间件
    'middleware' => array(
        'auth' => 'system\middleware\Auth',
    ),
);