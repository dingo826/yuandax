<?php
load("@.wechat");
/* 系统项目组可以共用的基类库，继承则可，自动加载 */
class WechatAction extends Action{
	public $wid;
	public $wxinfo, $basic;

	function __construct() {
		//echo 22;//exit;
		parent::__construct();
		//echo 11;exit;
		$this->wid = intval(I("get.wid"));
		if($this->wid<1) {
			echo "请确认访问来源是否正确。";
			exit;
		}
		$this->doload();
	}

	function doload() {
		$this->wxinfo = M('Weixin')->where("id='".$this->wid."'")->find();
		$this->assign('wxinfo', $this->wxinfo);
		session("wxinfo", $this->wxinfo);

		$this->basic = M('app_basicinfo')->where("wid='".$this->wid."'")->find();
		$this->assign('basic', $this->basic);

		//$token = I("get.token");
		//if($token) {

		//}
	}

	function getjssdktoken() {
		Vendor("jssdk");
		$jssdk = new JSSDK("wxf08f9c2f0205fe8e", "680d85325fb2453ed1680ca5845b2dbb");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign('signPackage',$signPackage);
	}
}
?>