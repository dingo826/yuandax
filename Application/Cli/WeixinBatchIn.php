<?php
/**
 * cli 方式执行微信关注用户批量入住
 * xujiang@cekasp.cn
 * 2015-03-18
 */
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
define("SYS_PATH", dirname(dirname(dirname(__FILE__))));
define('DS',       '/');

// 引入公共方法文件
$config = require_once(SYS_PATH.DS.'Application'.DS.'Conf'.DS.'config.php');
require_once(SYS_PATH.DS.'Application'.DS.'Common'.DS.'common.php');

// 引入获取用户基本信息接口模块
require_once(SYS_PATH.DS.'Application'.DS.'Lib'.DS.'Model'.DS.'WeixinUserBasicModel.class.php');
$wxUserBasic = new WeixinUserBasicModel();

// 链接数据库
$db = mysql_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD']);
mysql_select_db($config['DB_NAME']);
mysql_query('SET NAMES utf8');

$wid = $argv[1];	// 平台id

$sql = "select * from `".$config['DB_PREFIX']."weixin` where `id`=".$wid;
$res = mysql_query($sql);
$weixin = mysql_fetch_assoc($res);
$token = getAccessToken($weixin);

// 获取该账户的商户id
$sql = "select `id` from `".$config['DB_PREFIX']."app_qiyeoa_zp_company_info` where `aid`=".$wid;
$res = mysql_query($sql);
$row = mysql_fetch_assoc($res);
$bid = $row['id'];

// 获取该账户的关注用户
$sql = "select * from `".$config['DB_PREFIX']."subscribe` where `wid`=".$wid." group by open_id";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)){
	$data = $wxUserBasic->getUserBasicInfo($row['open_id'], $token);
	if(isset($data['errmsg'])) continue;	// 非关注状态无法获取

	// 检查用户是否存在，已存在更新。不存在添加
	$cksql = "select `id` from `".$config['DB_PREFIX']."app_mcard_member` where `wechatid`='".
		$data['openid']."' and `bid`=".$bid;
	$ckres = mysql_query($cksql);
	$ckrow = mysql_fetch_assoc($ckres);
	$region = $data['country'].'-'.$data['province'].'-'.$data['city'];
	$headimgurl = preg_replace("/0$/i", "64", $data['headimgurl']);

	if($ckrow['id'] > 0){
		// 更新
		$upSql = "update `".$config['DB_PREFIX']."app_mcard_member` set `name`='".
			$data['nickname']."',`sex`=".$data['sex'].",`region`='".
			$region."',`headimgurl`='".$headimgurl."' where `id`=".$ckrow['id'];
		mysql_query($upSql);
	}else{
		// 入住
		$cardSql = "select * from `".$config['DB_PREFIX']."app_mcard_member` where `bid`=".$bid." order by id desc limit 0,1";
		$cardRes = mysql_query($cardSql);
		$user = mysql_fetch_assoc($cardRes);
		if(!$user) {
			$cardnum = 100001;
		}else{
			$cardnum = $user['cardnum']+1;
			preg_match("[4]", $cardnum, $b);
			if(!empty($b)) {
				$cardnum = strtr($cardnum, 4, 5);
			}
		}

		$inSql = "insert into `".$config['DB_PREFIX']."app_mcard_member`(`bid`,`wechatid`,".
			"`cardnum`,`name`,`sex`,`region`,`headimgurl`) values($bid,'".
			$data['openid']."',$cardnum,'".$data['nickname'].
			"',$data[sex],'".$region."','".$headimgurl."')";
		mysql_query($inSql);
	}
}

?>
