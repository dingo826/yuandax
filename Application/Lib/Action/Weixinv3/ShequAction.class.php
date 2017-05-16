<?php
class ShequAction extends Weixinv3Action {

	private $_basicModel;

	public function __construct() {
		parent::__construct();
		$this->_basicModel = D('BasicInfo');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$today             = strtotime(date("Y-m-d"));
		$sevenday          = strtotime(date("Y-m-d"))-(7*24*60*60);
		$data['wereserve'] = M("wereserve_book")->where("wid='".$wid."' and status=1 and dateline>=".$today)->count();

		$data['board']     = M("board_message")->where("wid='".$wid."' and msg_st=1")->count();

		$data['message']   = M("member_message")->where("weixinid='".$wid."' and istype=1 and AddTime>".$sevenday)->count();

		$data['subscribe'] = M("app_mcard_member")->where("wid='".$wid."' and subscribe=1")->count();
		$data['checkin']   = M("app_mcard_member")->where("wid='".$wid."' and subscribe=1 and check_in=1")->count();

		$this->assign('data', $data);
		$this->display($this->Base_themplate);
    }

	function basicinfo() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		// 获取基本信息
		$info = $this->_basicModel->getBasicInfo($wid);

		if($_POST) {
			$data["wid"]       = $wid;
			$data["cnname"]    = I("post.cnname");
			$data["abbr"]      = I("post.abbr");
			$data["phone"]     = I("post.phone");
			$data["email"]     = I("post.email");
			$data["logo"]      = I("post.avater");
			$data["provincen"] = I("post.provincen");
			$data["cityn"]     = I("post.cityn");
			$data["countyn"]   = I("post.countyn");
			$data["address"]   = I("post.address");
			$data["etime"]     = time();

			if($info) {
				$recodid = M("app_basicinfo")->where("id='".$info['id']."' and wid='".$wid."'")->save($data);
			}else {
				$recodid = $this->_basicModel->addBasicInfo($data);
			}
			header("Location: ".U("index/basicinfo"));exit;

		}

		//if($info['avater']<>"/Public/img/mingp/avatar.jpg") {
			//$info['img'] = substr($info['logo'], 1);
		//}
		$info['img'] = $info['logo'];

		$this->assign('item', $info);
		$this->display($this->Base_themplate);
	}

	function introduce() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$info = $this->_basicModel->getBasicInfo($wid);
		if(empty($info)) {
			header("Location: ".U("index/basicinfo"));exit;
		}
		
		if($_POST) {
			$data["summary"]   = I("post.summary");
			$data["introduce"] = I("post.introduce");
			$data["etime"]     = time();

			$recodid = $this->_basicModel->setIntroduce($data, $info['id']);
			header("Location: ".U("index/introduce"));exit;

		}
		$info['introduce'] = htmlspecialchars_decode($info['introduce']);


		$this->assign('item', $info);
		$this->display($this->Base_themplate);
	}

	function switchtheme() {
		cookie("theme", "Weixin2");
		cookie("usertheme", "User2");
		$referer = $_SERVER['HTTP_REFERER'];
		IF(!$referer) $referer = U("index/index");
		header("Location: ".$referer);
	}

	function switcholdtheme() {
		cookie("theme", null);
		cookie("usertheme", null);
		$referer = $_SERVER['HTTP_REFERER'];
		IF(!$referer) $referer = U("index/index");
		header("Location: ".$referer);
	}
}