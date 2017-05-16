<?php
class XianmuAction extends WeixinAction {

	public function __construct() {
		parent::__construct();
	}

	function ajax() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 15;
		$Page     = $_POST['p'] ? intval($_POST['p']) : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$keyword = I("post.keyword");

		$btype = I("post.btype");
		$where = "wid='".$wid."'";
		switch($btype) {
			case "huodonglist":
				$where .= " and title like '%".$keyword."%'";
			    $count = M("app_huodong")->where($where)->count();
			    $list  = M("app_huodong")->where($where)->order("id desc")->page($Page.','.$pageSize)->select();
			    break;
			default :
			    break;
		}

		$ret['total'] = $count;
		$ret['list']  = $list;
		echo json_encode($ret, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
