<?php
/**
 * cli 方式执行群发信息
 * xujiang@cekasp.cn
 * 2015-01-16
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

//当前应用的路径，配合后面的图片上传
$droot = '/data01/web/'.$config['DIRECTORY'];

//微信token map
$tokenMap = array();
$today = strtotime(date("Y-m-d"));

// 获取未发送的记录,状态为等待，预约发送时间小于当前时间
$sql = "select * from ".$config['DB_PREFIX']."app_massrecord where status='w' and sendtime>='".$today."' and sendtime < ".time();
$res = mysql_query($sql);

if($res) {
	while($row = mysql_fetch_assoc($res)){
		if( !isset($weixin['wid']) ){
			// 获取微信信息
			$sql = "select * from ".$config['DB_PREFIX']."weixin where id=".$row['wid'];
			$wres = mysql_query($sql);
			$weixin = mysql_fetch_assoc($wres);

			$sql = "select * from ".$config['DB_PREFIX']."weixin_token where wid=".$weixin['id'];
			$tkres  = mysql_query($sql);
			$tkinfo = mysql_fetch_assoc($tkres);
			if($tkinfo) {
				if(time()-(int)$tkinfo["uptime"]>=7140){
					$tokenMap[$weixin['id']] = getAccessToken($weixin);
					$upSql = "update `".$config['DB_PREFIX']."weixin_token` set `token`='".$tokenMap[$weixin['id']]."', `uptime`='".time()."' where `wid`=".$weixin['id'];
					mysql_query($upSql);
				}else {
					$tokenMap[$weixin['id']] = $tkinfo['token'];
				}
			}else {
				$tokenMap[$weixin['id']] = getAccessToken($weixin);
				$upSql = "insert into `".$config['DB_PREFIX']."weixin_token` (`wid`, `token`, `uptime`) value ('".$weixin['id']."', '".$tokenMap[$weixin['id']]."', '".time()."')";
				mysql_query($upSql);
			}
		}

		// 生成群发数据
		//$status = sendMass($row, true, 'oZPTUjtCw4VSDvh4BKa2S35S3J-s');	// debug
		//$status = sendMass($row, true, 'oHo_ujgztn-3cU9X6Zo3h0efpFQI');
		$status = sendMass($row);

		// 更新当前记录发送状态
		$upSql = "update `".$config['DB_PREFIX']."app_massrecord` set `status`='$status' where `id`=".$row['id'];
		mysql_query($upSql);
		if($status == 't')
			echo "成功\n";
		else
			echo "失败\n";
	}
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
		$mediaId = assemblyNews($data['material_ids'], $data['cover_id'], $data['wid']);
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
	$sql = "select * from `".$config['DB_PREFIX']."app_article` where id in(".$ids.")";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$list[] = $row;
	}
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
	global $tokenMap, $droot, $config;
	$mpnewsJson = array();
	foreach($newsList as $key => $val){
		$onestr = substr($val['picurl'], 0, 1);
		if($onestr=='/') $file = $droot.$val['picurl'];
		else $file = $droot.'/'.$val['picurl'];

		$show_cover_pic = 1;
		if($val['show_cover_pic']==-1) $show_cover_pic = 0;

		$sourceurl = '';
		//if($val['type']) $sourceurl = getBusurlCli($val);

		if($val['type']) {
			if($val['type']=='business' && $val['business_type']=='huodonglist') $sourceurl = getoneBusi($val);
			else $sourceurl = getBusurl($val);
		}

		$file = createThump($file);
		$data = array('media' => '@'.$file);
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$tokenMap[$wid]."&type=thumb";
		$res = curl_post($url, $data);
		$res = json_decode($res, true);
		if( !isset($res['thumb_media_id']) ) return false;
		if( $val['id'] == $cover_id ){
			// 封面
			//$show_cover_pic = 1;
			$coverKey = $key;
		}else{

			$show_cover_pic = 0;
		}

		$val['content'] = htmlspecialchars_decode($val['content']);

		$val['content'] = preg_replace("/(src=[\"|\'])(http:\/\/".$config['ODOMIN'].")(\/[^\"|\']+)([\"|\'])/isU", "$1$3$4", $val['content']);
		$val['content'] = contentHandle($val['content'], $tokenMap[$wid]);
		//$val['content'] = preg_replace("/(src=[\"|\'])(\/[^\"|\']+)([\"|\'])/isU", "$1".$config['HTTP_STCDOMIN']."$2$3", $val['content']);
		
		//组装图文内容
		$articles[$key] = array(
			'thumb_media_id'     => $res['thumb_media_id'],
			'author'             => '社区',
			'title'              => $val['title'],
			'content_source_url' => $sourceurl,
			'content'            => $val['content'],
			'digest'             => $val['desc'],
			'show_cover_pic'     => (string)$show_cover_pic,
		);
	}

	// 封面新闻插入首位
	$coverItem = $articles[$coverKey];
	unset($articles[$coverKey]);
	array_unshift($articles, $coverItem);
	$mpnewsJson['articles'] = $articles;
	$mpnewsJson = json_encode($mpnewsJson, JSON_UNESCAPED_UNICODE);
	//$mpnewsJson = json_encode($mpnewsJson);
	//$mpnewsJson = urldecode($mpnewsJson);
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


/**
 * 获取模块父类型的url
 *
 * @return	string
 */
function getBusurlCli($data) {
	global $tokenMap, $droot, $config;
	switch($data['type']) {
		case 'article':
		    $url = "http://".$config["ODOMIN"].'/?g=wechat&m=channel&a=index&wid='.$data['wid'].'&id='.$data['id'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'link':
			$url = "http://".$data['link'];
		    break;
		case 'tel':
			$url = "tel:".$data['tel'];
		    break;
		case 'business':
			$url = "http://".$config["ODOMIN"].getBusinessurlCli($data);
		    break;
		case 'isempty':
			$url = '';
		    break;
		default:
		    $url = "http://".$config["ODOMIN"].'/?g=wechat&m=channel&a=index&wid='.$data['wid'].'&id='.$data['id'].'&wxref=mp.weixin.qq.com';
		    break;
	}
	return $url;
}

/**
 * 获取模块具体业务模块的url
 *
 * @return	string
 */
function getBusinessurlCli($data) {
	global $tokenMap, $droot, $config;
	switch($data['business_type']) {
		case 'official':
		    $url = '/?g=wechat&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'juweihui':
		    $url = '/?g=wechat&m=juweihui&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'communityservice':
		    $url = '/?g=wechat&m=communityservice&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'albums':
		    $url = '/?g=wechat&m=albums&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'survey':
		    $url = '/?g=wechat&m=survey&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'resevsection':
		    $url = '/?g=wechat&m=reservation&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'message':
		    $url = '/?g=wechat&m=board&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'circle':
            $url = '/?g=wechat&m=circle&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'wxqun':
		    $url = '/?g=wechat&m=wxqun&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		case 'huodong':
		    $url = '/?g=wechat&m=huodong&a=index&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
		default:
		    $url = '/?g=wechat&wid='.$data['wid'].'&wxref=mp.weixin.qq.com';
		    break;
	}
	return $url;
}

function getoneBusi($data, $wxfrom='') {
	global $db, $config;
	if($data["business_type"]=="huodonglist") {
		$gobsql  = "select * from ".$config['DB_PREFIX']."app_huodong where id IN (".$data['business_value'].")";
		$gobres  = mysql_query($gobsql);
		$onedate = mysql_fetch_assoc($gobres);
		$url     = "http://".$config["ODOMIN"]."/?g=wechat&m=huodong&a=detail&id=".$onedate['id']."&wid=".$onedate['wid']."&token=".$wxfrom['FromUserName']."&wxref=mp.weixin.qq.com";	 
	}

	return $url;
}
?>
