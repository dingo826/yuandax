<?php
/**
 * 微信圈
 * flli@yuandax.com
 * 2016/2/24
 */

class WxqunAction extends WeixinAction {

	private $member;

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 25;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$model = M('app_wxqun');

		$where = 'a.wid='.$wid." and a.status=2";

	    if(intval($_GET['isopen'])==1) $where .= " and a.isopen=1";
		if(intval($_GET['isopen'])==-1) $where .= " and a.isopen=0";

		if($_GET['keyword']) $where .= " and a.qunname like '%".I("get.keyword")."%'";

		$list = $model->table(array(
			     C('DB_PREFIX')."app_wxqun"   => "a",
			     C('DB_PREFIX')."app_wxqun_sysfenl" => "s",
		         ))->field("a.*, s.name as sname")->where($where." and a.qunfenl=s.id")->order("a.id desc")->select();
		foreach($list as $key => $val) {
			if(stripos('w'.$val['qrcode'], 'http://') != 1) {
				$list[$key]['thm_qrcode']  = preg_replace('/(\.)(\/data\/upload\/)([\d]+\/)([\d]+\/)([\w]+)(\.jpg|.gif|.png)/isU','$2$3$4s_$5$6',$val['qrcode']);
			}
		}

		$count = $model->table(array(C('DB_PREFIX')."app_wxqun"   => "a",))->where($where)->count();

		// 分页
		import('ORG.Util.Page');

		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();
		
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display($this->Base_themplate);
	}

	function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$model = M('app_wxqun');
		if($_POST) {
			$data['wid']       = $wid;
			$data['qunname']   = I("post.qunname");
			$data['qrcode']    = I("post.qrcode");
			$data['qunnotice'] = I("post.qunnotice");
			$data['qunfenl']   = I("post.qunfenl");
			$data['pnum']      = I("post.pnum");
			$data['isopen']    = I("post.isopen");
			$data['status']    = 2;
			$data['gly']       = I("post.gly");
			$data['qunzerwm']  = I("post.qunzerwm");
			$data['wechatid']  = I("post.wechatid");
			$data['mphone']    = I("post.mphone");
			$data['email']     = I("post.email");
			$data['ctime']     = $data['utime'] = time();

			$insertid = $model->add($data);
			header("location: ".U('index'));exit;
		}

		$fllist = M('app_wxqun_sysfenl')->select();
		$detail['public'] = 1;
		$this->assign('detail',$detail);
		$this->assign('fllist',$fllist);
		$this->display($this->Base_themplate);
	}

	function edit() {
		$id  = intval(I('get.id'));
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$model = M('app_wxqun');
		if($_POST) {
			$data['qunname']   = I("post.qunname");
			$data['qrcode']    = I("post.qrcode");
			$data['qunnotice'] = I("post.qunnotice");
			$data['qunfenl']   = I("post.qunfenl");
			$data['pnum']      = I("post.pnum");
			$data['isopen']    = I("post.isopen");
			$data['status']    = 2;
			$data['gly']       = I("post.gly");
			$data['qunzerwm']  = I("post.qunzerwm");
			$data['wechatid']  = I("post.wechatid");
			$data['mphone']    = I("post.mphone");
			$data['email']     = I("post.email");
			$data['utime']     = time();
			$upid = $model->where("id='".$id."'")->save($data);
			if($upid<1) {
				echo "<script>alert('信息填写有误，请核实');history.go(-1);</script>";exit;
			}
			header("location: ".U('index'));exit;
		}

		$detail = $model->find($id);
		$fllist = M('app_wxqun_sysfenl')->select();

		$this->assign('detail',$detail);
		$this->assign('fllist',$fllist);
		$this->display($this->Base_themplate);
	}

	function shenh() {
		$id = intval(I('get.id'));
		$aid   = session("aid");
		$model = M('app_wxqun');
		$data['status'] = 2;
		$upid = $model->where("id='".$id."'")->save($data);
		header("location:/weixin/Wxqun/index");exit;
	}

	function scltjl() {
		$id = intval(I('get.id'));
		$aid   = session("aid");
		$model = M('app_wxqun');
		if($_POST) {
			//print_r($_FILES);
			//$fileimage = $this->_upload();
			//print_r($fileimage);
			//$file = $fileimage[0]['savepath'].$fileimage[0]['savename'];echo "<br/>";echo "<br/>";
			$file = './data/upload/201603/03/56d7efba7e242.txt';
			$chatlist = M("app_wxqun_ltjl")->where("qid='".$id."'")->order("chattime desc")->limit(1)->select();
			$lastchat = $chatlist[0];
			$handle = fopen($file, "r");
			$i = 0;
			$data = '';
			if ($handle) {
				while (!feof($handle)) {
					$i++;
					$buffer = fgets($handle, 4096);
					preg_match('/[^－]*/ius', $buffer, $match);
					if($i>4 && $match[0]) {
						preg_match_all('/[^  ]*/ius', $buffer, $matche);
						$chattime = strtotime(preg_replace('/([^ ]* [^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)/isu','$1',$buffer));
						if($chattime>$lastchat['chattime']) {
							$val['chattime'] = $chattime;
							$val['sender'] = preg_replace('/([^ ]* [^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)/isu','$2',$buffer);
							$jfstatus = preg_replace('/([^ ]* [^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)/isu','$3',$buffer);
							$type = preg_replace('/([^ ]* [^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)/isu','$4',$buffer);
							$val['content'] = preg_replace('/([^ ]* [^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)\n/isu','$5',$buffer);

							$val['jfstatus'] = -1;
							if($jfstatus=='接收') {
								$val['jfstatus'] = 1;
							}elseif($jfstatus=='发送') {
								$val['jfstatus'] = 2;
							}

							$val['type'] = -1;
							if($type=='文本') {
								$val['type'] = 1;
							}
							$val['ctime'] = time();

							$data[] = $val;
						}
					}
				}
				fclose($handle);
			}
			if(empty($data)) {
				echo "<script>alert('暂无需要更新');location.href='/weixin/Wxqun/index';</script>";
				exit;
			}
			$pdata['qid'] = $id;
			$pdata['num'] = count($data);
			$pdata['ctime'] = time();
			$insertid = M("app_wxqun_ltpackge")->add($pdata);

			foreach($data as $key => $val) {
				$data[$key]['qid'] = $id;
				$data[$key]['pid'] = $insertid;
			}
			
			$insertid = M("app_wxqun_ltjl")->addAll($data);
			header("location:/weixin/Wxqun/index");exit;
		}
		$this->display($this->Base_themplate);
	}

	function ltjl() {
		$id = intval(I('get.id'));
		$aid   = session("aid");
		$model = M('app_wxqun_ltjl');

		$detail = M('app_wxqun')->where("id='".$id."'")->find();


		$where = "qid='".$id."'";

		$_GET['name'] && $where .= " and content like ('%".I('get.name')."%')" ;

		
		$count = $model->where($where)->count();

		// 分页
		import('ORG.Util.Page');
		$pev = 20;	// 每页显示记录数
		$page     = new Page($count, $pev, '', $this->_currentURL);
		

		$list = $model->where($where)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();

		$page = $page->show();

		
		
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('detail',$detail);

		$this->display($this->Base_themplate);
	}
}
