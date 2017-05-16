<?php
class WeialbumsAction extends WeixinAction {
	private $_model;
	public function __construct() {
		parent::__construct();
		$this->_model = D('Albums');
	}

	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		// 获取分类
		$cateList = $this->_model->getCategoryAll($wid, 'all');

		// 统计分类数量
		$count = $this->_model->countCategory($wid);
		$total = $count[0]['total'];	// 全部数量
		foreach($cateList as $key => $val){
			$total += $count[$val['id']]['total'];
			$cateList[$key]['total'] = $count[$val['id']]['total'] ? $count[$val['id']]['total'] : 0;
		}
		//$total = array('id'=>'','name'=>'全部','total'=>$total);
		//array_unshift($cateList, $total);

		// 获取相册列表
		if(intval(I('get.cid'))>0) $cid = intval(I('get.cid'));
		$page = I('get.p') ? I('get.p') : 1;
		if( isset($cid) ){
			$list = $this->_model->categoryFindAlbums($wid, $cid, $page);
			$pages = $this->_model->getPageInfo('albums', $wid, $this->_currentURL, $cid);
		}else{
			$list = $this->_model->getAlbumsAll($wid, $page);
			$pages = $this->_model->getPageInfo('albums', $wid, $this->_currentURL);
		}

		$this->assign("list", $list);
		$this->assign("count", $count);
		$this->assign("cateList", $cateList);
		$this->assign("pages", $pages);
		$this->assign("total", $total);
		$this->assign("cid", $cid);
		$this->display($this->Base_themplate);
	}

	function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST) {
			$data["wid"]          = $wid;
			$data["cid"]          = intval(I("post.cid"));
			$data["activityname"] = I("post.title");
			$data["note"]         = I("post.desc");
			$data["picurl"]       = I("post.show_bg_img");
			$data["etime"]        = time();
			$data["ctime"]        = time();

			$saveid = M("app_weialbums_albums")->add($data);
			header("location: ".U("weialbums/index"));exit;
		}
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST) {
			$id = intval(I("post.edit-id"));

			$data["cid"]          = intval(I("post.cid"));
			$data["activityname"] = I("post.title");
			$data["note"]         = I("post.desc");
			$data["picurl"]       = I("post.show_bg_img_edit");
			$data["etime"]        = time();

			$saveid = M("app_weialbums_albums")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("location: ".U("weialbums/index"));exit;
		}
		$this->display($this->Base_themplate);
	}

	function batchEditCategory() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$data["cid"]          = intval(I("post.category"));
		foreach($_POST['checked_id'] as $key => $val) {
			M('app_weialbums_albums')->where("id='".$val."' and wid='".$wid."'")->save($data);
		}
		header("location: ".U("weialbums/index"));exit;
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		foreach($_POST['checked_id'] as $key => $val) {
			M('app_weialbums_photos')->where("abid='".$abid."'")->delete();
			M('app_weialbums_albums')->where("id='".$val."' and wid='".$wid."'")->delete();
		}
		header("location: ".U("weialbums/index"));exit;
	}

	/**
	 * 照片列表
	 */
	public function photoslist() {
		$p    = I('get.p') ? I('get.p') : 1;
		$abid = intval(I('get.id'));

		$uid  = session("uid");
		$wid  = session("wid");

		if($_POST) {
			$num = M('app_weialbums_photos')->where('abid='.$abid)->count();
			if($_POST['new']) {
				foreach($_POST['new'] as $key => $val) {
					$num++;
					$temp['abid']  = $abid;
					$temp['title'] = $val["title"];
					$temp['url']   = $val["src"];
					$temp['ctime'] = time();
					$addarr[] = $temp;
				}
				M('app_weialbums_photos')->addall($addarr);
			}
			$temp = array();
			foreach($_POST['title'] as $key => $val) {
				$temp = array();
				$id   = $key;	
				$temp['title'] = $val[0];
				$updata = $temp;
				M('app_weialbums_photos')->where("photoid='".$id."' and abid='".$abid."'")->save($updata);
				
			}
			M('app_weialbums_albums')->where('id='.$abid.' and wid='.$wid)->setField('num',$num);
			header("location: ".U("weialbums/photoslist?id=".$abid));exit;
		}

		if( !empty($abid) ){
			$detail = $this->_model->getAlbum($abid);
			// 获取当前相册照片
			$list = $this->_model->albumsFindPhotos($abid, $p);
			$pages = $this->_model->getPhotoPage($this->_currentURL, $abid);
		}else{
			// 获取全部照片
			$list = $this->_model->getPhotosAll($wid, $p);
			$pages = $this->_model->getPhotoPage($this->_currentURL, null, $this->_wid);
		}

		// 获取分页信息
		$this->assign("list", $list);
		$this->assign("detail", $detail);
		$this->assign("abid", $abid);
		$this->assign("pages", $pages);
		$this->display($this->Base_themplate);
	}

	function delphoto() {
		$abid = intval(I('get.id'));
		$uid  = session("uid");
		$wid  = session("wid");

		$num = M('app_weialbums_photos')->where('abid='.$abid)->count();

		foreach($_POST['checked_id'] as $key => $val) {
			$num--;
			M('app_weialbums_photos')->where("photoid='".$val."' and abid='".$abid."'")->delete();
		}
		M('app_weialbums_albums')->where('id='.$abid.' and wid='.$wid)->setField('num',$num);
		header("location: ".U("weialbums/photoslist?id=".$abid));exit;
	}

	public function category() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$page = I('get.p') ? I('get.p') : 1;
		$list = $this->_model->getCategoryAll($wid, $page);

		$this->assign("list", $list);
		$this->display($this->Base_themplate);
	}

	/**
	 * 添加分类信息
	 */
	public function addcate() {	
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST) {
			$data          = I('post.');
			$data["wid"]   = $wid;
			$data["etime"] = time();
			$data["ctime"] = time();

			$status        = M('app_weialbums_albums_category')->add($data);
			header("location: ".U("weialbums/category"));exit;
		}

		$this->display($this->Base_themplate);
	}

	/**
	 * 修改分类信息
	 */
	public function editcate() {	
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id = intval(I('get.id'));

		if($_POST) {
			$data          = I('post.');
			$data["etime"] = time();
			$status        = M('app_weialbums_albums_category')->where('id='.$id)->save($data);
			header("location: ".U("weialbums/category"));exit;
		}

		$detail = M('app_weialbums_albums_category')->where('id='.$id)->find();
		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."addcate");
	}

	/**
	 * 删除分类信息
	 */
	public function delcate() {	
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$id = intval(I('get.id'));

		M('app_weialbums_albums_category')->where("id='".$id."' and wid='".$wid."'")->delete();
		header("location: ".U("weialbums/category"));exit;
	}
}