<?php
class EmployeeAction extends WeixinAction {

    public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$pageSize = 25;
		$Page = $_GET['p'] ? $_GET['p'] : 1;
		$limit = ($currentPage-1)*$pageSize;

		$where = "a.wid='".$wid."'";

		import('ORG.Util.Page');

		$list = M("app_qiyeoa_employee")->table(C('DB_PREFIX')."app_qiyeoa_employee as a")->field("a.*, d.name as dname")
			->join("left join ".C("DB_PREFIX")."app_qiyeoa_department as d on (a.departmentid=d.id)")->where($where)->order("a.sort desc")->page($Page.','.$pageSize)->select();

		$count = M("app_qiyeoa_employee")->table(C('DB_PREFIX')."app_qiyeoa_employee as a")->where($where)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();

		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display($this->Base_themplate);
    }

	public function add() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {

			$data["wid"]          = $wid;
			$data["departmentid"] = intval(I("post.department"));
			$data["name"]         = I("post.name");
			$data["sex"]          = intval(I("post.sex"));
			$data["avater"]       = I("post.avater");
			$data["mphone"]       = I("post.mphone");
			$data["email"]        = I("post.email");
			$data["qianming"]     = I("post.qianming");
			$data["sort"]         = intval(I("post.sort"));
			$data["etime"]        = $data["ctime"] = time();

			M("app_qiyeoa_employee")->add($data);
			header("Location: ".U("employee/index"));exit;
		}
		$list = M("app_qiyeoa_department")->where("wid='".$wid."' and isshow=1")->order("weight desc")->select();
		if(empty($list)) {
			header("Location: ".U("position/index"));exit;
		}
		$detail['sex'] = 1;
		$this->assign('list', $list);
		$this->assign('detail', $detail);
		$this->display($this->Base_themplate);
    }

	function edit() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval($_GET['id']);

		if($_POST) {

			$data["departmentid"] = intval(I("post.department"));
			$data["name"]         = I("post.name");
			$data["sex"]          = intval(I("post.sex"));
			$data["avater"]       = I("post.avater");
			$data["mphone"]       = I("post.mphone");
			$data["email"]        = I("post.email");
			$data["qianming"]     = I("post.qianming");
			$data["sort"]         = intval(I("post.sort"));
			$data["etime"]        = time();

			M("app_qiyeoa_employee")->where("id='".$id."' and wid='".$wid."'")->save($data);
			header("Location: ".U("employee/index"));exit;
		}

		$detail = M("app_qiyeoa_employee")->where("id='".$id."' and wid='".$wid."'")->find();

		if($detail['avater']<>"/Public/img/mingp/avatar.jpg") {
			$detail['img'] = substr($detail['avater'], 1);
		}

		$list = M("app_qiyeoa_department")->where("wid='".$wid."' and isshow=1")->order("weight desc")->select();
		if(empty($list)) {
			header("Location: ".U("position/index"));exit;
		}
		$this->assign('list', $list);
		
		$this->assign('detail', $detail);
		$this->display($this->Base_theme."/"."add");
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval($_GET['id']);

		M("app_qiyeoa_employee")->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U("employee/index"));exit;
	}

}