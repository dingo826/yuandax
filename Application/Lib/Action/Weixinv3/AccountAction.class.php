<?php
class AccountAction extends Weixinv3Action {

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

	public function info(){
		$uid     = session("uid");
		$Dmodel  = D("Weixin");
		$arr     = $Dmodel->getWeixinUArr($uid);
		$this->assign('info',$arr[0]);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid     = session("uid");
		if($_POST) {
			if($_FILES['plc_avatar']['name']) {
				//头像添加更换
				import('ORG.Net.UploadFile');
				$avatarpath='/data/weixin/'.session('wid').'/avatar';      //头像保存目录
				$rootpath=dirname(dirname(dirname(dirname(dirname(__FILE__)))));
				//文件夹不存在则创建
				if(!is_dir($rootpath.'/data/weixin/'.session('wid'))){
					mkdir($rootpath.'/data/weixin/'.session('wid'));
				}
				if(!is_dir($rootpath.$avatarpath)){
					mkdir($rootpath.$avatarpath);
				}
				$upload = new UploadFile();
				$upload->maxSize=1048576;                      // 设置附件上传大小
				$upload->allowExts=array('jpg');               // 设置附件上传类型
				$upload->uploadReplace=true;                   // 替换头像源文件
				$upload->savePath=$rootpath.$avatarpath.'/';   // 设置附件上传目录
				if($upload->upload()) {                       // 上传错误提示错误信息                           
					$info=$upload->getUploadFileInfo();        // 上传成功 获取上传文件信息
					$filepath=$info[0]['savepath'].$info[0]['savename']; // 获取上传文件路径 一会删除用
					$avatarwidth=array(512,256,128);
					$avatarheight=array(512,256,128);
					$avatar=array('big','middle','small');     //头像文件路径
					import('ORG.Util.Image');
					$image=new Image();
					for($i=0;$i<=2;$i++){
						$image->thumb($filepath,$info[0]["savepath"].$avatar[$i].".jpg",'jpg',$avatarwidth[$i],$avatarheight[$i]);
					}
					unlink($filepath);                         //删除头像原文件
				}
			}

			$arr     = D("Weixin")->getWeixinUArr($uid);

			$post             = I('post.', '', 'htmlspecialchars');
			$wid              = $post['id'];
			session('post', null);

			$date['gzname']    = $post['plc_name'];
			$date['gzid']      = $post['plc_sourceid'];
			$date['weixinid']  = $post['wechat_id'];
			$date['province']  = $post['location_p'];
			$date['city']      = $post['location_c'];
			$date['gzemail']   = $post['email'];

			$date['xtime']     = time();

			$Mmodel            = M("weixin");

			if($arr) {
				$records       = $Mmodel->where('id='.$wid.' and uid='.$uid)->save($date);
			}else {
				$date['uid']       = $uid;
				$date['tcode']     = md5(rand());
			    $date['token']     = strtoupper(substr(md5(rand()),0,6))."_".strtoupper(substr(md5(rand()),0,1));
				$date['btime']     = $date['xtime'] = $date['ctime'] = time();
				$date['etime']     = $date['btime']+($ulist[0]['days']*24*60*60);
				$records           = $Mmodel->add($date);
			}
		}
		header("location: ".U('account/info'));exit;
	}

	function author() {
		$uid     = session("uid");
		$Dmodel  = D("Weixin");
		if ($_POST){
    		$post = I('post.', '', 'htmlspecialchars');
			$id   = $post['id'];
    		$data['appid']     = $post['appid'];
    		$data['appsecret'] = $post['appsecret'];
			$data['istuog']    = intval($post['istuog']);
			$data['pinlv']     = $post['pinlv'];

			if($data['istuog']==1) {
				$data['dhour']     = $post['hour'];
			    $data['dmin']      = $post['min'];
			}
			
    		if (empty($data['appid'])||empty($data['appsecret'])){
				echo "<script>alert('应用ID和密钥必填');location.href='".U('setauth/index')."'</script>";
				exit;
    		}
    		$Dmodel->where("id='".$id."'")->save($data);
			header("location: ".U('setauth/index'));exit;
    	}
		$arr     = $Dmodel->getWeixinUArr($uid);
		$this->assign('info',$arr[0]);
		$this->display($this->Base_themplate);
	}
}
