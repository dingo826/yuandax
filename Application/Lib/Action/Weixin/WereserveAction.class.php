<?php
class WereserveAction extends WeixinAction {
	private $type 			= array("1"=>"限定时间", "2"=>"限定每日量 ", "3"=>"限定全部总量");
	private $keyword;
	private $state 			= array("1" =>"等待客服回电","2" =>"确认","3" =>"拒绝");
	private $remove_state  	= array("0" =>"是","1" =>"否");
	
	function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		//$wid = 347;
		$where = 'wid='.$wid;
		if(trim($_GET['keyword'])) $where .= " and title='".trim(I('get.keyword'))."'";
		$wereserve 	    =	M('wereserve');
		$wereserve_book =   M('wereserve_book');
		$list = $wereserve->where($where)->order('etime desc')->page($Page.','.$pageSize)->select();
		foreach ($list as $key=>$val){
			
			$nextweekb  = strtotime(date("Y-m-d", strtotime("+1 day")));
			$where_book = '`wereserve_rid`='.$val['id'].' and `wid`='.$val['wid'];
			$dlist  = $wereserve_book->where($where_book)->count();
			$where_book_state = '`wereserve_rid`='.$val['id'].' and `wid`='.$val['wid'].' and dateline>='.$nextweekb;
			$dlist_state      = $wereserve_book->where($where_book_state)->count();

			$nowday = strtotime(date("Y-m-d", strtotime("now")));
			$where_book_state = '`wereserve_rid`='.$val['id'].' and `wid`='.$val['wid'].' and dateline='.$nowday;
			$nlist_state      = $wereserve_book->where($where_book_state)->count();

			$list[$key]['count'] 		= $dlist;
			$list[$key]['count_state']  = $dlist_state;
			$list[$key]['count_nowday'] = $nlist_state;
		}

		import('ORG.Util.Page');
		$count = $wereserve->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('type',$this->type);
		$this->assign('list',$list);
		$this->assign('pages', $show);
		$this->display($this->Base_themplate);
	}
	function add(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post 							= I('post.', '', 'htmlspecialchars');
			$data['wid']  					= $wid;
			$keywords_xml					= array_filter(array_unique(explode(' ',$post['title'])));
			$data['keyword']				= implode(' ',$keywords_xml);
			$data['title']					= $post['title'];	
			$data['picurl']                 = $post['show_bg_img'];
			$data['headpic']                = $post['headpic'];
			$data['info']					= $post['info'];

			if(intval($post['worktime'])==2)
				$data['worktime'] = 2;
			else 
				$data['worktime'] = 1;

			if(intval($post['nummodel'])==2) {
				$data['nummodel'] = 2;
			}else {
				$data['nummodel'] = 1;				
			}
			$data['nums']     = intval($post['nums']);
			$data['recommend'] = $post['zxid'];

			$data['ctime']=time();
			$data['etime']=time();
			$result  = M('wereserve') -> add($data);
			$rid = $result;

			$daynum = 1;
			$days   = 1;
			$data   = '';
			while($daynum<6) {
				$dtime = strtotime("+$days day");
				$week = date("w", $dtime);
				if($detail['worktime']==1) {
					if($week<>6 && $week<>0) {
						$daynum++;
						$daytimearr[] = strtotime(date("Y-m-d", $dtime));
					}
				}else {
					$daynum++;
					$daytimearr[] = strtotime(date("Y-m-d", $dtime));
				}
				$days++;
			}
			$data['uptime'] = time();
			foreach($daytimearr as $val) {
				$data['daytime'] = $val;
				if($_POST[$val]["morn"]==1)
					$data['morn'] = 1;
				else
					$data['morn'] = -1;
				if($_POST[$val]["aftn"]==1)
					$data['aftn'] = 1;
				else
					$data['aftn'] = -1;
				$data['mornnums'] = intval($_POST[$val]['mornnums'])?intval($_POST[$val]['mornnums']):10;
				$data['aftnnums'] = intval($_POST[$val]['aftnnums'])?intval($_POST[$val]['mornnums']):10;
				$data['rid'] = $rid;
				$alldata[] = $data;
				
			}
			M('wereserve_matime')->addAll($alldata);
			header("location: ".U('wereserve/index'));exit;

		}
		$this->_timeset();
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);

		if($_POST) {
			$post 							= I('post.', '', 'htmlspecialchars');
			$keywords_xml					= array_filter(array_unique(explode(' ',$post['title'])));
			$data['keyword']				= implode(' ',$keywords_xml);
			$data['title']					= $post['title'];	
			$data['picurl']                 = $post['show_bg_img'];
			$data['headpic']                = $post['headpic'];
			$data['info']					= $post['info'];

			if(intval($post['worktime'])==2)
				$data['worktime'] = 2;
			else 
				$data['worktime'] = 1;

			if(intval($post['nummodel'])==2) {
				$data['nummodel'] = 2;
			}else {
				$data['nummodel'] = 1;				
			}
			$data['nums']     = intval($post['nums']);
			$data['recommend'] = $post['zxid'];

			$data['etime']=time();
			$result  = M('wereserve')->where('id='.$rid.' and wid='.$wid)->save($data);

			$daynum = 1;
			$days   = 1;
			$data   = '';
			while($daynum<6) {
				$dtime = strtotime("+$days day");
				$week = date("w", $dtime);
				if($detail['worktime']==1) {
					if($week<>6 && $week<>0) {
						$daynum++;
						$daytimearr[] = strtotime(date("Y-m-d", $dtime));
					}
				}else {
					$daynum++;
					$daytimearr[] = strtotime(date("Y-m-d", $dtime));
				}
				$days++;
			}
			$data['uptime'] = time();

			$dtime = strtotime("+1 day");
			$list = M('wereserve_matime')->where('rid='.$rid." and daytime>=".$daytimearr[0])->select();
			foreach($list as $val) {
				$dtimelist[$val['daytime']] = $val;
			}

			foreach($daytimearr as $val) {
				$data['daytime'] = $val;
				if($_POST[$val]["morn"]==1)
					$data['morn'] = 1;
				else
					$data['morn'] = -1;
				if($_POST[$val]["aftn"]==1)
					$data['aftn'] = 1;
				else
					$data['aftn'] = -1;
				//$data['mornnums'] = intval($_POST[$val]['mornnums'])?intval($_POST[$val]['mornnums']):10;
				//$data['aftnnums'] = intval($_POST[$val]['aftnnums'])?intval($_POST[$val]['mornnums']):10;

				$data['mornnums'] = intval($post['nums']);
				$data['aftnnums'] = intval($post['nums']);

				if($dtimelist[$val]){
					M('wereserve_matime')->where('id='.$dtimelist[$val]['id'])->data($data)->save();
				}else {
					$data['rid'] = $rid;
					M('wereserve_matime')->add($data);
				}				
			}			
			header("location: ".U('wereserve/index'));exit;

		}

		$wereserve 	= M('wereserve');
		$detail     = $wereserve->where('id='.$rid.' and wid='.$wid)->find();

		$this->assign('detail',$detail);
		$this->_timeset();
		$this->display($this->Base_themplate);
	}

	public function DeleteReserve(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);

		$where 	= "`id`=".$rid." and `wid`=".$wid;
		
		M('wereserve')->where($where)->delete();
		M('wereserve_matime')->where("rid='".$rid."'")->delete();
		M('wereserve_time')->where("rid='".$rid."'")->delete();
		M('wereserve_book')->where("wid=".$wid." and wereserve_rid='".$rid."'")->delete();
		M('log_reservation')->where("wid=".$wid." and wereserve_rid='".$rid."'")->delete();
		header("location: ".U('index'));exit;
	}

	public function order() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$detail = M('wereserve')->find($rid);

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "a.`wid`='".$wid."' and a.remove_state=1";

		if($_GET['keyword']) $where .= " and (a.contact like '%".I('get.keyword')."%' or a.contacttel like '%".I('get.keyword')."%')";

		$list = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->field("a.*, d.title as dtitle")
			->join("left join ".C("DB_PREFIX")."wereserve as d on (a.wereserve_rid=d.id)")->where($where)->order("a.addtime desc")->select();

		import('ORG.Util.Page');

		$count = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->assign('detail', $detail);
		$this->display($this->Base_themplate);
	}
	
	public function reservemanage() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);

		$detail = M('wereserve')->find($rid);

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "a.`wid`='".$wid."' and a.wereserve_rid='".$rid."' and a.remove_state=1";

		if($_GET['keyword']) $where .= " and (a.contact like '%".I('get.keyword')."%' or a.contacttel like '%".I('get.keyword')."%')";

		$list = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->field("a.*, d.title as dtitle")
			->join("left join ".C("DB_PREFIX")."wereserve as d on (a.wereserve_rid=d.id)")->where($where)->order("a.addtime desc")->select();

		import('ORG.Util.Page');

		$count = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->assign('detail', $detail);
		$this->display($this->Base_themplate);
	}

	public function recycle() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);

		$detail = M('wereserve')->find($rid);

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where = "a.`wid`='".$wid."' and a.remove_state=0";

		if($rid>0) $where .= " and a.wereserve_rid='".$rid."'";

		if($_GET['keyword']) $where .= " and (a.contact like '%".I('get.keyword')."%' or a.contacttel like '%".I('get.keyword')."%')";

		$list = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->field("a.*, d.title as dtitle")
			->join("left join ".C("DB_PREFIX")."wereserve as d on (a.wereserve_rid=d.id)")->where($where)->order("a.addtime desc")->select();

		import('ORG.Util.Page');

		$count = M("wereserve_book")->table(C('DB_PREFIX')."wereserve_book as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->assign('detail', $detail);
		$template = '';
		if($rid<1) $template = 'recyclebin';
		$this->display($template);
	}
	
	//修改订单状态:拒绝订单
	function reject() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);
		$id  = intval($_GET['id']);

		$data["status"] = -1;
		$where = "`wid`='".$wid."' and wereserve_rid='".$rid."' and id='".$id."'";
		M("wereserve_book")->where($where)->save($data);

		header("location: ".U('ReserveManage?rid='.$rid));exit;
	}

	//修改订单状态:确认订单有效
	function accept() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);
		$id  = intval($_GET['id']);

		$data["status"] = 2;
		$where = "`wid`='".$wid."' and wereserve_rid='".$rid."' and id='".$id."'";
		M("wereserve_book")->where($where)->save($data);

		header("location: ".U('ReserveManage?rid='.$rid));exit;
	}

	//删除：进回收站
	function remove() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$rid = intval($_GET['rid']);
		$id  = intval($_GET['id']);

		$data["remove_state"] = 0;
		$where = "`wid`='".$wid."' and wereserve_rid='".$rid."' and id='".$id."'";
		M("wereserve_book")->where($where)->save($data);

		header("location: ".U('ReserveManage?rid='.$rid));exit;
	}

	private function _timeset() {
		$timeq = array("上午","下午");
		$wereserve = M('wereserve');
		$wtime  = M('wereserve_time');
		$matime = M('wereserve_matime');
		$wid   = session("wid");
		$rid   = intval($_GET['rid']);

		$detailTime = $wereserve->where('id='.$rid.' and wid='.$wid)->find();

		$daynum = 1;
		$days   = 1;
		while($daynum<6) {
			$dtime = strtotime("+$days day");
			$week = date("w", $dtime);
			if($detailTime['worktime']==1) {
				if($week<>6 && $week<>0) {
					$daynum++;
					$daytimearr[] = strtotime(date("Y-m-d", $dtime));
				}
			}else {
				$daynum++;
				$daytimearr[] = strtotime(date("Y-m-d", $dtime));
			}
			$days++;
		}

		$list = $matime->where('rid='.$rid." and daytime>=".$daytimearr[0])->select();
		foreach($list as $val) {
			$dtimelist[$val['daytime']] = $val;
		}
	
		$this->assign('mon', $mon);
		$this->assign('tue', $tue);
		$this->assign('wed', $wed);
		$this->assign('thu', $thu);
		$this->assign('fri', $fri);
		$this->assign('detailTime', $detailTime);
		$this->assign('dtimelist', $dtimelist);
		$this->assign('daytimearr', $daytimearr);
		$this->assign('timeq', $timeq);
	}
}
