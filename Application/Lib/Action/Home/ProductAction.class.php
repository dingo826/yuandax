<?php
class ProductAction extends Action {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->assign('controller', 'product');
    	$this->display();
    }
}
