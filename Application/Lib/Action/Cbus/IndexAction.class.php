<?php
class IndexAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$formid = $_SESSION["formid"];

		$pageSize = 10;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$orderby = "g.id desc";
		if(intval(I("get.orderby"))==1) {
			$orderby = "g.pdiff desc";
		}

		$cbuslist = M("relat_community_cbus")->where("sqid='".$formid."' and status=2")->select();

		foreach($cbuslist as $key => $val) {
			$cbusidarr[] = $val["cbusid"];
		}

		$templist = M("cbus_goods_dels")->where("sqid='".$formid."'")->select();
		$dellist  = '';
		foreach($templist as $key => $val) {
			$dellist[] = $val["gid"];
		}

		$where = "g.bid IN ('".implode("','", $cbusidarr)."') and g.status=1 and g.isdel=-1";
		if($dellist) $where .= " and g.id NOT IN ('".implode("','", $dellist)."')";

		$rwhere = "gr.sqid='".$formid."' and gr.bid IN ('".implode("','", $cbusidarr)."') and g.status=1 and g.isdel=-1";

		$recommendlist = M("cbus_goods_recommends")->table(C('DB_PREFIX')."cbus_goods_recommends as gr ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_goods as g on (g.id=gr.gid)")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=gr.bid)")
			        ->where($rwhere)->order("gr.etime desc")->page('1, 10')->select();

		foreach($recommendlist as $key => $val) {
			$recommendlist[$key]["thumb"]   = thumbname($val['logo']);
		}


		$list     = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->where($where)->order($orderby)->page($Page.', '.$pageSize)->select();

		/*$list     = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->where("g.fromshequ='".$formid."'")->select();*/

		$goodscategorylist  = M('cbus_goods_category')->order('id desc')->select();
		foreach($goodscategorylist as $key=>$val) {
			//echo "cid='".$val['id']."' and status=1 and isdel=-1"."<br/>";
			$count = M("cbus_goods")->where("cid='".$val['id']."' and status=1 and isdel=-1 and bid IN ('".implode("','", $cbusidarr)."')")->count();
			$goodscategorylist[$key]["count"] = $count;
		}
		//exit;

		$category = M("cbus_category")->select();
		foreach($category as $key => $val) {
			$catearr[$val['id']] = $val;
		}
		foreach($list as $key => $val) {
			$list[$key]["thumb"]   = thumbname($val['logo']);
			$list[$key]["ctitle"] = $catearr[$val['cid']]["ctitle"];
		}

		$this->assign('goodscategorylist', $goodscategorylist);
		$this->assign('recommendlist',     $recommendlist);
		$this->assign('list',              $list);
		$this->display();
    }

	function ajax() {
		$formid = $_SESSION["formid"];

		$pageSize = 10;
		$Page     = $_POST['p'] ? $_POST['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$orderby = "g.id desc";
		if(intval(I("get.orderby"))==1) {
			$orderby = "g.pdiff desc";
		}

		$cbuslist = M("relat_community_cbus")->where("sqid='".$formid."' and status=2")->select();

		foreach($cbuslist as $key => $val) {
			$cbusidarr[] = $val["cbusid"];
		}

		$templist = M("cbus_goods_dels")->where("sqid='".$formid."'")->select();
		$dellist  = '';
		foreach($templist as $key => $val) {
			$dellist[] = $val["gid"];
		}

		$where = "g.bid IN ('".implode("','", $cbusidarr)."') and g.status=1 and g.isdel=-1";
		if($dellist) $where .= " and g.id NOT IN ('".implode("','", $dellist)."')";

		$list     = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        //->join("left join ".C("DB_PREFIX")."relat_community_cbus as r on (g.bid=r.cbusid)")
			        ->where($where)->order($orderby)->page($Page.', '.$pageSize)->select();
		foreach($list as $key => $val) {
			$list[$key]["thumb"]    = thumbname($val['logo']);
			$list[$key]["discount"] = ceilremovezero(ceil(($val["price"]/$val["oprice"])*100));
		}

		echo json_encode($list, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
