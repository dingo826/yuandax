<?php
class WebslideAction extends WeixinAction{

	private	$_model;

	function __construct() {
		parent::__construct();
		$this->_model = M('app_webslide');
	}

	function index(){
		parent::loadType();
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$list = $this->_model->where("wid='".$wid."'")->select();

		foreach($list as $key =>$val) {
			if($val['picurl']=="/static/weixin/image/webslide.jpg") {
				$list[$key]['picurl_s'] = $val['picurl'];
			}else {
				$list[$key]['picurl_s'] = preg_replace('/\.(\/data\/upload\/)([\d]+\/)([\d]+\/)([\d]+\/)([\w]+)(\.jpg|\.gif|\.png)/isU','$1$2$3$4$5_s$6', $val['picurl']);
			}
		}

    	$this->assign("list", $list);
		$this->display($this->Base_themplate);
	}

	function add(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post            = I("post.");
			$post['is_show'] = intval(I('post.is_show'));
			$post['wid']     = $wid;
			$post['etime']   = time();
			$post['ctime']   = time();

			$this->_model->add($post);
			header("location: ".U('index'));exit;
		}
		$detail["is_show"] = "1";
		$detail["isindex"] = "1";

		$CategoryList = D('MicroCategory')->getCategoryList($wid);

		$this->assign("CategoryList", $CategoryList);
		$this->assign("detail", $detail);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		if($_POST){
			$post            = I('post.');
			$post['is_show'] = intval(I('post.is_show'));
			$post['etime']   = time();

			$this->_model->where("id='".$id."' and wid='".$wid."'")->save($post);
			header("location: ".U('index'));exit;
		}

		$detail = $this->_model->where("id='".$id."' and wid='".$wid."'")->find();
		if($detail['picurl']=="/static/weixin/image/webslide.jpg") {
			$detail['picurl_s'] = $detail['picurl'];
		}else {
			$detail['picurl_s'] = $detail['picurl'];
		}

		$CategoryList = D('MicroCategory')->getCategoryList($wid);

		$this->assign("CategoryList", $CategoryList);

		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."add");
	}

	/**
	 * 删除分类
	 */
	public function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		$this->_model->where("id='".$id."' and wid='".$wid."'")->delete();
		header("location: ".U('webslide/index'));exit;
	}

	/**
	 * 修改状态
	 */
	public function setshow() {
		$uid    = intval(session("uid"));
		$wid    = intval(session('wid'));
		$id     = intval(I('get.id'));
		$isshow = intval(I('get.isshow'));

		$data['is_show'] = $isshow;

		$this->_model->where("id='".$id."' and wid='".$wid."'")->save($data);
		header("location: ".U('webslide/index'));exit;
	}
}
?>
