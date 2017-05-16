<?php
class WeixinAction extends UserAction {

    public function index(){
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
		header("location: ".U('weixin/index'));exit;
	}
}
