<?php
class MessageAction extends WeixinAction {

	private $_Model;

	public function __construct() {
		parent::__construct();
		$this->_Model = D('Receive');
		$this->assign('tget', I("get."));
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		//$wid = 348;

		$page   = $_GET['p'] ? intval($_GET['p']) : 1;
    $pagesize=10;
		$title  = $_GET['title'] ? addslashes(I("get.title", '', 'htmlspecialchars')) : '';
		$where = "weixinid=".$wid;
		if($_GET['title']) {
			$where .= " and Content like '%".$title."%'";
		}

		$list = $this->_Model->setPageSize(10)->setWhere($where)->getList($page);
		$page = $this->_Model->getPageInfo($this->_currentURL);

		foreach ($list as $key => $val) {
			$wchat[] = $val['FromUserName'];
		}
		$wchat = (array_unique($wchat));

		$mlist = M("app_mcard_member")->where("wid='".$wid."' and wechatid IN ('".implode("', '", $wchat)."')")->select();
		foreach($mlist as $key => $val) {
			$temp['nickname']   = $val['name'];
			$temp['sex']        = $val['sex'];
			$temp['region']     = $val['region'];
			$temp['headimgurl'] = $val['headimgurl'];
			$temp['uid']		= $val['id'];

			$ulist[$val['wechatid']] = $temp;
		}
    $this->assign('list',$list);
		$this->assign('ulist',$ulist);
    	$this->assign('pages',$page);
		$this->display($this->Base_themplate);
    }

	function dialogue() {
		$mid = intval(I("get.mid"));
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		//$wid = 348;

		$page   = $_GET['p'] ? intval($_GET['p']) : 1;
		$pagesize=10;
		$total = M("member_message")->where("weixinid=".$wid." and mid=".$mid)->count();		
		$list  = M("member_message")->where("weixinid=".$wid." and mid=".$mid)->page($page,$pagesize)->order("CreateTime DESC")->select();

		$tinfo = M("app_mcard_member")->where("wid='".$wid."' and id='".$mid."'")->find();
    

		$this->assign("list",   $list);
		$this->assign("lastid", $list[0]['id']);
		$this->assign("tinfo",  $tinfo);
		$this->assign("total",  $total);
		$this->display($this->Base_themplate);
	}

	function ajax() {
		$mid = intval(I("get.mid"));
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$page   = $_POST['page'] ? intval($_POST['page']) : 2;
		$pagesize=10;	
		$list  = M("member_message")->where("weixinid=".$wid." and mid=".$mid)->page($page,$pagesize)->order("CreateTime DESC")->select();


		$data['status'] = 2;
		if($list) {
			$data['status'] = 1;
			$data['jsondata'] = $list;			
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);exit;
	}

	function getnewinfo() {
		$mid = intval(I("get.mid"));
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$lastmsgid = intval(I("post.lastmsgid"));
		$list  = M("member_message")->where("weixinid=".$wid." and mid=".$mid." and istype=1 and id>".$lastmsgid)->order("CreateTime DESC")->select();

		$data['status'] = -1;
		if($list) {
			$data['status'] = 1;
			$data['lastid'] = $list[0]['id'];
			$data['jsondata'] = array_reverse($list);			
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);exit;
	}

	function postreply() {
		$mid = intval(I("get.mid"));
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$content = I("post.content");

		if($_POST) {
			$_Msg      = M("member_message");

			$lastnews  = $_Msg->where("weixinid=".$wid." and mid=".$mid)->field("CreateTime,FromUserName")->order("CreateTime DESC")->find();

			$touser    = $lastnews["FromUserName"];
			$timestamp = (int)$lastnews["CreateTime"]; //最后用户发送消息时间
			$time      = time();
			if($time-$timestamp>=172800){
				$result['msgid']    = -1;
				$result['content']  = "此会话已过期，消息回复必须在会话48小时以内完成,以最后一条消息时间为准";
				echo json_encode($result, JSON_UNESCAPED_UNICODE);exit;
			}

			$weixin = D("Weixin");
			$token  = $weixin -> getToken($wid);

			$api_url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
			$api_data["touser"]  = $touser;
			$api_data["msgtype"] = "text";
			$api_data["text"]    = array("content"=>$content);
			$api_json            = json_encode($api_data, JSON_UNESCAPED_UNICODE);

			$result = json_decode(curl_post($api_url, $api_json),true);
			if($result["errcode"]==0){
				//存入发送记录
				$_typeid = array('text'=>1,'image'=>2,'voice'=>3,'video'=>4,'shortvideo'=>5); //后期可能添加其他消息类型
				$save["FromUserName"] = $touser;
				$save["istype"]       = 2;
				$save["MsgType"]      = $_POST['msgtype'] ? $_typeid[$_POST['msgtype']] : 1;
				$save["CreateTime"]   = $time;
				$save["AddTime"]  	  = $time;
				$save["Content"]	  = $content;
				$save["mid"]		  = $mid;
				$save["weixinid"]	  = $wid;
				$id = $_Msg->data($save)->add();
				
				$data['msgid']    = 1;
				//$data['lastid']   = $id;
				$data['jsondata'] = $save;
			}else{
				$data['msgid']   = -1;
				$data['content'] = "发送失败".$result["errmsg"];
			}
			echo json_encode($data, JSON_UNESCAPED_UNICODE);exit;
		}
	}
}