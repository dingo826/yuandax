<?php
class ShopAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_getsign();
		
	}

    public function detail() {
		$id        = intval($_GET['id']);
		$formid    = $_SESSION["formid"];
		$bminfo    = M("cbus_account")->where("id='".$id."'")->find();
		$intro     = M("cbus_introduction")->where("bid='".$bminfo['id']."'")->find();
		$piclist   = M("cbus_business_pic")->where("bid='".$bminfo['id']."'")->select();
		$category  = M("cbus_category")->select();
		foreach($category as $key => $val) {
			$catearr[$val['id']] = $val;
		}

		$templist = M("cbus_goods_dels")->where("bid='".$id."' and sqid='".$formid."'")->select();
		$dellist  = '';
		foreach($templist as $key => $val) {
			$dellist[] = $val["gid"];
		}

		$owhere = "g.bid='".$id."' and g.status=1 and g.isdel=-1";

		if($dellist) $owhere .= " and g.id NOT IN ('".implode("','", $dellist)."')";

		$orderby = "g.id desc";
		/*$olist    = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->join("left join ".C("DB_PREFIX")."relat_community_cbus as r on (g.bid=r.cbusid)")
			        ->where("g.fromshequ='".$formid."' and g.bid='".$id."' and g.isdel=-1")->order($orderby)->limit(3)->select();*/

		$olist     = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->where($owhere)->order($orderby)->limit(3)->select();
		//print_r($olist);exit;


		$this->assign('catearr', $catearr);
		$this->assign('bminfo',  $bminfo);
		$this->assign('intro',   $intro);
		$this->assign('piclist', $piclist);
		$this->assign('olist',   $olist);
		$this->display();
    }

	function rated() {
		$formid    = $_SESSION["formid"];
		if($_POST) {
			$type   = intval(I("post.type"));
			$shopid = intval(I("post.shopid"));

			$this->_getSessionNum();

			$raterecord = M("cbus_goods_rated")->where("bid='".$shopid."' and fromshequ='".intval(I("get.fromid"))."'")->find();

			if($raterecord) {
				$retjson['status']  = -1;
				$retjson['message'] = "已评价";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}

			$data["bid"]       = $shopid;
			$data["fromshequ"] = $formid;
			$data["etime"]     = time();
			$data["ctime"]     = time();

			if($type==-1) {
				$data["ratetype"] = -1;
			}else {
				$data["ratetype"] = 1;
			}
			$data["isvisitor"] = 1;
			$data["mid"]       = $_SESSION["randmid"];

			$insertid = M("cbus_goods_rated")->add($data);

			if($insertid>0) {
				if($type==1) M("cbus_account")->where("id='".$shopid."'")->setInc("zan");
				else M("cbus_account")->where("id='".$shopid."'")->setInc("cai");
			}
		}
		if(intval($insertid)<1) {
			$retjson['status']  = -2;
			$retjson['message'] = "评价失败";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$retjson['status']  = 1;
		$retjson['message'] = "评价成功";
		echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
		exit;
	}

	function complain() {
		$formid    = $_SESSION["formid"];
		if($_POST) {
			$shopid = intval(I("get.shopid"));
			$this->_getSessionNum();
			foreach($_POST["complain"] as $val) {
				$temp["bid"]       = $shopid;
				$temp["fromshequ"] = $formid;
				$temp["typeid"]    = $val;
				$temp["isvisitor"] = 1;
				$temp["mid"]       = $_SESSION["randmid"];
				$temp["etime"]     = time();
				$temp["ctime"]     = time();
				$data[]            = $temp;
			}
			$insertid = M("cbus_account_complain")->addAll($data);
		}

		if(intval($insertid)<1) {
			$retjson['status']  = -2;
			$retjson['message'] = "举报失败";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$retjson['status']  = 1;
		$retjson['message'] = "举报成功";
		echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
		exit;

	}
}
