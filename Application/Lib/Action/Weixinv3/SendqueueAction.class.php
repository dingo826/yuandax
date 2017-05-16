<?php
class SendqueueAction extends Weixinv3Action {
	protected $_model;		// 模型

	public function __construct() {
		parent::__construct();
		$this->_model = M('npv3_sendqueues');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$today = strtotime(date("Y-m-d"));

		$defaulttimepara = M("npv3_sendqueues_defaulttime")->where("wid='".$wid."'")->find();

		$where = "wid='".$wid."' and senddate>".($today-1);

		$temp = M("npv3_sendqueues")->where($where)->order("senddate asc")->limit('0, 5')->select();
		foreach($temp as $key => $val) {
			if($val["status"]!=1) $val["status"] = 2;
			$list[$val['senddate']]         = $val;
			$list[$val['senddate']]["list"] = M("npv3_sendqueues_contents")->where("qid=".$val["id"])->order("id asc")->select();
		}

		$dtlist = '';
		for($i=1; $i<6; $i++) {
			$dtlist[] = $today+($i-1)*24*60*60;
		}

		$this->assign('list', $list);		
		$this->assign('today', $today);
		$this->assign('dtlist', $dtlist);
		$this->assign('defaulttimepara', $defaulttimepara);
		$this->display($this->Base_themplate);
    }

	function dataexchange() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$nid  = intval(I("post.nid"));
		$pid  = intval(I("post.pid"));
		$step = intval(I("post.step"));

		$exchangelist = M("npv3_sendqueues")->where("id='".$nid."' or id='".$pid."'")->select();
		//print_r($exchangelist);exit;

		if(count($exchangelist)==2) {
			$updata = "";
			$updata["senddate"] = $exchangelist[0]["senddate"];
			$updata["etime"]    = time();
			M("npv3_sendqueues")->where("id='".$exchangelist[1]["id"]."'")->save($updata);

			$updata = "";
			$updata["senddate"] = $exchangelist[1]["senddate"];
			$updata["etime"]    = time();
			M("npv3_sendqueues")->where("id='".$exchangelist[0]["id"]."'")->save($updata);
		}else {
			$updata = "";
			$updata["senddate"] = $exchangelist[0]["senddate"]+24*60*60*$step;
			$updata["etime"]    = time();
			M("npv3_sendqueues")->where("id='".$exchangelist[0]["id"]."'")->save($updata);
		}

		echo 1;exit;
	}

	//设置立即发送
	function nowsend() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I("post.id"));

		$updata["isnowsend"] = 1;

		M("npv3_sendqueues")->where("id='".$id."'")->save($updata);

		echo 1;exit;
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id = intval(I("post.id"));
		$record = M("npv3_sendqueues")->where("id='".$id."' and wid='".$wid."'")->delete();

		print_r($record);exit;

		echo 1;exit;
	}
}