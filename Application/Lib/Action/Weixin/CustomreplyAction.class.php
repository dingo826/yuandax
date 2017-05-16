<?php
class CustomreplyAction extends WeixinAction {

	private	$_cateModel;	// 资讯分类

	public function __construct() {
		parent::__construct();
		parent::loadType();
		$this->_cateModel = D('MicroCategory');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$keyword = I('get.keyword');

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "wid='".$wid."'";
		if($keyword) $where .= " and keyword like '%".$keyword."%'";

		$list = M("app_custom_reply")->field("*, text as content")->where($where)->order("id desc")->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');
		$count = M("app_custom_reply")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display($this->Base_themplate);
    }

	function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post    = I("post.");
			$keyarr  = explode(" ", $post["keyword"]);
			foreach($keyarr as $key => $val) {
				$list = M("app_custom_reply")->where("wid='".$wid."' and (keyword='".$val."' or keyword like '".$val." %' or keyword like '% ".$val."' or keyword like '% ".$val." %')")->find();
				if($list) {
					echo "<script>alert('关键词(".$val.")与现有系统里的其他关键词冲突,请核实!');history.back(-1);</script>";exit;
				}
			}
			$data['wid']      = $wid;
			$data['name']     = $post["name"];
			$data['keyword']  = $post["keyword"];
			$data['type']     = $post["type"];
			
			$data['etime']    = time();
			$data['ctime']    = time();

			$data['business_type'] = '';
			$data['business_value']= '';
			$data['news_ids']      = '';

			if($post["type"]=="article") $data['news_ids'] = implode(",", $post["articleids"]);
			else if($post["type"]=="business") {
				$data['business_type'] = $post["business_type"];
				if($post["isdatalist"]==1) $data['business_value'] = implode(",", $post["xiamuids"]);
			}else {
				$data['text'] = $post["content"];
			}

			M("app_custom_reply")->add($data);
			header("location: ".U('index'));exit;
		}
		$catlist = $this->_cateModel->getCategoryList($wid, "article");

		$this->assign("catlist", $catlist);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I("get.id"));
		$detail = M("app_custom_reply")->where("id='".$id."' and wid='".$wid."'")->find();
		$detail['content'] = $detail["text"];
		if(!$detail) {
			header("location: ".U('index'));exit;
		}
		if($_POST) {

			$post    = I("post.");
			$keyarr  = explode(" ", $post["keyword"]);
			foreach($keyarr as $key => $val) {
				$list = M("app_custom_reply")->where("wid='".$wid."' and id<>".$id." and (keyword='".$val."' or keyword like '".$val." %' or keyword like '% ".$val."' or keyword like '% ".$val." %')")->find();
				if($list) {
					echo "<script>alert('关键词(".$val.")与现有系统里的其他关键词冲突,请核实!');history.back(-1);</script>";exit;
				}
			}
			$data['name']     = $post["name"];
			$data['keyword']  = $post["keyword"];
			$data['type']     = $post["type"];
			$data['etime']    = time();

			if($post["type"]=="article") $data['news_ids'] = implode(",", $post["articleids"]);
			else if($post["type"]=="business") {
				$data['business_type'] = $post["business_type"];
				if($post["isdatalist"]==1) $data['business_value'] = implode(",", $post["xiamuids"]);
			}else {
				$data['text'] = $post["content"];
			}

			M("app_custom_reply")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("location: ".U('index'));exit;
		}
		if($detail["type"]=="article") {
			if($detail['news_ids']) {
				 $newslist = M("app_article")->field("id, title, picurl")->where("id IN (".$detail['news_ids'].")")->select();
				 $arrids = explode(",", $detail['news_ids']);
				 foreach($arrids as $key => $row) {
					 foreach($newslist as $nkey => $nrow) {
						 if($nrow['id']==$row) {
							 $temp[$key] = $nrow;
						 }
					 }
				 }
				 $newslist = $temp;
				 $this->assign('newslist', $newslist);
			}
		}else if($detail["type"]=="business") {
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
		$this->assign('detail', $detail);
		$this->assign('res', $detail);
		$this->display($this->Base_theme."/"."add");
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I("get.id"));
		M("app_custom_reply")->where("id='".$id."'")->delete();
		header("location: ".U('index'));exit;
	}
}