<?php
class MemberAction extends WechatAction {
	public    $user, $uid;
	
	public function __construct() {
		parent::__construct();
		$token   = I("get.token", '', 'htmlspecialchars');
		$member = M("app_mcard_member")->where("wid='".$this->wid."' and wechatid='".$token."'")->find();
		if(empty($member)) {
			echo "请采用正确方式打开居民中心";exit;
		}
		$this->user = $member;
		$this->uid  = $this->user['id'];
		$this->assign('member', $member);
		$this->assign('tget', I("get."));
	}

    public function index() {
		$token   = I("get.token", '', 'htmlspecialchars');
		$category   = M("app_category")->where("`wid`='".$this->wid."' and category_id=0 and is_home=1 and type='business' and business_type not in ('communityservice', 'juweihui', 'vipcard')")->order("sort desc")->select();
		foreach($category as $key => $val) {
			$category[$key]['url'] = getBusinessmyurl($val);
		}

		$message = M("app_board_topic")->field("*, sum(admin_new) as nhf")->where("openid='".$token."' and admin_new>0")->find();

		$where = "`wid`='".$this->wid."' and wechatid='".$token."'  and remove_state=1 and status IN (-1, -2, 2)";
		$reservation = M("wereserve_book")->where($where)->find();

		$communityservice   = M("app_communityservice_category")->where("`wid`='".$this->wid."'")->order("id desc")->select();

		$config  = M('app_mcard_config')->where("wid='".$this->wid."'")->find();

		$list    = D('Circle')->getMyCircle($this->user, $this->wid);

		$this->assign('communityservice', $communityservice);
		$this->assign('reservation', $reservation);
		$this->assign('category', $category);
		$this->assign('message',  $message);
		$this->assign('list',     $list);
		$this->assign('config',   $config);
		$this->display();
    }

	function userinfo() {
		$token   = I("get.token", '', 'htmlspecialchars');
		$model   = M("app_mcard_member");
		if($_POST) {
			$post               = I('post.', '',  'htmlspecialchars');

			if($post['truename']) $data['name']          = $post['truename'];
			if($post['tel'])      $data['tel']           = $post['tel'];
			if($post['sex'])      $data['sex']           = $post['sex'];
			//$data['age']         = $post['age'];
			
			if($post['buildingNo'])    $data['buildingNo']    = $post['buildingNo'];
			if($post['unit'])          $data['unit']		  = $post['unit'];
			if($post['doorNumber'])    $data['doorNumber']    = $post['doorNumber'];
			if($post['householdType']) $data['householdType'] = $post['householdType'];
			if($post['birthday'])      $data['birthday']      = $post['birthday'];
			//$data['info']		   = $post['info'];
			$data['check_in']      = 1;
			$data['etime']         = time();
			$user    = $model->where("wid=".$this->wid." and wechatid='".$token."'")->find();
			$uid     = $user['id'];
			$checkin = $user['check_in'];

			$save    = $model->where("id='".$uid."' and wid=".$this->wid." and wechatid='".$token."'")->save($data);

			// 检查入住情况
			if( !$checkin ){
				// 居民入住
				$chData = array('op_type'=>'residents', 'wid'=>$this->wid, 'op_id'=>$uid, 'act_type'=>'checkin', 'time'=>time());
				M('log_checkin')->add($chData);
			}
			echo "<script>history.back(-1);</script>";
			exit;
		}
		$minfo    = $model->where("wid=".$this->wid." and wechatid='".$token."'")->find();
		$this->assign("minfo", $minfo);
		$this->display();
	}
}
