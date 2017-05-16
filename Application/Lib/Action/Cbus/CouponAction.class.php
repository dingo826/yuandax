<?php
class CouponAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_getsign();
		
	}

    public function detail() {
		$formid    = $_SESSION["formid"];
		$bminfo    = $_SESSION["cbus_bminfo"];
		$id        = intval($_GET['id']);
		$detail    = M("cbus_goods")->where("id='".$id."'")->find();

		$shop      = M("cbus_account")->find($detail["bid"]);

		$category  = M("cbus_category")->select();
		foreach($category as $key => $val) {
			$catearr[$val['id']] = $val;
		}

		//$olist    = M("cbus_goods")->where("cid='".$detail['cid']."' and id!='".$id."'")->limit(3)->select();

		/*$olist    = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->where("g.fromshequ='".$formid."' and g.cid='".$detail['cid']."' and g.id!='".$id."'")->limit(3)->select();*/

		$cbuslist = M("relat_community_cbus")->where("sqid='".$formid."' and status=2")->select();

		foreach($cbuslist as $key => $val) {
			$cbusidarr[] = $val["cbusid"];
		}


		$templist = M("cbus_goods_dels")->where("bid='".$detail['bid']."' and sqid='".$formid."'")->select();
		$dellist  = '';
		foreach($templist as $key => $val) {
			$dellist[] = $val["gid"];
		}
		$owhere = "g.bid IN ('".implode("','", $cbusidarr)."') and g.status=1 and g.isdel=-1 and g.id!='".$id."'";

		if($dellist) $owhere .= " and g.id NOT IN ('".implode("','", $dellist)."')";


		$orderby = "g.id desc";
		/*$olist    = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->join("left join ".C("DB_PREFIX")."relat_community_cbus as r on (g.bid=r.cbusid)")
			        ->where("g.fromshequ='".$formid."' and r.status=2 and isdel=-1 and g.cid='".$detail['cid']."' and g.id!='".$id."'")->order($orderby)->limit(3)->select();*/

		$olist     = M("cbus_goods")->table(C('DB_PREFIX')."cbus_goods as g ")->field("g.*, a.nickname as nickname, a.xpoint as xpoint, a.ypoint as ypoint")
			        ->join("left join ".C("DB_PREFIX")."cbus_account as a on (a.id=g.bid)")
			        ->where($owhere)->order($orderby)->limit(3)->select();


		$piclist  = M("cbus_goods_pic")->where("goodsid='".$detail['id']."'")->select();
		$this->assign('detail',  $detail);
		$this->assign('piclist', $piclist);
		$this->assign('shop',    $shop);
		$this->assign('catearr', $catearr);
		$this->assign('olist',   $olist);
		$this->display();
    }
}
