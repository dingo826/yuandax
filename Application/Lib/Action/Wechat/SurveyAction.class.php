<?php
class SurveyAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$list   = M("app_survey")->where("`wid`='".$this->wid."'")->order("id desc")->select();
		$this->assign('list', $list);
		$this->assign('nowtime', time());
		$this->display();
    }

	public function begin() {
		$id     = intval(I("get.id"));
		$detail = M("app_survey")->where("id='".$id."'")->find();
		$this->assign('detail', $detail);
		$this->display();
	}

	public function register() {
		$wid     = intval(I("get.wid"));
		$id      = intval(I("get.id"));
		$token   = I("get.token", '', 'htmlspecialchars');

		$user  = M("app_survey_user")->where("sid='".$id."' and token='".$token."'")->find();
		if($user['suggest'] || $user['status']==1) {
			header("location: ".U('end?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}
		if($user) {
			header("location: ".U('process?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}		

		$detail  = M("app_survey_user")->where("sid='".$id."' and token='".$token."'")->find();
		if($detail) {
			header("location: ".U('process?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
		}

		if($_POST) {
			$post  = I("post.", '', 'htmlspecialchars');

			$data['username'] = $post['username'];
			$data['phone']    = $post['phone'];
			$data['token']    = $token;
			$data['sid']      = $id;
			$data['ctime']    = time();

			$save          = M('app_survey_user')->add($data);
			header("location: ".U('process?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
		}

		$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
		$this->assign('member', $member);
		$this->display();
	}

	public function process() {
		$wid     = intval(I("get.wid"));
		$id      = intval(I("get.id"));
		$token   = I("get.token", '', 'htmlspecialchars');

		$user  = M("app_survey_user")->where("sid='".$id."' and token='".$token."'")->find();
		if(empty($user)) {
			header("location: ".U('register?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}
		if($user['suggest'] || $user['status']==1) {
			header("location: ".U('end?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}

		if($_POST) {
			$post          = I("post.", '', 'htmlspecialchars');			
			$tid = 0;
			foreach($_POST['value'] as $key => $val) {
				$temp = '';
				if($key>$tid) $tid = $key;
				$temp['uid']   = $user['id'];
			    $temp['sid']   = $id;
			    $temp['ctime'] = time();
				$temp['tid'] = $key;
				$templist    = array_keys($val);
				foreach($templist as $key2 => $val2) {
					$temp['value'.$val2] = 1;
				}
				$save = M("app_survey_info")->add($temp);
			}
			M("app_survey_user")->where("id='".$user['id']."'")->setField("setp", $tid);
			header("location: ".U('suggest?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));
			exit;
		}
		$detail = M("app_survey")->where("id='".$id."'")->find();
		$list   = M("app_survey_subject")->where("sid='".$id."'")->select();

		$this->assign('detail', $detail);
		$this->assign('list', $list);
		$this->display();
	}

	public function suggest() {
		$wid     = intval(I("get.wid"));
		$id      = intval(I("get.id"));
		$token   = I("get.token", '', 'htmlspecialchars');
		$user  = M("app_survey_user")->where("sid='".$id."' and token='".$token."'")->find();
		if(empty($user)) {
			header("location: ".U('register?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}

		if($user['suggest'] || $user['status']==1) {
			header("location: ".U('end?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}

		if($_POST) {
			$post   = I("post.", '', 'htmlspecialchars');
			$data['status']  = 1;
			$data['suggest'] = $post['suggest'];
			M("app_survey_user")->where("id='".$user['id']."'")->save($data);
			header("location: ".U('end?id='.$id.'&wid='.$wid.'&token='.$token.'&wxref='.I('get.wxref')));exit;
			exit;
		}

		$detail = M("app_survey")->where("id='".$id."'")->find();
		$this->assign('detail', $detail);
		$this->display();
	}

	public function end() {
		$wid     = intval(I("get.wid"));
		$id      = intval(I("get.id"));
		$token   = I("get.token", '', 'htmlspecialchars');
		$detail = M("app_survey")->where("id='".$id."'")->find();
		$this->assign('detail', $detail);
		$this->display();
	}
}
