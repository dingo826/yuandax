<?php
class ArticleAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		//echo 1;exit;
		$this->assign('tget', I("get."));
	}

    public function index() {
		$this->getjssdktoken();
		$id = intval(I("get.id"));
		if($id<1) {
			header("location: ".U('index/index?wid='.$this->wid.'&token='.I("get.token").'&wxref=mp.weixin.qq.com'));
			exit;
		}
		$detail            = M("app_article")->where("id='".$id."'")->find();
		$detail['content'] = htmlspecialchars_decode($detail['content']);

		$site       = M("app_micro_site")->where('`wid`='.$this->wid)->find();
		$templateid = intval($site['detailed_id']);
		if($templateid<1) $templateid = 49;

		$sourceurl = '';
		if($detail['type']) $sourceurl = getBusurl($detail);

		$this->assign('detail', $detail);
		$this->assign('sourceurl', $sourceurl);
		$this->display("Article/".$templateid);
    }

	function previewnewspaper() {
		$id = intval(I("get.id"));

		$detail            = M("newspapers_lists")->where("id='".$id."'")->find();
		$cdetail           = M("newspapers_contents")->where("listid='".$id."'")->find();

		$newspapers_category_set = M("jd_newspapers_category_set")->where("id='".$detail['setid']."'")->find();
		$statement = "本文转载自".$newspapers_category_set['newspapername'];

		$detail['show_cover_pic']= 1;

		/*$desc = htmlspecialchars_decode($cdetail["content"]);
		$desc = preg_replace("/<style>.+<\/style>/is", "", $desc);
		$desc = preg_replace("/<script>.+<\/script>/is", "", $desc);
		$desc = mb_substr(trimall(strip_tags($desc)), 0, 80);

		preg_match('/[^，|,|。|\.|\?|：|(|（]+/u', $desc, $matches);

		$insertflag = $matches[0];*/

		//$clcontent = htmlspecialchars($cdetail['content']);
		//$clcontent = $cdetail['content'];

		//echo htmlspecialchars_decode($clcontent)."<br/><br/><br/><br/><br/><br/>";

		//$quhtmlcontent = htmlspecialchars_decode($clcontent);
	    /*$quhtmlcontent = preg_replace("/<style>.+<\/style>/is", "", $quhtmlcontent);
		$quhtmlcontent = preg_replace("/<script>.+<\/script>/is", "", $quhtmlcontent);

		$quhtmlcontent = str_replace(';nbsp;','',$quhtmlcontent);
		$quhtmlcontent = str_replace('&amp','',$quhtmlcontent);
		$quhtmlcontent = str_replace('&lt;br&gt;','',$quhtmlcontent);
		$quhtmlcontent = strip_tags($quhtmlcontent);
		$quhtmlcontent = trimall(preg_replace('/[\r|\n]/','',$quhtmlcontent));

		$lencd = mb_strlen($quhtmlcontent);*/

		$cdetail['content'] = "<div style='color: red;'>（".$statement."）</div><br/>".$cdetail['content'];

		//$cdetail['content'] = str_replace($insertflag, "（".$statement."）".$insertflag, $cdetail['content']);

		$detail['content'] = $cdetail['opicdata'].htmlspecialchars_decode($cdetail['content']);
		$detail['picurl']  = $detail['thumb'];

		$site       = M("app_micro_site")->where('`wid`='.$this->wid)->find();
		$templateid = intval($site['detailed_id']);
		if($templateid<1) $templateid = 49;

		$sourceurl = '';
		if($detail['type']) $sourceurl = getBusurl($detail);

		$this->assign('detail', $detail);
		$this->assign('sourceurl', $sourceurl);

		//echo $quhtmlcontent."<br/>".$lencd."<br/><br/><br/><br/><br/>";
		$this->display("Article/".$templateid);
	}
}
