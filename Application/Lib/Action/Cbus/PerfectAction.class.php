<?php
class PerfectAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_business();
		
	}

    public function setpone() {
		if($_SESSION['cbus_bminfo']['status']!=2) {
			header("location: ".U('manage/index?fromid='.$_SESSION["formid"]));
			exit;
		}
		$categorylist = M("cbus_category")->select();
		$this->assign('categorylist', $categorylist);
		$this->display();
    }

	function finish() {
		$bminfo = $_SESSION["cbus_bminfo"];
		$this->assign('bminfo', $bminfo);
		$this->display();
	}

	public function dosetpone() {
		if($_POST) {
			$data['logo']     = I("post.logo");
			if($_POST['images']) {
				$temp = '';
				foreach($_POST['images'] as $val) {
					$temp['bid'] = $_SESSION['cbus_bid'];
					$temp['fromshequ'] = $_SESSION["formid"];
					$temp['image']     = $val;
					$temp['etime']     = time();
					$temp['ctime']     = time();
					$picarr[] = $temp;
				}
			}
			$data['cid']      = intval(I("post.cid"));
			$data['tel']      = I("post.tel");
			$data['province'] = I("post.province");
			$data['city']     = I("post.city");
			$data['county']   = I("post.county");
			$data['address']  = I("post.address");
			$data['xpoint']   = I("post.xpoint");
			$data['ypoint']   = I("post.ypoint");
			$data['status']   = 1;
			$data['etime']    = time();

			$upid = M("cbus_account")->where("id='".$_SESSION['cbus_bid']."'")->save($data);
			if($upid>0) {
				M("cbus_business_pic")->addAll($picarr);
			}
			$ret['status']  = 1;
			$ret['message'] = "信息完善成功";
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}
