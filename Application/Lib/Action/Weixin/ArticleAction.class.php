<?php
class ArticleAction extends WeixinAction {

	private	$_infoModel;	// 资讯库
	private	$_cateModel;	// 资讯分类

	public function __construct() {
		parent::__construct();
		parent::loadType();
		$this->_cateModel = D('MicroCategory');
	}

	private $eventtype = array("article"=>"article_id", "link"=>"link", "tel"=>"tel", "map"=>"", "activity"=>"activity_type", "business"=>"business_type", "car"=>"car_type", "estate"=>"estate_type", "food"=>"food_type", "shop"=>"shop_type");

	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "a.wid='".$wid."'";

		if(intval($_GET['cid'])>0) $where .= " and a.cid='".intval(I("get.cid"))."'";
		if($_GET['keyword'])       $where .= " and a.title like ('%".intval(I("get.keyword"))."%')";

		$list = M("app_article")->table(C('DB_PREFIX')."app_article as a")->field("a.*, d.classname as classname")
			->join("left join ".C("DB_PREFIX")."app_category as d on (a.cid=d.id)")->where($where)->order("a.id desc")->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');

		$count = M("app_article")->table(C('DB_PREFIX')."app_article as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$catlist = $this->_cateModel->getCategoryList($wid, "article");

		$this->assign("catlist", $catlist);
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display($this->Base_themplate);
	}

	public function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post	= I('post.');

			$post['content'] = preg_replace('/data-src=".*"/isU', '', $post['content']);
			$num = preg_match_all("/<img .* src=[\"|\'](http\:\/\/.+)[\"|\']/isU", $post['content'], $match);
			$ycimgarr = array_unique($match[1]);
			if($ycimgarr) {
				$con = $post['content'];
				foreach($ycimgarr as $key => $val) {
					if(strstr($val, 'http://mmbiz.qpic.cn')) {
						$xzhimg = GrabImagewebp($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}else if(!strstr($val, 'http://www.yuandax.com')) {
						$xzhimg = GrabImage($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}
				}
				$post['content'] = $con;				
			}

			$data['uid'] = $uid;
			$data['wid'] = $wid;

			$data['title']     = $post['title'];
			$data['top']       = intval($post['zhiding']);
			$data['news_reco'] = intval($post['tuij']);
			$data['picurl']    = $post['show_bg_img'];
			$data['desc']      = $post['desc'];
			$data['content']   = htmlspecialchars($post['content']);
			$data['cid']       = $post['cid'];
			$data['type']      = $post['type'];

			$data['show_cover_pic'] = -1;
			if($post['show_cover_pic']==1) $data['show_cover_pic'] = 1;

			if($data['type']=='link') $data['link']      = $post['url'];
			if($data['type']=='business') {
				$data['business_type'] = $post['business_type'];
				if($post["isdatalist"]==1) $data['business_value'] = implode(",", $post["xiamuids"]);
			}
			
			$data['etime'] = time();
			$data['ctime'] = time();
			$data['gzid']  = $this->weixin['gzid'];		
			
			if($data['top']==1) $data["sortid"] = 50+1000;
			if($data['news_reco']==1) $data["sortid"] = $data["sortid"]+1000;

			M("app_article")->add($data);
			header("Location: ".U("article/index"));exit;
		}
		$catlist = $this->_cateModel->getCategoryList($wid, "article");

		$this->assign("catlist", $catlist);
		$this->display($this->Base_themplate);
	}

	function extractwximg() {
		if($_POST) {
			//$content	= $_POST['content'];
			$post['content'] = I('post.content');

			$post['content'] = preg_replace('/data-src=".*"/isU', '', $post['content']);
			$num = preg_match_all("/<img .* src=[\"|\'](http\:\/\/.+)[\"|\']/isU", $post['content'], $match);
			$ycimgarr = array_unique($match[1]);
			if($ycimgarr) {
				$con = $post['content'];
				foreach($ycimgarr as $key => $val) {
					if(strstr($val, 'http://mmbiz.qpic.cn')) {
						$xzhimg = GrabImagewebp($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}else if(!strstr($val, 'http://www.yuandax.com')) {
						$xzhimg = GrabImage($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}
				}
				$post['content'] = $con;
			}
			echo $post['content'];exit;
		}
	}

	function publishwx() {
		if($_POST) {
			$url = $_POST['url'];
			$html = file_get_contents($url);
			echo $html;
			exit;
		}
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));

		$detail = M("app_article")->where("id='".$id."' and wid='".$wid."'")->find();

		if($_POST) {
			$post	= I('post.');

			$post['content'] = preg_replace('/data-src=".*"/isU', '', $post['content']);
			$num = preg_match_all("/<img .* src=[\"|\'](http\:\/\/.+)[\"|\']/isU", $post['content'], $match);
			$ycimgarr = array_unique($match[1]);
			if($ycimgarr) {
				$con = $post['content'];
				foreach($ycimgarr as $key => $val) {
					if(strstr($val, 'http://mmbiz.qpic.cn')) {
						$xzhimg = GrabImagewebp($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}else if(!strstr($val, 'http://www.yuandax.com')) {
						$xzhimg = GrabImage($val);
						$xzhimg = substr($xzhimg, 1);
						$con = str_replace($val, $xzhimg, $con);
					}
				}
				$post['content'] = $con;				
			}

			$data['title']     = $post['title'];
			$data['top']       = intval($post['zhiding']);
			$data['news_reco'] = intval($post['tuij']);
			$data['picurl']    = $post['show_bg_img'];
			$data['desc']      = $post['desc'];
			$data['content']   = htmlspecialchars($post['content']);
			$data['cid']       = $post['cid'];
			$data['link']      = $post['url'];

			$data['show_cover_pic'] = -1;
			if($post['show_cover_pic']==1) $data['show_cover_pic'] = 1;

			$data['type']      = $post['type'];
			$data['link']      = '';
			$data['business_type'] = '';
			if($data['type']) {
				if($data['type']=='link') $data['link']      = $post['url'];
			    if($data['type']=='business') $data['business_type'] = $post['business_type'];
				if($post["isdatalist"]==1) $data['business_value'] = implode(",", $post["xiamuids"]);
			}
			
			$data['etime'] = time();

			$data["sortid"] = $detail['sortid'];

			if($detail['top']==1 && $data['top']==0) $data["sortid"] = $data['sortid']-1000;
			if($detail['top']==0 && $data['top']==1) $data["sortid"] = $data['sortid']+1000;

			if($detail['news_reco']==1 && $data['news_reco']==0) $data["sortid"] = $data['sortid']-1000;
			if($detail['news_reco']==0 && $data['news_reco']==1) $data["sortid"] = $data['sortid']+1000;

			if($data['news_reco']==1) $data["sortid"] = $data["sortid"]+1000;

			M("app_article")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("Location: ".U("article/index"));exit;
		}

		
		$detail['content'] = htmlspecialchars_decode($detail['content']);

		$catlist = $this->_cateModel->getCategoryList($wid, "article");
		$this->assign("catlist", $catlist);

		if($detail["type"]=="business") {
			if($detail["business_type"]=="huodonglist") {
				 $blist  = M("app_huodong")->where("id IN (".$detail['business_value'].")")->order("id desc")->select();
				 $arrids = explode(",", $detail['business_value']);
				 foreach($arrids as $key => $row) {
					 foreach($blist as $nkey => $nrow) {
						 if($nrow['id']==$row) {
							 $temp[$key] = $nrow;
						 }
					 }
				 }
				 $blist = '';
				 $blist = $temp;
				 $this->assign('businesslist', $blist);
			}
		}

		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."add");
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));
		M("app_article")->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U("article/index"));exit;
	}

	function settop() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));
		$val = intval(I('get.val'));

		$detail = M("app_article")->where("id='".$id."' and wid='".$wid."'")->find();

		$data["top"] = $val;

		if($val==1) $data["sortid"] = $detail["sortid"]+1000;
		if($val==0) $data["sortid"] = $detail["sortid"]-1000;

		M("app_article")->where("id='".$id."' and wid='".$wid."'")->save($data);
		header("Location: ".U("article/index"));exit;
	}

	function setrec() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id	 = intval(I('get.id'));
		$val = intval(I('get.val'));

		$detail = M("app_article")->where("id='".$id."' and wid='".$wid."'")->find();

		$data["news_reco"] = $val;

		if($val==1) $data["sortid"] = $detail["sortid"]+1000;
		if($val==0) $data["sortid"] = $detail["sortid"]-1000;

		M("app_article")->where("id='".$id."' and wid='".$wid."'")->save($data);
		header("Location: ".U("article/index"));exit;
	}

	function ajax() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 15;
		$Page     = $_POST['p'] ? intval($_POST['p']) : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "a.wid='".$wid."'";

		if(intval($_POST['cid'])>0) $where .= " and a.cid='".intval(I("post.cid"))."'";
		if($_POST['keyword'])       $where .= " and a.title like ('%".intval(I("post.keyword"))."%')";

		$list = M("app_article")->table(C('DB_PREFIX')."app_article as a")->field("a.id, a.title, a.picurl")
			->join("left join ".C("DB_PREFIX")."app_category as d on (a.cid=d.id)")->where($where)->order("a.id desc")->page($Page.','.$pageSize)->select();

		$count = M("app_article")->table(C('DB_PREFIX')."app_article as a")->where($where)->count();

		$ret['total'] = $count;
		$ret['list']  = $list;

		echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
		exit;
	}

	function extendtime() {
		echo 1;exit;
	}
}
