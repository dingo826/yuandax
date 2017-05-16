<?php
class ReplysubscribeAction extends WeixinAction 
{
	private $eventtype = array("article"=>"article_id", "link"=>"link", "tel"=>"tel", "map"=>"", "activity"=>"activity_type", "business"=>"business_type", "car"=>"car_type", "estate"=>"estate_type", "food"=>"food_type", "shop"=>"shop_type");
	private $_textResponse;		// 文本回复
	private $_infoResponse;		// 图文回复

	public function __construct() {
		parent::__construct();
		$this->_textResponse = D('TextResponse');
		$this->_infoResponse = D('Infomation');
	}

	function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$res = M("app_followreply")->where("wid='".$wid."'")->find();
		//print_r($res);exit;

		if($_POST) {
			$type        = intval(I("post.type"));
			$followrtype = intval(I("post.followrtype"));
			$content     = I("post.content");
			

			$udata["followrtype"] = $followrtype;
			if($followrtype==1) {
				if($type==2) {
					$udata["followrtype"] = 2;
				}
			}
			M("weixin")->where("id='".$wid."'")->save($udata);

			//$data["content"] = htmlspecialchars(strip_tags(htmlspecialchars_decode($content), '<a>'));
			//print_r($_POST);exit;
			$data["content"] = $content;
			$data["news_id"] = I("post.ids");
			$data["etime"]   = time();

			if($res) {
				M("app_followreply")->where("id = '".$res['id']."' and wid='".$wid."'")->save($data);
			}else {
				$data["wid"]   = $wid;
				M("app_followreply")->add($data);
			}
			header("Location: ".U('Replysubscribe/index'));exit;
		}

		// 获取关注时回复
		//$res = $this->_textResponse->findSpecifiedText($wid, 'subscribe');
		$newsid_arr = explode(',', $res['news_id']);
		$newsid_arr = array_fill_keys($newsid_arr, 1);

		// 获取资讯列表
		$info = $this->_infoResponse->getInfoAll($wid);
		foreach($info as $key => $val){
			//$info[$key]['content'] = htmlspecialchars_decode($val['content']);
			$info[$key]['content'] = $val['content'];
		}

		// 选定项置顶
		$info = $this->_infoResponse->selectedTop($info, $newsid_arr);

		$this->assign('newsid_arr', $newsid_arr);
		$this->assign('res', $res);
		$this->assign('newsInfo', $info);
		$this->assign('total', count($info));
		$this->display($this->Base_themplate);
	}
}
