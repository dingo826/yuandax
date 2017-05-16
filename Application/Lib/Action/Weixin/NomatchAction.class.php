<?php
class NomatchAction extends WeixinAction 
{
	private $_infoResponse;		// 图文回复

	public function __construct() {
		parent::__construct();
		$this->_infoResponse = D('Infomation');
	}

	function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$res = M("app_nomatch")->where("wid='".$wid."'")->find();

		if($_POST) {
			$type        = intval(I("post.type"));
			$followrtype = intval(I("post.followrtype"));
			$content     = I("post.content");

			$udata["nomatchrtype"] = $followrtype;
			if($followrtype==1) {
				if($type==2) {
					$udata["nomatchrtype"] = 2;
				}
			}
			M("weixin")->where("id='".$wid."'")->save($udata);

			$data["content"] = htmlspecialchars(strip_tags(htmlspecialchars_decode($content), '<a>'));
			$data["news_id"] = I("post.ids");
			$data["etime"]   = time();

			if($res) {
				M("app_nomatch")->where("id = '".$res['id']."' and wid='".$wid."'")->save($data);
			}else {
				$data["wid"]   = $wid;
				M("app_nomatch")->add($data);
			}
			header("Location: ".U('nomatch/index'));exit;
		}

		// 获取关注时回复
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
		$this->display($this->Base_module."@Replysubscribe/index");
	}
}
