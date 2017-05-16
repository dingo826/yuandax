<?php
class TemplateAction extends WeixinAction{
  function index(){
	$uid = intval(session("uid"));
	$wid = intval(session('wid'));
  	$template_ment = M("template_ment");
  	$template      = D('template');
  	$list = $template_ment->where('parent_id=0 and is_parent=1')->field('id,name,anchor')->select();
  	$column = $template->where('`template_ment_id`=1 and `status`=1')->order('id desc')->select();

  	$channel = $template->where('template_ment_id=2 and `status`=1')->order('id desc')->field('id,title,img_url,content,flags')->select();

  	$arr_ment = $template->where('template_ment_id=3 and `status`=1')->order('id desc')->field('id,title,img_url,content,flags')->select();

  	$detailed = $template->where('template_ment_id=4 and `status`=1')->order('id desc')->field('id,title,img_url,content,flags')->select();

  	$menu = $template->where('template_ment_id=5 and `status`=1')->order('id desc')->field('id,title,img_url,content,flags')->select();
  	foreach ($menu as $key=>$val_menu){
  		$val_menu['img_url']  	= substr($val_menu['img_url'], 1);
  		$arr_menu[] 			= $val_menu;
  	}

	$detail = M("app_micro_site")->where('`wid`='.$wid)->find();
	$indexmubanid = intval($detail['home_id'])>0?intval($detail['home_id']):1;
	$channelmubanid  = intval($detail['channel_id'])>0?intval($detail['channel_id']):42;
	$listmubanid  = intval($detail['list_id'])>0?intval($detail['list_id']):27;
	$artmubanid  = intval($detail['detailed_id'])>0?intval($detail['detailed_id']):49;
  	
  	$this->assign('menu',$arr_menu);
  	$this->assign('detailed',$detailed);
  	$this->assign('ment',$arr_ment);
  	$this->assign('channel',$channel);
  	$this->assign('column',$column);

  	$this->assign('indexmubanid', $indexmubanid);
	$this->assign('channelmubanid',  $channelmubanid);
	$this->assign('listmubanid',  $listmubanid);
	$this->assign('artmubanid',  $artmubanid);

	$this->assign('list',$list);
  	$this->display($this->Base_themplate);
  }

  function edittemplate() {
	  $uid = intval(session("uid"));
	  $wid = intval(session('wid'));
	  $detail = M("app_micro_site")->where('`wid`='.$wid)->find();
	  if($_POST) {
		  $id  = I("post.id");
		  $key = I("post.key");
		  if($key == 'home') {
			  $data['home_id'] = $id;
		  }elseif($key == 'channel') {
			  $data['channel_id'] = $id;
		  }elseif($key == 'list') {
			  $data['list_id'] = $id;
		  }elseif($key == 'detailed') {
			  $data['detailed_id'] = $id;
		  }

		  $data['etime'] = time();
		  if($detail) {
			  $upid = M("app_micro_site")->where('`wid`='.$wid)->save($data);
		  }else {
			  $data['ctime'] = time();
			  $data['wid'] = $wid;
			  $upid = M("app_micro_site")->add($data);
		  }
		  echo $upid;exit;
	  }
  }
}
