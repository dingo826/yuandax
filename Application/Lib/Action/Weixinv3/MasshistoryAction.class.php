<?php
class MasshistoryAction extends Weixinv3Action {

	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 5;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "wid='".$wid."' and (status=2 or status=3 or status=4 or status=5)";

		$list = M("npv3_sendqueues")->where($where)->order("senddate desc")->page($Page.','.$pageSize)->select();

		foreach($list as $key => $row) {
			$list[$key]["sendlist"] = M("npv3_sendqueues_contents")->where("qid=".$row["id"])->order("id asc")->select();
			if($row["status"]==3 || $row["status"]==-1) {
				$list[$key]["sendresult"] = M("npv3_sendqueues_results")->where("qid=".$row["id"])->find();
			}
		}

		import('ORG.Util.Page');

		$count = M("npv3_sendqueues")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display($this->Base_themplate);
    }

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id  = intval(I("get.id"));

		$where = "wid='".$wid."' and id='".$id."'";

		$detail = M("npv3_sendqueues")->where($where)->find();

		if(!$detail) {
			alert("无该条的群发记录");exit;
		}

		if($detail["status"]!=2 && $detail["status"]!=3 ) {
			alert("该条群发的状态不对");exit;
		}

		$weixin = D("Weixin");
		$token  = $weixin->getToken($wid);
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=".$token;
		$senddata = "";
		$senddata["msg_id"] = $detail["msg_id"];
		$senddata = json_encode($senddata, JSON_UNESCAPED_UNICODE);

		$res = curl_post($url, $senddata);
		//$res = '{"errcode": 0,"errmsg": "ok"}';
		//echo $res;exit;
		$res = json_decode($res, true);
		if($res["errcode"]!=0) {
			alert("删除失败");
		}
		$updata["status"] = 4;
		$updata["etime"]  = time();

		$upid = M("npv3_sendqueues")->where("id='".$id."' and wid='".$wid."'")->save($updata);
		header("location: ".U("index"));exit;
	}

}