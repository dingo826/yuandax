<?php
class PlugmenuAction extends Weixinv3Action {
	private $type 		= array("article"=>"图文", "link"=>"链接", "tel"=>"电话", "map"=>"导航", "activity"=>"活动", "business"=>"业务模块", "car"=>"微汽车", "estate"=>"微房产", "food"=>"微餐饮", "shop"=>"微商城", "vshop2"=>"微商城2.2");
	private $eventtype 	= array("article"=>"article_id", "link"=>"link", "tel"=>"tel", "map"=>"", "activity"=>"activity_type", "business"=>"business_type", "car"=>"car_type", "estate"=>"estate_type", "food"=>"food_type", "shop"=>"shop_type");
	private $status 	= array("1"=>"显示", "0"=>"隐藏");

	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$model   	= M('app_plugmenu');
		$homemenu 	= M('app_homemenu');
		$list    = $model->where("wid='".$wid."'")->order("sort desc")->select();
		$dlist   = $homemenu->where("wid='".$wid."'")->find();

		$this->assign("list", $list);
		$this->assign("dlist", $dlist);
		$this->assign("type", $this->type);
		$this->display($this->Base_themplate);
    }

	function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post = I("post.", '', 'htmlspecialchars');
			$data['name']			=	$post['name'];
			$data['sort']			=	$post['order'];
			$data['is_show']		= 	intval($post['display']);
			$data['icon']			=	$post['icon'];
			$data['type']			=	$post['type'];
			$data['link']			=	$post['link'];
			$data['tel']			=	$post['tel'];
			$data['market_type'] 	= 	$post['market_type'];
			$data['shop_type']   	= 	$post['shop_type'];
			$data['food_type']   	= 	$post['food_type'];
			$data['estate_type'] 	= 	$post['estate_type'];
			$data['car_type']    	= 	$post['car_type'];
			$data['business_type'] 	= 	$post['business_type'];
			$data['business_value'] =   $post['business_value'];
			$data['activity_type'] 	= 	$post['activity_type'];
			$data['activity_value'] = 	$post['activity_value'];
			$data['place'] 			= 	$post['place'];
			$data['lng']   			= 	$post['lng'];
			$data['lat']   			= 	$post['lat'];


			$data['wid']    = $wid;
			$data['etime'] 	= $data['ctime'] = time();
			M('app_plugmenu')->add($data);

			header("Location: ".U("plugmenu/index"));exit;
		}
		$detail['is_show'] = 1;
		$this->assign("detail", $detail);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		if($_POST) {
			$post = I("post.", '', 'htmlspecialchars');
			$data['name']			=	$post['name'];
			$data['sort']			=	$post['order'];
			$data['is_show']		= 	intval($post['display']);
			$data['icon']			=	$post['icon'];
			$data['type']			=	$post['type'];
			$data['link']			=	$post['link'];
			$data['tel']			=	$post['tel'];
			$data['market_type'] 	= 	$post['market_type'];
			$data['shop_type']   	= 	$post['shop_type'];
			$data['food_type']   	= 	$post['food_type'];
			$data['estate_type'] 	= 	$post['estate_type'];
			$data['car_type']    	= 	$post['car_type'];
			$data['business_type'] 	= 	$post['business_type'];
			$data['business_value'] =   $post['business_value'];
			$data['activity_type'] 	= 	$post['activity_type'];
			$data['activity_value'] = 	$post['activity_value'];
			$data['place'] 			= 	$post['place'];
			$data['lng']   			= 	$post['lng'];
			$data['lat']   			= 	$post['lat'];

			$data['etime'] 	= $data['ctime'] = time();
			M('app_plugmenu')->where("id='".$id."' and wid='".$wid."'")->save($data);

			header("Location: ".U("plugmenu/index"));exit;
		}

		$detail = M('app_plugmenu')->where("id='".$id."' and wid='".$wid."'")->find();
		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."add");
	}

	public function setshow() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$data['is_show'] = intval(I('get.isshow'));
		$data['etime']   = time();
		$id = I('get.id');
		$status = M('app_plugmenu')->where("id='".$id."' and wid='".$wid."'")->save($data);
		header("Location: ".U("plugmenu/index"));exit;
	}

	public function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		M('app_plugmenu')->where("id='".$id."' and wid='".$wid."'")->delete();
		header("location: ".U('plugmenu/index'));exit;
	}

	public function homemenu(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$model   = M('app_homemenu');
		$dlist   = $model->where("wid='".$wid."'")->find();
		if(IS_POST){
			$post = I("post.", '', 'htmlspecialchars');
			
			$data['namecolor']    = $post['namecolor'];
			$data['homemenu']	  =	$post['homemenu'];
			$data['copyright']	  = $post['copyright'];
			$data['etime']        = time();
			$model   = M('app_homemenu');
			//print_r($data);exit;
			if($dlist) {
				$save    = $model->where("wid=".$wid." and id='".$dlist['id']."'")->save($data);
			}else {
				$data['wid']    = $wid;
				$save  = $model->add($data);
			}	
			header("location: ".U('plugmenu/index'));exit;
		}
	}
}