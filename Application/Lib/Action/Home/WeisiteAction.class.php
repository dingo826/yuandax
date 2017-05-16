<?php
class WeisiteAction extends Action {
	
	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$token = I("get.token");
		$wid   = intval(I("get.wid"));

		header("location: ".U("Wechat/index/index?wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com"));exit;
    }
}
