<?php
/**
 * 微信用户关注入住处理
 */
class WeixinUserInModel extends Model
{
	private	$_member;	// 用户模型
	private $_wxUserBasic;	// 用户基础信息获取模块
	private $_subscribe;	// 关注历史记录模型

	public function __construct() {
		parent::__construct();
		$this->_member = M('app_mcard_member');
		$this->_wxUserBasic = D('WeixinUserBasic');
		$this->_subscribe = M('subscribe');
	}

	/**
	 * 微信用户关注时获取基础信息作为入住使用
	 *
	 * @param	string	wid
	 * @param	string	openID
	 * @return	null
	 */
	public function inCommunity($wid, $openID) {
		// 获取token
		$token = D('Weixin')->getToken($wid);

		// 获取开发者原始id
		$gzid = M('weixin')->where('id='.$wid)->getField('gzid');

		$this->_setBasicInfo($token, $openID, $gzid, $wid);
		return;
	}


	/**
	 * 更新居民列表
	 *
	 * @param	int		平台id
	 * @return	array	更新状态
	 */
	public function upwechatList($wid) {
		$list = array();

		// 获取token
		$token = D('Weixin')->getToken($wid);

		$res = $this->_wxUserBasic->getSubscribeList($token);

		if( isset($res['errcode']) ) return $res;
		$list = array_merge($list,$res['data']['openid']);
		$next_openid = trim($res['next_openid']);


		// 获取开发者原始id
		$gzid = M('weixin')->where('id='.$wid)->getField('gzid');

		// 居民入住
		foreach($list as $openID){
			$this->_setBasicInfo($token, $openID, $gzid, $wid);
		}

		// 更新入住用户关注标记
		$openids = implode(',', $list);
		$openids = "'" . str_replace(",", "','", $openids) . "'";
		$this->_member->where("wid=$wid and wechatid in (".$openids.")")->save(array('subscribe'=>1));
		$res = array('status'=>'successful');
		return $res;
	}

	/**
	 * 更新居民列表
	 *
	 * @param	int		平台id
	 * @return	array	更新状态
	 */
	public function upwechatList2($wid, $op=null) {
		$dlist = array();

		// 获取token
		$token = D('Weixin')->getToken($wid);

		$res = $this->_wxUserBasic->getSubscribeList($token, $op);

		if( isset($res['errcode']) ) return $res;
		$dlist = array_merge($dlist, $res['data']['openid']);
		$list = array_slice($dlist, 0, 100);

		$ret["total"] = $res["total"];
		

		if(count($dlist)>100) {
			$ret['count'] = 100;
			$next_openid = $res['data']['openid'][99];
		}else {
			$ret['count'] = $res['count'];
			$next_openid = trim($res['next_openid']);
		}
		$ret["next_openid"] = $next_openid;


		// 获取开发者原始id
		$gzid = M('weixin')->where('id='.$wid)->getField('gzid');

		// 居民入住
		foreach($list as $openID){
			$this->_setBasicInfo($token, $openID, $gzid, $wid);
		}

		// 更新入住用户关注标记
		$openids = implode(',', $list);
		$openids = "'" . str_replace(",", "','", $openids) . "'";
		$this->_member->where("wid=$wid and wechatid in (".$openids.")")->save(array('subscribe'=>1));
		//$res = array('status'=>'successful');
		$ret["status"] = 'successful';
		return $ret;
	}

	/**
	 * 设置信息入住
	 *
	 * @param	string	token
	 * @param	string	openid
	 * @param	string	gzid 开发者原始id
	 * @param	int		wid 平台id
	 * @return	null
	 */
	private function _setBasicInfo($token, $openID, $gzid, $wid) {
		// 获取基础信息
		$basicInfo = $this->_wxUserBasic->getUserBasicInfo($openID, $token);
		if( isset($basicInfo['errmsg']) ) {
			return;	// 获取数据错误，不入住
		}

		// 历史记录不存在加入，存在不处理
		$id = $this->_subscribe->where("open_id='".$openID."' and wid=$wid")->getField('id');
		if( !($id > 0) ){
			// 更新关注历史
			$subHistory = array(
				'wid' => $wid,
				'original_id' => $gzid,
				'open_id' => $openID,
				'event'	=> 'subscribe',
				'time'	=> $basicInfo['subscribe_time'],
			);
			$this->_subscribe->data($subHistory)->add();
		}

		// 用户入住
		$data = array(
			'name'		=> $basicInfo['nickname'],
			'sex'		=> $basicInfo['sex'],
			'region'	=> $basicInfo['country'].'-'.$basicInfo['province'].'-'.$basicInfo['city'],
			'headimgurl'=> $basicInfo['headimgurl'],
			'subscribe'	=> 1,
			'etime'		=> time(),
		);

		// 检查用户是否有入住过
		$id = $this->_member->where("wid=".$wid." and wechatid='".$openID."'")->getField('id');
		$zilei = 4;
		if($id > 0){			
			// 曾经入住过，更新信息
			$this->_member->where("wid=".$wid." and wechatid='".$openID."'")->save($data);
		}else{
			$zilei = 2;
			// 第一次入住,计算卡号
			$user = $this->_member->where("wid='".$wid."'")->order("id desc")->find();                                
			if(!$user) {
				$cardnum = 100001;
			}else{
				$cardnum = $user['cardnum']+1;
				preg_match("[4]", $cardnum, $b);
				if(!empty($b)) {
					$cardnum = strtr($cardnum, 4, 5);
				}
			}							 
			$data['wid'] = $wid;
			$data['wechatid'] = $openID;
			$data['cardnum'] = $cardnum;
			$data['ctime']	= $data['etime'];
			
			$id = $this->_member->data($data)->add();
		}
		D("Member")->writelog($id, $wid, 1, $zilei);
		return;
	}

}
