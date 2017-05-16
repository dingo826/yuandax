<?php
/**
 * 微信model
 * 2015-01-07
 */
class WeixinModel extends Model
{
	private $_model;	// 微信数据模型

	public function __construct()
	{
		parent::__construct();
		$this->_model = M('weixin');
	}

	/**
	 * 获取微信信息
	 *
	 * @param	int	账号id
	 * @return	array
	 */
	public function getWeixinUArr($uid)
	{
		$res = $this->_model->where('uid='.$uid)->limit(10)->select();
		return $res;
	}

	/**
	 * 获取微信信息
	 *
	 * @param	int	社区id
	 * @return	array
	 */
	public function getWeixinInfo($wid)
	{
		$res = $this->_model->where('id='.$wid)->find();
		return $res;
	}
	
	/**
	 * 获取微信token
	 *
	 * @param	int	社区id
	 * @return	array
	 */
	public function getToken($wid){
		$M=M("weixin_token");
		
		$info=$M->where("wid=".$wid)->find();
		
		//数据库不存在记录 获取并添加记录
		if(empty($info['token'])){
			$token          = $this->setToken($wid);
			$data["token"]  = $token;
			$data["uptime"] = time();
			$data["wid"]    = $wid;
			$M->data($data)->add();
			return $token;
		}
		
		//检测时间是否过期
		if(time()-(int)$info["uptime"]>=7140){
			$token          = $this->setToken($wid);
			$data["token"]  = $token;
			$data["uptime"] = time();
			$M->where("wid=".$wid)->data($data)->save();
			return $token;
		}
		
		return $info["token"];
	}
	
	/**
	 * 重置微信token
	 *
	 * @param	int	社区id
	 * @return	array
	 */
	public function setToken($wid){
		$weixin = $this->getWeixinInfo($wid);
		$token  = getAccessToken($weixin);
		return $token;
	}
}
