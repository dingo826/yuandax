<?php
class CategoryAction extends Weixinv3Action {

	private $_microCategory;
	private	$_model;
	
	function __construct() {
		parent::__construct();
		parent::loadType();
		$this->_microCategory = D('MicroCategory');
		$this->_model = M('app_category');
		
	}
  //重置sort排序
	function checksort() {
    $wid = intval(session('wid'));
		$category_id  = intval(I('get.category_id'));
    $list=M("app_category")->where('wid="'.$wid.'" and category_id="'.$category_id.'"')->select();
	  $a=count($list);
    $list1=M("app_category")->where('wid="'.$wid.'" and category_id="'.$category_id.'"')->group('sort')->select();
    $b=count($list1);
    //$list=M("app_category")->where('wid="'.$wid.'" and category_id="'.$category_id.'"')->select();
    $i=50;
    if($a!=$b)
      foreach($list as $key => $val){
        $list[$key]['sort']=$i;
        M("app_category")->data($list[$key])->save();
        $i--;
      }
	}

	/**
	 * 分类列表页面
	 */
	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
    $id  = intval(I('get.id'));
    $this->checksort();

		$list = $this->_microCategory->getCategoryList($wid, null, 0);

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
  //判断模块并输出
  public function zilei() {
	  $this->checksort();

	  $uid          = intval(session("uid"));
	  $wid          = intval(session('wid'));
      $id           = intval(I('get.id'));
	  $category_id  = intval(I('get.category_id'));

	  //获取当前分类
	  //查询数据库当前类的信息
	   $nowclass = M("app_category")->where('id="'.$id.'"')->find();
	   //print_r($nowclass);exit;

	   //页面分类名称输出
	   $this->assign("classname", $nowclass);
	   $this->assign("cid",$id);
	   //如果当前分类是业务类
	   if($nowclass['type']!="article") {
		   if($nowclass['type']=="tel") {
			   $url = $nowclass['tel'];
			   $this->assign("linkurl", $url);
			   $this->display("tel");
			   exit;
		   }
		   if($nowclass['type']=="business") {
			   $url = "http://".C('ODOMIN').getBusinessurl($nowclass);
			   $navcontent = $this->fetch('Public:category_'.$nowclass["business_type"]);
		   }
		   if($nowclass['type']=="link")     {
			   $url = "http://".$nowclass['link'];
			   $navcontent = $this->fetch('Public:category_link');
		   }

		   $this->assign("navcontent", $navcontent);
		   $this->assign("linkurl", $url);		  
		   $this->display("business");
		   exit;
	   }
	   if($nowclass['type']=="article") {
		   $childnum = $this->_model->where("category_id='".$id."'")->count();//判断是否有子栏目
		   if($childnum>0){
			   $list =  M("app_category")->where('wid="'.$wid.'" and category_id="'.$id.'"')->select();
			   foreach($list as $key =>$val) {
				   if($val['picurl']=="/Public/static/images/pic_1.jpg") {
					   $list[$key]['picurl_s'] = $val['picurl'];
				   }else {
					   $list[$key]['picurl_s'] = preg_replace('/(\/data\/upload\/)([\d]+\/)([\d]+\/)([\d]+\/)([\w]+)(\.jpg|\.gif|\.png)/isU','$1$2$3$4$5_s$6', $val['picurl']);
				   }
			   }
			   $this->assign("list", $list);
			   $this->display("index");
			   exit;
		   }
		   $articlenum = M("app_article")->where("cid='".$id."'")->count();//判断是否存在已有文章
		   if($articlenum<1) {
			   $this->assign("status", -1);
			   $this->display("index");
			   exit;
		   }
		   if($articlenum>0){
			   $pageSize   = 25;
			   $Page       = $_GET['p'] ? $_GET['p'] : 1;
			   $m_article  = M("app_article");
			   
			   $where     =  'wid="'.$wid.'" and cid="'.$id.'"';
			   if($_GET['keyword']) $where .= " and title like '%".I("get.keyword")."%' ";

			   $list  = $m_article->where($where)->page($Page.','.$pageSize)->order('sortid desc')->select();
			   $count = $m_article->where($where)->count();

			   import('ORG.Util.Page');
			   $Page  = new Page($count, $pageSize);
			   $show  = $Page->show();
			   // 获取分类列表
			   $a = M('app_category')->where('wid="'.$wid.'" and type="article"')->select();
			   foreach($a as $key => $val){
				   $category_id=$a[$key]['category_id'];
				   $calist = M('app_category')->where('wid="'.$wid.'" and id !="'.$category_id.'" and type="article"')->order('sort desc')->select();
			   }
			   $this->assign("calist", $calist);
			   $this->assign("list", $list);
			   $this->assign("status",2);
			   $this->assign('page', $show);
			   $this->display("index");
			   exit;
		   }
	   }
	   $this->display("index");
  }
  //文章置顶
  /*public function top(){
    $wid = intval(session('wid'));
		$post = I("post.");
    $id =$post['id'];//文章id
    $cid =$post['cid']//该条文章的cid
    $list=M("app_article")->where('wid="'.$wid.'" and cid="'.$cid.'"')->select();
	  $a=count($list);
    $list1=M("app_article")->where('wid="'.$wid.'" and cid="'.$cid.'"')->group('sortid')->select();
    $b=count($list1);
    $i=50;
    if($a!=$b)
      foreach($list as $key => $val){
        $list[$key]['sortid']=$i;
        M("app_article")->data($list[$key])->save();
        $i--;
      }
    $first= M("app_article")->field('sortid')->limit(1)->select();
    $data["sortid"]=$first[0]['sortid']+1;
    M("app_article")->where('id="'.$id.'"')->save($data);
  }*/
  //移动文章到别的分类
  public function move(){
    $post = I("post.");
    $list=$post['moveid'];
    //$list3 = $this->_model->where("category_id='".$post['cid']."'")->count();
    //$list =implode(',',$post['moveid']);
    foreach($list as $key => $val){ 
      $data["cid"]=$post['cid'];
      $a=M("app_article")->where('id='.$list[$key])->save($data);
    }
    if($a>-1){
      echo "1";
    }
    else{
      echo "0";
    }
    
  }
  //前移后移
  public function change(){
    $post = I("post.");
    $list=$post["change"];
    foreach($list as $key => $val){ 
     
      $data["sort"]=$list[$key]['sortid'];
      $a=M("app_category")->where('id='.$list[$key]['categoryid'])->save($data);

    }
    if($a>0){
      $return["status"]=1;
    $this->ajaxReturn($return["status"]);
    }
    else{
      $return["status"]=-1;
    $this->ajaxReturn($return["status"]);
    }
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
	 * 检查是否能删除分类
	 */
	public function checkdel() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$post = I("post.");
    $id =$post['id'];
    
    $list4 = M("app_article")->where("cid='".$id."'")->count();
		$list = $this->_model->where("category_id='".$id."'")->count();
		if($list>0 || $list4>0){
      echo "0";exit;
		}
    else{
      echo "1";exit;
    }
		//header("location: ".U('Category/index'));exit;
	}
  //删除分类
  public function del(){
		$wid = intval(session('wid'));
		$post = I("post.");
    $id = $post['id'];
    $this->_model->where("id='".$id."' and wid='".$wid."'")->delete();
    echo "1";
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
