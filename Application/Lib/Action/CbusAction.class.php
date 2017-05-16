<?php
load("@.cbus");
/* 系统项目组可以共用的基类库，继承则可，自动加载 */
class CbusAction extends Action {

	public function __construct() {
		parent::__construct();
		$this->_doLoad();
		$this->assign('Tget', I("get."));
	}

	function _doLoad() {
		$fromid = intval(I("get.fromid"));
		if($fromid>0) {
			$_SESSION["formid"] = $fromid;
		}
		if($_SESSION["formid"]<1) {
			echo "来源错误";exit;
		}
		$this->assign('formid', $_SESSION["formid"]);
	}

	function _business() {
		$cbus_bid = $_SESSION['cbus_bid'];
		if($cbus_bid<1) {
			header("location: ".U('login/index?fromid='.$_SESSION["formid"]));
			exit;
		}
		$where  = "id='".$cbus_bid."'";
		$bminfo = M("cbus_account")->where($where)->find();
		if(empty($bminfo)) {
			header("location: ".U('login/index?fromid='.$_SESSION["formid"]));
			exit;
		}
		$bminfo["snum"] = $this->_setnum($bminfo['id']);
		$_SESSION["cbus_bminfo"] = $bminfo;
		if($bminfo['status']==2 && strtolower(MODULE_NAME)!="perfect" && strtolower(MODULE_NAME)!="image") {
			header("location: ".U('perfect/setpone?fromid='.$_SESSION["formid"]));
			exit;
		}

	}

	function _getsign() {
		Vendor("jssdk");
		$jssdk = new JSSDK("wx4f99fc59c023125f", "99c88026caad6bd38a15465c737f2ea9");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign('signPackage',$signPackage);
	}

	function _setnum($id) {
		$str = '';
		if($id<10) {
			$str = "A000".$id;
		}else if($id<100) {
			$str = "A00".$id;
		}else if($id<1000) {
			$str = "A0".$id;
		}else if($id<10000) {
			$str = "A".$id;
		}else{
			$str = "A".$id;
		}
		return $str;
	}

	function _getSessionNum() {
		if(!$_SESSION["randmid"])
			$_SESSION["randmid"] = mt_rand(10000, 99999);
	}
}
?>
