<?php
class AccountAction extends UserAction {

    public function index(){
		$uid     = session("uid");
		if($_POST) {
			$_SESSION['post'] = I('post.', '', 'htmlspecialchars');
			header("location: ".U('account/index'));exit;
		}
		if($_SESSION['post']) {
			$post              = I('session.post');
			session('post', null);

			$date['mphone']    = $post['mobile'];
			$date['email']     = $post['email'];
			$date['qq']        = $post['qq'];

			$count = preg_match_all('/([^0-9a-zA-Z]+)/isU', $date['username'], $match);
			if($count>0) {
				header("location: ".U('account/index'));exit;
			}

			$date['etime'] = time();

			$Mmodel    = M("User");
			$records   = $Mmodel->where('id='.$uid)->save($date);
			header("location: ".U('account/index'));exit;
		}

		$Dmodel  = D("User");
		$user    = $Dmodel->load_core($uid);

		$this->assign('detail',$user);
		$this->display($this->Base_themplate);
    }

	public function modifypwd(){
		$uid     = session("uid");
		if($_POST) {
			$_SESSION['post'] = I('post.', '', 'htmlspecialchars');
			header("Location: ".U('modifypwd'));exit;
		}
		if($_SESSION['post']) {
			$post              = I('session.post');
			session('post', null);

			$opwd             = $post['old_password'];			
			$npwd             = $post['new_password'];
			$rpwd             = $post['repassword'];

			if($npwd<>$rpwd) {
				echo "<script>alert('两次密码输入不一致');location.href='".U('modifypwd')."'</script>";exit;
			}

			$Dmodel  = D("User");
			$user    = $Dmodel->getInformation($uid);
			if($user['password'] != md5(md5($opwd).$user['salt'])) {
				echo "<script>alert('原始密码错误');location.href='".U('modifypwd')."'</script>";
				exit;
			}

			$date['password']  = md5(md5($npwd).$user['salt']);
			$date['etime']     = time();

			$records   = $Dmodel->where('id='.$uid)->save($date);
			
			if($records==1) {
				echo "<script>alert('密码修改成功');location.href='".U('modifypwd')."'</script>";exit;
			}
			echo "<script>alert('密码修改失败');location.href='".U('modifypwd')."'</script>";exit;
		}
		$this->display($this->Base_themplate);
    }
}
