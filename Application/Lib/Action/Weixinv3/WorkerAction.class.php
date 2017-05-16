<?php
class WorkerAction extends Weixinv3Action {

	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$worklist = M('npv3_worker')->where("wid='".$wid."'")->order('id desc')->select();
		$temp = "";
		foreach($worklist as $key => $row) {
			$tempid[] = $row["mid"];
			if($row["typeid"]==1) {
				$temp["preview"] = $row;
			}else {
				$temp["review"] = $row;
			}
		}
		$worklist = $temp;

		$where = "wid='".$wid."' and subscribe=1";
		if($tempid) $where .= " and id not in (".implode(",", $tempid).")";

		$count = M('app_mcard_member')->where($where)->count();

		import('ORG.Util.Page');
		$pageSize = 25;	// 每页显示记录数
		$page     = new Page($count, $pageSize);
		$list     = M('app_mcard_member')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		$opelist  = M('app_mcard_member')->where("id in (".implode(",", $tempid).")")->order('id desc')->select();

		$this->assign('list', $list);
		$this->assign('opelist', $opelist);
		$this->assign("count", $count);	// 关注总数
		$this->assign('pages', $page->show());
		$this->assign('worklist', $worklist);

		$this->display($this->Base_themplate);
    }

	function setpreviewworker() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id  = intval(I("get.id"));

		$model = M("npv3_worker");

		$worker = $model->where("wid='".$wid."' and typeid=1")->find();

		$savedata["mid"]    = $id;
		$savedata["etime"]  = time();

		if(!$worker) {
			$savedata["wid"]    = $wid;
			$savedata["typeid"] = 1;
			$savedata["ctime"]  = time();
			$upid = $model->add($savedata);
		}else {
			$upid = $model->where("wid='".$wid."' and typeid=1")->save($savedata);
		}

		header("location: ".U("index"));exit;
	}

	function setreviewworker() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id  = intval(I("get.id"));

		$model = M("npv3_worker");

		$worker = $model->where("wid='".$wid."' and typeid=2")->find();

		$savedata["mid"]    = $id;
		$savedata["etime"]  = time();

		if(!$worker) {
			$savedata["wid"]    = $wid;
			$savedata["typeid"] = 2;
			$savedata["ctime"]  = time();
			$upid = $model->add($savedata);
		}else {
			$upid = $model->where("wid='".$wid."' and typeid=2")->save($savedata);
		}

		header("location: ".U("index"));exit;
	}

	function setsendtime() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$settime = intval(I("post.time"));
		$detail = M("npv3_sendqueues_defaulttime")->where("wid='".$wid."'")->find();
		if($detail["settime"]!=$settime) {
			$data["wid"]     = $wid;
			$data["settime"] = $settime;
			$data["etime"]   = time();
			if($detail) {
				M("npv3_sendqueues_defaulttime")->where("wid='".$wid."'")->save($data);
			}else {
				$data["ctime"] = time();
				M("npv3_sendqueues_defaulttime")->add($data);
			}
		}
		echo 1;exit;
	}

	function setonesendtime() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I("post.id"));
		$settime = intval(I("post.time"));

		$data["sendtime"] = $settime;
		$data["etime"]    = time();
		M("npv3_sendqueues")->where("id='".$id."' and wid='".$wid."'")->save($data);
		echo 1;exit;
	}
}