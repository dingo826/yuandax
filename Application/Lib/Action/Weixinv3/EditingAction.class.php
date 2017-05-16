<?php
class EditingAction extends Weixinv3Action {
	protected $_massManage;		// 群发管理模型
	private $_microCategory;

	public function __construct() {
		parent::__construct();
		$this->_massManage = D('MassManage');
		$this->_microCategory = D('MicroCategory');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$catelist = $this->_microCategory->getCategoryList($wid, "article", 0);
		
		/************/
		$jd = M("relat_jd_sq")->where("sqid='".$wid."'")->find();
		$newspapers_category = M("jd_newspapers_category")->where("jdid='".$jd['jdid']."' and isenable=1")->order("orderby desc")->select();

		$this->assign('newspapers_category', $newspapers_category);
		/************/

		//$this->assign('list',         $list);
		$this->assign('catelist',     $catelist);
		$this->assign('detail',       $detail);
		$this->assign('material_ids', $material_ids);
		$this->display($this->Base_themplate);
    }

	public function send() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			if(!$_POST['ids']) {
				echo "<script>alert('请勾选所需要发送的文章');location='".U('index')."'</script>";
				exit;
			}
			$ptype              = intval($_POST['ispreview']);

			$idsarr = explode(",", $_POST['ids']);
			$param['cover_id']     = $idsarr[0];
			$param['type']         = "mpnews";
			$param['material_ids'] = $_POST['ids'];			

			$workermodel = M("npv3_worker");
			$worker = $workermodel->where("wid='".$wid."' and typeid='".$ptype."'")->find();
			if(!$worker) {
				echo "<script>alert('请先设置预览/审核人员');location='".U('index')."'</script>";
				exit;
			}
			$member = D("Member")->getMemberinfo($worker["mid"]);
			$status = $this->_massManage->immediatelySend($param, $wid, 1, $member["wechatid"]);

			if($status!='t') {
				echo "<script>alert('发送失败');location='".U('index')."'</script>";
				exit;
			}
			echo "<script>alert('发送成功');location='".U('index')."'</script>";
			exit;
		}
		echo "<script>alert('非法提交');location='".U('index')."'</script>";
		exit;
	}

	function tosendqueue() {
		$uid   = intval(session("uid"));
		$wid   = intval(session('wid'));
		$today = strtotime(date("Y-m-d"));

		$settedlist = M("npv3_sendqueues")->where("wid='".$wid."' and senddate>".($today-1))->order("senddate asc")->limit("0, 5")->select();

		for($i=0; $i<5; $i++) {
			$flagdate = $today+86400*$i;
			if($settedlist[$i]["senddate"]!=$flagdate) {
					$setdate = $flagdate;
					break;
			}
		}

		$data["wid"]      = $wid;
		$data["senddate"] = $setdate;
		$data["etime"]    = time();
		$data["ctime"]    = time();
		
		$insertid = M("npv3_sendqueues")->add($data);

		$idsarr = explode(",", $_POST['ids']);
		foreach($idsarr as $key => $row) {
			if((string)intval($row)==(string)$row) {
				$newsid["article"][] = $row;
			}else {
				$newsid["paper"][]   = $row;
			}
		}

		$_infomation = D('Infomation');
		$_newspaper  = D('Newspaper');

		// 获取图文详情
		if($newsid["article"]) {
			$newsList = $_infomation->getDetailedList(implode(",", $newsid["article"]));
		}

		if($newsid["paper"]) {
			foreach($newsid["paper"] as $key => $row) {
				$temp2_arr  = explode("_", $row);
				$tempdetail = $_newspaper->getInfoDetailed($temp2_arr[1]);
				$tempdetail["npid"] = $row;
				$newsList[] = $tempdetail;
			}
		}

		foreach($newsList as $key => $row) {
			if($row['npid']) $temp[$row['npid']] = $row;
			else $temp[$row['id']] = $row;
		}
		$newsList = $temp;

		$temp = '';
		$addlist = '';
		foreach($idsarr as $key => $row) {
			$sourceurl = '';
			if($newsList[$row]['type']) {
				if($newsList[$row]['type']=='business' && $newsList[$row]['business_type']=='huodonglist') $sourceurl = D("Busitype")->getoneBusi($row);
			    else $sourceurl = getBusurl($row);
			}

			$temp["sourcetype"]         = 1;
			if($newsList[$row]["npid"]) {
				$temp["sourcetype"]         = 2;
				$temp["sourceid"]           = $wid;
			}else {
				$temp["sourceid"]           = $newsList[$row]["id"];
			}
			$temp["wid"]                = $wid;
			$temp["qid"]                = $insertid;
			$temp["coverimg"]           = $newsList[$row]["picurl"];
			$temp["title"]              = $newsList[$row]["title"];
			$temp["content_source_url"] = $sourceurl;
			$temp["content"]            = htmlspecialchars_decode($newsList[$row]["content"]);
			$temp["digest"]             = $newsList[$row]["desc"];
			$temp["show_cover_pic"]     = $newsList[$row]["show_cover_pic"];
			$temp["etime"]              = time();
			$temp["ctime"]              = time();
			$addlist[] = $temp;
		}
		M("npv3_sendqueues_contents")->addAll($addlist);
		header("location: ".U("sendqueue/index"));
		exit;
	}

	function articleajax() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$sid = intval($_POST['sid']);

		if($_POST){
			$pageSize = 50;
			$Page = $_GET['p'] ? $_GET['p'] : 1;
			$limit = ($currentPage-1)*$pageSize;

			$listid = M("app_category")->where("category_id='".$sid."'")->getField("id");
			if(is_array($listid)) {
				$listid[] = $sid;
				$cid = implode($listid);
			}else {
				if($listid) $cid = $sid.",".$listid;
				else $cid = $sid;
			}
			//$ddd = implode(",", $listid);
			//echo $ddd;


			$where = "wid='".$wid."' and cid IN (".$cid.")";
			$list = M("app_article")->where($where)->field("id, uid, wid, title, sortid, picurl, desc, send_count")->order("id desc")->page($Page.','.$pageSize)->select();
			if(!$list) $list = array();
			$this->ajaxReturn($list);
			exit;
		}
	}

	function newspapers() {
		$today = (int)strtotime(date("Y-m-d"));
		$yesterdaylastone = $today-1-24*60*60;
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id = intval(I("post.id"));

		if($id<1) {
			echo -1;
			exit;
		}

		$where = "a.cstatus=1 and a.intime>'".$yesterdaylastone."'";
		if($id>0) $where .= " and a.cid='".$id."'";

		$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.cid as cid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where($where)->order("a.id desc")->select();		

		if(count($list)<30) {
			$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.cid as cid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where("a.cstatus=1 and a.cid='".$id."'")->order("a.id desc")->page('1, 60')->select();
		}

		if($list) {
			$list = D("Newspaper")->updatesort($list);
		}
		echo json_encode($list, JSON_UNESCAPED_UNICODE);exit;
		exit;
	}

	function newspapers2() {
		$today = (int)strtotime(date("Y-m-d"));
		$yesterdaylastone = $today-1-24*60*60;
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id = intval(I("post.id"));

		if($id<1) {
			echo -1;
			exit;
		}

		$where = "a.cstatus=1 and a.intime>'".$yesterdaylastone."'";
		if($id>0) $where .= " and a.cid='".$id."'";

		$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where($where)->order("a.id desc")->select();		

		if(count($list)<30) {
			$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where("a.cstatus=1 and a.cid='".$id."'")->order("a.id desc")->page('1, 60')->select();
		}

		if($list) {
			$list = D("Newspaper")->updatesort($list);

			foreach($list as $key => $val) {
				$list[$key]["title"]   .= "&nbsp;&nbsp;(标题得分：".$list[$key]["titlescore"]."(取".$list[$key]["titlerulesper"].")；内容得分：".$list[$key]["contentscore"]."(取".$list[$key]["contentrulesper"].")；图片得分：".$list[$key]["picscore"]."(取".$list[$key]["picrulesper"].")；关键词得分：".$list[$key]["keywordScore"]."(取".$list[$key]["keywordrulesper"].")；总得分：".$list[$key]["sortsocre"]."；)。内容长度：".$list[$key]["contentlen"]."字。";
			}
		}
		echo json_encode($list, JSON_UNESCAPED_UNICODE);exit;
		exit;
	}

	function searchart() {
		$today = (int)strtotime(date("Y-m-d"));
		$yesterdaylastone = $today-1-24*60*60*15;
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$relat = M("relat_jd_sq")->where("sqid='".$wid."'")->find();
		if($relat['jdid']<1) {
			echo -1;exit;
		}

		$category = M("jd_newspapers_category")->field("id")->where("jdid='".$relat['jdid']."'")->select();

		$keyword = trim(I("post.keyword"));
		if(!$keyword) {
			echo -1;exit;
		}

		$where = "a.cstatus=1 and a.intime>'".$yesterdaylastone."' and a.title like '%".$keyword."%'";
		if($category) {
			$ctemp = '';
			foreach($category as $val) {
				$ctemp[] = $val['id'];
			}
			if($ctemp) $where .= " and a.cid in ('".implode("', '", $ctemp)."')";
		}

		$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.cid as cid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where($where)->order("a.id desc")->page('1, 30')->select();

		if($list) {
			$list = D("Newspaper")->updatesort($list);
		}else {
			echo -1;exit;
		}

		echo json_encode($list, JSON_UNESCAPED_UNICODE);exit;
		exit;
	}
}