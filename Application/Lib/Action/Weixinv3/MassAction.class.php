<?php
class MassAction extends Weixinv3Action {
	protected $_massManage;		// 群发管理模型

	public function __construct() {
		parent::__construct();
		$this->_massManage = D('MassManage');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 50;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = "wid='".$wid."'";

		$list = M("app_article")->where($where)->field("id, uid, wid, title, sortid, picurl, desc, send_count")->order("id desc")->page($Page.','.$pageSize)->select();

		// 当前图文
		$id = intval(I('get.id'));
		$detail = $this->_massManage->getDetail($id);
		if($detail['material_ids']) $material_ids = $detail['material_ids'].',';

		// 选定项置顶
		$list = D('Infomation')->selectedTop($list, $newsList);

		/************/
		$jd = M("relat_jd_sq")->where("sqid='".$wid."'")->find();
		$newspapers_category = M("jd_newspapers_category")->where("jdid='".$jd['jdid']."' and isenable=1")->order("orderby desc")->select();
		//print_r($newspapers_category);exit;
		//$artlist = M("")

		$this->assign('newspapers_category', $newspapers_category);
		/************/

		$this->assign('list',     $list);
		$this->assign('detail',   $detail);
		$this->assign('material_ids', $material_ids);
		$this->display($this->Base_themplate);
    }

	public function index2(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 50;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = "wid='".$wid."'";

		$list = M("app_article")->where($where)->field("id, uid, wid, title, sortid, picurl, desc, send_count")->order("id desc")->page($Page.','.$pageSize)->select();

		// 当前图文
		$id = intval(I('get.id'));
		$detail = $this->_massManage->getDetail($id);
		if($detail['material_ids']) $material_ids = $detail['material_ids'].',';

		// 选定项置顶
		$list = D('Infomation')->selectedTop($list, $newsList);

		/************/
		$jd = M("relat_jd_sq")->where("sqid='".$wid."'")->find();
		$newspapers_category = M("jd_newspapers_category")->where("jdid='".$jd['jdid']."' and isenable=1")->order("orderby desc")->select();
		//print_r($newspapers_category);exit;
		//$artlist = M("")

		$this->assign('newspapers_category', $newspapers_category);
		/************/

		$this->assign('list',     $list);
		$this->assign('detail',   $detail);
		$this->assign('material_ids', $material_ids);
		$this->display($this->Base_themplate);
    }

	public function text() {
		$this->display($this->Base_themplate);
	}

	public function record() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$page = I('get.p') ? I('get.p') : 1;	// 页码
		$status = I('get.status') ? I('get.status') : null;	// 发送状态
		$list = $this->_massManage->getRecordList($wid, $page, $status);

		// 获取分页信息
		$pages = $this->_massManage->getPageInfo($wid, $status);
		//print_r($list);exit;
		$this->assign('list', $list);
		$this->assign('pages', $pages);
		$this->assign('status', $status);
		$this->display($this->Base_themplate);
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));
		$status = $this->_massManage->delMassRecord($id);
		header("location: ".U('record'));
	}

	/**
	 * 重新群发
	 */
	public function reSend()
	{
		$id = I('get.id');	// 记录id
		$detail = $this->_massManage->getDetail($id);
		$_POST = array(
			'id'	=> $detail['id'],
			'type'	=> $detail['type'],
			'content'	=> $detail['content'],
			'immediately'	=> 1,
			'material_ids'	=> explode(',', $detail['material_ids']),
			'ids'	=> $detail['material_ids'],
			'cover_id'	=> $detail['cover_id'],
		);
		$this->send();
	}

	public function send() {
		//print_r($_POST);//exit;
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$isdebug              = intval($_POST['ispreview']);
			$debuser              = $_POST['previewwechatid'];
			$post['isdebug']      = intval($_POST['ispreview']);
			$post['debuser']      = $_POST['previewwechatid'];
			$post['cover_id']     = intval($_POST['cover_id']);
			$post['immediately']  = intval($_POST['immediately']);
			$post['content']      = $_POST['content'];
			$post['type']         = $_POST['type'];
			$post['material_ids'] = $_POST['ids'];
			$post['sendtime']     = $_POST['sendtime'];
			if($post['type']=='mpnews' && !$_POST['cover_id']) {
				echo "没有选择封面";exit;
			}

			if($post['immediately'] == 1) {
				$status = $this->_massManage->immediatelySend($post, $wid, $isdebug, $debuser);
				$post['sendtime'] = time();
				if($isdebug) {
					$data = array("openid"=>$debuser, "wid"=>$wid, "lasttime"=>$post['sendtime']);
					$uplist = M("upreview")->where(array("wid"=>$wid, "openid"=>$debuser))->find();
					if(empty($uplist)) 
						M("upreview")->add($data);
					else 
						M("upreview")->where("id='".$uplist['id']."'")->save($data);
				}
			}else {
				$status = 'w';
			    $post['sendtime'] = strtotime($post['sendtime']);
			}

			// 记录历史，预览不记录
			if(!$isdebug){
				$this->_massManage->addMassRecord($post, $wid, $status);
				// 发送累计
				D('Infomation')->sendCumulative($post['material_ids'], $wid);
			}
			header("location: ".U('mass/record'));
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

		/*foreach($list as $key => $val) {
			echo $val["id"]."-====".D("Newspaper")->picScore($val["picnums"], $val["isusedefaultpic"])."<br/>";
		}*/
		

		if(count($list)<30) {
			$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, a.cid as cid, a.title as title, a.thumb as thumb, a.titlelen  as titlelen, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.contentlen as contentlen, b.picnums as picnums, c.newspapername as origin")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->where("a.cstatus=1 and a.cid='".$id."'")->order("a.id desc")->page('1, 60')->select();
		}

		if($list) {
			$list = D("Newspaper")->updatesort($list);

			/*foreach($list as $key => $val) {
				//$list[$key]["content"] = mb_substr(trimall(strip_tags($list[$key]["content"])), 0, 80);
				$list[$key]["title"]   .= "&nbsp;&nbsp;(标题得分：".$list[$key]["titlescore"]."(取".$list[$key]["titlerulesper"].")；内容得分：".$list[$key]["contentscore"]."(取".$list[$key]["contentrulesper"].")；图片得分：".$list[$key]["picscore"]."(取".$list[$key]["picrulesper"].")；关键词得分：".$list[$key]["keywordScore"]."(取".$list[$key]["keywordrulesper"].")；总得分：".$list[$key]["sortsocre"]."；)。内容长度：".$list[$key]["contentlen"]."字。";
			}*/
		}
		//print_r($list);exit;
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

		/*foreach($list as $key => $val) {
			echo $val["id"]."-====".D("Newspaper")->picScore($val["picnums"], $val["isusedefaultpic"])."<br/>";
		}*/
		

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
				//$list[$key]["content"] = mb_substr(trimall(strip_tags($list[$key]["content"])), 0, 80);
				$list[$key]["title"]   .= "&nbsp;&nbsp;(标题得分：".$list[$key]["titlescore"]."(取".$list[$key]["titlerulesper"].")；内容得分：".$list[$key]["contentscore"]."(取".$list[$key]["contentrulesper"].")；图片得分：".$list[$key]["picscore"]."(取".$list[$key]["picrulesper"].")；关键词得分：".$list[$key]["keywordScore"]."(取".$list[$key]["keywordrulesper"].")；总得分：".$list[$key]["sortsocre"]."；)。内容长度：".$list[$key]["contentlen"]."字。";
			}
		}
		//print_r($list);exit;
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
		//print_r($list);exit;

		if($list) {
			$list = D("Newspaper")->updatesort($list);
		}else {
			echo -1;exit;
		}

		echo json_encode($list, JSON_UNESCAPED_UNICODE);exit;
		exit;
	}

	function daochu() {
		$list = M("newspapers_lists")->table(C('DB_PREFIX')."newspapers_lists as a")
			  ->field("a.id as id, a.jdid as jdid, d.name as cname, a.title as title, a.thumb as thumb, a.acqdate as acqdate, a.isusedefaultpic as isusedefaultpic, a.ctime as ctime, b.content as content, b.`desc` as 'desc', b.picnums as picnums, c.newspapername as origin, a.intime as intime")
			  ->join("left join ".C("DB_PREFIX")."newspapers_contents as b on (a.id=b.listid)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category_set as c on (a.setid=c.id)")
			  ->join("left join ".C("DB_PREFIX")."jd_newspapers_category as d on (a.cid=d.id)")
			  ->where("1=1")->order("a.id desc")->select();

		//print_r($list);exit;

		Vendor("PHPExcel");
		Vendor("PHPExcel.Writer.Excel5");
		Vendor("PHPExcel.IOFactory.php");
		$objPHPExcel = new PHPExcel();
		//print_r($objPHPExcel);exit;

		$objPHPExcel->getProperties()->setCreator("挖米 wami.cc")
									 ->setLastModifiedBy("挖米 wami.cc")
									 ->setTitle("Office 2003 XLS Test Document")
									 ->setSubject("Office 2003 XLS Test Document")
									 ->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
									 ->setKeywords("office 2003 openxml php")
									 ->setCategory("Test result file");
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'ID')
		            ->setCellValue('B1', '分类')
		            ->setCellValue('C1', '报刊')
		            ->setCellValue('D1', '标题')
		            ->setCellValue('E1', '标题长度')
			        ->setCellValue('F1', '内容长度')
			        ->setCellValue('G1', '文章日期')
			        ->setCellValue('H1', '入库时间');
		$i=1;
		foreach($list as $key => $row) {
			$i++;
			$titlelen = mb_strlen(trimall($row["title"]));

			$quhtmlcontent = htmlspecialchars_decode($row["content"]);
			$quhtmlcontent = preg_replace("/<style>.+<\/style>/is", "", $quhtmlcontent);
			$quhtmlcontent = preg_replace("/<script>.+<\/script>/is", "", $quhtmlcontent);
			$quhtmlcontent = str_replace(';nbsp;','',$quhtmlcontent);
			$quhtmlcontent = str_replace('&amp','',$quhtmlcontent);
			$quhtmlcontent = str_replace('&lt;br&gt;','',$quhtmlcontent);
			$quhtmlcontent = strip_tags($quhtmlcontent);
			$quhtmlcontent = trimall(preg_replace('/[\r|\n]/','',$quhtmlcontent));
			$contentlen    = mb_strlen($quhtmlcontent);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$i, $row['id'])
						->setCellValue('B'.$i, $row['cname'])
						->setCellValue('C'.$i, $row['origin'])
						->setCellValue('D'.$i, $row['title'])
						->setCellValue('E'.$i, $titlelen)
			            ->setCellValue('F'.$i, $contentlen)
						->setCellValue('G'.$i, $row['acqdate'])
						->setCellValue('H'.$i, date("Y-m-d H:i:s", $row['intime']));
		}

		$objPHPExcel->getActiveSheet()->setTitle('文章列表');
		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$time      = time();
		$filename  = date("YmdHis", $time).'.xls'; 
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;		
	}
}