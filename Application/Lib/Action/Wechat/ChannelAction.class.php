<?php
class ChannelAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$id = intval(I("get.id"));
		if($id<1) {
			header("location: ".U('index/index?wid='.$this->wid.'&token='.I("get.token").'&wxref=mp.weixin.qq.com'));
			exit;
		}
		$category   = M("app_category")->where("`wid`='".$this->wid."' and category_id='".$id."' and is_home=1")->order("sort desc")->select();
		if($category) $this->channeldo($category);
		else $this->listdo();
		exit;
    }

	function channeldo($clist) {
		$id     = intval(I("get.id"));
		$detail = M("app_category")->where("`wid`='".$this->wid."' and id='".$id."'")->find();

		$site       = M("app_micro_site")->where('`wid`='.$this->wid)->find();
		$templateid = intval($site['channel_id']);
		if($templateid<1) $templateid = 42;

		foreach($clist as $key => $val) {
			$clist[$key]['url'] = getBusurl($val);
			}

		$this->assign('detail', $detail);
		$this->assign('clist', $clist);
		//echo $templateid;exit;
		$this->display("Channel/".$templateid);
	}

	function listdo() {
		$id     = intval(I("get.id"));
		$detail = M("app_category")->where("`wid`='".$this->wid."' and id='".$id."'")->find();

		$pageSize = 10;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$limit    = ($currentPage-1)*$pageSize;

		$where    = "`wid`='".$this->wid."' and cid='".$id."'";

		$list   = M("app_article")->where($where)->order("id desc")->page($Page.','.$pageSize)->select();
		foreach($list as $key => $row) {
			if($row['picurl']=='/Public/images/default02/sqzx.jpg') {
				$list[$key]['picurl_s'] = $row['picurl'];
			}else {
				$list[$key]['picurl_s'] = preg_replace('/(\/data\/upload\/)([\d]+\/)([\d]+\/)([\d]+\/)([\w]+)(\.jpg|\.gif|\.png)/isU','$1$2$3$4$5_s$6', $row['picurl']);
				if(!$list[$key]['picurl_s']) $list[$key]['picurl_s'] = $row['picurl'];
			}
			$list[$key]['picurl'] = $list[$key]['picurl_s'];
			
		}

		import('ORG.Util.Pagewx');

		$count = M("app_article")->where($where)->count();
		$Page  = new Pagewx($count, $pageSize);
		$Page->setConfig("theme", '%upPage%<div class="item"><div class="pagenum">%nowPage%页/%totalPage%页</div></div> %downPage% %prePage%');
		$show  = $Page->show();

		$site       = M("app_micro_site")->where('`wid`='.$this->wid)->find();
		$templateid = intval($site['list_id']);
		if($templateid<1) $templateid = 27;

		$this->assign('detail', $detail);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display("List/".$templateid);
	}

	function ajax() {
		$id     = intval(I("get.id"));
		$pageSize = 15;
		$Page     = $_GET['p'] ? $_GET['p'] : 1;
		$page     = $page+1;
		$limit    = ($currentPage-1)*$pageSize;

		$where    = "`wid`='".$this->wid."' and cid='".$id."'";

		$list   = M("app_article")->where($where)->order("id desc")->page($Page.','.$pageSize)->select();
		foreach($list as $key => $row) {
			$list['$key']['cover'] = $row['picurl'];
			$list['$key']['url']   = U('article/index?wid='.$this->wid.'&token='.I('get.token').'&wxref=mp.weixin.qq.com');
		}
		echo json_encode($list, JSON_UNESCAPED_UNICODE);exit;
	}
}
