<?php
function site_webslide() {
	$wxinfo = session("wxinfo");
	$wid = intval($wxinfo['id']);
	$webslide   = M("app_webslide")->where("`wid`='".$wid."' and is_show=1 and isindex=1")->order("sort desc")->select();
	foreach($webslide as $key => $val) {
		$webslide[$key]['url'] = getBusurl($val);
	}
	return $webslide;
}

function site_channelwebslide() {
	$wxinfo = session("wxinfo");
	$wid = intval($wxinfo['id']);
	$webslide   = M("app_webslide")->where("`wid`='".$wid."' and is_show=1 and ischannel=1")->order("sort desc")->select();
	foreach($webslide as $key => $val) {
		$webslide[$key]['url'] = getBusurl($val);
	}
	return $webslide;
}

function site_category($pid = 0) {
	$wxinfo = session("wxinfo");
	$wid = intval($wxinfo['id']);
	$where = "`wid`='".$wid."' and is_home=1";
	if($pid>0) $where .= " and category_id=".$pid;
	else $where .= " and category_id=0";
	$category   = M("app_category")->where($where)->order("sort desc")->select();
	foreach($category as $key => $val) {
		$category[$key]['url'] = getBusurl($val);
	}
	return $category;
}