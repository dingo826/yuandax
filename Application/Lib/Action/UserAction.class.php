<?php
/* 系统项目组可以共用的基类库，继承则可，自动加载 */
class UserAction extends Action
{
	function __construct()
	{
		parent::__construct();
		$this->checklogin();
		$this->ChoiceTemplate();

		// 获取头部信息
		$this->_getUsers();
	}

	private function _getUsers() {
		$wid = $this->_wid = session('wid');
		$weixin = M('Weixin')->where('id='.$wid)->field('id,avatar,gzname')->find();

		$this->assign('weixin', $weixin);
	}

    // 控制器初始化处理 可以让所有项目组共同使用
	function checklogin() {
		if(session("uid")<1) header("Location: /login");
	}

	function ChoiceTemplate() {
		$template = cookie("usertheme");
		if(!$template) $template = "User";

		$this->Base_module    = $template;
		$this->Base_theme     = $template."@".MODULE_NAME;
		$this->Base_themplate = $this->Base_theme."/".strtolower(ACTION_NAME);

		$funname = "selectcolumn".$template;
		$this->$funname();
		
	}

	function selectcolumnUser() {
		$this->assign('selctactive', "five");
	}

	function selectcolumnUser2() {
		$actarray = array(			
			"account" => "nine",
			"setauth" => "nine",
			"weixin" => "nine",
		);

		$this->assign('selctactive', $actarray[strtolower(MODULE_NAME)]);
	}
}
?>
