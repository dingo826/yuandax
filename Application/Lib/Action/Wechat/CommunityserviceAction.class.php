<?php
class CommunityserviceAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$list   = M("app_communityservice_category")->where("`wid`='".$this->wid."'")->order("id desc")->select();

		$this->assign('list', $list);
		$this->display();
    }

	public function dolist() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));

		$detail = M("app_communityservice_category")->find($id);
		$list   = M("app_communityservice_list")->where("cid='".$id."' and `wid`='".$this->wid."'")->order("id desc")->select();

		$this->assign('detail', $detail);
		$this->assign('list', $list);
		$this->display();
	}
}
