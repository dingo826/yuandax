<?php
class WxqunAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$model = M('app_wxqun');

		$where = 'a.wid='.$this->wid." and a.status=2";

		$list = $model->table(array(
			     C('DB_PREFIX')."app_wxqun"         => "a",
			     C('DB_PREFIX')."app_wxqun_sysfenl" => "s",
		         ))->field("a.*, s.name as sname")->where($where." and a.qunfenl=s.id")->order("a.id desc")->select();

		$this-> assign("list", $list);
		$this->display();
    }

	function detail() {
		$model = M('app_wxqun');
		$id    = intval(I('get.id'));
		$detail = M('app_wxqun')->find($id);
		$this-> assign("detail", $detail);
		$this->display();
	}
}
