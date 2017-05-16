<?php
class ManageAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_business();
		$this->_getsign();
	}

    public function index(){
		$bminfo   = $_SESSION["cbus_bminfo"];

		$pageSize = 10;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where    = "bid='".$bminfo["id"]."' and isdel=-1";
		if($_GET['status']==-1) {
			$where .= " and status=-1";
			$nowstatus = -1;
		}else {
			$where .= " and status=1";
			$nowstatus = 1;
		}

		$orderby = "id desc";

		$list     = M("cbus_goods")->where($where)->order($orderby)->page($Page.', '.$pageSize)->select();

		$shangj   = M("cbus_goods")->where("bid='".$bminfo["id"]."' and status=1")->count();
		$xiajia   = M("cbus_goods")->where("bid='".$bminfo["id"]."' and status=-1")->count();
		$category = M("cbus_category")->select();
		foreach($category as $key => $val) {
			$catearr[$val['id']] = $val;
		}
		$bminfo["category"] = $catearr[$bminfo['id']]["ctitle"];

		$goodscategorylist = M("cbus_goods_category")->select();
		foreach($goodscategorylist as $key => $val) {
			$goodscatearr[$val['id']] = $val;
		}

		foreach($list as $key => $val) {
			$list[$key]["thumb"]    = thumbname($val['logo']);
			$list[$key]["ctitle"] = $goodscatearr[$val['cid']]["gctitle"];
		}

		$nowtime = time();

		$this->assign('nowstatus', $nowstatus);
		$this->assign('nowtime',   $nowtime);
		$this->assign('shangj',    $shangj);
		$this->assign('xiajia',    $xiajia);
		$this->assign('bminfo',    $bminfo);
		$this->assign('catearr',   $catearr);
		$this->assign('list',      $list);
		$this->display();
    }

	function ajax() {
		$bminfo   = $_SESSION["cbus_bminfo"];

		$pageSize = 10;
		$Page     = $_POST['p'] ? $_POST['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where    = "bid='".$bminfo["id"]."'";
		if($_GET['status']==-1) 
			$where .= " and status=-1";
		else 
			$where .= " and status=1";

		$orderby = "id desc";

		$list     = M("cbus_goods")->where($where)->order($orderby)->page($Page.', '.$pageSize)->select();

		$goodscategorylist = M("cbus_goods_category")->select();
		foreach($goodscategorylist as $key => $val) {
			$goodscatearr[$val['id']] = $val;
		}

		foreach($list as $key => $val) {
			$list[$key]["ctitle"]   = $goodscatearr[$val['cid']]["gctitle"];
			$list[$key]["thumb"]    = thumbname($val['logo']);
			$list[$key]["discount"] = ceilremovezero(ceil(($val["price"]/$val["oprice"])*100));
		}

		$nowtime = time();

		echo json_encode($list, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
