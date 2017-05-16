<?php
class UserModel extends Model{
	protected $tableName = 'user';

	function checkPwd($user, $pwd) {
		$list    = $this->where("username='".$user."'")->find();
		if(empty($list)) {
			return -1;
			exit;
		}
		if($list['password'] != md5(md5($pwd).$list['salt'])) {
			return -2;
			exit;
		}
		return $list;
	}

	/*会员资料更新*/
	/*模式：普通*/
	/*********************************************************************************
	参数：
	  $uid : 用户ID
	返回：
	$mlist : 用户数据
	*********************************************************************************/
	function getInformation($uid) {
		return $mlist = $this->find($uid);
	}

	/*加载会员核心资料*/
	/*模式：普通*/
	/*********************************************************************************
	参数：
	$uid : 用户ID
	*********************************************************************************/
	function load_core($uid){
		$sql = "select m.*, g.gname as gname, g.gsname as gsname, g.gdesc as `desc` from ".C('DB_PREFIX')."user as m left join ".C('DB_PREFIX')."group as g on g.id=m.user_ugroup where m.id='".$uid."';";
		$mlist  = $this -> query($sql);
		return $mlist[0];
	}
}
?>