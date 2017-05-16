<?php
class ReservationAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');

		$list 	= M('wereserve')->where(array('wid'=>$wid))->order('etime desc')->limit(50)->select();
		foreach($list as $key => $val) {
			$clist 	= M('wereserve_catelist')->where(array('rid'=>$val['id']))->order('addtime desc')->limit(10)->select();
			$list[$key]['clist'] = $clist;
		}

		$where = "`wid`='".$wid."' and wechatid='".$token."'  and remove_state=1";

		$nums = M('wereserve_book')->where($where)->count();

		$this->assign('nums',$nums);
		$this->assign('list',$list);
		$this->display();
    }

	public function yuding() {
		$wereserve      = M('wereserve');
		$wereserve_book = M('wereserve_book');
		$wtime          = M('wereserve_time');
		$matime         = M('wereserve_matime');

		$wid   = intval(I("get.wid"));
		$rid   = intval(I("get.id"));
		$token = I("get.token", '', 'htmlspecialchars');

		if($_POST) {
			print_r($_POST);
			$this->SubmitBook();
			header("location: ".U('my?wid='.$wid.'&token='.$token.'&wxref=mp.weixin.qq.com'));			
			exit;
		}		

		$where = "`id`=".$rid." and `wid`=".$wid;
		$dlist = $wereserve->where($where)->find();

		if($dlist['recommend']) {
			$plist = M('app_article')->where("id in (".$dlist['recommend'].")")->select();
		}

		$daynum = 1;
		$days   = 1;
		while($daynum<6) {
			$dtime = strtotime("+$days day");
			$week = date("w", $dtime);
			if($dlist['worktime']==1) {
				if($week<>6 && $week<>0) {
					$daynum++;
					$daytimearr[] = strtotime(date("Y-m-d", $dtime));
				}
			}else {
				$daynum++;
				$daytimearr[] = strtotime(date("Y-m-d", $dtime));
			}

			//if($week<>6 && $week<>0) {
				//$daynum++;
				//$daytimearr[] = strtotime(date("Y-m-d", $dtime));
			//}
			$days++;
		}

		$text    = json_decode($dlist['text'],true);
		$select  = json_decode($dlist['select'],true);
		$where_book = "`wereserve_rid`=".$rid." and `aid`=".$wid." and `wechatid`='".$token."' and `remove_state`=1";
		
		$count = $wereserve_book->where($where_book)->count();

		$templist = $wereserve_book->field("dateline, tslot, count(*) as count")->where("dateline>0")->group("dateline, tslot")->select();
		$yyclist = '';
		//print_r($templist);
		foreach($templist as $val) {
			if($val['tslot']==2) {
				$val['morcount'] = 0;
				$val['aftcount'] = $val['count'];
			}else {
				$val['morcount'] = $val['count'];
				$val['aftcount'] = 0;
			}
			if(empty($yyclist[$val['dateline']])) {
				$yyclist[$val['dateline']] = $val;
			}else {
				$yyclist[$val['dateline']]['aftcount'] += $val['aftcount'];
				$yyclist[$val['dateline']]['morcount'] += $val['morcount'];			
			}
			$yyclist[$val['dateline']]['count'] = $clist[$val['dateline']]['aftcount']+$clist[$val['dateline']]['morcount'];
		}
	
		foreach ($select as $key => $val){
			$val['svalue']  = explode('|', $val['svalue']);
			$arr[] = $val;
		}
		$dlist['info']  = $content=str_replace("\n","",$dlist['info']);
		$dlist['token'] = $token;

		$tlist = $matime->where('rid='.$rid." and daytime>=".$daytimearr[0])->order("daytime asc")->select();

		foreach ($tlist as $val) {
			$dtimelist[$val['daytime']] = $val;
		}
		foreach ($daytimearr as $val) {
			if(empty($dtimelist[$val])) {
				if($dlist['nummodel']==2) {
					$dtimelist[$val]['mornnums'] = $dlist['nums'];
					$dtimelist[$val]['aftnnums'] = $dlist['nums'];
					$dtimelist[$val]['morcount'] = $yyclist[$val]['morcount'];
					$dtimelist[$val]['aftcount'] = $yyclist[$val]['aftcount'];
					$dtimelist[$val]['mornsynums'] = $dlist['nums']-$yyclist[$val]['morcount'];
					$dtimelist[$val]['aftnsynums'] = $dlist['nums']-$yyclist[$val]['aftcount'];
				}
			}else {
				$dtimelist[$val]['morcount'] = $yyclist[$val]['morcount'];
				$dtimelist[$val]['aftcount'] = $yyclist[$val]['aftcount'];
				$dtimelist[$val]['mornsynums'] = $dtimelist[$val]['mornnums']-$yyclist[$val]['morcount'];
				$dtimelist[$val]['aftnsynums'] = $dtimelist[$val]['aftnnums']-$yyclist[$val]['aftcount'];
			}
		}

		$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
		$this->assign("member", $member);

		$this->assign('mon', $mon);
		$this->assign('tue', $tue);
		$this->assign('wed', $wed);
		$this->assign('thu', $thu);
		$this->assign('fri', $fri);

		$this->assign('count',$count);
		$this->assign('dlist',$dlist);
		$this->assign('clist',$clist);
		$this->assign('plist',$plist);
		$this->assign('text',$text);
		$this->assign('select',$arr);

		$this->assign('daytimearr', $daytimearr);
		$this->assign('dtimelist', $dtimelist);

		$this->display();
	}

	function SubmitBook(){
		$wereserve_book 		=	M('wereserve_book');

		$post 					= I('post.', '', 'htmlspecialchars');

		$data['wereserve_rid']  = 	intval(I('get.id'));
		$data['wid']			= 	intval(I('get.wid'));
		$data['wechatid']		= 	I('get.token');
		$data['contact']		= 	$post['truename'];
		$data['contacttel']		= 	$post['tel'];

		$data['dateline']		= 	$post['date'];
		$data['tslot']		    = 	intval($post['tslot']);
		$data['addtime']		=	time();

		$where = array('wereserve_rid'=>$data['wereserve_rid'], 'wid'=>$data['wid'], 'wechatid'=>$data['wechatid'], 'dateline'=>$data['dateline'], 'remove_state'=>'1');
		$dlist  = $wereserve_book->where($where)->getField('contact');
		if($dlist) {
			echo "<script>alert('您已预约".date("Y-m-d", $data['dateline'])."，请勿重复预约!');</script>;history.back(-1);";
		    exit;
		}

		$op_id = M('app_mcard_member')->where("wechatid='".$data['wechatid']."'")->getField('id');
		if($op_id<1) {
			echo "<script>alert('请求非法');</script>;history.back(-1);";
			exit;
		}

		$result  = $wereserve_book -> add($data);

		// 记录预约日志
		
		$ndata = $adata = array(
			'wid'           => $data['wid'],
			'wereserve_rid' => $data['wereserve_rid'],
			'op_type'       => 'residents',
			'op_id'         => $op_id,
			'act_type'      => 'new',
			'time'	        => time(),
		);
		M('log_reservation')->data($ndata)->add();	// 新增预约
		return;
	}

	function my() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');

		$wereserve_book 		=	M('wereserve_book');
		$wereserve 				=	M('wereserve');

		$where = "a.`wid`='".$wid."' and a.wechatid='".$token."'  and a.remove_state=1";

		$list = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->field("a.*, d.title as dtitle")
			->join("left join ".C("DB_PREFIX")."wereserve as d on (a.wereserve_rid=d.id)")->where($where)->order("a.addtime desc")->select();	

		$this->assign('list',$list);
		$this->display();
	}

	//取消订单
	function cancel() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');
		$id    = intval($_GET['id']);

		$data["status"] = -2;
		$where = "`wid`='".$wid."' and wechatid='".$token."' and id='".$id."'";
		M("wereserve_book")->where($where)->save($data);

		echo "<script>window.history.go(-1);</script>";exit;
	}

	//订单完成
	function complete() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');
		$id    = intval($_GET['id']);

		$data["status"] = 3;
		$where = "`wid`='".$wid."' and wechatid='".$token."' and id='".$id."'";
		M("wereserve_book")->where($where)->save($data);

		echo "<script>window.history.go(-1);</script>";exit;
	}
}
