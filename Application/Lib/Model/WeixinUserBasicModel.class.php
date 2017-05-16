<?php
/**
 * 获取用户基本信息模块
 * 说明：
 *		通过用户openid机制获取
 * xujiang@ceasp.cn
 * 2015-03-16
 */
class WeixinUserBasicModel
{
	private	$_openId;	// 用户标识
	private	$_api;		// 接口地址
	private $_lang;		// 返回国家地区语言版本
	private $_subListApi;	// 关注列表接口地址

	public function __construct()
	{
		$this->_lang = 'zh_CN';
		$this->_api = 'https://api.weixin.qq.com/cgi-bin/user/info?';
		$this->_subListApi = 'https://api.weixin.qq.com/cgi-bin/user/get?';
	}

	/**
	 * 获取用户的基本信息
	 *	昵称、性别、所在城市、所在国家、所在省份、用户头像
	 *
	 * @param	string	openid
	 * @param	string	token
	 * @return	mix
	 */
	public function getUserBasicInfo($openId, $token) {
		if( !empty($openId) && is_string($openId) )
			$this->_openId = $openId;
		else
			throw new Exception('用户id不符合预期!');

		if( empty($token) || !is_string($token) )
			throw new Exception('token不符预期!');
		
		// 设置接口参数
		$param = array('access_token'=>$token, 'openid'=>$this->_openId, 'lang'=>$this->_lang);
		$param = http_build_query($param);
		$url = $this->_api . $param;
		$data = file_get_contents($url);
		$data = $this->_parser($data);
		return $data;
	}


	/**
	 * 获取当前服务号/订阅号的关注用户列表
	 *
	 * @param	string	token
	 * @param	int		next_openid
	 * @return	array
	 */
	public function getSubscribeList($token, $next_openid = null)
	{
		// 设置查询参数
		$param = array('access_token'=>$token);
		if( isset($next_openid) && is_numeric($next_openid) )
			$param['next_openid'] = $next_openid;
		$param = http_build_query($param);
		$url = $this->_subListApi . $param;
		$data = file_get_contents($url);
		$data = json_decode($data, true);
		return $data;
	}


	/**
	 * 解析返回数据
	 *
	 * @param	json
	 * @param	array
	 * @return	mix
	 */
	private function _parser($data)
	{
		$data = json_decode($data, true);
		if( isset($data['errcode']) ) return $data;	// 返回错误信息

		if( $data['subscribe'] != 1 ) return array('errmsg'=>'非关注用户!'); // 非关注用户，无法拉取信息

		$data['headimgurl'] = substr($data['headimgurl'], 0, -1) . '64';

		return $data;
	}
}
