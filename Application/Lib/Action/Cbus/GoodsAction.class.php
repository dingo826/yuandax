<?php
class GoodsAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_business();
		
	}

    public function add() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$_SESSION['post'] = $_POST;
			$insertid = $this->_add();
			$ret["status"] = -1;
			if(intval($insertid)>0) $ret["status"] = 1;
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);
			exit;
			exit;
		}
		$categorylist = M("cbus_category")->select();

		foreach($categorylist as $key => $val) {
			$catearr[$val['id']] = $val;
		}

		$goodscategorylist = M("cbus_goods_category")->select();

		$xiajiatime = time()+7*24*60*60;

		$this->assign('categorylist',      $categorylist);
		$this->assign('catearr',           $catearr);
		$this->assign('goodscategorylist', $goodscategorylist);
		$this->assign('bminfo',            $bminfo);
		$this->assign('xiajiatime',        $xiajiatime);
		$this->display();
    }

	function edit() {
		$bminfo   = $_SESSION["cbus_bminfo"];
		$id       = intval(I("get.id"));
		$detail   = M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->find();

		if($_POST) {
			if($_POST['images']) {
				$temp = '';
				foreach($_POST['images'] as $val) {
					$temp['bid']       = $bminfo['id'];
					$temp['goodsid']   = $id;
					$temp['fromshequ'] = $_SESSION["formid"];
					$temp['image']     = $val;
					$temp['etime']     = time();
					$temp['ctime']     = time();
					$picarr[] = $temp;
				}
			}
			$data["name"]     = I("post.name");
			$data["desc"]     = I("post.desc");
			$data["oprice"]   = I("post.oprice");
			$data["price"]    = I("post.price");
			$data["pdiff"]    = $data['oprice']-$data['price'];
			$data["cid"]      = intval(I("post.cid"));
			$data["deadline"] = strtotime(I("post.deadline"));
			$data["etime"]    = time();

			if(!$detail['logo']) 
				$data["logo"]  = $picarr[0]["image"];

			if($data["deadline"]>time()) 
				$data["status"] = 1;

			$upid = M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->save($data);

			if($upid>0) {
				M("cbus_goods_pic")->addAll($picarr);
			}
			$ret["status"] = -1;
			if(intval($upid)>0) $ret["status"] = 1;
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$goodspiclist = M("cbus_goods_pic")->where("bid='".$bminfo['id']."' and goodsid='".$detail['id']."'")->order("id asc")->select();

		$goodscategorylist = M("cbus_goods_category")->select();

		$this->assign('goodscategorylist', $goodscategorylist);
		$this->assign('goodspiclist',      $goodspiclist);
		$this->assign('detail',            $detail);
		$this->display();
	}

	function editstatus() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$id = intval(I("post.gid"));
			$data["status"] = 1;
			if(intval(I("post.status")) == 1) $data["status"] = -1;

			$upid = M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->save($data);

			$retjson['status']  = $data["status"];
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	function del() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$id = intval(I("post.gid"));

			$delid = M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->delete();

			$retjson['status']  = 1;
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	function _add() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_SESSION['post']) {
		    $post = $_SESSION['post'];
			$_SESSION['post'] = '';

			if(!trim($post['name']) || !trim($post['oprice']) || !trim($post['price']) || !trim($post['deadline']) || !$post['images']) {
				return -1;
			}
			$data["bid"]       = $bminfo['id'];
			$data["fromshequ"] = $_SESSION["formid"];
			$data["name"]      = $post['name'];
			$data["desc"]      = $post['desc'];
			$data["logo"]      = $post['images'][0];
			$data["oprice"]    = $post['oprice'];
			$data["price"]     = $post['price'];
			$data["pdiff"]     = $post['oprice']-$post['price'];
			$data["cid"]       = $post['cid'];
			$data["deadline"]  = strtotime($post['deadline']);
			$data["etime"]     = time();
			$data["ctime"]     = time();
			$insertid = M("cbus_goods")->add($data);
			if($post['images'] && intval($insertid)>0) {
				$temp = '';
				foreach($post['images'] as $val) {
					$temp['bid'] = $_SESSION['cbus_bid'];
					$temp['fromshequ'] = $_SESSION["formid"];
					$temp['goodsid']   = $insertid;
					$temp['image']     = $val;
					$temp['etime']     = time();
					$temp['ctime']     = time();
					$picarr[] = $temp;
				}
				M("cbus_goods_pic")->addAll($picarr);
			}
			return $insertid;
		}
		
	}

	function delimg() {
		$bminfo = $_SESSION["cbus_bminfo"];
		$id     = intval(I("get.id"));
		$imgid  = intval(I("get.imgid"));

		$img    = M("cbus_goods_pic")->where("id='".$imgid."'")->find();
		$delnum = M("cbus_goods_pic")->where("id='".$imgid."' and bid='".$bminfo['id']."'")->delete();

		$detail = M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->find();
		if($detail['logo']==$img['image']) {
			$newimg    = M("cbus_goods_pic")->where("goodsid='".$id."' and bid='".$bminfo['id']."'")->order("id asc")->find();
			$newimgsrc = "";
			if($newimg) $newimgsrc = $newimg["image"];
			$goodupdata["logo"] = $newimgsrc;
			M("cbus_goods")->where("id='".$id."' and bid='".$bminfo['id']."'")->save($goodupdata);
		}
		$status = -1;
		if(intval($delnum)>0) {
			$status = 1;
		}
		echo $status;
		exit;

	}
}
