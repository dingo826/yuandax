<?php 
class RegAction extends Action {
	
	public function Index() {
		if($_POST) {
			$post = I('post.', '', 'htmlspecialchars');
			$pw    = $post['password'];
			if(!trim($post['username'])) {
				echo "用户名不得为空";exit;
			}
			$date['username']  = $post['username'];
			$date['mphone']    = $post['phone'];
			$date['email']     = $post['email'];
			//echo $date['email'];exit;
			$date['qq']        = $post['qq'];
			$date['province'] = $post['location_p'];
			$date['company'] = $post['company'];
			$date['city'] = $post['location_c'];
			$date['country'] = $post['location_a'];
			$count = preg_match_all('/([^0-9a-zA-Z]+)/isU', $date['username'], $match);
			$error = array('error'=>'username');
			$success = array('error'=>'success');
			if($count>0) {
				$this->ajaxReturn($error);
				exit;
			}
			
			$date['salt']      = strtoupper(substr(md5(rand()),0,6));
			$date['password']  = md5(md5($pw).$date['salt']);

			$date['rtime'] = $date['otime'] = $date['etime'] = $date['ctime'] = time();
			//$date['etime'] = $date['etime'] + (7*24*60*60);
			$User    = M("User");
			$one     = $User->where("username='".$date['username']."'")->find();
			if($one) {
				$error = array('error'=>'-1');
				$this->ajaxReturn($error);
				exit;
			}
			$one     = $User->where("mphone='".$date['mphone']."'")->find();
			if($one) {
				$error = array('error'=>'-2');
				$this->ajaxReturn($error);
				exit;
			}
			$one     = $User->where("email='".$date['email']."'")->find();
			if($one) {
				$error = array('error'=>'-3');
				$this->ajaxReturn($error);
				exit;
			}
			$uid     = $User->add($date);
			if($uid>0) {
				$this->ajaxReturn($success);
				exit;
			}else {
				$error = array('error'=>'-4');
				$this->ajaxReturn($error);
				exit;
			}
			
		}
		$this->display();
	}

	function chongz() {
		echo md5(md5("wx13812528573")."4D526D");exit;
	}
}
?>