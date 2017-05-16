<?php
/**
 * 社区圈子
 */

class CircleAction extends Weixinv3Action {
	private $_model, $_basismodel;

	public function __construct() {
		parent::__construct();
		$this->_model      = M('app_circle');
	}

	public function index() {
		$uid    = intval(session("uid"));
		$wid    = intval(session('wid'));

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where  = 'wid='.$wid;

		$stype  = intval(I('get.stype'));
		if($stype==1) $where .= " and status=1";
		elseif($stype==-1) $where .= " and status=0";

		$keyword   = I('get.keyword');

		if($keyword) $where .= " and name like '%".$keyword."%'";
		$list = $this->_model->where($where)->order("id desc")->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');

		$count = $this->_model->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		// 统计人数
		foreach($list as $key => $val){
			$list[$key]['memberCount'] = count(json_decode($val['member_list'], true));
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display($this->Base_themplate);
	}

	function add() {
		$uid    = intval(session("uid"));
		$wid    = intval(session('wid'));

		if($_POST) {
			$post = I("post.", '', 'htmlspecialchars');

			$data['wid']        = $wid;
			$data['name']		= $post['name'];	            // 圈子名字
			$data['logo']		= $post['logo'];	            // 圈子logo
			$data['news_id']	= $post['news_id'];	            // 资讯类型
			$data['description']= $post['description'];	        // 简介
			$data['status']		= intval($post['status']);		// 启用状态
			$data['public']		= intval($post['public']);		// 是否公开
			$data['createtime'] = time();
			
			$save = $this->_model->add($data);
			header("location: ".U('index'));exit;
		}

		$detail['public'] = 1;
		$detail['status'] = 1;

		// 获取资讯分类
		$newsCategory = $this->_getcategory();
		$this->assign('detail',       $detail);
		$this->assign('newsCategory', $newsCategory);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid    = intval(session("uid"));
		$wid    = intval(session('wid'));
		$id     = intval(I('get.id'));

		$where  = "id='".$id."' and wid='".$wid."'";

		if($_POST) {
			$post = I("post.", '', 'htmlspecialchars');

			$data['name']		= $post['name'];	            // 圈子名字
			$data['logo']		= $post['logo'];	            // 圈子logo
			$data['news_id']	= $post['news_id'];	            // 资讯类型
			$data['description']= $post['description'];	        // 简介
			$data['status']		= intval($post['status']);		// 启用状态
			$data['public']		= intval($post['public']);		// 是否公开
			
			$save = $this->_model->where($where)->save($data);
			header("location: ".U('index'));exit;
		}

		$detail = $this->_model->where($where)->find();

		// 获取资讯分类
		$newsCategory = $this->_getcategory();

		$this->assign('detail',       $detail);
		$this->assign('newsCategory', $newsCategory);
		$this->display($this->Base_theme."/"."add");
	}

	function member() {
		$uid    = intval(session("uid"));
		$wid    = intval(session('wid'));
		$id     = intval(I('get.id'));

		$stype  = intval(I('get.stype'));

		$detail = $this->_model->find($id);

		//获取人员列表
		$uidList = D('Circle')->_getCircleMemberList($id);
		$uids = implode(',', array_keys($uidList));
		$where = 'id in ('.$uids.')';
		if($name != '') $where .= " and name like '%".$name."%'";
		$memberList = M('app_mcard_member')->where($where)->select();
		
		// 判断住户进入圈子的审核情况
		foreach($memberList as $key => $val){
			$val['bigheadimgurl'] = preg_replace('/\/64/isU', '/0', $val['headimgurl']);
			$memberList[$key] = $val;

			$cids = json_decode($val['circle_ids'], true);
			if (isset($cids[$id])){
				if($stype == 1){
					unset($memberList[$key]);
					continue;
				}
				$memberList[$key]['status'] = 1;
			}else{
				$memberList[$key]['status'] = -1;
			}
		}

		//print_r($memberList);exit;

		$this->assign('detail', $detail);
		$this->assign('list',   $memberList);
		$this->assign('id',     $id);
		$this->display($this->Base_themplate);
	}

	function review() {
		$uid      = intval(session("uid"));
		$wid      = intval(session('wid'));
		$circleid = intval(I('get.circleid'));
		$id       = intval(I('get.id'));

		$model = M('app_mcard_member');
		$where = 'id=' . $id;

		// 获取该用户当前圈子标签
		$circle             = $this->_getUserCircle($id);
		$circle[$circleid]  = 1;
		$data['circle_ids'] = json_encode($circle);

		$affrow = $model->where($where)->save($data);
		header("location: ".U('member?id='.$circleid));
		exit;
	}

	function del() {
		$uid      = intval(session("uid"));
		$wid      = intval(session('wid'));
		$id       = intval(I('get.id'));
		$this->_model->where("wid='".$wid."' and id='".$id."'")->delete();
		header("location: ".U('index'));
		exit;
	}

	function delmember() {
		$uid      = intval(session("uid"));
		$wid      = intval(session('wid'));
		
		$circleid = intval(I('get.circleid'));
		$uid      = intval(I('get.id'));

		$memberModel = M('app_mcard_member');
		$circleModel = M('app_circle');
		// 删除用户的圈子标签
		$circle = $this->_getUserCircle($uid);
		unset($circle[$circleid]);
		$data['circle_ids'] = json_encode($circle);
		$affrow = $memberModel->where('id=' . $uid)->save($data);
		if($affrow > 0){
			// 删除圈子中的用户标记
			$member = D('Circle')->_getCircleMemberList($circleid);
			unset($member[$uid]);
			$cdata['member_list'] = json_encode($member);
			$caff = $circleModel->where('id=' . $circleid)->save($cdata);			
		}
		header("location: ".U('member?id='.$circleid));exit;
	}

	/**
	 * 获取资讯分类
	 */
	private function _getcategory() {
		$wid    = intval(session('wid'));
		$where  = "wid='".$wid."' and type='article'";
    	$list = M('app_category')->where($where)->select();
    	if ($list){
    		$list = $this->_dumpTreeList($list);
    	}
    	return $list;
	}

	/**
	 * 获取用户的圈子标签
	 *
	 * @param	int
	 * @return	array
	 */
	private function _getUserCircle($uid) {
		$circle = M('app_mcard_member')->where('id=' . $uid)->getField('circle_ids');
		$circle = json_decode($circle, true);
		return $circle;
	}

	private function &_dumpTreeList($arr, $parentId = 0, $lv = 0) {
		$lv++; $tree = array(); 
		foreach ((array)$arr as $row) { 
			if ($row['category_id'] == $parentId ) { 
				$row['level'] = $lv - 1;
				if($row['category_id']!=0) $row['sty']   = "|";
                for($i = 0; $i < $row['level']; $i++) {
                    $row['sty'] .= "--";
                }
				$tree[] = $row;
				if ( $children = $this->_dumpTreeList($arr, $row['id'], $lv)) {
					$tree = array_merge($tree, $children); 
				} 
			} 
		} 
		return $tree; 
	}
}
