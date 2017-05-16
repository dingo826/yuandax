<?php
class HuodongAction extends WeixinAction{

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

		$list     = M('app_huodong')->where($where)->order('id desc')->page($Page.','.$pageSize)->select();

		import('ORG.Util.Page');
		$count = M('app_huodong')->where($where)->count();
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

			
			$data['wid']      = $wid;
			$data['title']    = $post['title'];
			$data['picurl']   = $post['show_bg_img'];

			$data['cost']     = intval($post['cost']);
			$data['baoxian']  = intval($post['baoxian']);
			$data['minnum']   = intval($post['minnum']);
			$data['maxnum']   = intval($post['maxnum']);

			$data['desc']     = $post['desc'];
			$data['content']  = htmlspecialchars($post['content']);

			$data['bbtime']   = strtotime($post['bbtime']);
			$data['betime']   = strtotime($post['betime']);
			$data['hdbtime']  = strtotime($post['hdbtime']);
			$data['hdetime']  = strtotime($post['hdetime']);
			
			$data['etime']    = time();
			$data['ctime']    = time();
			$insertid         = M('app_huodong')->add($data);

			if($_POST['diy_name']) {
				foreach($_POST['diy_name'] as $key => $val) {
					$temp['wid']       = $wid;
					$temp['huodongid'] = $insertid;
					$temp['keyname']   = $val;
					$temp['prompt']    = $_POST['diy_msg'][$key];
					$temp['inputtype'] = $_POST['diy_type'][$key];
					$temp['required']  = $_POST['diy_check'][$key];
					$temp['etime']     = time();
					$temp['ctime']     = time();
					$subinfoarr[]      = $temp;
				}
				if(!empty($subinfoarr)) M("app_huodong_subinfo")->addAll($subinfoarr);
			}			

			header("location: ".U('index'));exit;
		}

		$detail['bbtime']  = time();
		$detail['betime']  = $detail['bbtime']+(3*24*60*60);
		$detail['hdbtime'] = time();
		$detail['hdetime'] = $detail['hdbtime']+(3*24*60*60);
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

			$data['cost']     = intval($post['cost']);
			$data['baoxian']  = intval($post['baoxian']);
			$data['minnum']   = intval($post['minnum']);
			$data['maxnum']   = intval($post['maxnum']);

			$data['desc']     = $post['desc'];
			$data['content']  = htmlspecialchars($post['content']);

			$data['bbtime']   = strtotime($post['bbtime']);
			$data['betime']   = strtotime($post['betime']);
			$data['hdbtime']  = strtotime($post['hdbtime']);
			$data['hdetime']  = strtotime($post['hdetime']);
			
			$data['etime']    = time();

			$save             = M('app_huodong')->where("id='".$id."' and wid='".$wid."'")->save($data);

			if($_POST['diy_name']) {
				foreach($_POST['diy_name'] as $key => $val) {
					$temp = '';
					if($val) {
						if(intval($_POST['diy_insertid'][$key])>0) {
							$nodelarr[]        = $_POST['diy_insertid'][$key];
							$temp['keyname']   = $val;
							$temp['prompt']    = $_POST['diy_msg'][$key];
							$temp['inputtype'] = $_POST['diy_type'][$key];
							$temp['required']  = $_POST['diy_check'][$key];
							$temp['etime']     = time();
							M("app_huodong_subinfo")->where("id='".$_POST['diy_insertid'][$key]."' and wid='".$wid."' and huodongid='".$id."'")->save($temp);
						}else {
							$temp['wid']       = $wid;
							$temp['huodongid'] = $id;
							$temp['keyname']   = $val;
							$temp['prompt']    = $_POST['diy_msg'][$key];
							$temp['inputtype'] = $_POST['diy_type'][$key];
							$temp['required']  = $_POST['diy_check'][$key];
							$temp['etime']     = time();
							$temp['ctime']     = time();
							$subinfonew[]      = $temp;
						}
					}
				}
				M("app_huodong_subinfo")->where("wid='".$wid."' and huodongid='".$id."' and id NOT IN ('".implode("', '", $nodelarr)."')")->delete();
				if(!empty($subinfonew)) M("app_huodong_subinfo")->addAll($subinfonew);
			}

			header("location: ".U('index'));exit;
		}

		$detail   = M('app_huodong')->where("id='".$id."' and wid='".$wid."'")->find();
		$detail['content'] = htmlspecialchars_decode($detail['content']);

		$sublist  = M('app_huodong_subinfo')->field("id as diy_id, keyname as diy_name, prompt as diy_msg, inputtype as diy_type, required as diy_check")->where("huodongid='".$id."' and wid='".$wid."'")->order("id asc")->select();

		$this->assign("detail",  $detail);
		$this->assign("sublist", $sublist);
		$this->display($this->Base_theme."/"."add");
	}

	function record() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));
		$isothercustom = 1;
		$detail   = M('app_huodong')->where("id='".$id."'")->find();

		$subinfo  = M('app_huodong_subinfo')->where("huodongid='".$id."'")->order("id asc")->select();

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;
		$where    = "huodongid='".$id."' and wid='".$wid."'";
		$list     = M('app_huodong_records')->where($where)->page($Page.','.$pageSize)->select();
		foreach($list as $key => $row) {
			if($detail['baoxian']==1) {
				$baoxian = M("app_huodong_baoxian")->where("recordid='".$row['id']."'")->select();
				if($baoxian) $list[$key]['baoxian'] = $baoxian;
			}
			if($row['uid']>0) {
				$member = M("app_mcard_member")->where("wid='".$wid."' and id='".$row['uid']."'")->find();
			}
			$list[$key]['member'] = $member;

			if($subinfo) {
				$isothercustom = 2;
				$subkey = 0;
				foreach($subinfo as $key2 => $val2) {
					$recordsubinfo = M("app_huodong_records_subinfo")->where("huodongid='".$id."' and recordid='".$row['id']."' and subid='".$val2['id']."'")->find();
					$list[$key]["subinfo"][$subkey]["key"]   = $val2['keyname'];
					$list[$key]["subinfo"][$subkey]["value"] = $recordsubinfo['value'];
					$subkey++;
				}
			}
		}

		import('ORG.Util.Page');
		$count = M('app_huodong_records')->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign("detail",  $detail);
		$this->assign("list",  $list);
		$this->assign("isothercustom",  $isothercustom);
		$this->assign('pages', $show);
		$this->display($this->Base_themplate);
	}

	function del() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));
		$detail   = M('app_huodong')->where("id='".$id."'")->find();
		$where    = "huodongid='".$id."' and wid='".$wid."'";
		$list     = M('app_huodong_records')->where($where)->find();
		if($detail || $list) {
			echo "<script>alert('活动已开始无法删除!');history.go(-1);</script>";exit;
		}

		M("app_huodong")->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U("index"));exit;
	}

	function export() {
		$uid      = session("uid");
		$wid      = session("wid");
		$id       = intval(I('get.id'));
		$detail   = M('app_huodong')->where("id='".$id."'")->find();
		$where    = "huodongid='".$id."' and wid='".$wid."'";
		$list     = M('app_huodong_records')->where($where)->select();

		Vendor("PHPExcel");
		Vendor("PHPExcel.Writer.Excel5");
		Vendor("PHPExcel.IOFactory.php");
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("源达 yuandax.com")
									 ->setLastModifiedBy("源达 yuandax.com")
									 ->setTitle("Office 2003 XLS Test Document")
									 ->setSubject("Office 2003 XLS Test Document")
									 ->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
									 ->setKeywords("office 2003 openxml php")
									 ->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', $detail['title']."(".$detail['nums'].")");
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		if($detail['baoxian']==1) {
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A2', 'ID')
						->setCellValue('B2', '联系人')
						->setCellValue('C2', '手机')
						->setCellValue('D2', '保险')
						->setCellValue('E2', '报名人数(人)')
						->setCellValue('F2', '报名时间');
		}else {
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A2', 'ID')
						->setCellValue('B2', '联系人')
						->setCellValue('C2', '手机')
						->setCellValue('D2', '报名人数(人)')
						->setCellValue('E2', '报名时间');
		}

		$i=2;
		foreach($list as $key => $row) {
			$i++;
			$addtime  = date("Y-m-d H:i:s", $row['ctime']);

			if($detail['baoxian']==1) {
				$bxarr = M("app_huodong_baoxian")->where("recordid='".$row['id']."'")->select();
				foreach($bxarr as $bxkey => $bxrow) {
					$bxstr .= $bxrow['name'].':'.$bxrow['idcard'].';';
				}

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$i, $row['id'])
							->setCellValue('B'.$i, $row['contact'])
							->setCellValue('C'.$i, "'".$row['mphone'])
							->setCellValue('D'.$i, $bxstr)
							->setCellValue('E'.$i, $row['nums'])
							->setCellValue('F'.$i, $addtime);
			}else {
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$i, $row['id'])
						->setCellValue('B'.$i, $row['contact'])
						->setCellValue('C'.$i, "'".$row['mphone'])
						->setCellValue('D'.$i, $row['nums'])
						->setCellValue('E'.$i, $addtime);
			}
		}

		$objPHPExcel->getActiveSheet()->setTitle($detail['title'].'活动报名列表');
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
?>