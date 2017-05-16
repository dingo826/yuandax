<?php
/* 系统项目组可以共用的基类库，继承则可，自动加载 */
class ApiAction extends Action{
	public $Linwechat;
	public $wxinfo;

	function __construct() {
		parent::__construct();
		$this->gettoken();
		Vendor("Linwechat");
		$para['isdebug'] = true;
		//file_put_contents("logs.txt", json_encode($GLOBALS['HTTP_RAW_POST_DATA'])."\n", FILE_APPEND);

		$wechatObj = new Linwechat(Token, $para);
		$this->Linwechat = $wechatObj;
		if(!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
			$wechatObj->OneValid();exit;
		}else {
			if(!$this->Linwechat->Valid()){exit;}
		}
	}

    // 控制器初始化处理 可以让所有项目组共同使用
	function gettoken() {
		$tcode = trim($_GET['u']);
		$list = M('Weixin');
		$info = $list->where("tcode='$tcode'")->find();
		$this->wxinfo = $info;
		if($info){
			define("Token",$info['token']);
		}else{
			exit;
		}
	}
}
?>