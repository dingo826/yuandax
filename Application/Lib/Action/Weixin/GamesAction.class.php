<?php
class GamesAction extends WeixinAction {

	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$this->display($this->Base_themplate);
    }
}