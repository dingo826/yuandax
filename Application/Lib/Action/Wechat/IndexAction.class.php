<?php
class IndexAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
	}

    public function index() {
		$site       = M("app_micro_site")->where('`wid`='.$this->wid)->find();
		$templateid = intval($site['home_id']);
		if($templateid<1) $templateid = 1;

		$this->display($templateid);
    }
}
