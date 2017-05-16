<?php
class LogoutAction extends UserAction {

    public function index(){
		$_SESSION['uid'] = '';
		$_SESSION['wid'] = '';
		unset($_SESSION['uid']);
		unset($_SESSION['wid']);		
		session("uid", null);
		session("wid", null);
		unset($_SESSION);
		session(null);
		header("location: /");
		exit;
    }
}
