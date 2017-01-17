<?php
return array(
    //REWRITE模式
    'URL_MODEL' => 2,
	//数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'test_thinkphp', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'think_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  => TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	'TMPL_L_DELIM'    => '<{',
    'TMPL_R_DELIM'    => '}>',
    // 显示页面Trace信息 调试使用
    'SHOW_PAGE_TRACE' =>true,
    'TRACE_PAGE_TABS' => array(     
        'base'  => '基本',     
        'file'  => '文件',     
        'think' => '流程',     
        'error' => '错误',     
        'sql'   => 'SQL',     
        'debug' => '调试',     
        'user'  => '用户'
    )
);