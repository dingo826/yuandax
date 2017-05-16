<?php
class SurveyAction extends Weixinv3Action {

	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

	function index() {
		$uid      = session("uid");
		$wid      = session("wid");

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = 'wid='.$wid;

		$list     = M('app_survey')->where($where)->order('etime desc')->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');
		$count = M('app_survey')->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("list", $list);
		$this->assign('pages', $show);
		$this->assign('nowtime', time());
		$this->display($this->Base_themplate);
	}

	function add() {
		$uid      = session("uid");
		$wid      = session("wid");
		if($_POST) {
			$post = I("post.");
			$data['title']    = $post['title'];
			$data['picurl']   = $post['show_bg_img'];
			$data['note']     = $post['note'];
			$data['note_end'] = $post['note_end'];
			$data['btime']    = strtotime($post['btime']);
			$data['etime']    = strtotime($post['etime']);

			$data['wid']      = $wid;
			$data['ctime']    = time();
			$save          = M('app_survey')->add($data);
			header("location: ".U('index'));exit;
		}
		$detail['btime'] = time();
		$detail['etime'] = $detail['btime']+(7*24*60*60);
		$this->assign("detail", $detail);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));

		if($_POST) {
			$post = I("post.");
			$data['title']    = $post['title'];
			$data['picurl']   = $post['show_bg_img'];
			$data['note']     = $post['note'];
			$data['note_end'] = $post['note_end'];
			$data['btime']    = strtotime($post['btime']);
			$data['etime']    = strtotime($post['etime']);

			$save   = M('app_survey')->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("location: ".U('index'));exit;
		}

		$detail   = M('app_survey')->where("id='".$id."' and wid='".$wid."'")->find();
		$this->assign("detail", $detail);
		$this->display($this->Base_theme."/"."add");
	}

	function basicresult() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$detail   = M('app_survey')->where("wid='".$wid."' and id='".$id."'")->find();

		$sid      = intval($detail['id']);
		$list     = M('app_survey_subject')->where("sid='".$sid."'")->order("tid desc")->select();
		$this    -> assign("list", $list);
		$this    -> assign("detail", $detail);
		$this->display($this->Base_themplate);
	}

	function questionlist() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));

		$detail   = M('app_survey')->where("id='".$id."' and wid='".$wid."'")->find();

		$where    = "sid='".$id."'";
		$title = I("get.title", '', 'htmlspecialchars');
		if($title) $where .= " and title like '%".$title."%'";
		$list     = M('app_survey_subject')->where($where)->order("tid desc")->select();

		$this  -> assign("detail", $detail);
		$this  -> assign("list", $list);
		$this->display($this->Base_themplate);
	}

	function addoption() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));
		if($_POST) {
			$post            = I("post.");
			$data['title']   = trim($post['title']);
			$data['option1'] = trim($post['option1']);
			$data['option2'] = trim($post['option2']);
			$data['option3'] = trim($post['option3']);
			$data['option4'] = trim($post['option4']);
			$data['option5'] = trim($post['option5']);
			$data['num']     = intval($post['limit_count']);

			if(strlen($data['title'])<2 || strlen($data['option1'])<1 || strlen($data['option2'])<1 || $data['num']>5 || $data['num']<1) {
				echo "<script>alert('提交的数据不满足条件，请重新按要求输入');history.back(-1);</script>";
				exit; 
			}

			$data['sid']   = $id;
			$save          = M('app_survey_subject')->add($data);

			header("location: ".U('questionlist?id='.$id));exit;
		}
		$detail   = M('app_survey')->where("id='".$id."' and wid='".$wid."'")->find();
		$this->assign("detail", $detail);
		$this->display($this->Base_themplate);
	}

	function editquestion() {
		$uid      = session("uid");
		$wid      = session("wid");
		$sid      = intval(I('get.sid'));
		$id       = intval(I('get.id'));
		if($_POST) {
			$post            = I("post.");
			$data['title']   = trim($post['title']);
			$data['option1'] = trim($post['option1']);
			$data['option2'] = trim($post['option2']);
			$data['option3'] = trim($post['option3']);
			$data['option4'] = trim($post['option4']);
			$data['option5'] = trim($post['option5']);
			$data['num']     = intval($post['limit_count']);

			if(strlen($data['title'])<2 || strlen($data['option1'])<1 || strlen($data['option2'])<1 || $data['num']>5 || $data['num']<1) {
				echo "<script>alert('提交的数据不满足条件，请重新按要求输入');history.back(-1);</script>";
				exit; 
			}

			$save          = M('app_survey_subject')->where("sid='".$sid."' and tid='".$id."'")->save($data);

			header("location: ".U('questionlist?id='.$sid));exit;
		}
		$detail   = M('app_survey')->where("id='".$sid."' and wid='".$wid."'")->find();
		$this->assign("detail", $detail);

		$subject  = M('app_survey_subject')->where("tid='".$id."' and sid='".$sid."'")->find();
		$this->assign("subject", $subject);
		$this->display($this->Base_theme."/"."addoption");
	}

	function chart() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval($_GET['id']);
		$detail   = M('app_survey')->where("id='".$id."'")->find();
		$slist    = M('app_survey_subject')->where("sid='".$id."'")->select();

		foreach($slist as $key => $val) {
			$valdata = '';
			$vlist = M("app_survey_info")->field("sum(`value1`) as valnum1, sum(`value2`) as valnum2, sum(`value3`) as valnum3, sum(`value4`) as valnum4, sum(`value5`) as valnum5")->where("tid='".$val['tid']."'")->find();
			$temp['option'] = $val['option1'];
			$temp['valnum'] = $vlist['valnum1'];
			$valdata[]      = $temp;

			$temp['option'] = $val['option2'];
			$temp['valnum'] = $vlist['valnum2'];
			$valdata[]      = $temp;

			if($val['option3']) {
				$temp['option'] = $val['option3'];
			    $temp['valnum'] = $vlist['valnum3'];
				$valdata[]      = $temp;
			}

			if($val['option4']) {
				$temp['option'] = $val['option4'];
			    $temp['valnum'] = $vlist['valnum4'];
				$valdata[]      = $temp;
			}

			if($val['option5']) {
				$temp['option'] = $val['option5'];
			    $temp['valnum'] = $vlist['valnum5'];
				$valdata[]      = $temp;
			}
			$piedatemp["title"] = $val["title"];
			$piedatemp["vlist"] = $valdata;
			$piedata[]          = $piedatemp;
		}

		$sql      = "select *, FROM_UNIXTIME(ctime, '%Y-%m-%d') as ctimet, count(*) as count from ".C('DB_PREFIX')."app_survey_user where sid='".$id."' group by ctimet order by ctimet asc";
		$flist    = M()->query($sql);
		foreach($flist as $key => $val) {
			$ftime[]  = $val['ctimet'];
			$fcount[] = $val['count'];
		}

		$vftime  = '"'.implode('","', $ftime).'"';
		$vfcount = implode(',', $fcount);
		$this    ->assign("detail", $detail);
		$this    ->assign("piedata", $piedata);
		$this    ->assign("flist", $flist);
		$this    ->assign("vftime", $vftime);
		$this    ->assign("vfcount", $vfcount);
		$this->display($this->Base_themplate);
	}

	function userdata() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval($_GET['id']);

		$detail   = M('app_survey')->where("id='".$id."'")->find();

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = "sid='".$id."'";

		$ulist    = M('app_survey_user')->where($where)->order('ctime desc')->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');
		$count = M('app_survey_user')->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("ulist", $ulist);
		$this->assign('pages', $show);
		$this->assign('detail', $detail);
		$this->display($this->Base_themplate);
	}

	function userresult() {
		$uid      = session("uid");
		$aid      = session("aid");
		$sid      = intval($_GET['id_survey']);
		$uid      = intval($_GET['uid']);

		$detail   = M('app_survey')->where("id='".$sid."'")->find();
		$slist    = M('app_survey_subject')->where("sid='".$sid."'")->select();
		foreach($slist as $key => $val) {
			$flist= M('app_survey_info')->where("uid='".$uid."' and tid='".$val['tid']."' and sid='".$sid."'")->find();
			$slist[$key]['info'] = $flist;
		}
		$this    ->assign("detail", $detail);
		$this    ->assign("slist", $slist);
		$this->display($this->Base_themplate);
	}

	function showperdata() {
		$uid     = session("uid");
		$wid     = session("wid");
		$id      = intval(I("get.id"));
		$uid     = intval(I("get.uid"));
		$subject = M("app_survey_subject")->where("sid='".$id."'")->select();
		$info    = M("app_survey_info")->where("sid='".$id."' and uid='".$uid."'")->select();
		foreach($info as $key => $val) {
			$ninfo[$val['tid']] = $val;
		}
		$html    = "";

		foreach($subject as $key => $val) {
			$html .= '<tr><td>'.$val['title'].'</td><td><div class="result">';
			for($i=1; $i<6; $i++) {
				if($val['option'.$i]) {
					if(intval($ninfo[$val['tid']]['value'.$i])==1) {
						$html .= '<div class="checked">'.$val['option'.$i].'</div>';
					}else {
						$html .= '<div>'.$val['option'.$i].'</div>';
					}
				}
			}
			$html .= '</div></td></tr>';
		}


		echo $html;
		exit;
	}

	function del() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I("get.id"));
		$detail = M('app_survey')->where("wid='".$wid."' and id='".$id."'")->find();

		$del = M('app_survey')->where("id='".$id."'")->delete();
		$del = M('app_survey_subject')->where("sid='".$id."'")->delete();
		//$del = M('app_survey_user')->where("sid='".$sid."'")->delete();
		//$del = M('app_survey_info')->where("sid='".$sid."'")->delete();
		header("location: ".U('index'));exit;
	}

	function delquestion() {
		$uid      = session("uid");
		$wid      = session("wid");
		$sid      = intval(I('get.sid'));
		$id       = intval(I('get.id'));

		$del = M('app_survey_subject')->where("sid='".$sid."' and tid='".$id."'")->delete();
		header("location: ".U('questionlist?id='.$sid));
		exit;
	}

	
}
?>