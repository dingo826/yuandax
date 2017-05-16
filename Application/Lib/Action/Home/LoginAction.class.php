<?php 
class LoginAction extends Action {
	
	public function Index() {
		if($_POST) {
			$error = array("-1"=>"用户名错误",
				           "-2"=>"密码错误",
				           "-3"=>"用户名或者密码不能为空");
			$_SESSION["weixin"] = '';
			unset($_SESSION["weixin"]);

			if(empty($_POST["username"]) || empty($_POST["password"])){
				$data['errno'] = 60001;
				$data['url_route'] = '';
				$data['error'] = $error["-3"];
				$this->ajaxReturn($data);
				exit;
			}			

			$post = I('post.', '', 'htmlspecialchars');
			$us                = $post['username'];
			$pw                = $post['password'];

			if(strlen(trim($us))<6) {
				$data['errno'] = 60001;
				$data['url_route'] = '';
				$data['error'] = $error["-1"];
				$this->ajaxReturn($data);
				exit;
			}
			if(strlen(trim($pw))<6) {
				$data['errno'] = 60001;
				$data['url_route'] = '';
				$data['error'] = $error["-2"];
				$this->ajaxReturn($data);
				exit;
			}

			$count = preg_match_all('/([^0-9a-zA-Z]+)/isU', $us, $match);
			if($count>0) {
				$data['errno'] = 60001;
				$data['url_route'] = '';
				$data['error'] = "用户名错误";
				$this->ajaxReturn($data);
				exit;
			}
			$Dmodel  = D("User");
			$user    = $Dmodel->checkPwd($us, $pw);
			if($user==-1 || $user==-2) {
				$data['errno'] = 60001;
				$data['url_route'] = '';
				$data['error'] = $error[$user];
				$this->ajaxReturn($data);
				exit;
			}

			/**
			 * 获取社区id
			 */
			//$wid = M('weixin')->where('uid='.$user['id'])->getField('id');
			$weixin = M('weixin')->where('uid='.$user['id'])->find();
			$wid = $weixin["id"];
			$data['errno'] = 200;
			if($wid) {
				//$data['url_route'] = '/weixin/index/aid/'.$wid;
				if($weixin["sysversion"]==2) {
					$data['url_route'] = U("weixinv3/editing");
				}else {
					$data['url_route'] = U("weixin/index");
				}
			}else {
				if($weixin["sysversion"]==2) {
					$data['url_route'] = U("weixinv3/account");
				}else {
					$data['url_route'] = U("/user");
				}
			}
			$data['error'] = '';

			//是否记住账号
			if($_POST["rememberme"][0]==1){
				setcookie("cookie_us",$us);
			}else{
				setcookie("cookie_us",null);
			}
			
			session('uid', $user['id']);
			session('wid', $wid);
			
			$this->ajaxReturn($data);
			exit;
		}
		$list = M('News_center')->where($where)->order('etime desc')->page('1, 2')->select();
		$category = M('news_category')->field('id,name')->select();
		foreach($category as $val){
			$cateList[$val['id']] = $val['name'];
		}
		$cookie_us=$_COOKIE["cookie_us"] ? $_COOKIE["cookie_us"] : "";
		$this->assign('cookie_us', $cookie_us);
		$this->assign('controller', 'login');
		$this->assign('list',$list);
		$this->assign('cateList',$cateList);
		$this->display();
	}

	function logout () {

		// 删除session
		session('user', null);
		session('uid', null);
		session('bid', null);
		session('wid', null);
		session('[destroy]');

		// 删除cookie
		setcookie('name_cn', null);
		setcookie('address', null);
		setcookie('address_detail', null);
		header("Location: /login.html");
	}
	
	function findpswd(){
		if (IS_POST){
			$user 	= I('post.user');
			$mail 	= I('post.mail');
			if (empty($user)){
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '请填写你的账号';
				$this->ajaxReturn($re);
				exit;
			}
			
			$detail 	= M('user')->where(array('username'=>$user))->find();
			
			if (empty($mail)||$detail['email']!=$mail){
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '用户名和邮箱不匹配';
				$this->ajaxReturn($re);
				exit;
			}
			$findcode 		 = strtoupper(substr(md5(rand()),0,6));
			M('user')->where(array('id'=>$detail['id']))->save(array('findcode'=>$findcode));
			//发送邮件
			$baseurl 	= C('HTTP_DOMIN')."login/changepswd.html?u=".$user."&p=".md5($findcode);
			$url 		= "http://mailcenter.huisou.com/sentEmail?email=".$mail."&taskId=68&userId=22241&url=".urlencode($baseurl);
			
			if ($this->http_get($url)){
				$re['errno'] = 0;
				$re['url'] 	 = '/';
				$re['error'] = '重置密码邮件已发送，请登录邮箱查看';
				$this->ajaxReturn($re);
				exit;
			}else {
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '邮件发送超时，请稍后重试';
				$this->ajaxReturn($re);
				exit;
			}
		}
		$this->display();
	}
	
	function changepswd(){
		if (IS_POST){
			$code 	= I('post.code');
			$user 	= I('post.user');
			$pswd 	= I('post.pswd');
			
			if (empty($code)||empty($user)){
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '该链接已失效，请重新获取';
				$this->ajaxReturn($re);
				exit;
			}
			if (empty($pswd)){
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '请输入新密码';
				$this->ajaxReturn($re);
				exit;
			}
			
			$detail 	= M('user')->where(array('username'=>$user))->find();
			if (MD5($detail['findcode'])!=$code){
				$re['errno'] = 6001;
				$re['url'] 	 = '';
				$re['error'] = '该链接已失效，请重新获取';
				$this->ajaxReturn($re);
				exit;
			}
			
			$newpswd 	= md5(md5($pswd).$detail['salt']);
			M('user')->where(array('id'=>$detail['id']))->save(array('etime'=>time(),'findcode'=>'','password'=>$newpswd));
			
			$re['errno'] = 0;
			$re['url'] 	 = '/login.html';
			$re['error'] = '重置密码成功';
			$this->ajaxReturn($re);
			exit;
		}
		$this->display();
	}
	
	private function http_get($url){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}
}
?>
