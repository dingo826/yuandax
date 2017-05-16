<?php
class PositionAction extends WeixinAction {

    public function index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$data = '';
			foreach($_POST['department'] as $key => $val) {
				$item           = '';
				if(trim($val['name'])) {
					$item['wid']    = $wid;
					$item['name']   = trim($val['name']);
					$item['weight'] = intval($val['weight']);
					$item['isshow'] = intval($val['show']);
					$item['etime']  = time();

					if($key>0) {
						M("app_qiyeoa_department")->where("id='".$key."' and wid='".$wid."'")->save($item);
					}else {
						$data['insert'][] = $item;
					}
				}			
			}
			if($data['insert']) {
				M("app_qiyeoa_department")->addAll($data['insert']);
			}

			header("Location: ".U("position/index"));exit;
			exit;
		}
		$list = M("app_qiyeoa_department")->where("wid='".$wid."'")->order("weight desc")->select();
		$this->assign('list', $list);
		$this->display($this->Base_themplate);
    }

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I("post.id"));
		M("app_qiyeoa_department")->where("id='".$id."' and wid='".$wid."'")->delete();
		$data["status"] = 1;
		echo json_encode($data);exit;
	}


}