<?php
class CustommenuAction extends Weixinv3Action {
	private	$_Model;	// 主库

	public function __construct() {
		parent::__construct();
		$this->_Model = M('app_custommenu');
	}

    public function index(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

		$list = $this->_Model->where("wid='".$wid."'")->order('parent_id asc,sort desc')->select();
    	if ($list){
    		foreach ($list as $val){
    			if ($val['parent_id']==0){
    				foreach ($list as $v){
    					if ($v['parent_id']==$val['id']){
    						$val['child'][]=$v;
    					}
    				}
    				$tlist[]=$val;
    			}
    		}
    	}
    	$list = $tlist;

		$this->assign('list', $list);		
		$this->display($this->Base_themplate);
    }

	function post() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($_POST) {
			$post = $this->_post();
			foreach($post['new']['key'] as $k => $v) {
				if(strstr(strtolower($v), "http://")) {
					$post['new']['type'][$k] = 2;
				}else {
					$post['new']['type'][$k] = 1;
				}
			}

			foreach($post['ps']['key'] as $k => $v) {
				if(strstr(strtolower($v), "http://")) {
					$post['ps']['type'][$k] = 2;
				}else {
					$post['ps']['type'][$k] = 1;
				}
			}

			foreach ($post['new']['type'] as $key=> $val){
    			foreach ($post['new'] as $k=>$v){
    				$tmpdata[$k]      = $v[$key];
    			}
				$tmpdata['wid']   = $wid;
    			$tmpdata["etime"] = $tmpdata['ctime']=time();
    			$data[]=$tmpdata;
    			unset($tmpdata);
    		}

			if ($data){
    			$this->_Model->addAll($data);
    		}

			foreach ($post['ps'] as $uk=>$uv){
				if(strstr(strtolower($uv['key']), "http://")) {
					$uv['type'] = 2;
				}else {
					$uv['type'] = 1;
				}

    			if (!$uv['is_show']){
    				$uv['is_show']=0;
    			}

    			$uv['etime']=time();
    			$this->_Model->where("id='".$uk."' and wid='".$wid."'")->save($uv);
    		}
		}
		header("Location: ".U('custommenu/index'));
		exit;
	}

	function del() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		$id  = intval(I('get.id'));
		$this->_Model->where("id='".$id."' and wid='".$wid."'")->delete();
		header("Location: ".U('custommenu/index'));
		exit;
	}

	public function createmenu(){
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));

    	$list = $this->_Model->where("wid='".$wid."' and is_show=1")
    	    	->order('parent_id asc,sort desc')->select();
   	 	if(!$list){
			$return['status']= -1;
    		$return['error']='请先添加有效启用的菜单';
    		$this->ajaxReturn($return);
    		exit;
		}
		foreach ($list as $val){
			if ($val['parent_id']==0){
				$tval['name']=urlencode($val['name']);
				foreach ($list as $v){
					if ($v['parent_id']==$val['id']){
						if ($v['type']==2){
							$tmpv['type']='view';
							$tmpv['url'] = urlencode($v['key']);
						}else {
							$tmpv['type']='click';
							$tmpv['key']=urlencode($v['key']);
						}
						$tmpv['name'] = urlencode($v['name']);
						$tval['sub_button'][]=$tmpv;
						unset($tmpv);
					}
				}
				if (count($tval['sub_button'])>5){
					$return['status']= -1;
					$return['error']='2级菜单最多只能开启5个';
					$this->ajaxReturn($return);
					exit;
				}
				if (!$tval['sub_button']){
					if ($val['type']==2){
						$tval['type']='view';
						$tval['url']=urlencode($val['key']);
					}else {
						$tval['type']='click';
						$tval['key']=urlencode($val['key']);
					}
				}
				$tlist[]=$tval;
				unset($tval);
			}
		}

		if (count($tlist)>3){
			$return['status']= -1;
			$return['error']='1级菜单最多只能开启3个';
			$this->ajaxReturn($return);
			exit;
		}


		$weixin = D("Weixin");
		$access_token  = $weixin->getToken($wid);
		if (!$access_token){
			$return['status'] = -2;
			$return['error']  = '获取access_token失败请检查授权设置是否正确填写';
			$this->ajaxReturn($return);
			exit;
		}
		//post提交菜单

		$creat_menu_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$postresult     = $this->http_post($creat_menu_url, urldecode(json_encode(array('button'=>$tlist))));
		if ($postresult){
			$postjson = json_decode($postresult,true);
			if (!$postjson||isset($postjson['errcode'])){
				$return['status'] = $postjson['errcode'];
				$return['error'] = $postjson['errmsg'];
			}else {
				$return['status'] = 0;
				$return['error']  = '生成成功';
				$return['url']    = U('custommenu/index');
			}
		}else {
			$return['status'] = -1;
			$return['error']  = '系统异常';
		}
		$this->ajaxReturn($return);
		exit;    	
    }

	private function http_post($url,$param){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if (is_string($param)) {
			$strPOST = $param;
		} else {
			$aPOST = array();
			foreach($param as $key=>$val){
				$aPOST[] = $key."=".urlencode($val);
			}
			$strPOST =  join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($oCurl, CURLOPT_POST,true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}
}