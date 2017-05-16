<?php
class CbusAction extends WeixinAction{

	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

	function index() {
		$uid      = session("uid");
		$wid      = session("wid");

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = 'r.sqid='.$wid;

		/*$list     = M('cbus_account')->table(C('DB_PREFIX')."cbus_account as a ")->field("a.*, c.ctitle as ctitle")
		            ->join("left join ".C("DB_PREFIX")."cbus_category as c on (c.id=a.cid)")	
		            ->where($where)->order('a.id desc')->page($Page.','.$pageSize)->select();*/

		$list     = M('relat_community_cbus')->table(C('DB_PREFIX')."relat_community_cbus as r ")->field("r.id as relatid, r.status as relatstatus, a.*, c.ctitle as ctitle")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=r.cbusid)")
		            ->join("left join ".C("DB_PREFIX")."cbus_category as c on (c.id=a.cid)")	
		            ->where($where)->order('a.id desc')->page($Page.','.$pageSize)->select();


		foreach($list as $key=>$val) {
			$list[$key]['snum'] = $this->_setnum($val['id']);
		}

		import('ORG.Util.Page');
		$count = M('relat_community_cbus')->table(C('DB_PREFIX')."relat_community_cbus as r ")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("list", $list);
		$this->assign('pages', $show);

		$this->display($this->Base_themplate);
	}

	function oblist() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$busi     = M('cbus_account')->find($id);

		$where    = "g.fromshequ='".$wid."' and g.bid='".$id."'";

		$list     = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, gc.gctitle as gctitle")
		            ->join("left join ".C("DB_PREFIX")."cbus_goods_category as gc on (gc.id=g.cid)")	
		            ->where($where)->order('g.id desc')->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');
		$count = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("busi",  $busi);
		$this->assign("list",  $list);
		$this->assign('pages', $show);

		$this->display($this->Base_themplate);

	}

	function category() {
		$uid      = session("uid");
		$wid      = session("wid");

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$list     = M('cbus_category')->order('id desc')->page($Page.','.$pageSize)->select();
		foreach($list as $key=>$val) {
			$count = M("cbus_account")->where("cid='".$val['id']."'")->count();
			$list[$key]["count"] = $count;
		}

		import('ORG.Util.Page');
		$count = M('cbus_category')->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("list",  $list);
		$this->assign('pages', $show);
		$this->display($this->Base_themplate);
	}

	function profile() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));

		$relat    = M("relat_community_cbus")->where("cbusid='".$id."' and sqid='".$wid."'")->find();

		$introduction = M("cbus_introduction")->where("bid='".$id."'")->find();
		if($_POST) {
			//print_r($_POST);exit;
			$post = $_POST;

			$data["cid"]       = intval($post["cid"]);
			$data["tel"]       = $post["tel"];
			$data["province"]  = $post["province"];
			$data["city"]      = $post["city"];
			$data["county"]    = $post["county"];
			$data["contactus"] = $post["contactus"];
			$data["address"]   = $post["address"];
			$data["xpoint"]    = $post["p_x"];
			$data["ypoint"]    = $post["p_y"];
			$data["etime"]     = time();
			$upid = M("cbus_account")->where("id='".$id."'")->save($data);

			$reldata["status"] = intval($post["freeze"]);
			$reldata["etime"]  = time();
			M("relat_community_cbus")->where("cbusid='".$id."' and sqid='".$wid."'")->save($reldata);

			$introdata['content'] = $post["introd"];
			$introdata['etime']   = time();

			if($introduction) {
				M("cbus_introduction")->where("bid='".$id."'")->save($introdata);
			}else {
				$introdata['bid']     = $id;
				$introdata['ctime']   = time();
				M("cbus_introduction")->add($introdata);
			}

			$referer = U('cbus/profile?id='.$id); 
		
			if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
			header("location: ".$referer);
			exit;
		}
		$detail   = M("cbus_account")->where("id='".$id."' and fromshequ='".$wid."'")->find();
		

		$category = M('cbus_category')->select();

		$this->assign('detail',       $detail);
		$this->assign('relat',        $relat);
		$this->assign('category',     $category);
		$this->assign('introduction', $introduction);
		$this->display($this->Base_themplate);
	}

	function review() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$referer = U('cbus/index'); 
		
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];

		$data["status"] = 2;
		M("relat_community_cbus")->where("id='".$id."' and sqid='".$wid."'")->save($data);
		header("location: ".$referer);
	}

	function freeze() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$referer = U('cbus/index'); 
		
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];

		$data["status"] = -1;
		M("relat_community_cbus")->where("id='".$id."' and sqid='".$wid."'")->save($data);
		header("location: ".$referer);
	}

	function unfreeze() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$referer = U('cbus/index'); 
		
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];

		$data["status"] = 2;
		M("relat_community_cbus")->where("id='".$id."' and sqid='".$wid."'")->save($data);
		header("location: ".$referer);
	}

	function goods() {
		$uid      = session("uid");
		$wid      = session("wid");

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$cbuslist = M("relat_community_cbus")->where("sqid='".$wid."' and status=2")->select();

		foreach($cbuslist as $key => $val) {
			$cbusidarr[] = $val["cbusid"];
		}

		$templist = M("cbus_goods_dels")->where("sqid='".$wid."'")->select();
		$dellist  = '';
		foreach($templist as $key => $val) {
			$dellist[] = $val["gid"];
		}

		$where = "g.bid IN ('".implode("','", $cbusidarr)."') and isdel=-1";
		if($dellist) $where .= " and g.id NOT IN ('".implode("','", $dellist)."')";

		//M("cbus_goods")->where("deadline<'".time()."' and status=1")->save($dldata);

		$dldata["status"]      = -1;
		$dldata["isrecommend"] = -1;
		$dldata["weight"]      = 0;
		M("cbus_goods")->where("deadline<'".time()."'")->save($dldata);

		$rwhere = "gr.sqid='".$wid."' and gr.bid IN ('".implode("','", $cbusidarr)."') and g.status=1 and g.isdel=-1";

		$rtemplist = M("cbus_goods_recommends")->table(C('DB_PREFIX')."cbus_goods_recommends as gr ")->field("gr.*")
			        ->join("left join ".C("DB_PREFIX")."cbus_goods as g on (g.id=gr.gid)")
			        ->where($rwhere)->order("gr.etime desc")->page('1, 10')->select();
		//print_r($recommendlist);exit;
		foreach($rtemplist as $key => $val) {
			$recommendlist[$val['gid']] = $val;
		}

		$list     = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, c.gctitle as gctitle")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
		            ->join("left join ".C("DB_PREFIX")."cbus_goods_category as c on (c.id=g.cid)")	
		            ->where($where)->order('g.id desc')->page($Page.','.$pageSize)->select();

		foreach($list as $key=>$val) {
			$list[$key]['discount']   = ceilremovezero(ceil($val["price"]/$val["oprice"]*100));
			$list[$key]['zwdiscount'] = numtochinese($list[$key]['discount']);
			if($recommendlist[$val['id']]) {
				$list[$key]['isnowrecommend'] = 1;
			}
		}

		import('ORG.Util.Page');
		$count = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("list", $list);
		$this->assign('pages', $show);

		$this->display($this->Base_themplate);
	}

	function oneshopgood() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$detail   = M("cbus_account")->where("id='".$id."'")->find();

		$where = "g.bid ='".$id."'";

		$list     = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, c.gctitle as gctitle")
		            ->join("left join ".C("DB_PREFIX")."cbus_goods_category as c on (c.id=g.cid)")	
		            ->where($where)->order('g.id desc')->page($Page.','.$pageSize)->select();

		foreach($list as $key=>$val) {
			$list[$key]['discount']   = ceilremovezero(ceil($val["price"]/$val["oprice"]*100));
			$list[$key]['zwdiscount'] = numtochinese($list[$key]['discount']);
			if($recommendlist[$val['id']]) {
				$list[$key]['isnowrecommend'] = 1;
			}
		}

		import('ORG.Util.Page');
		$count = M('cbus_goods')->table(C('DB_PREFIX')."cbus_goods as g ")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("list",   $list);
		$this->assign('pages',  $show);
		$this->assign('detail', $detail);

		$this->display($this->Base_themplate);
	}

	function goodcategory() {
		$uid      = session("uid");
		$wid      = session("wid");

		$list     = M('cbus_goods_category')->order('id desc')->select();
		foreach($list as $key=>$val) {
			$count = M("cbus_goods")->where("cid='".$val['id']."'")->count();
			$list[$key]["count"] = $count;
		}

		$this->assign('list', $list);
		$this->display($this->Base_themplate);
	}

	function recommendgoods() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$referer  = U('cbus/goods');

		$where = "id='".$id."' and isdel=-1";

		$detail   = M("cbus_goods")->where($where)->find();

		if(!$detail) {
			if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
		    header("location: ".$referer);
			exit;
		}

		$cbusdetail = M("relat_community_cbus")->where("sqid='".$wid."' and cbusid='".$detail['bid']."'")->select();
		
		if(!$cbusdetail) {
			if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
		    header("location: ".$referer);
			exit;
		}

		$updata["etime"] = time();

		$where     = "sqid='".$wid."' and gid='".$id."' and bid = '".$detail['bid']."'";

		$recommend = M("cbus_goods_recommends")->where($where)->find();

		if($recommend) {
			M("cbus_goods_recommends")->where($where)->save($updata);
		}else {
			$updata["sqid"]  = $wid;
			$updata["bid"]   = $detail['bid'];
			$updata["gid"]   = $detail['id'];
			$updata["ctime"] = time();
			M("cbus_goods_recommends")->add($updata);
		}

		/*$dldata["isrecommend"] = 1;
		M("cbus_goods")->where("id='".$id."'")->save($dldata);*/
				
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
		header("location: ".$referer);
	}

	function recommendgoodscancel() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));

		/*$dldata["isrecommend"] = -1;
		$dldata["weight"]      = 0;
		M("cbus_goods")->where("id='".$id."'")->save($dldata);*/

		$where     = "sqid='".$wid."' and gid='".$id."'";

		M("cbus_goods_recommends")->where($where)->delete();

		$referer = U('cbus/goods');		
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
		header("location: ".$referer);
	}

	function delgoods() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));

		M("cbus_goods_recommends")->where("sqid='".$wid."' and gid='".$id."'")->delete();

		$where    = "id='".$id."' and isdel=-1";		

		$detail   = M("cbus_goods")->where($where)->find();

		$updata["sqid"]  = $wid;
		$updata["bid"]   = $detail['bid'];
		$updata["gid"]   = $detail['id'];
		$updata["etime"] = time();
		$updata["ctime"] = time();
		M("cbus_goods_dels")->add($updata);

		$referer = U('cbus/goods');		
		if($_SERVER["HTTP_REFERER"]) $referer = $_SERVER["HTTP_REFERER"];
		header("location: ".$referer);
	}

	function _setnum($id) {
		$str = '';
		if($id<10) {
			$str = "A000".$id;
		}else if($id<100) {
			$str = "A00".$id;
		}else if($id<1000) {
			$str = "A0".$id;
		}else if($id<10000) {
			$str = "A".$id;
		}else{
			$str = "A".$id;
		}
		return $str;
	}
}
?>