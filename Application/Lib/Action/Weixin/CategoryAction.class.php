<?php
class CategoryAction extends WeixinAction {

	private $_microCategory;
	private	$_model;
	
	function __construct() {
		parent::__construct();
		parent::loadType();
		$this->_microCategory = D('MicroCategory');
		$this->_model = M('app_category');
	}

	/**
	 * 分类列表页面
	 */
	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$list = $this->_microCategory->getCategoryList($wid);

		foreach($list as $key =>$val) {
			if($val['picurl']=="/Public/static/images/pic_1.jpg") {
				$list[$key]['picurl_s'] = $val['picurl'];
			}else {
				$list[$key]['picurl_s'] = preg_replace('/(\/data\/upload\/)([\d]+\/)([\d]+\/)([\d]+\/)([\w]+)(\.jpg|\.gif|\.png)/isU','$1$2$3$4$5_s$6', $val['picurl']);
			}
		}

    	$this->assign("list", $list);
    	$this->display($this->Base_themplate);
	}

	/**
	 * 更新分类信息
	 */
	public function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		if($_POST){
			$post            = I('post.');
			$post['wid']     = $wid;
			$post['is_home'] = intval($post['is_home']);
			$post['etime']   = $post['ctime'] = time();

			$this->_model->add($post);
			header("location: ".U('Category/index'));exit;
		}

		// 获取分类列表
		$calist = $this->_microCategory->getCategoryList($wid, "article");
		$detail['is_home'] = 1;
		$this->assign("detail", $detail);

		$this->assign("calist", $calist);
		$this->display($this->Base_themplate);
    }

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		if($_POST){
			$post            = I('post.');
			$post['is_home'] = intval($post['is_home']);
			$post['etime']   = time();

			$this->_model->where("id='".$id."' and wid='".$wid."'")->save($post);
			header("location: ".U('Category/index'));exit;
		}

		$detail = $this->_microCategory->getCategoryDetail($id, $wid);
		$detail['picurl_s'] = $detail['picurl'];

		// 获取分类列表
		$calist = $this->_microCategory->getCategoryList($wid, null, 0);

		$this->assign("calist", $calist);
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

		$list = $this->_model->where("category_id='".$id."'")->select();
		if($list) {
			echo "<script>alert('该分类下还有子分类');location.href='".U('Category/index')."'</script>";exit;
		}
		$this->_model->where("id='".$id."' and wid='".$wid."'")->delete();
		header("location: ".U('Category/index'));exit;
	}

	/**
	 * 修改状态
	 */
	public function setstatus() {
		$param = I('get.');
		$status = $this->_microCategory->setStatus($param['id'], 'is_home', $param['status']);
		if ($status)
			$msg = "状态修改成功";
		else
			$msg = "状态修改失败，请联系管理员";
		header("location: ".U('Category/index'));exit;
		//redirect('/weixin/microcategory/aid/'.$this->_wid);
	}

	public function delall(){
		$idarr 		= I('post.ids',0,'intval');
		$idstr 		= implode("','", $idarr);
		M('micro_category')->where("sid=".$this->binfo['id']." and id in('".$idstr."')")->delete();
		$this->ajaxReturn(array('errno'=>0));
		exit;
	}
	
	public function getactivity(){
		$type = intval($this->_post('type'));
		switch ($type){
			case 11:
				$list = M('Coupon')->where("wid = '".session('aid')."' and status=1")->field('id,a_name,keyword,begintime as start_time,endtime as end_time')->order('id desc')->select();
				break;
			case 12:
				$list = M('Card')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 13:
				$list = M('Big_wheel')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 14:
				$list = M('vote')->where("wid = '".session('aid')."' and status=1")->field('id,vtitle as a_name,keyword,begintime as start_time,endtime as end_time')->order('id desc')->select();
				break;
			case 15:
				$list = M('Exam')->where("wid = '".session('aid')."' and state=1")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 16:	
				$list = M('smashegg')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 17:	
				$list = M('app_fruit')->where("wid = '".session('aid')."' and status=1")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
				
			default:
				$list = null;
				break;
		}
		foreach ($list as $v){
			$v['start_time'] 	= date('Y-m-d H:i:s',$v['start_time']);
			$v['end_time'] 		= date('Y-m-d H:i:s',$v['end_time']);
			$nv[]=$v;
		}
		if(empty($nv)){
			$nv = "";
		}
		$result['success']	=true;
		$result['counts']	=count($list);
		$result['data']		=$nv;
		$this->ajaxReturn($result);
		exit;
	}
	private function initactivity($type){
		switch ($type){
			case 12:
				$list = M('Card')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 13:
				$list = M('Big_wheel')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 14:
				$list = M('vote')->where("wid = '".session('aid')."' and status=1")->field('id,vtitle as a_name,keyword,begintime as start_time,endtime as end_time')->order('id desc')->select();
				break;
			case 11:
				$list = M('Coupon')->where("wid = '".session('aid')."' and status=1")->field('id,a_name,keyword,begintime as start_time,endtime as end_time')->order('id desc')->select();
				break;
			case 15:
				$list = M('Exam')->where("wid = '".session('aid')."' and state=1")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 16:	
				$list = M('smashegg')->where("w_id = '".session('aid')."' and state=2")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 17:	
				$list = M('app_fruit')->where("wid = '".session('aid')."' and status=1")->field('id,a_name,keyword,start_time,end_time')->order('id desc')->select();
				break;
			case 'hotels':
				$list = M('Hotel')->where("wid='".session('aid')."'")->field('id,keyword,title as a_name')->select();
				break;
			case 'survey':
				$list = M('app_survey')->where("aid='".session('aid')."'")->field('id,keyword,title as a_name')->select();
				break;
			case 'invitation':
				$list = M('wedding')->where("aid='".session('aid')."'")->field('id,keyword,title as a_name')->select();
				break;
			case 'reservation':
				$list = M('wereserve')->where("aid='".session('aid')."'")->field('id,keyword,title as a_name')->select();
				break;
			default:
				$list = null;
				break;
		}
		foreach ($list as $v){
			$v['start_time'] = date('Y-m-d H:i:s',$v['start_time']);
			$v['end_time'] = date('Y-m-d H:i:s',$v['end_time']);
			$nv[]=$v;
		}
		return $nv;
	}
}
