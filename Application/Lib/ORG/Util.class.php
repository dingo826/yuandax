<?php
/**
 * 常用工具
 * 一些通用方法
 * xujiang@cekasp.cn
 * 2014-11-18
 */

class Util
{
	/**
	 * 获取微信用户access_token
	 *
	 * @param	array
	 * @return	string
	 */
	static function getAccessToken($weixin)
	{
		$accessToken = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.
			$weixin['appid'].'&secret='.$weixin['appsecret']), true);
		$accessToken = $accessToken['access_token'];
		return $accessToken;
	}

	/**
	 * 发送post请求
	 *
	 * param	string
	 * param	string
	 * return	mix
	 */
	static function curlPost($url, $vars)
	{
		$ch = curl_init();      
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);        
		curl_setopt($ch, CURLOPT_URL,$url);       
		curl_setopt($ch, CURLOPT_POST, 1);    
		curl_setopt($ch, CURLOPT_HEADER, 0) ;    
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);       
		$data = curl_exec($ch);          
		curl_close($ch);        
		if ($data)     
			return $data;       
		else  
			return false;
	}
}
