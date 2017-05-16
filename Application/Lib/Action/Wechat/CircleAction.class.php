<?php
class CircleAction extends WechatAction {
	protected $Model;	     //圈子模型
	public    $user, $uid;
	
	public function __construct() {
		parent::__construct();
		$this->Model  = D('Circle');
		$this->openid = I("get.token");

		$this->user = M('app_mcard_member')->where(array('wechatid'=>$this->openid))->find();
		$this->uid = $this->user['id'];
		$this->assign('tget', I("get."));
	}

    public function index() {
		$list = $this->Model->getMyCircle($this->user, $this->wid);
		//print_r($list);exit;

		$this->assign('list', $list);
		$this->display();
    }

	function show() {
		$circleId = I('get.id');
		
		// 获取圈子信息
		$details = $this->Model->getCircleDetails($circleId);

		$issave = -1;
		$cmlist = json_decode($details["member_list"], true);
		//print_r($this->user);exit;
		$circleIds = json_decode($this->user['circle_ids'], true);
		if($this->uid>0) {
			if($cmlist[$this->uid]>0 && $circleIds[$circleId]==1) {
				$issave = 1;
			}else {
				$issave = -2;
			}
		}

		// 获取关联新闻
		$newsList = $this->Model->getAssociatedNews($details['news_id'], $this->wid);

		$this->assign('details', $details);
		$this->assign('newsList', $newsList);
		$this->assign('issave', $issave);
		$this->display();
	}

	/**
	 * 圈子资讯页
	 */
	public function news() {
		$circleId = I('get.id');
		
		// 获取圈子信息
		$details = $this->Model->getCircleDetails($circleId);

		$issave = -1;
		$cmlist = json_decode($details["member_list"], true);
		if($this->uid>0) {
			if($cmlist[$this->uid]>0) {
				$issave = 1;
			}
		}

		// 获取关联新闻
		$newsList = $this->Model->getAssociatedNews($details['news_id'], $this->wid);

		$this->assign('details', $details);
		$this->assign('newsList', $newsList);
		$this->assign('issave', $issave);
		$this->display();
	}

	/**
	 * 圈子话题页
	 */
	public function topic() {
		$circleId = I('get.cid');
		
		// 获取圈子信息
		$details = $this->Model->getCircleDetails($circleId);

		$issave = -1;
		$cmlist = json_decode($details["member_list"], true);
		if($this->uid>0) {
			if($cmlist[$this->uid]>0) {
				$issave = 1;
			}
		}

		$this->assign('details', $details);
		$this->assign('issave', $issave);
		$this->display("error");
	}

	/**
	 * 圈子相册页
	 */
	public function photo()
	{
		$circleId = I('get.cid');
		
		// 获取圈子信息
		$details = $this->Model->getCircleDetails($circleId);

		$issave = -1;
		$cmlist = json_decode($details["member_list"], true);
		if($this->uid>0) {
			if($cmlist[$this->uid]>0) {
				$issave = 1;
			}
		}

		$this->assign('details', $details);
		$this->assign('issave', $issave);
		$this->display("error");
	}

	function join() {
		$id     = I('get.id');
		$refere = $_SERVER['HTTP_REFERER'];
		if($this->uid<1) {
			echo "<script>alert('您还未入住该小区，需要先入住');location.href='".U('index/index?wid='.$this->wid.'&token='.I("get.token").'&wxref=mp.weixin.qq.com')."';</script>";
			exit;
		}

		// 加入圈子
		$status = $this->Model->join($id, $this->uid);
		if($refere) {
			$reurl = $refere;
		}else {
			$reurl = U('circle/index?wid='.$this->wid.'&token='.I("get.token").'&wxref=mp.weixin.qq.com');
		}

		header("location: ".$reurl);
		exit;
	}

	/**
	 * 圈子介绍
	 */
	function info() {
		$circleId = I('get.id');

		// 获取圈子信息
		$details = $this->Model->getCircleDetails($circleId);
		$uidList = $this->Model->_getCircleMemberList($circleId);
		$uidList = array_keys($uidList);

		$memberList = M('app_mcard_member')->where("`id` IN ('".implode("','",$uidList)."')")->select();
		foreach($memberList as $key => $val) {
			$val['bigheadimgurl'] = preg_replace('/\/64/isU', '/0', $val['headimgurl']);
			$memberList[$key] = $val;
		}
		//echo "`id` IN ('".implode("','",$uidList)."')";exit;
		//print_r($memberList);exit;

		// 计算人数
		$details['count'] = count(json_decode($details['member_list'], true));
		$this->assign('details', $details);
		$this->assign('memberList', $memberList);
		$this->display();
	}

	function quite() {
		$circleid = I('get.id');
		$uid      = $this->uid;

		$memberModel = M('app_mcard_member');
		$circleModel = M('app_circle');
		// 删除用户的圈子标签
		$circle = $this->_getUserCircle($uid);
		unset($circle[$circleid]);
		$data['circle_ids'] = json_encode($circle);
		$affrow = $memberModel->where('id=' . $uid)->save($data);
		if($affrow > 0){
			// 删除圈子中的用户标记
			$member = D('Circle')->_getCircleMemberList($circleid);
			unset($member[$uid]);
			$cdata['member_list'] = json_encode($member);
			$caff = $circleModel->where('id=' . $circleid)->save($cdata);			
		}
		header("location: ".U('index?id='.$circleId.'&wid='.$this->wid.'&token='.I("get.token").'&wxref=mp.weixin.qq.com'));exit;
	}

	 /* 获取用户的圈子标签
	 *
	 * @param	int
	 * @return	array
	 */
	private function _getUserCircle($uid) {
		$circle = M('app_mcard_member')->where('id=' . $uid)->getField('circle_ids');
		$circle = json_decode($circle, true);
		return $circle;
	}
}
