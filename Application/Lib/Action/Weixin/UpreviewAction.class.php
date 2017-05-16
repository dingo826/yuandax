<?php
/**
 * 预览功能会员筛选控制器
 * xujiang@cekasp.com
 * 2015-01-14
 */

class UpreviewAction extends WeixinAction {

	public function __construct() {
		parent::__construct();
	}

	function Index() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$uplist = '';
		
		$temp = M("upreview")->where("wid='".$wid."'")->order("lasttime desc")->select();
		$i    = 1;
		foreach($temp as $key => $val) {
			$uplist[$val['openid']] = $i;
			$i++;
		}
		//print_r($uplist);exit;

		$where = "wid='".$wid."'";
		$list = M("app_mcard_member")->where($where)->select();
		foreach($list as $key => $val) {
			if($uplist[$val['wechatid']]) {
				$temp['id']         = $val['id'];
				$temp['wechatid']   = $val['wechatid'];
				$temp['name']       = $val['name'];
				$temp['headimgurl'] = $val['headimgurl'];
				$arr2[]             = $temp;

				//$str_2[$uplist[$val['wechatid']]] = "<li><label><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"40\">&nbsp;<input type=\"radio\" name=\"optionsRadios\" class=\"upopt\" value=\"".$val['wechatid']."\" /></td><td width=\"55\"><img src=\"".$val['headimgurl']."\" width=\"45\"></td><td width=\"425\" ><b>".$val['name']."</b>&nbsp;&nbsp;</td></tr></table></label></li>";
			}else {
				$temp['id']         = $val['id'];
				$temp['wechatid']   = $val['wechatid'];
				$temp['name']       = $val['name'];
				$temp['headimgurl'] = $val['headimgurl'];
				$arr3[]             = $temp;

				//$str_1[] = "<li><label><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"40\">&nbsp;<input type=\"radio\" name=\"optionsRadios\" class=\"upopt\" value=\"".$val['wechatid']."\"></td><td width=\"55\"><img src=\"".$val['headimgurl']."\" width=\"45\"></td><td width=\"425\" ><b>".$val['name']."</b>&nbsp;&nbsp;</td></tr></table></label></li>";
			}			
		}
		ksort($arr2);
		if($arr2) $arr = (array_merge($arr2, $arr3));
		else       $arr = $arr3;
		echo json_encode($arr, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
