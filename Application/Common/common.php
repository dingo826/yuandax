<?php
/**
 * 获取模块父类型的url
 *
 * @return	string
 */
function getBusurl($data) {
	switch($data['type']) {
		case 'article':
			$url = "http://".C("ODOMIN").U('wechat/channel/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'link':
			$url = "http://".$data['link'];
		    break;
		case 'tel':
			$url = "tel:".$data['tel'];
		    break;
		case 'column':
			$url = '#';
			if($data['column']>0) {
				$category   = M("app_category")->where("id='".$data['column']."'")->find();
				$url = getBusurl($category);
			}
		    break;
		case 'business':
			$url = "http://".C("ODOMIN").getBusinessurl($data);
		    break;
		case 'isempty':
			$url = '';
		    break;
		default:
			$url = "#";
		    break;
	}
	return $url;
}

/**
 * 获取模块具体业务模块的url
 *
 * @return	string
 */
function getBusinessurl($data) {
	switch($data['business_type']) {
		case 'official':
			$url = U('wechat/index/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'vipcard':
			$url = U('wechat/member/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'juweihui':
			$url = U('wechat/juweihui/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'communityservice':
			$url = U('wechat/communityservice/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'albums':
			$url = U('wechat/albums/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'survey':
			$url = U('wechat/survey/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'resevsection':
			$url = U('wechat/reservation/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'message':
			$url = U('wechat/board/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'circle':
			$url = U('wechat/circle/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'wxqun':
			$url = U('wechat/wxqun/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'huodong':
			$url = U('wechat/huodong/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'huodonglist':
			$url = U('wechat/huodong/detail?id='.$data['business_value'].'&wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'vote':
			$url = U('wechat/vote/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		default:
			$url = U('wechat/index/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
	}
	return $url;
}

/**
 * 获取模块具体业务模块的会员中心的url
 *
 * @return	string
 */
function getBusinessmyurl($data) {
	switch($data['business_type']) {
		case 'official':
			$url = U('index/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'vipcard':
			$url = U('member/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'juweihui':
			$url = U('juweihui/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'communityservice':
			$url = U('communityservice/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'albums':
			$url = U('albums/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'survey':
			$url = U('survey/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'resevsection':
			$url = U('reservation/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'message':
			$url = U('board/index?wid='.$data['wid']."&type=1&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'circle':
			$url = U('circle/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		case 'wxqun':
			$url = U('wxqun/index?wid='.$data['wid']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
		default:
			$url = U('index/index?wid='.$data['wid'].'&id='.$data['id']."&token=".I('get.token')."&wxref=mp.weixin.qq.com");
		    break;
	}
	return $url;
}

/**
 * 获取当前完整url
 *
 * @return	string
 */
function getCurrentURL()
{
	return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

/**
 * 获取AccessToken
 *
 * param	array
 * return	string
 */
function getAccessToken($weixin) {		
	$accessToken = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$weixin['appid'].'&secret='.$weixin['appsecret']), true);
	$accessToken = $accessToken['access_token'];
	return $accessToken;
}

/**
*
*根据微信规则更新：把正文里的图片上传到微信服务器上
*
**/
function contentHandle($content, $tokenMap, $syspath='' ) {
	if(!$syspath) $syspath=dirname(dirname(dirname(__FILE__)));
	$num = preg_match_all("/src=[\"|\'](\/[^\"|\']+)[\"|\']/isU", $content, $match);
	$md5arr = '';
	foreach($match[1] as $key => $val) {
		$md5file = md5_file($syspath.$val);
		if(!isset($md5arr[$md5file])) {
			$data = array('media' => '@'.$syspath.$val);
			$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$tokenMap;
			$res = curl_post($url, $data);
			$res = json_decode($res, true);
			$md5arr[$md5file] = $res['url'];
			$content = str_replace($val, $md5arr[$md5file], $content);
		}else {
			$content = str_replace($val, $md5arr[$md5file], $content);
		}
	}
	$content = str_replace('/static/image/weixineditor/background/1.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nicZibsHRyhrJ2UQbFnJVy1g67eovUS5biaQllP0MLs4Y1b6bPU5nMEdrg/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/2.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n0Be5iaMnPpWzhDJ2SJRSLZeLQicoZWJibfLFnkKM90Ldibj1w456oHLZlA/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/3.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nhJyo4H8RX9SoWgibISveXsfC3PPwg1vfSfITY1u2icbM4IerOk8RcTUQ/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/4.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n3mNDvk9PiatrUoGYfAiaWUUNEJckQJKBlzXlicfTymnXvWxZLETKgP3Ug/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/5.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nWG59HpCczKwEP9biaK5NuiaVb7ClzC6AAQibmtkZOibiaHxricMcb6O18YYA/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/6.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nEutqkiaVic796lXwicZRJicrexDNeYPBGH9C89zvByCqFe3ZWjvRPDSiaww/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/7.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4niaibMJSEAts4TYeYKULV66Qk9JqRzxXt0zoxXnib9rcwpFhbniaeMP5FBQ/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/8.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nuibP9xoEc8kk6qfxmkXQL31NHmQIAf6rInGljHBiaaxubptiaxlOBCQ8w/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/9.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nsM4NDqDIyvzY1lV4mp0aTu3rA199tzmMyMlq3WUEScNEScWUN0Efgg/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/10.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nq8DuOKAVOdpfCVicxVhv3IIseGKkoCzGKJfbO8xvmMmUriaqH6AOz1Eg/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/11.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nicrgjiakiatJnC9tWl5FQdPLLTRTwdXSYUgzSiaqhia2HiaNwb4u9uDIXz0A/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/12.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nicqiaEhiaWEofzyjK5Jx5pC8NeAKNWWpjO6lN42YJohLYdgZ1v52Wp2uA/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/13.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n7WlLVpI70ibRBrGfEpzMZ45P183XxENJ5xx3Wk8TdTBTjBEFJE6v89g/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/14.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n8piaGRXyw1omZD9RVA10W8bGY5gtwUuYDPhxRpX28wsIPVkBbHf0xRQ/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/15.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nIaeDGr8bGwA2Z6Libhib3SxAC7WEd7vyTia93sWicjjXrRia27uRBcqJVfw/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/16.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nV0LOcibZ6fs1RvJLaNXSTnjQ9Bg4WeCzictFOJxmsWSZq84UepT0kCVw/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/17.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nV0LOcibZ6fs1RvJLaNXSTnjQ9Bg4WeCzictFOJxmsWSZq84UepT0kCVw/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/18.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nWY2PNDB0B24774fdHN8K7nJibn5d1b4TyRpZqw2VS1LzIR6TWibKQcvQ/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/19.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nQoSXUz7iaDHTL1jsldoVjBibEKkfiaupT0OsIARdW8gF9EOR087W0P6icw/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/20.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nld1xuwOFs2k3agxw0Iic5gicV9vhkG46L9RFRU4XonclqYAQCGibqhciaw/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/21.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nAa9yrO4rfoZ5q8YElq0yVvwNNNYaoGt2KhKGfDWSRjy9oYV7iagZa7A/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/22.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4nSneJXLNzGfN0FIrrznvSRKsIxlwBbjianPgc5w06HrxHgVe2L0DG7IA/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/23.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n8WWnbUk5Pe2cBPuFII8jTTicBo9hKibgRB5PLoQ8LYdpZmcGycvZC8RA/0?wx_fmt=gif', $content);
	$content = str_replace('/static/image/weixineditor/background/24.gif', 'https://mmbiz.qlogo.cn/mmbiz_gif/P4v50ZTrHOvyyn0x2aOOqC9svZ3wJX4n6qkv25tiaSP5gUzkaFVib66Z1E3Zg7DsAaIibV0F4B2qWK4Iic0g3vPx0A/0?wx_fmt=gif', $content);
	//echo $content;exit;
	return $content;
}

/**
 * 发送post请求
 *
 * param	string
 * param	string
 * return	mix
 */
function curl_post($url, $vars)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);        
	curl_setopt($ch, CURLOPT_URL,$url);       
	curl_setopt($ch, CURLOPT_POST, 1); 
	//curl_setopt($ch, CURLOPT_TIMEOUT,10);
	curl_setopt($ch, CURLOPT_HEADER, 0) ;    
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);       
	$data = curl_exec($ch); 
	$error = curl_error($ch);
	curl_close($ch);
	//var_dump($data);exit;
	if ($data)     
		return $data;       
	else {
		var_dump($error);
		return false;
	}
}

function vpost($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    
	if($data) {
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	}

    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}

/**
 * 去除非中文字符
 *
 * param	string
 * return	string
 */
 function Rechina($str) {
	preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
	$str = implode('', $matches[0]);
	return $str;
 }

/**
 * 去除空格换行
 *
 * param	string
 * return	string
 */
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);   
}

function thumbname($str, $suffix="_s") {
	$arr = explode(".", $str);
	return $arr[0].$suffix.".".$arr[1];

}

/**
 * 返回文章来源
 *
 * param	string
 * return	string
 */
function retarticlesource($sourceid=1) {
	$retstr = "文章";
	if($sourceid==2)
		$retstr = "报刊";
	return $retstr;
}

/**
 * 返回群发状态
 *
 * param	string
 * return	string
 */
function retmassstatus($status=1) {
	$retstr = "等待群发";
	if($status==2)
		$retstr = "已群发";
	if($status==3)
		$retstr = "群发成功";
	if($status==4)
		$retstr = "已删除";
	if($status==-1)
		$retstr = "群发失败";
	return $retstr;
}

/**
 * 返回群发状态
 *
 * param	string
 * return	string
 */
function retwxmassstatus($status) {
	$retstr = "群发成功";
	if($status!="send success")
		$retstr = "群发失败:".$status;
	return $retstr;
}

/**
 * 获取用户微信信息
 *
 * @param	int		社区id
 * @return	array
 */
function getWeixinInfo($wid)
{
	$res = M('weixin')->where('id='.$wid)->find();
	return $res;
}

function _sprintf( $str, array $arr, $needle='%s') {
	if(empty($needle)) return '';
	$oarr   = explode($needle, $str);
	$count = count($oarr);

	$tstr  = '';
	$j     = 0;
	for($i=0; $i<($count-1); $i++) {
		$tstr .= $oarr[$i].$arr[$j];
	    $j++;
	}

	$tstr .= $oarr[$count-1];
	return $tstr;
	
}

function get_tags($title, $num = 10) {
	vendor('Pscws.Pscws4', '', '.class.php');
	$pscws = new PSCWS4();
	$pscws->set_dict(AFT_WIDGET . 'etc/dict.utf8.xdb');
	$pscws->set_rule(AFT_WIDGET . 'etc/rules.utf8.ini');
	$pscws->set_ignore(true);
	$pscws->send_text($title);
	$words = $pscws->get_tops($num);
	$pscws->close();
	$tags = array();
	foreach ($words as $val) {
		$tags[] = $val['word'];
	}
	return implode(',', $tags);
}

function getTree(&$products,&$result){		
		$tree			= array();
		foreach( $products as &$product){ 
        	if($product['pid']== 0){
        		$tree[] = &$product;           
        	}else{
           		$products[$product['pid']]['son'][] = &$product; 
        	}	
		}
		getTreeData($tree,$result); 
}
function getTreeData(&$tree,&$data){
		static $i = 0;
		$i++;
		$tmp ='';
		if($i>1){
			for($j=1;$j<$i-1;$j++){
				$tmp	.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
			}
				$tmp	.= '|----';
			}
			
		foreach($tree as &$value){
			$value['class'] = $tmp;
    		$data[$value['id']]			= &$value;
		    if(isset($value['son'])){
            	getTreeData($value['son'],$data);
           		unset($value['son']); 
            	$i--;
        	} 
    	}
}

function Weekname($timstamp) {
	$week = date("w", $timstamp);
	if($week=="1") $week = "一";
	if($week=="2") $week = "二";
	if($week=="3") $week = "三";
	if($week=="4") $week = "四";
	if($week=="5") $week = "五";
	if($week=="6") $week = "六";
	if($week=="0") $week = "日";
	return $week;
}

function numtochinese($str) {
	$arr["0"] = "零";
	$arr["1"] = "一";
	$arr["2"] = "二";
	$arr["3"] = "三";
	$arr["4"] = "四";
	$arr["5"] = "五";
	$arr["6"] = "六";
	$arr["7"] = "七";
	$arr["8"] = "八";
	$arr["9"] = "九";
	$zwstr = "";

	for($i=0; $i<strlen($str); $i++) {
		$zwstr = $zwstr.$arr[substr($str, $i, 1)];
	}
	return $zwstr;
}

function ceilremovezero($num) {
	$str = $num;
	if($num== (ceil($num/10)*10)) $str = $num/10;
	return $str;
}

function Tslot($tslot) {
	$str = "上午";
	if($tslot=="2") $str = "下午";
	return $str;
}

function serviceUrl($val) {
	$url = '';
	if($val=="resevsection") {
		 $url =  C('HTTP_DOMIN')."?m=ReserveBook&a=relist&wid=".I("get.wid")."&token=".I("get.token")."&wxref=mp.weixin.qq.com";
	}
	if($val=="message") {
		 $url =  C('HTTP_DOMIN')."?g=app&m=board&wid=".I("get.wid")."&token=".I("get.token")."&wxref=mp.weixin.qq.com";
	}
	if($val=="communityservice") {
		 $url =  C('HTTP_DOMIN')."?g=app&m=membercard&&wid=".I("get.wid")."&token=".I("get.token")."&wxref=mp.weixin.qq.com";
	}
	return $url;
}

//文件转换二进制流进行BASE64转换后生成MD5码
function mgReadFile($fileName) {
    if(is_readable($fileName)) {
        $handle = fopen($fileName,'rb');
        if(flock($handle,LOCK_SH)) {
            $data = fread($handle,filesize($fileName));
            fclose($handle);
            return md5(base64_encode($data));
        }else {
            return false;
        }
    }else {
        return false;
    }
    return false;
}

function format_url($url) {
	$url = str_replace("\\", "/", $url);
	$parseurlarr = parse_url($url);
	$exarr = explode("/", $parseurlarr['path']);
	$i=0;
	foreach($exarr as $key => $val) {
		if($val) {
			if($val=='..' && $i>0) {
				$i--;
			}else {
				$temp[$i] = $val;
				$i++;
			}
		}
	}
	$str = $parseurlarr['scheme']."://".$parseurlarr['host']."/".implode("/", $temp);
	return $str;
}

function getImg($url = "", $filename = "") {
	//去除URL连接上面可能的引号
	//$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
	$hander = curl_init();
	$fp = fopen($filename,'wb');
	curl_setopt($hander,CURLOPT_URL,$url);
	curl_setopt($hander,CURLOPT_FILE,$fp);
	curl_setopt($hander,CURLOPT_HEADER,0);
	curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($hander, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($hander, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
	curl_setopt($hander,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
	curl_setopt($hander,CURLOPT_TIMEOUT,60);
	curl_exec($hander);
	$data = curl_exec($hander);
	curl_close($hander);
	fclose($fp);
	Return true;
}

function GrabImage($url, $filename='', $path="./data/upload/") {
	$url = format_url($url);

	if($url=="") return false;

	if($path=="./data/upload/")
		$path .= date("Y")."/";
	if (!file_exists($path))
		mkdir($path);

	$path .= date("m")."/";
	if (!file_exists($path))
		mkdir($path);

	$path .= date("d")."/";
	if (!file_exists($path))
		mkdir($path);

	if($filename=="") { 
		$ext=strrchr($url,"."); 
		//if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") return false;
		if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") $ext=".jpg";
		$filename=substr(md5(date("YmdHis").rand().$ext), 0, 13).$ext; 
	}
	ob_start(); 
	readfile($url);
	$img = ob_get_contents(); 
	ob_end_clean();
	$size = strlen($img);
	$filename = $path.$filename;

	$fp2=@fopen($filename, "a"); 
	fwrite($fp2,$img); 
	fclose($fp2);

	return $filename; 
}

/*****
兼容webp格式
*****/
function GrabImagewebp($url, $filename='', $path="./data/upload/") { 
	if($url=="") return false;

	if($path=="./data/upload/")
		$path .= date("Y")."/";
	if (!file_exists($path))
		mkdir($path);

	$path .= date("m")."/";
	if (!file_exists($path))
		mkdir($path);

	$path .= date("d")."/";
	if (!file_exists($path))
		mkdir($path);

	if($filename=="") { 
		$ext=strrchr($url,"."); 
		//if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") return false;
		if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") {
			$purl = parse_url($url);
			parse_str($purl['query'], $parr);
			if($parr['tp']) {
				$ext = ".webp";
			}else {
				$ext = ".jpg";
			}
		}
		$randname = substr(md5(date("YmdHis").rand().$ext), 0, 13);
		$filename = $randname.$ext;
		if($ext==".webp") {
			$filetemp = $randname.".jpg";
		}
		
	}

	ob_start(); 
	readfile($url); 
	$img = ob_get_contents(); 
	ob_end_clean(); 
	$size = strlen($img);
	$filename = $path.$filename;

	$fp2=@fopen($filename, "a"); 
	fwrite($fp2,$img); 
	fclose($fp2);

	if($ext==".webp") {
		$convname = $path.$filetemp;
		$retname = D("Imagick")->convertformat($filename, $convname);
		$filename = $retname;
	}

	return $filename; 
}

function subscribetowz($codeid=1) {
	$str = "关注中";
	if($codeid==0)
		$str = "已取消关注";
	return $str;
}

function alert($str, $refurl='') {
	if(!$refurl) {
		echo "<script>alert('".$str."');history.back(-1);</script>";
	}else {
		echo "<script>alert('".$str."');location.href='".$refurl."'</script>";
	}
	exit;
}

/**
* 验证手机号是否正确
* @param INT $mobile
*/
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
		echo "fasle";
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}