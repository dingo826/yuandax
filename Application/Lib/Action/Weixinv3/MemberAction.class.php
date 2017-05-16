<?php
class MemberAction extends Weixinv3Action {

	private	$_Model, $_configModel;

	public function __construct() {
		parent::__construct();
		$this->_Model        = M('app_mcard_member');
		$this->_configModel  = M('app_mcard_config');
	}

    public function index() {
		$this->checkset();
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$where = "wid='".$wid."'";

		$count = M('app_mcard_member')->where(array("wid"=>$wid))->count();

		import('ORG.Util.Page');
		$pageSize = 10;	// 每页显示记录数
		$page     = new Page($count, $pageSize);
		$res      = M('app_mcard_member')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();		

		// 设置用户的各项状态
		foreach($res as $val){
			$list[$val['wechatid']] = $val;
		}

		$this->assign('list', $list);
		$this->assign("count", $count);	// 关注总数
		$this->assign('pages', $page->show());

		$this->display($this->Base_themplate);
    }

	function edit() {
		$this->checkset();
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));

		if($_POST) {
			$post                  = I('post.', '',  'htmlspecialchars');
			$data['name']          = $post['truename'];
			$data['tel']           = $post['tel'];
			$data['sex']           = $post['sex'];
			$data['age']           = $post['age'];
			$data['freeze']		   = intval($post['freeze']);	     // 冻结状态

			if($post['id_card'])
				$data['id_card']   = $post['id_card'];	             // 身份证

			if($post['qq'])
				$data['qq']		   = $post['qq'];	                 // qq

			if(intval($post['buildingNo'])>0)
				$data['buildingNo']= $post['buildingNo'];	         // 楼号

			if(intval($post['unit'])>0)
				$data['unit']	   = $post['unit'];	                 // 单元号

			if(intval($post['doorNumber'])>0)
				$data['doorNumber']= $post['doorNumber'];	         // 门牌号

			$data['householdType']= intval($post['householdType']);	 // 住户类型

			$data['real_name'] = intval($post['real_name']);	     // 实名认证

			if($post['birthday'])
				$data['birthday']  = $post['birthday'];	             // 生日

			$data['etime']         = time();

			$where = "id='".$id."' and wid='".$wid."'";
			$oldRealName = M("app_mcard_member")->where($where)->getField('real_name');
			$model   = M("app_mcard_member");
			$save    = $model->where($where)->save($data);

			if( ($data['real_name'] == 1) && (!$oldRealName) ){
				// 记录认证行为
				$chData = array('op_type'=>'admin', 'wid'=>$wid, 'op_id'=>$id, 'act_type'=>'auth', 'time'=>time());
				M('log_checkin')->add($chData);
			}			

			header("location: ".U('edit?id='.$id));
			exit;
		}

		$uinfo = M('app_mcard_member')->where('wid='.$wid.' and id='.$id)->find();

		// 获取历史关注/取消关注记录
		$history = M('subscribe')->field('event,group_id,time')->where("open_id='".$uinfo['wechatid']."'")->order("time asc")->select();

		$this->assign('uinfo',   $uinfo);
		$this->assign("history", $history);
		$this->display($this->Base_themplate);
	}

	function set() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$model   = M('app_mcard_config');
		$config  = $model->where("wid='".$wid."'")->find();
		$text    = json_decode($config['text'], true);
		$select  = json_decode($config['select'], true);

		if($_POST) {
			$post                          = I('post.');
			$data['cardname']              = $post['cname'];
			$data['cname_color']           = $post['cname_color'];
			$data['card_bg']               = $post['card_bg'];
			$data['numbercolor']           = $post['numbercolor'];
			$data['name_is_edit']          = intval($post['name_is_edit']);
			$data['phone_is_edit']         = intval($post['phone_is_edit']);

			$data['hidelogo']              = intval($post['hidelogo']);

			$data['buildingNo_is_edit']    = intval($post['buildingNo_is_edit']);
			$data['unit_is_edit']          = intval($post['unit_is_edit']);
			$data['doorNumber_is_edit']    = intval($post['doorNumber_is_edit']);
			$data['householdType_is_edit'] = intval($post['householdType_is_edit']);
			$data['instructions']          = $post['instructions'];	// 居民卡说明

			$text = array();
			for($i=1;$i<6;$i++) {
				$temp = array();
				if($post['txt'.$i]) {
					$temp["txt"]         = $post['txt'.$i];
					$temp["value"]       = $post['value'.$i];
					$temp["txt_is_show"] = $post['txt_is_show'.$i];
					$temp["txt_is_edit"] = $post['txt_is_edit'.$i];
					$text[] = $temp;
				}			
			}
			$data['text'] = json_encode($text);

			$select = array();
			for($i=1;$i<6;$i++) {
				$temp = array();
				if($post['select'.$i]) {
					$temp["select"]         = $post['select'.$i];
					$temp["svalue"]          = $post['svalue'.$i];
					$temp["select_is_show"] = $post['select_is_show'.$i];
					$temp["select_is_edit"] = $post['select_is_edit'.$i];
					$select[] = $temp;
				}
			}
			$data['select'] = json_encode($select);

			$data['etime'] = time();

			if($config) {
				$save  = $model->where("wid=".$wid)->save($data);
			}else {
				$data['wid'] = $wid;
				$save  = $model->add($data);
			}
			$cardApp = I('post.cardApp');
			M('residentcard_app')->where('wid=' . $wid)->delete();	// 删除原有配置
			foreach($cardApp as $category){
				$all[] = array('wid'=>$wid, 'category'=>$category);
			}
			M('residentcard_app')->addAll($all);
			header("location: ".U('member/set'));exit;
		}

		// 获取app列表
		$appList  = M('app_list')->select();

		// 获取app选中项
		$tmp = M('residentcard_app')->where('wid='.$wid)->select();
		foreach($tmp as $val){
			$checkApp[$val['category']] = 1;
		}
		$capplist = $tmp;

		$this	-> assign("appList", $appList);
		$this	-> assign("capplist", $capplist);
		$this	-> assign("checkApp", $checkApp);
		$this   -> assign("config", $config);
		$this   -> assign("text", $text);
		$this   -> assign("select", $select);
		$this->display($this->Base_themplate);
	}

	function upwechat() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$service = D('WeixinUserIn');
		$return= $service->upwechatList($wid);
		echo 1;exit;
	}

	function upwechat2() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$jisu = I("get.jisu");
		$op   = I("get.op");
		$service = D('WeixinUserIn');
		$return= $service->upwechatList2($wid, $op);
		$total = $return["total"];
		$jisu  = $jisu + $return["count"];
		$nextopein = $return["next_openid"];;
		if($total>$jisu) {
			echo "总计有:".$total."，已获取:".$jisu."个用户，再次进行获取;";
			echo "<script>setTimeout('location.href=\"".U('upwechat2?jisu='.$jisu.'&op='.$nextopein)."\";' , 3000)</script>";
			exit;
		}else {
			echo "总计有:".$total."，已获取:".$jisu."个用户，获取完成;";
		}
		//print_r($return);
		exit;
	}

	function checkset() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$config = $this->_configModel->where("wid='".$wid."'")->find();
		if(!$config) {
			header("location: ".U('member/set'));exit;
		}
		$this->assign('config', $config);
	}
}