<?php
class JieybAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		Vendor("jssdk");
		$jssdk = new JSSDK("wxf08f9c2f0205fe8e", "680d85325fb2453ed1680ca5845b2dbb");
		$signPackage = $jssdk->GetSignPackage();
		//print_r($signPackage);exit;
		$this->assign('signPackage',$signPackage);
		$this->display();
    }
}
