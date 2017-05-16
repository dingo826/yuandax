<?php
class IndexAction extends ApiAction {
	private $data_array;
	private $arr;
	private $campaign_arr;
	private $wid;
	private $member;
	function __construct() {
		parent::__construct();
	}
    public function index(){
		$data  = $this->Linwechat->Receive()->_get();
		$this->data_array = $data;
		$weixin = M('Weixin');
		$wid = $weixin->where("tcode='".I('get.u')."'")->find();
		if ($wid){
			$this->wid = $wid['id'];
		}else {
			exit;
		}
    	$this->write();
		if(method_exists('IndexAction',$data['MsgType'])){
			$this->$data['MsgType']();
		}
    }
    
    /**
     * 将接收信息写入数据库
     */
    public function write(){
    	$alltype = array('text','image','link','location','video','voice','event','shortvideo');
		
    	if(in_array($this->data_array['MsgType'],$alltype)){
			$this->data_array['AddTime']=time();
			$this->data_array['weixinid'] = $this->wxinfo['id'];
			
			//获取关注用户mid
			$tinfo = $this->_getMember();
			$this->data_array['mid'] = $tinfo['id'];
			
			file_put_contents("jslogs.txt", json_encode($this->data_array), FILE_APPEND);
			$receive = M('Receive_'.$this->data_array['MsgType']);
			$receive->data($this->data_array)->add();
			
			
			$_type = array('text','image','video','voice','shortvideo');
		    $_typeid = array('text'=>1,'image'=>2,'voice'=>3,'video'=>4,'shortvideo'=>5);
			if(in_array($this->data_array['MsgType'],$_type)){
				$this->data_array['MsgType']=$_typeid[$this->data_array['MsgType']];
				
				//语音文字识别
				if($this->data_array['MsgType']==3 && !empty($this->data_array['Recognition'])){
					$this->data_array['Content'] = $this->data_array['Recognition'];
				}
				
				//解析emoji表情
				if($this->data_array['MsgType']==1){
					$tmpStr = json_encode($this->data_array['Content']);
					$tmpStr = preg_replace("#(\\\ue[0-9a-f]{3})#ie","addslashes('\\1')",$tmpStr);
					$this->data_array['Content'] = json_decode($tmpStr);
					
					//解析QQ默认为文字表情
					load("@.qqfacedecode");
					$this->data_array['Content']=qqface_convert_html($this->data_array['Content']);
				}
				
				$_recieve = M("member_message");
			    $_recieve->data($this->data_array)->add();
			}
		}
    }
    
    /**
     * 文本信息的自动回复
     */
    public function text() {
    	$keyname   = $this->data_array['Content'];

		$tmpKeyname = preg_replace("/\s/i", "", $keyname);
		$tmpKeyname = str_replace("—", "-", $tmpKeyname);
		if( false !== strpos($tmpKeyname, "报名-") )      //报名功能
			$keyname = "报名";
		
    	$keywords  = M('app_custom_reply');
    	$gzid      = $this->data_array["ToUserName"];
    	$where     = "`wid`='".$this->wid."' and (keyword='".$keyname."' or keyword like '".$keyname." %' or keyword like '% ".$keyname."' or keyword like '% ".$keyname." %')";
    	$detail    = $keywords->where($where)->find();

		if($detail) {
			$xmldata['ToUserName']   = $this->data_array['FromUserName'];
			$xmldata['FromUserName'] = $this->data_array['ToUserName'];
			$xmldata['CreateTime']   = time();

			if($detail['type']=="text") {
				$xmldata['MsgType']  = 'text';
				$xmldata['Content']  = htmlspecialchars_decode($detail['text']);
			}elseif($detail['type']=="article") {
				$newslist = M("app_article")->field("id, desc, title, picurl, link")->where("wid=".$this->wid." and id IN (".$detail['news_ids'].")")->select();
				if($newslist) {
					$arrids = explode(",", $detail['news_ids']);
					foreach($arrids as $key => $row) {
						foreach($newslist as $nkey => $nrow) {
							if($nrow['id']==$row) {
								$temp[$key] = $nrow;
							}
						}
					}
					$newslist = $temp;
					$xmldata['MsgType']       = 'news';
					$xmldata['ArticleCount']  = count($newslist);
					foreach($newslist as $key => $row) {
						$temp["Title"]       = $row["title"];
						$temp["Description"] = $row["desc"];
						$temp["PicUrl"]      = 'http://'.C('ODOMIN').$row["picurl"];
						$temp['Url']         = "http://".C('ODOMIN')."/?g=wechat&m=article&id=".$row['id']."&wid=".$this->wid."&token=".$this->data_array['FromUserName']."&wxref=mp.weixin.qq.com";
						$xmldata['Articles'][] = $temp;
					}
				}
			}elseif($detail['type']=="business") {
				$xmldata['MsgType']       = 'news';
				$xmldata['ArticleCount']  = 1;
				if($detail["business_type"]=="huodonglist") {
					$arr = D("Busitype")->getarrBusi($detail, $this->data_array);
					$xmldata['ArticleCount']  = count($arr);
					$xmldata['Articles'] = $arr;
				}else {
					$arr = D("Busitype")->getBusiness($detail['business_type']);
					$arr['Url'] = D("Busitype")->getBusiurl($detail['business_type'], $this->wid, $this->data_array['FromUserName']);
					$xmldata['Articles'][] = $arr;
				}
			}
		}else {
			if($this->wxinfo['nomatchrtype']>0) {
				$nomatch = M('app_nomatch')->where("wid='".$this->wid."'")->find();
				$xmldata['ToUserName']   = $this->data_array['FromUserName'];
			    $xmldata['FromUserName'] = $this->data_array['ToUserName'];
			    $xmldata['CreateTime']   = time();
				if($this->wxinfo['nomatchrtype']==1) {
					$xmldata['MsgType']  = 'text';
					$xmldata['Content']  = htmlspecialchars_decode($nomatch['content']);
				}elseif($this->wxinfo['nomatchrtype']==2) {
					$xmldata['MsgType']       = 'news';
					$xmldata['ArticleCount']  = 1;
					$arr = D("Busitype")->getBusiness($detail['business_type']);
					$arr['Url'] = "http://".C('ODOMIN')."/?g=wechat&wid=".$this->wid."&token=".$this->data_array['FromUserName']."&wxref=mp.weixin.qq.com";
					$xmldata['Articles'][] = $arr;
				}
			}else {
				exit;
			}
		}

		

		$xml = $this->Linwechat->xml_encode($xmldata);
		echo $xml;
		exit;
    }
    
    /**
     * 被动接收事件的自动回复
     */
    public function event(){
		if ($this->data_array['Event']=='subscribe'){
			//记录订阅事件
			$this->_setSubscribe($this->data_array['Event']);

			//订阅事件回复
    		echo $this->subscribe();exit;
		}
		if ($this->data_array['Event']=='unsubscribe'){
			//记录取消订阅事件
			$this->_setSubscribe($this->data_array['Event']);
		}
    	if ($this->data_array['Event']=='CLICK') {
    		//自定义菜单的点击事件
    		$this->data_array['Content']=$this->data_array['EventKey'];
    		$this->text();
    	}
		if ($this->data_array['Event']=='MASSSENDJOBFINISH') {
    		//群发之后事件返回
    		$this->masssendjobfinish();
    	}
    }
    
    /**
     * 订阅事件的自动回复
     */
	public  function subscribe() {
    	$detail = M('app_followreply')->where("wid='".$this->wid."'")->find();
		if($this->wxinfo['followrtype']==1) {
			$xmldata['ToUserName']   = $this->data_array['FromUserName'];
		    $xmldata['FromUserName'] = $this->data_array['ToUserName'];
		    $xmldata['CreateTime']   = time();
			$xmldata['MsgType']      = 'text';
			$xmldata['Content']      = htmlspecialchars_decode($detail['content']);
		}
    	return $xml = $this->Linwechat->xml_encode($xmldata);
	}

	/**
	 * 记录用户的关注/取消关注事件
	 *
	 * @param	string
	 */
	private function _setSubscribe($event) {
		if( !empty($event) ){

			// 保存事件信息
			$data = array(
				'wid'		=> $this->wid,
				'original_id'	=> $this->data_array['ToUserName'],
				'open_id'	=> $this->data_array['FromUserName'],
				'event'		=> $event,
				'time'		=> $this->data_array['CreateTime'],
			);
			M('subscribe')->data($data)->add();

			// 入住
			if($event == 'subscribe'){
				D('WeixinUserIn')->inCommunity($this->wid, $this->data_array['FromUserName']);
			}else{
				// 更新居民关注状态为取消关注
				$set = array('subscribe'=>0, 'check_in'=>0, 'etime'=>time());
				$where = "wid=".$this->wid." and wechatid='".$this->data_array['FromUserName']."'";
				M('app_mcard_member')
					->where($where)
					->save($set);

				$minfo = M('app_mcard_member')->where($where)->find();
				D("Member")->writelog($minfo['id'], $this->wid, 1, 3);

				//记录取消入住事件
				$op_id = M('app_mcard_member')->where($where)->getField('id');
				$chData = array('op_type'=>'residents', 'wid'=>$this->wid, 'op_id'=>$op_id, 'act_type'=>'quit', 'time'=>time());
				M('log_checkin')->add($chData);
				D("Member")->writelog($id, $this->wid, 1, 3);
			}

		}
	}

	function masssendjobfinish() {
		file_put_contents("logs.txt", json_encode($GLOBALS['HTTP_RAW_POST_DATA'])."--====\n", FILE_APPEND);
		$temp = json_encode($this->data_array);		
		$retarr = json_decode($temp, true);
		$MsgID = $retarr["MsgID"];

		$detail = M("npv3_sendqueues")->where("msg_id='".$MsgID."' and status=2")->find();
		if(!$detail) {
			echo "该条数据已记录结果";
			exit;
		}

		if($retarr["Status"]=="send success") {
			$insertdata = "";
			$insertdata["wid"]         = $detail["wid"];
			$insertdata["qid"]         = $detail["id"];
			$insertdata["createtime"]  = $retarr["CreateTime"];
			$insertdata["msgid"]       = $retarr["MsgID"];
			$insertdata["status"]      = $retarr["Status"];
			$insertdata["totalcount"]  = $retarr["TotalCount"];
			$insertdata["filtercount"] = $retarr["FilterCount"];
			$insertdata["sentcount"]   = $retarr["SentCount"];
			$insertdata["errorcount"]  = $retarr["ErrorCount"];
			$insertdata["checkstate"]  = $retarr["CopyrightCheckResult"]["CheckState"];
			$insertdata["etime"]       = time();
			$insertdata["ctime"]       = time();
			M("npv3_sendqueues_results")->add($insertdata);

			$updata = "";
			$updata["status"] = 3;
			M("npv3_sendqueues")->where("msg_id='".$MsgID."' and status=2")->save($updata);
		}else {
			file_put_contents("logs.txt", $retarr["Status"]."--====\n", FILE_APPEND);
			$insertdata = "";
			$insertdata["wid"]         = $detail["wid"];
			$insertdata["qid"]         = $detail["id"];
			$insertdata["createtime"]  = $retarr["CreateTime"];
			$insertdata["msgid"]       = $retarr["MsgID"];
			$insertdata["status"]      = $retarr["Status"];
			$insertdata["totalcount"]  = $retarr["TotalCount"];
			$insertdata["filtercount"] = $retarr["FilterCount"];
			$insertdata["sentcount"]   = $retarr["SentCount"];
			$insertdata["errorcount"]  = $retarr["ErrorCount"];
			$insertdata["checkstate"]  = $retarr["CopyrightCheckResult"]["CheckState"];
			$insertdata["etime"]       = time();
			$insertdata["ctime"]       = time();
			M("npv3_sendqueues_results")->add($insertdata);

			$updata = "";
			$updata["status"] = -1;
			M("npv3_sendqueues")->where("msg_id='".$MsgID."' and status=2")->save($updata);
		}
		exit;
	}
	
	function _getMember() {
		if(!isset($this->member)) {
			$where  = "wid=".$this->wid." and wechatid='".$this->data_array['FromUserName']."'";
			$member = M("app_mcard_member")->where($where)->find();
			$this->member = $member;
		}
		return $this->member;
	}
}
