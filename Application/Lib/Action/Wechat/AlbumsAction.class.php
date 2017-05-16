<?php
class AlbumsAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$list   = M("app_weialbums_albums")->where("`wid`='".$this->wid."' and isadmin=1")->order("sort desc")->select();
		$this->assign('list', $list);
		$this->display();
    }

	public function dolist() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));

		$detail = M("app_weialbums_albums")->find($id);
		$list   = M("app_weialbums_photos")->where("`abid`='".$detail['id']."'")->field("url as src, title")->order("photoid desc")->select();

		$this->assign('detail', $detail);
		$this->assign('list', $list);
		$this->assign('picjson', json_encode($list));
		$this->display("list");
	}
}
