<?php
/**
 * 居民model
 * xujiang@cekasp.cn
 * 2015-04-10
 */
class MemberModel extends Model
{
	private	$_member;

	public function __construct()
	{
		parent::__construct();
		$this->_member = M('app_mcard_member');
	}

	/**
	 * 获取会员信息
	 *
	 * @param	int		mid
	 * @return	array
	 */
	public function getMemberinfo($mid)
	{
		$res = $this->_member->where('id='.$mid)->find();
		return $res;
	}

	/**
	 * 获取用户头像列表
	 *
	 * @param	int		bid
	 * @return	array
	 */
	public function getMemberHead($wid)
	{
		// 头像列表
		$list = $this->_member->where('wid='.$wid)->field('id,name,wechatid,headimgurl')->select();
		$res = array();

		// 转换成openid关联数组
		foreach($list as $val){
			$res[$val['wechatid']] = $val;
		}
		return $res;
	}
  
	public function writelog($mid, $wid, $leix, $zilei) {
		if((int)$mid<1 || (int)$leix<1 || (int)$zilei<1) {
			echo "记录日志的必要参数错误";exit;
		}
		$neir = "";
		if($leix == 1) {
			if($zilei==2) {
				$neir = "首次关注";
			}else if ($zilei==3) {
				$neir = "取消关注";
			}else if ($zilei==4) {
				$neir = "再次关注";
			}
		}

		$data['mid'] = $mid;
		$data['wid'] = $wid;
		$data['leix'] = $leix;
		$data['zilei'] = $zilei;
		$data['neir'] = $neir;
		$data['ctime'] = time();
		M("member_record")->data($data)->add();
		return;		
	}
}
