<?php
class BoardAction extends WechatAction {

	private $_bid, $_board;			// 留言板id
	
	public function __construct() {
		parent::__construct();
		$wid   = intval(I("get.wid"));
		$board = M("app_board")->where("wid='".$wid."'")->find();

		// 获取留言板id
		$this->_bid   = $board['id'];
		$this->_board = $board;

		$this->assign("board", $board);
		$this->assign('tget', I("get."));
	}

    public function index() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');
		$type  = intval(I("get.type"));

		

		if($type==1) {
			$where = "t.bid='".$this->_bid."'";
			$where .= " and t.openid='".$token."'";
		}else {
			$where = "t.bid='".$this->_bid."' and (t.public=1 and ((t.user_public=0 and t.openid='".$token."') or (t.user_public=1)) )";
		}
		$list  = M()->table(C('DB_PREFIX')."app_board_topic as t")
			        ->join("left join ".C("DB_PREFIX")."app_mcard_member as m ON t.openid=m.wechatid")
			        ->field('t.*, m.name, m.headimgurl')
			        ->where($where)->order("t.modifytime desc")->select();

		$this->assign("list", $list);
		$this->display();
    }

	public function addtopic() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');

		if($_POST) {
			$_SESSION['post'] = $_POST;
			header("location: ".U('addtopic?wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
		}
		if($_SESSION['post']) {
			//$_SESSION["tipost"] = $_SESSION["tipost"]+1;
			$post = $_SESSION['post'];
		    $_SESSION['post'] = '';
			if(isset($post['userPublic'])) $post['userPublic'] = 0;
			else $post['userPublic'] = 1;
			$data = array(
				'bid'		  => $this->_bid,
				'openid'	  => $token,
				'contents'	  => $post['contents'],
				'public'      => $this->_board['public'],
				'user_public' => $post['userPublic'],
				'createtime'  => time(),
				'modifytime'  => date("Y-m-d H:i:s")
			);
			//print_r($data);exit;

			$affrow = M('app_board_topic')->add($data);

			/*$op_id = M('app_mcard_member')->where("wechatid='".$data['openid']."'")->getField('id');
			if($op_id>0) {
				$ldata = array(
						'wid' => $wid,
						'tid'=> $affrow,
						'op_type' => 'residents',
						'op_id' => $op_id,
						'act_type' => 'msg',
						'time' => time(),
				);
			}*/
			header("location: ".U('index?wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
		}
		$this->display();
	}

	public function topicDetails() {
		$wid    = intval(I("get.wid"));
		$token  = I("get.token", '', 'htmlspecialchars');
		$tid    = intval(I('get.tid'));

		if($_POST) {//继续提交留言
			//print_r($_POST);exit;
			$post = I("post.");
			$data["tid"]        = $tid;
			$data["openid"]     = $token;
			$data['contents']   = $post["message"];
			$data["createtime"] = time();
			$res = M("app_board_dialogue")->add($data);

			if($res>0) {
				M("app_board_topic")->where("id='".$tid."'")->setInc("user_new");
			}
			header("location: ".U('topicDetails?tid='.$tid.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
		}

		$first = M('app_board_topic')->where('id='.$tid)->field('openid,contents,createtime,status')->find();

		$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$first['openid']."'")->find();

		$res    = M('app_board_dialogue')->where("tid='".$tid."'")->order('createtime asc')->select();

		$topic[]  = $first;
		$topic[0]['identity'] = 0;

		if($res) $list = array_merge($topic, $res);
		else     $list = $topic;

		if($token==$first['openid']) {
			$dialoguesave["admin_new"] = "0";
			M('app_board_topic')->where("id='".$tid."'")->save($dialoguesave);
		}

		$this->assign("member", $member);
		$this->assign("list",   $list);
		$this->assign("detail", $topic[0]);
		$this->display();
	}
}
