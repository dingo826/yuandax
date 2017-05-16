<?php
class ProfileAction extends CbusAction {
	
	public function __construct() {
		parent::__construct();
		$this->_business();
		
	}

    public function edit() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$upid = $this->_edit();
			if($upid>0) {
				$ret['status']  = 1;
				$ret['message'] = "信息修改成功";
			}else {
				$ret['status']  = -1;
				$ret['message'] = "信息修改失败";
			}
			echo json_encode($ret, JSON_UNESCAPED_UNICODE);
			exit;
			exit;
		}
		$categorylist = M("cbus_category")->select();
		$bpiclist     = M("cbus_business_pic")->where("bid='".$bminfo['id']."'")->select();
		$introduction = M("cbus_introduction")->where("bid='".$bminfo['id']."'")->find();

		$jdsqlist     = M("relat_jd_sq")->table(C('DB_PREFIX')."relat_jd_sq as r ")->field("j.id as id, j.name, b.abbr as sqname, b.wid as sqid")
			          ->join("left join ".C("DB_PREFIX")."jd_user as j on (j.id=r.jdid)")
			          ->join("left join ".C("DB_PREFIX")."app_basicinfo as b on (b.wid=r.sqid)")->select();

		foreach($jdsqlist as $key => $val) {
			$relatjdse[$val['id']]['id']    = $val["id"];
			$relatjdse[$val['id']]['name']  = $val["name"];
			$relatjdse[$val['id']]['count'] = $relatjdse[$val['id']]['count']+1;
			$relatjdse[$val['id']]['sub'][] = $val;
		}
		$coveragelist = M("relat_community_cbus")->where("cbusid='".$bminfo['id']."'")->select();
		$coveragenum  = count($coveragelist);

		$this->assign('categorylist', $categorylist);
		$this->assign('bpiclist',     $bpiclist);
		$this->assign('bminfo',       $bminfo);
		$this->assign('introduction', $introduction);
		$this->assign('relatjdse',    $relatjdse);
		$this->assign('coveragelist', $coveragelist);
		$this->assign('coveragenum',  $coveragenum);
		$this->display();
    }

	function editpwd() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$opwd    = I("post.opwd");
			$newpwd  = I("post.newpwd");
			$newpwd2 = I("post.newpwd2");
			if($newpwd!=$newpwd2) {
				$retjson['status']  = -1;
				$retjson['message'] = "两次输入新密码不一致";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}
			if(md5(md5($opwd).$bminfo['lin_salt'])!=$bminfo['passwd']) {
				$retjson['status']  = -1;
				$retjson['message'] = "原密码错误";
				echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
				exit;
			}

			$updata["passwd"] = md5(md5($newpwd2).$bminfo['lin_salt']);
			$updata["etime"]  = time();
			$upid = M("cbus_account")->where("id='".$bminfo['id']."'")->save($updata);

			if($upid>0) {
				$_SESSION['cbus_bid']    = 0;
				$_SESSION["cbus_bminfo"] = "";
				unset($_SESSION['cbus_bid']);
				unset($_SESSION['cbus_bminfo']);
				unset($_SESSION);
				$retjson['status']  = 1;
				$retjson['message'] = "密码修改成功，请使用新密码重新登录";
			}else {
				$retjson['status']  = -1;
				$retjson['message'] = "密码修改失败";
			}
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	function editcoverage() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$coveragelist = M("relat_community_cbus")->where("cbusid='".$bminfo['id']."'")->select();
			foreach($coveragelist as $key => $val) {
				$list[$val['sqid']] = $val;
			}

			foreach($_POST['complain'] as $key => $val) {
				if(!$list[$val]) {
					$temp["sqid"]   = $val;
					$temp["cbusid"] = $bminfo['id'];
					$temp["etime"]  = time();
					$temp["ctime"]  = time();
					$adddata[] = $temp;
				}
			}
			$delwhere = "cbusid='".$bminfo['id']."' and sqid!='".$bminfo['fromshequ']."' and sqid not in ('".implode("','", $_POST['complain'])."')";
			M("relat_community_cbus")->where($delwhere)->delete();
			M("relat_community_cbus")->addAll($adddata);

			$retjson['status']  = 1;
			$retjson['message'] = "修改成功";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}
		$retjson['status']  = -1;
		$retjson['message'] = "修改失败";
		echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
		exit;
	}

	function _edit() {
		$bminfo       = $_SESSION["cbus_bminfo"];
		if($_POST) {
			$post = $_POST;

			if($_POST['images']) {
				$temp = '';
				foreach($_POST['images'] as $val) {
					$temp['bid']       = $bminfo['id'];
					$temp['fromshequ'] = $_SESSION["formid"];
					$temp['image']     = $val;
					$temp['etime']     = time();
					$temp['ctime']     = time();
					$picarr[] = $temp;
				}
			}

			$data["nickname"]  = $post["nickname"];
			$data["logo"]      = $post["logo"];
			$data["cid"]       = intval($post["cid"]);
			$data["tel"]       = $post["tel"];
			$data["province"]  = $post["province"];
			$data["city"]      = $post["city"];
			$data["county"]    = $post["county"];
			$data["contactus"] = $post["contactus"];
			$data["address"]   = $post["address"];
			$data["xpoint"]    = $post["p_x"];
			$data["ypoint"]    = $post["p_y"];
			$data["etime"]     = time();
			$upid = M("cbus_account")->where("id='".$bminfo['id']."'")->save($data);
			if($post['introd']) {
				$introduction         = M("cbus_introduction")->where("bid='".$bminfo['id']."'")->find();
				$introdata['bid']     = $bminfo['id'];
				$introdata['content'] = $post["introd"];
				$introdata['etime']   = time();
				if($introduction) {
					M("cbus_introduction")->where("bid='".$bminfo['id']."'")->save($introdata);
				}else {
					$introdata['ctime']   = time();
					M("cbus_introduction")->add($introdata);
				}				
			}

			if($upid>0) {
				M("cbus_business_pic")->addAll($picarr);
			}
			return $upid;

		}
	}

	function delimg() {
		$bminfo = $_SESSION["cbus_bminfo"];
		$imgid  = intval(I("get.imgid"));
		$delnum = M("cbus_business_pic")->where("id='".$imgid."' and bid='".$bminfo['id']."'")->delete();
		$status = -1;
		if(intval($delnum)>0) {
			$status = 1;
		}
		echo $status;
		exit;

	}
}
