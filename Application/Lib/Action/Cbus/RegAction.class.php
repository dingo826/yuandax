<?php
class RegAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
	}

    public function index(){
		if($_POST) {
			$post = $_POST;
			$vercode = I("post.mphonecode");

			if($_SESSION['vercode']['time']+60<time()) {
				$retjson['status']  = -1;
				$retjson['message'] = "验证码超时";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			if($_SESSION['vercode']['code']!=$vercode) {
				$retjson['status']  = -1;
				$retjson['message'] = "验证码错误";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			
			if(!trim($post['nickname'])) {
				$retjson['status']  = -1;
				$retjson['message'] = "名称不得为空";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			if($post['repasswd']!=$post['passwd']) {
				$retjson['status']  = -1;
				$retjson['message'] = "两次密码不一致";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			if(!isMobile($post['mphone'])) {
				$retjson['status']  = -1;
				$retjson['message'] = "手机号码错误";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			$detail = M("cbus_account")->where("mphone='".$mobile."'")->find();
			if($detail) {  
				$retjson['status'] = -1;
				$retjson['message'] = "该手机号码已被使用";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}

			$mid = $this->doRegistration();
			$_SESSION['cbus_bid'] = $mid;
			$retjson['status']  = 1;
			$retjson['message'] = "注册成功";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			//header("location: ".U('perfect/setpone?fromid='.$_SESSION["formid"]));
			exit;
		}
		$this->display();
    }

	function doRegistration() {
		$post = $_POST;
		$data['lin_salt']  = strtoupper(substr(md5(rand().time()),0,6));
		$data['nickname']  = $post["nickname"];
		$data['mphone']    = $post["mphone"];
		$data['passwd']    = md5(md5($post['repasswd']).$data['lin_salt']);
		$data['fromshequ'] = $_SESSION["formid"];
		$data['status']    = 2;
		$data['etime']     = time();
		$data['ctime']     = time();
		$insertid = M("cbus_account")->add($data);

		if($insertid<1) {
			$retjson['status']  = -1;
			$retjson['message'] = "注册失败";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$relatdata["sqid"]   = $_SESSION["formid"];
		$relatdata["cbusid"] = $insertid;
		$relatdata["etime"]  = time();
		$relatdata["ctime"]  = time();
		$$relatinsertid = M("relat_community_cbus")->add($relatdata);
		return $insertid;
	}
}
