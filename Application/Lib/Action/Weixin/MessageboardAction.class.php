<?php
class MessageboardAction extends WeixinAction {
  
  private $_msgServ;	// 留言板service
	private $_bid;		// 留言板id
	private $_member;	// 居民service
	private $_basic;	// 基础信息service
  
  public function __construct() {
		parent::__construct();
		
        $this->_msgServ = D('Message');
		$this->_member  = D('Member');
		$this->_basic   = D('BasicInfo');
        $this->_wid     = $this->weixin['id']; //347
    
		// 获取留言板id
		$this->_bid = M('app_board')->where('wid='.$this->_wid)->getField('id');
		$this->assign('boardid', $this->_bid);
	}
  
  public function index(){
    // 获取当前留言板的话题列表
		$p = I('get.p') ? I('get.p') : 1;
		$status = I('get.status') ? I('get.status') : 'all';
		$res = $this->_msgServ->getTopicList($this->_bid, $this->_currentURL, 'after', $status, $p);

		// 获取话题的最后会话
		foreach($res['list'] as $val){
			$topicId .= $val['id'] . ',';
		}
		$topicId = trim($topicId, ',');
		$lastDialogue = $this->_msgServ->getLastDialogue($topicId, $this->_bid);
		foreach($res['list'] as $key => $val){
			$res['list'][$key]['last'] = $lastDialogue[$val['id']];
		}


		// 用户头像列表
		$member = $this->_member->getMemberHead($this->_wid);
    
		$this->assign('list', $res['list']);
		$this->assign('pages', $res['page']);
		$this->assign('member', $member);
		$this->assign('status', $status);
		$this->display($this->Base_themplate);
  }
  
  /**
	 * 留言板设置
	 */
	public function setting()
	{
    if($_POST){
			// 保存设置内容
			$data = I('post.');
			$status = $this->_msgServ->saveBoardSet($this->_wid, $data);
			if(false !== $status){
				$return['errno'] = 200;
				$return['error'] = "留言板设置成功";
			}else{
				$return['errno'] = 60001;
				$return['error'] = "留言板设置失败";
			}
      
			redirect(U('messageboard/index'));
			exit;
		}
    
		$info = $this->_msgServ->getBoardSet($this->_wid);
		$member = $this->_msgServ->getAdminList($this->_bid);
    
		$this->assign('info', $info);
		$this->assign('member', $member);
		$this->display($this->Base_themplate);
	}


	/**
	 * 话题对话列表
	 */
	public function dialogueList()
	{
		// 对话内容
		$tid = I('get.tid');
		$res = $this->_msgServ->getTopicContent($this->_bid, $tid);

		// 用户头像列表
		$member = $this->_member->getMemberHead($this->_wid);
		$total = count($res);

		// 话题发起人
		$originator = $res[$total-1]['openid'];

		// 管理员头像，即社区logo
		$basic = $this->_basic->getBasicInfo($this->_wid, 'logo');

		$this->assign('member', $member);
		$this->assign('list', $res);
		$this->assign('tid', $tid);
		$this->assign('total', $total);
		$this->assign('originator', $originator);
		$this->assign('adminLogo', $basic['logo']);
		$this->display($this->Base_themplate);
	}

	/**
	 * 管理员回复
	 */
	public function reply()
	{
		$data = I('post.');
		$affrow = $this->_msgServ->replyTopic($data['tid'], $data['content'], 'admin');
		if(false !== $affrow){
			// 回复成功
			// 记录回复日志
			$openid = M('app_board_topic')->where('id='.$data['tid'])->getField('openid');
			$op_id = M('app_mcard_member')->where("wechatid='".$openid."'")->getField('id');
			$ldata = array(
				'wid' => $this->_wid,
				'tid'=> $data['tid'],
				'op_type' => 'admin',
				'op_id' => $op_id,
				'act_type' => 'reply',
				'time' => time(),
			);
			M('log_msg')->data($ldata)->add();
      
      $return['status'] = 1;
		}else{
			// 回复失败
      $return['status'] = 2;
		}
		
    $this->ajaxReturn($return);
		exit;
	}

	/**
	 * 黑名单操作
	 */
	public function backListAction()
	{
		$data = I('get.');
		if($data['type'] == 'join')
			// 加入黑名单
			$affrow = $this->_msgServ->joinBackList($data['bid'], $data['openid']);
		elseif($data['type'] == 'undo')
			// 从黑名单恢复
			$affrow = $this->_msgServ->undoBackList($data['bid'], $data['openid']);

		if(false !== $affrow){
			//操作成功
		}else{
			//操作失败
		}
		redirect('/weixin/board/lists/aid/'.$this->_wid);
		exit;
	}

	/**
	 * 黑名单列表
	 */
	public function backlist()
	{

		// 获取黑名单
		$list = $this->_msgServ->getBackList($this->_bid);

		// 用户头像列表
		$member = $this->_member->getMemberHead(session('bid'));
		$this->assign('member', $member);
		$this->assign('list', $list);
		$this->display($this->Base_themplate);
	}

	/**
	 * 删除话题
	 */
	public function delTopic()
	{
    $tids = trim(I('post.tids'), ',');
		$status = I('get.status') ? '?status='.I('get.status') : '';
		$type = I('get.type');
    
		$affrow = $this->_msgServ->delTopic($tids);
		if(false !== $affrow){
			//操作成功
			$return['errno'] = 200;
			$return['error'] = '话题删除成功！';
		}else{
			//操作失败
			$return['errno'] = 60001;
			$return['error'] = '话题删除失败！';
		}

		$this->ajaxReturn($return);
	}

	/**
	 * 手动修改处理状态
	 */
	public function updateStatus()
	{
    $tids = trim(I('post.tids'), ',');
		$status = I('post.status');
		$affrow = $this->_msgServ->updateTopicStatus($tids, $status);
    
		if(false !== $affrow){
			//操作成功
			$return['errno'] = 200;
			$return['error'] = '处理状态已修改！';
		}else{
			//操作失败
			$return['errno'] = 60001;
			$return['error'] = '处理状态修改失败！';
		}

    $this->ajaxReturn($return);
	}

	/**
	 * 修改话题的公开属性
	 */
	public function updatePublic()
	{
		$tids = trim(I('post.tids'), ',');
		$isPublic = I('post.isPublic');

		// 用户发布时设置不公开的话题，管理员无公开权限
		$affrow = $this->_msgServ->setPublic($tids, $isPublic);
		if(false !== $affrow){
			//操作成功
			$return['errno'] = 200;
			$return['error'] = '公开状态已修改！'.$isPublic;
		}else{
			//操作失败
			$return['errno'] = 60001;
			$return['error'] = '公开状态修改失败！';
		}

    $this->ajaxReturn($return);
	}
}