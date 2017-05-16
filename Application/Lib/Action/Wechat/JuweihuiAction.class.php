<?php
class JuweihuiAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
	}

    public function index() {
		$wid   = intval(I("get.wid"));
		$token = I("get.token", '', 'htmlspecialchars');

		
		$list   = M("app_qiyeoa_employee")->where("wid='".$wid."'")->select();


		// 获取雇员列表
		$employee = M('app_qiyeoa_employee');
		$list = $employee->alias('e')
			->join('left join '.C('DB_PREFIX').'app_qiyeoa_department as d on d.id=e.departmentid')
			->where('e.wid='.$wid)
			->field('e.* ,d.name as dname')
			->order('e.sort desc')
			->select();

		// 获取基本设置
		$detail = M("app_basicinfo")->where("wid='".$wid."'")->find();

		$detail['introduce'] = htmlspecialchars_decode($detail['introduce']);

		$this->assign('detail', $detail);
		$this->assign('list',   $list);
		$this->display();
    }
}
