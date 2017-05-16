<?php
/**
 * 代替社区群发信息
 * xujiang@cekasp.cn
 * 2015-06-23
 */
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
define("SYS_PATH", dirname(dirname(dirname(__FILE__))));
define('DS',       '/');

// 引入公共方法文件
$config = require_once(SYS_PATH.DS.'Application'.DS.'Conf'.DS.'config.php');
require_once(SYS_PATH.DS.'Application'.DS.'Common'.DS.'common.php');

// 引入群发数据生成类
require_once(SYS_PATH.DS.'Application'.DS.'Lib'.DS.'Model'.DS.'MassModel.class.php');

// 引入缩略图处理类
require_once(SYS_PATH.DS.'Application'.DS.'Lib'.DS.'Model'.DS.'ImagickModel.class.php');
require_once(SYS_PATH.DS.'Application'.DS.'Lib'.DS.'Model'.DS.'ImagickManageModel.class.php');
require_once(SYS_PATH.DS.'Application'.DS.'Lib'.DS.'Model'.DS.'ImagickManageCli.class.php');
$imagick = new ImagickManageCli();
$imagick->setImagick(new ImagickModel());

$mass = new MassModel();

// 链接数据库
$db = mysql_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD']);
mysql_select_db($config['DB_NAME']);
mysql_query('SET NAMES utf8');

//微信token map
$tokenMap = array();
//$dataProId = '349';	// 群发数据提供方id


// 准备代群发社区信息
$comList = getWaitCommunity();
if( empty($comList) ){
	echo "暂无代发社区------".time()."\n";exit;
}
// 准备群发信息

/*测试数据 $_sql="select * from ".$config['DB_PREFIX']."weixin where id=349";
$_result=mysql_query($_sql,$db);
$comList[0]=mysql_fetch_assoc($_result); */

// 开始群发
foreach($comList as $wid => $weixin){
	//先检查已发是否少于频率 或者 上次发送为上周
	$flag=check_numsDefault($weixin['scqf']);
	
	if($flag==false){
		if((int)$weixin['nums']>=(int)$weixin['pinlv']){
			echo "$weixin[id]发送已经达到本周频率";
		    continue;
		}
	}/*  else{
		$backtime=((int)date('N')-1)*86400;
	    $thisMonday=(int)strtotime(date("Y-m-d"))-$backtime;
		echo $thisMonday;
		echo ','.date("Y-m-d H:i:s",$thisMonday);
		echo ','.$weixin['scqf'];
		if((int)$weixin['scqf']==$thisMonday){
			echo "eq";
		}
	}exit; */

	$massInfo = massInfoPrepare($weixin['province'], $weixin['city']);
	//print_r($massInfo);exit;
	if( empty($massInfo) ){
		echo "暂无可用数据------".time()."\n";continue;
	}

	$tokenMap[$weixin['id']] = getAccessToken($weixin);
	$massInfo['wid']         = $weixin['id'];

	// 生成群发数据
	$status = sendMass($massInfo);
	//$status = 't';

	// 更新当前记录发送状态
	//$upSql = "update `".$config['DB_PREFIX']."app_massrecord` set `status`='$status' where `id`=".$row['id'];
	//mysql_query($upSql);
	if($status == 't') {
		$nowday = strtotime(date("Y-m-d"));
		//检查是否为上周 上周则设置发送数为1 不然则+1
		if($flag){
			$nums=", `nums`='1'";
		}else{
			$nums=", `nums`=`nums`+1";
		}
		$upSql = "update `".$config['DB_PREFIX']."weixin` set `scqf`='".$nowday."'".$nums." where `id`=".$weixin['id'];
		//$insql = "Insert into `".$config['DB_PREFIX']."app_massrecord` (wid, material_ids, cover_id, type, sendtime, status, ctime, etime, sfxt) value ";
		//$insql .= "('".$weixin['id']."', '".$massInfo['newsid']."', '".$massInfo['topid']."', '".$massInfo['type']."', '".time()."', 't', '".time()."', '".time()."', '2')";
		//echo $upSql;exit;
		mysql_query($upSql);
		//mysql_query($insql);
		echo "成功\t$weixin[id]\n";
	}else {
		echo "失败\t$weixin[id]\n";
	}
}
exit;

/**
 * 待发送社区准备
 *	说明：检查是否存在允许代发信息社区，
 *		并检查当天是否已有群发记录，
 *		且已到代发时间点
 */
function getWaitCommunity()
{
	global $db, $dataProId, $config;

	$nowday = strtotime(date("Y-m-d"));
	$begin = strtotime(date('Y-m-d',time()));	// 今天开始时间
	$end = strtotime(date('Y-m-d 23:59:59',time()));	// 今天结束时间
	$hours = date('H');	// 当前小时数
	$minutes = date('i');	// 当前分钟数
	$curSeconds = $hours * 60 * 60 + $minutes * 60;	// 当天已过秒数

	// 是否有允许代发信息社区
	//$sql = "select * from ".$config['DB_PREFIX']."weixin where istuog=1 and id <> ".$dataProId;
	$sql = "select * from ".$config['DB_PREFIX']."weixin where istuog=1 and scqf<>".$nowday;
	$res = mysql_query($sql, $db);
	$list = array();
	
	while($row = mysql_fetch_assoc($res)){

		// 允许代发时间点是否已到
		$seconds = $row['dhour'] * 60 * 60 + $row['dmin'] * 60;
		if($curSeconds >= $seconds)
			$list[$row['id']] = $row;
	}


	// 当天没有已群发或预约群发信息
	$unavailable = null;
	$sql = "select wid from ".$config['DB_PREFIX']."app_massrecord where sendtime >= ".$begin." and sendtime <= ".$end." group by wid";
	$res = mysql_query($sql, $db);
	while($row = mysql_fetch_assoc($res)){
		$unavailable[$row['wid']] = 1;
	}

	$newList = isset($unavailable) ? array_diff_key($list, $unavailable) : $list;
	return $newList;
}

/**
 * 群发数据准备
 *	取数据提供方最近一次群发数据信息
 */
function massInfoPrepare($prov, $city)
{
	global $db, $dataProId, $config;
	$nowday = strtotime(date("Y-m-d"));
	$info = array();
	$sql = "select * from ".$config['DB_PREFIX']."send where sdate = '".
		$nowday."' and province='".$prov."' and city='".$city."' order by id desc limit 0,1";
	$res = mysql_query($sql, $db);
	$info = mysql_fetch_assoc($res);
	return $info;
}

/**
 * 发送群发数据
 *
 * @param	array	群发记录信息
 * @param	boole
 * @param	str		用户id
 * @return	str		发送状态
 */
function sendMass($data, $debug=false, $openId='')
{	
	global $mass, $tokenMap;
	if($debug){
		// 测试模式，发送给测试用户
		$mass->setPreview($openId);
	}else{
		$mass->setMassType('group')
			->setIsToAll(true);
	}

	// 设置数据类型与数据内容
	if($data['type'] == 'text'){
		// 文本模式
		$mass->setMsgType('text')
			->setContents($data['content']);
	}elseif($data['type'] == 'mpnews'){
		// 图文模式
		$mediaId = assemblyNews($data['newsid'], $data['topid'], $data['wid']);
		if(false === $mediaId) {
			echo "图片上传处理失败！";exit;
		}

		$mass->setMsgType('mpnews')
			->setMediaId($mediaId);
	}

	// 生成群发详情
	$massInfo = $mass->getPostData();

	// 发送
	if($debug)
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$tokenMap[$data['wid']];
	else                                                                                                                    
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$tokenMap[$data['wid']];
	$res = curl_post($url, $massInfo);
	$res = json_decode($res, true);
	if(!$res['errcode'])
		$status = 't';
	else
		$status = 'f';
	return $status;
}

/**
 * 创建图文，生成多媒体id
 *
 * @param	str		图文id列表
 * @param	int		封面id
 * @param	int		社区id
 * @return	str		mediaid
 *
 */
function assemblyNews($material_ids, $cover_id, $wid)
{
	global $tokenMap;

	// 获取图文
	$newsList = getDetailedList($material_ids);

	// 生成图文内容
	$mpnewsJson = createMpnewsContents($newsList, $cover_id, $wid);
	if(false === $mpnewsJson) return false;

	// 上传图文获取图文id
	$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$tokenMap[$wid];
	$res = curl_post($url, $mpnewsJson);
	$res = json_decode($res, true);
	if( isset($res['media_id']) )
		return $res['media_id'];    // 成功返回图文消息id
	else
		return false;
}

/**
 * 获取图文详情
 *
 * @param	str
 * @return	array
 */
function getDetailedList($ids)
{
	global $db, $config;
	//$sql = "select * from `".$config['DB_PREFIX']."app_ptext` where id in(".$ids.")";
	$sql = "select * from `".$config['DB_PREFIX']."sendnews` where id in(".$ids.")";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$list[] = $row;
	}
	$arr = explode(',', $ids);
	$temparr = '';
	foreach($list as $val) {
		$weiz = array_keys($arr, $val['id'], true);
		$temparr[$weiz[0]] = $val;
	}
	ksort($temparr);
	$list = $temparr;
	return $list;
}

/**
 * 上传图片获取图片多媒体id
 * 并组装图文结构
 *
 * @param	array
 * @param	int
 * @param	int
 * @return	str
 */
function createMpnewsContents($newsList, $cover_id, $wid)
{
	global $tokenMap, $config;
	$mpnewsJson = array();
	foreach($newsList as $key => $val){
		//print_r($val);//exit;
		$file = SYS_PATH.DS.$val['pic'];
		//echo $file;exit;
		$file = createThump($file);
		$data = array('media' => '@'.$file);
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$tokenMap[$wid]."&type=image";
		$res = curl_post($url, $data);
		$res = json_decode($res, true);
		//print_r($res);exit;
		
		//if( !isset($res['thumb_media_id']) ) return false;
		if( !isset($res['media_id']) ) return false;
		if( $val['id'] == $cover_id ){
			// 封面
			$show_cover_pic = 1;
			$coverKey = $key;
		}else{

			$show_cover_pic = 0;
		}
		$val['content'] = preg_replace("/(src=[\"|\'])(http:\/\/".$config['ODOMIN'].")(\/[^\"|\']+)([\"|\'])/isU", "$1$3$4", $val['content']);
		$val['content'] = contentHandle($val['content'], $tokenMap[$wid]);
		//$val['content'] = preg_replace("/(src=[\"|\'])(\/[^\"|\']+)([\"|\'])/isU", "$1http://".$config['ODOMIN']."$2$3", $val['content']);
		
		//组装图文内容
		$articles[$key] = array(
			//'thumb_media_id' => $res['thumb_media_id'],
			'thumb_media_id' => $res['media_id'],
			'author'    => urlencode(addslashes('社区')),
			'title'     => urlencode(addslashes($val['title'])),
			//'content_source_url' => $val['link'],
			'content_source_url' => '',
			'content'   => urlencode(addslashes(htmlspecialchars_decode($val['content']))),
			'digest'    => urlencode(addslashes($val['desc'])),
			'show_cover_pic' => (string)$show_cover_pic,
		);
	}

	// 封面新闻插入首位
	$coverItem = $articles[$coverKey];
	unset($articles[$coverKey]);
	array_unshift($articles, $coverItem);
	$mpnewsJson['articles'] = $articles;
	//$mpnewsJson = json_encode($mpnewsJson, JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE 是php 5.4 才支持的
	$mpnewsJson = json_encode($mpnewsJson);
	$mpnewsJson = urldecode($mpnewsJson);
	return $mpnewsJson;
}

/**
 * 检查并生成缩略图
 * 
 * @param	string	原图地址
 * @return	mix		缩略图地址/false 失败
 */
function createThump($file)
{
	global $imagick;

	// 检查是否存在缩略图，存在则使用
	$status = $imagick->checkThump($file);
	if(false !== $status) return $status;

	// 缩略图不存在生成后返回
	$file = $imagick->createThump($file);
	return $file;
}

//检测是否为本周
function check_numsDefault($scqf){
	$backtime=((int)date('N')-1)*86400;                     //获取周一回退时间戳
	$thisMonday=(int)strtotime(date("Y-m-d"))-$backtime;    //获取周一时间戳
	$last=(int)$scqf;
	if($last<$thisMonday){                             //比较上次发送的时间是否为本周 大于周一为本周小于则为上周
		return true;
	}else{
		return false;
	}
}
?>
