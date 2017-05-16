<?php
class HuodongAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
		$this->assign('tget', I("get."));
	}

    public function index() {
		$wid    = intval(I("get.wid"));
		$token  = I("get.token", '', 'htmlspecialchars');
		$list   = M("app_huodong")->where("`wid`='".$wid."'")->order("id desc")->select();

		$hdall   = M("app_huodong_records")->where("`wid`=".$wid)->sum('nums');

		$hdbming = M("app_huodong")->where("`wid`='".$this->wid."' and bbtime<".time()." and betime>".time())->count();

		$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
		//print_r($member);//exit;
		$myhd   = M("app_huodong_records")->where("uid=".$member["id"])->count();
		//echo "uid=".$member["id"];exit;
		

		$this->assign('list', $list);
		$this->assign('nowtime', time());
		$this->assign('hdall', $hdall);
		$this->assign('hdbming', $hdbming);
		$this->assign('myhd', $myhd);
		$this->display();
    }

	public function detail() {
		$wid    = intval(I("get.wid"));
		$id     = intval(I("get.id"));
		$token  = I("get.token", '', 'htmlspecialchars');

		$detail = M("app_huodong")->where("`wid`='".$wid."' and id=".$id)->find();

		$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
		$this->assign("member", $member);
		if($member) {
			$mbaom  = M("app_huodong_records")->where("`wid`='".$wid."' and huodongid=".$id." and uid=".$member['id'])->find();

			$mdianz = M("app_huodong_dianz")->where("wid='".$wid."' and huodongid=".$id." and uid='".$member['id']."'")->find();
			if($mdianz) {
				$_SESSION[$id]['isdianz'] = 1;
			}
		}

		if($_POST) {
			$post = I("post.");

			if($detail['maxnum']<($detail['nums']+intval($post["nums"]))) {
				echo -2;exit;
			}
			
		    if(!$mbaom) $mbaom  = M("app_huodong_records")->where("`wid`='".$wid."' and huodongid=".$id." and mphone=".$post['mphone'])->find();
			
					
			if($mbaom) {
				echo -1;exit;
			}
			$data['wid']       = $wid;
			$data['huodongid'] = $id;
			$data['token']     = $token;
			$data['uid']       = -1;
			if(intval($member["id"])>0) $data['uid'] = $member["id"];
			$data["contact"]   = $post["contact"];
			$data["mphone"]    = $post["mphone"];
			$data["nums"]      = intval($post["nums"]);

			$data["status"]    = 1;
			$data["etime"]     = time();
			$data["ctime"]     = time();

			$insertid = M("app_huodong_records")->add($data);
			if($insertid>0) {
				M("app_huodong")->where("id=".$id)->setInc("nums", $data["nums"]);
				if($detail['baoxian']==1) {
					foreach($_POST['person'] as $bxkey => $bxval) {
						$bxtemparr['wid']       = $wid;
			            $bxtemparr['huodongid'] = $id;
				        $bxtemparr['recordid']  = $insertid;
						$bxtemparr['name']      = $bxval;
						$bxtemparr['idcard']    = $_POST['IdCard'][$bxkey];
						$bxtemparr['etime']     = time();
						$bxtemparr['ctime']     = time();

						$bxdata[] = $bxtemparr;
					}
					M("app_huodong_baoxian")->addAll($bxdata);
				}

				if($_POST["diydata"]) {
					foreach($_POST["diydata"] as $key => $val) {
						$temp = '';
						$temp['wid']       = $wid;
						$temp['huodongid'] = $id;
						$temp['recordid']  = $insertid;
						$temp['subid']     = $key;
						$temp['value']     = $val;
						$temp['etime']     = time();
						$temp['ctime']     = time();
						$recordsubarr[]    = $temp;
					}
					if(!empty($recordsubarr)) M("app_huodong_records_subinfo")->addAll($recordsubarr);
				}
			}
			$_SESSION["huodongbm_".$id]['isbaom'] = 1;
			echo 1;exit;
		}

		$isdianz = intval($_SESSION["huodongdz_".$id]['isdianz']);

		//$detail = M("app_huodong")->where("`wid`='".$wid."' and id=".$id)->find();
		$detail['content'] = htmlspecialchars_decode($detail['content']);

		$sublist  = M('app_huodong_subinfo')->field("id as diy_id, keyname as diy_name, prompt as diy_msg, inputtype as diy_type, required as diy_check")->where("huodongid='".$id."' and wid='".$wid."'")->order("id asc")->select();

		$this->assign('detail', $detail);
		$this->assign("sublist", $sublist);
		$this->assign('mbaom', $mbaom);
		$this->assign('nowtime', time());
		$this->assign('isdianz', $isdianz);
		$this->display();
    }

	function dianz() {
		$wid    = intval(I("get.wid"));
		$id     = intval(I("get.id"));
		$token  = I("get.token", '', 'htmlspecialchars');
		if($token) {
			$member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
			$dianz  = M("app_huodong_dianz")->where("`wid`='".$wid."' and uid=".$member['id'])->find();
			if($dianz) {
				echo -1;exit;
			}
		}
		//print_r($_SESSION);exit;

		if($_SESSION["huodongdz_".$id]['isdianz']==1) {
			echo -1;exit;
		}

		$data["wid"]       = $wid;
		$data["huodongid"] = $id;
		$data["token"]     = $token;
		$data["uid"]       = $member['id'];
		$data["ctime"]     = time();

		M("app_huodong")->where("id=".$id)->setInc("dianzs");
		$_SESSION["huodongdz_".$id]['isdianz'] = 1;
		if($member) {
			M("app_huodong_dianz")->add($data);
		}
		echo 1;exit;
	}
}
