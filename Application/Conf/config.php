<?php
return array(
	//'配置项'=>'配置值'
	'APP_GROUP_LIST' => 'Home,Admin,Weixin,Weixinv3,User,Api,Wechat,Cbus', //项目分组设定
    'DEFAULT_GROUP'  => 'Home', //默认分组
	'DEFAULT_MODULE'     => 'Index', //默认模块
    'URL_MODEL'          => '2', //URL模式
	'URL_CASE_INSENSITIVE'  => true, 		//URL不区分大小写
	'SHOW_PAGE_TRACE' =>false,

	'LANG_SWITCH_ON' => true,   // 开启语言包功能
	'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST'        => 'zh-cn', // 允许切换的语言列表 用逗号分隔
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

	//数据库配置1
   	'DB_TYPE'   => 'mysql', // 数据库类型
   	'DB_HOST'   => '120.27.27.78', // 服务器地址 
	//'DB_NAME'   => 'v2yuanda', // 数据库名
	//'DB_USER'   => 'v2yuanda', // 用户名
	//'DB_PWD'    => '67PH8m28N4nxGuJv', // 密码 
	'DB_NAME'   => 'res_wsq', // 数据库名
	'DB_USER'   => 'res_wsq', // 用户名
	'DB_PWD'    => '!h53g@#j', // 密码 
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'wx_', // 数据库表前缀 

	'MEMBER_AUTH_KEY' 	 => 'qt_6b68484e05bff62b8098e32a67aa839c',
	'ADMIN_AUTH_KEY' 	 => 'ht_rere484e05fshjytr7874582a67aa839b',
	'WEB_TITLE'      	 => "源达微商系统—国内最大的微信公众服务平台",
	"WEB_KEYWORDS"   	 => "源达微商系统",
	"WEB_DESCRIPTION"    => "源达微商系统",
	"COMPANY"    => "源达",

	'DIRECTORY'		     => 'v2.yuandax.com',

	'PIC_UPLOAD_URL' 	 => 'http://v2.yuandax.com/',//前台图片来源(没找到引用的地方)
	'HTTP_DOMIN'		 => 'http://v2.yuandax.com/',//设置域名

	'DOMIN'		         => 'v2.yuandax.com/',//设置域名(没找到引用的地方)

	'ODOMIN'		     => 'v2.yuandax.com',//设置域名(Cli\InsteadsendNews.php引用了，需要去掉)
	'HTTP_STCDOMIN'		 => 'http://v2.yuandax.com',//设置域名(Action\Api\IndexAction.class.php引用了，需要去掉)

	'BAIDU_KEY' 		 => "B3d2693a283424c4fce1fe0a88cf94dc",
	'SESSION_AUTO_START' => true,	//是否开启session
);
?>
