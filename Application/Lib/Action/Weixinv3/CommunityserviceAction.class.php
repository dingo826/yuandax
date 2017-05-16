<?php
/**
 * 社区服务控制器
 * 2016/4/28
 */

class CommunityserviceAction extends Weixinv3Action {
	private $_model;

	public function __construct() {
		parent::__construct();
		//$this->_model = D('CommunityService');
	}

	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = "a.wid='".$wid."'";

		if(intval($_GET['cid'])>0) $where .= " and a.cid='".intval(I("get.cid"))."'";
		if($_GET['keyword'])       $where .= " and a.name like ('%".intval(I("get.keyword"))."%')";

		$list = M("app_communityservice_list")->table(C('DB_PREFIX')."app_communityservice_list as a")->field("a.*, d.name as cname")
			->join("left join ".C("DB_PREFIX")."app_communityservice_category as d on (a.cid=d.id)")->where($where)->order("a.id desc")->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');

		$count = M("app_communityservice_list")->table(C('DB_PREFIX')."app_communityservice_list as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$catlist = M("app_communityservice_category")->where("wid='".$wid."'")->order("id desc")->select();

		$this->assign("catlist", $catlist);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display($this->Base_themplate);
	}

	public function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST) {
			$data["wid"]     = $wid;
			$data["cid"]     = intval(I("post.ctype"));
			$data["name"]    = I("post.name");
			$data['icon']    = I("post.show_bg_img");
			$data["tel"]     = I("post.phone");
			$data["note"]    = I("post.beizhu");
			$data["details"] = I("post.content");
			$data["link"]    = I("post.url");

			$data["etime"] = time();
			$data["ctime"] = time();

			M("app_communityservice_list")->add($data);
			header("Location: ".U("communityservice/index"));exit;
		}

		$list = M("app_communityservice_category")->where("wid='".$wid."'")->order("id desc")->select();
		$this->assign('list', $list);
		$this->display($this->Base_themplate);
	}

	public function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));

		if($_POST) {
			$data["cid"]     = intval(I("post.ctype"));
			$data["name"]    = I("post.name");
			$data['icon']    = I("post.show_bg_img");
			$data["tel"]     = I("post.phone");
			$data["note"]    = I("post.beizhu");
			$data["details"] = I("post.content");
			$data["link"]    = I("post.url");

			$data["etime"] = time();

			M("app_communityservice_list")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("Location: ".U("communityservice/index"));exit;
		}

		$detail = M("app_communityservice_list")->where("id='".$id."' and wid='".$wid."'")->find();

		$list = M("app_communityservice_category")->where("wid='".$wid."'")->order("id desc")->select();
		$this->assign('list', $list);
		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."add");
	}

	public function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));
		M("app_communityservice_list")->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U("communityservice/index"));exit;
	}

	public function category() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$list = M("app_communityservice_category")->where("wid='".$wid."'")->order("id desc")->select();

		import('ORG.Util.Page');

		$count = M("app_communityservice_category")->where("wid='".$wid."'")->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display($this->Base_themplate);
	}

	public function addcat() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST) {
			$post	= I('post.');

			$data['wid']         = $wid;
			$data['name']        = $post['title'];
			$data['description'] = $post['qianming'];
			$data['icon']        = $post['show_bg_img'];
			//$data['status']      = intval($post['status']);

			$data['ctime'] = time();

			M("app_communityservice_category")->add($data);
			header("Location: ".U("communityservice/category"));exit;
		}
		$this->display($this->Base_themplate);
	}

	public function editcat() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));

		if($_POST) {
			$post	= I('post.');

			$data['name']        = $post['title'];
			$data['description'] = $post['qianming'];
			$data['icon']        = $post['show_bg_img'];
			//$data['status']      = intval($post['status']);

			M("app_communityservice_category")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("Location: ".U("communityservice/category"));exit;
		}
		$detail = M("app_communityservice_category")->where("id='".$id."' and wid='".$wid."'")->find();

		$this->assign('detail', $detail);
		$this->display($this->Base_theme."/"."addcat");
	}

	public function delcat() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));
		M("app_communityservice_category")->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U("communityservice/category"));exit;
	}
}
?>
