<?php
class HudongAction extends Weixinv3Action {

	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$this->display($this->Base_themplate);
    }
}