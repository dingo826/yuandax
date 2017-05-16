<?php
/**
 * 回复业务模块展示图
 */
class BusitypeModel extends Model
{
	/**
	 * 获取业务模块对应的图片
	 *
	 * @param	str
	 * @return	array
	 */
	public function getBusiness($type) {
		$arr = array();
		switch($type){
		case "official":	// 微官网
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/official.jpg', 'Title'=>'进入社区官网', 'Description'=>'进入社区官网');
			break;
		case "vipcard":		// 居民卡	
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/card_cover1.jpg', 'Title'=>'进入社区居民卡', 'Description'=>'进入社区居民卡');
			break;
		case "resevsection":	// 微预约频道
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/head_pic.jpg', 'Title'=>'进入社区预约', 'Description'=>'进入社区预约');
			break;
		case "message":		// 微留言
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/twfm.jpg', 'Title'=>'进入社区留言', 'Description'=>'进入社区留言');
			break;
		case "albums":		// 微相册
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/albums_head_url.jpg', 'Title'=>'进入社区相册', 'Description'=>'进入社区相册');
			break;
		case "juweihui":		// 居委会
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/juweihui.jpg', 'Title'=>'进入居委会', 'Description'=>'进入居委会');
			break;
		case "communityservice":// 微服务
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/shequfuwu.jpg', 'Title'=>'进入社区服务', 'Description'=>'进入社区服务');
			break;
		case "circle":		// 微圈子
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/circle.jpg', 'Title'=>'进入圈子', 'Description'=>'进入圈子');
			break;
		case "survey":		// 微调研
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/survey.jpg', 'Title'=>'进入调研中心', 'Description'=>'进入调研中心');
			break;
		case "wxqun":		// 微信群
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default/wxqun.jpg', 'Title'=>'进入微信群', 'Description'=>'进入微信群');
			break;
		case "huodong":		// 报名活动
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default02/sqhd.jpg', 'Title'=>'进入活动报名', 'Description'=>'进入活动报名');
		case "vote":		// 投票
			$arr = array('PicUrl'=>'http://'.C('ODOMIN').'/Public/images/default02/sqtp.jpg', 'Title'=>'进入在线投票', 'Description'=>'进入在线投票');
			break;
		}

		return $arr;
	}

	/**
	 * 获取业务模块对应的URL
	 *
	 * @param	str
	 * @return	str
	 */
	public function getBusiurl($type, $wid, $token) {
		$url = '';
		switch($type){
		case "official":	// 微官网
			$url = "http://".C('ODOMIN')."/?g=wechat&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "vipcard":		// 居民卡	
			$url = "http://".C('ODOMIN')."/?g=wechat&m=member&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "resevsection":	// 微预约频道
			$url = "http://".C('ODOMIN')."/?g=wechat&m=reservation&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "message":		// 微留言
			$url = "http://".C('ODOMIN')."/?g=wechat&m=board&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "albums":		// 微相册
			$url = "http://".C('ODOMIN')."/?g=wechat&m=albums&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "juweihui":		// 居委会
			$url = "http://".C('ODOMIN')."/?g=wechat&m=juweihui&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "communityservice":// 微服务
			$url = "http://".C('ODOMIN')."/?g=wechat&m=communityservice&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "circle":		// 微圈子
			$url = "http://".C('ODOMIN')."/?g=wechat&m=circle&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "survey":		// 微调研
			$url = "http://".C('ODOMIN')."/?g=wechat&m=survey&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "wxqun":		// 微信群
			$url = "http://".C('ODOMIN')."/?g=wechat&m=wxqun&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		case "huodong":		// 活动报名
			$url = "http://".C('ODOMIN')."/?g=wechat&m=huodong&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
		case "vote":		// 投票
			$url = "http://".C('ODOMIN')."/?g=wechat&m=vote&wid=".$wid."&token=".$token."&wxref=mp.weixin.qq.com";
			break;
		}

		return $url;
	}

	function getarrBusi($data, $wxfrom='') {
		if($data["business_type"]=="huodonglist") {
			$blist  = M("app_huodong")->where("id IN (".$data['business_value'].")")->order("id desc")->select();
			$arrids = explode(",", $data['business_value']);
			 foreach($arrids as $key => $row) {
				 foreach($blist as $nkey => $nrow) {
					 if($nrow['id']==$row) {
						 $temp["Title"]       = $nrow["title"];
						 $temp["Description"] = $nrow["desc"];
						 $temp["PicUrl"]      = 'http://'.C('ODOMIN').$nrow["picurl"];
						 $temp['Url']         = "http://".C('ODOMIN')."/?g=wechat&m=huodong&a=detail&id=".$nrow['id']."&wid=".$nrow['wid']."&token=".$wxfrom['FromUserName']."&wxref=mp.weixin.qq.com";
						 $xtemp[]             = $temp;
					 }
				 }
			 }			 
		}
		$blist = '';
		$blist = $xtemp;
		return $blist;
	}

	function getoneBusi($data, $wxfrom='') {
		if($data["business_type"]=="huodonglist") {
			$onedate = M("app_huodong")->where("id IN (".$data['business_value'].")")->order("id desc")->find();
			$url     = "http://".C('ODOMIN')."/?g=wechat&m=huodong&a=detail&id=".$onedate['id']."&wid=".$onedate['wid']."&token=".$wxfrom['FromUserName']."&wxref=mp.weixin.qq.com";	 
		}

		return $url;
	}
	
}
?>
