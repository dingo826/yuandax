<?php
class LoginAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
	}

    public function index(){
		if($_POST) {
			$minfo = $this->doLogging();
			$_SESSION['cbus_bid'] = $minfo["id"];
			$ret["status"]  = 1;
			$ret["message"] = "登录成功";
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);
			exit;
		}
		$this->display();
    }

	function doLogging() {
		$post = $_POST;
		$_SESSION["post"] = '';

		if(!isMobile($post['mphone'])) {
			$ret["status"]  = -1;
			$ret["message"] = "错误的手机号";
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
		}

		$minfo = D("CbusUser")->_find($post["mphone"]);
		if(empty($minfo)) {
			$ret["status"]  = -1;
			$ret["message"] = "无该手机号关联的帐号";
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
		}
		$passwd    = md5(md5($post['passwd']).$minfo['lin_salt']);
		if($passwd!=$minfo['passwd']) {
			$ret["status"]  = -1;
			$ret["message"] = "密码错误";
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
		}
		return $minfo;
	}
}
