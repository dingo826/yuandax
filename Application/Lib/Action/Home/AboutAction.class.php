<?php
class AboutAction extends Action {
	public function __construct() {
		parent::__construct();
	}
	public function index()
	{
		$this->assign('controller', 'about');
    	$this->display();
    }
}
