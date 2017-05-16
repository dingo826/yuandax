<?php
class IndexAction extends Action {
	
	public function __construct() {
		parent::__construct();
	}

    public function index(){
		$where = "status=1";
		$list = M('News_center')->where($where)->order('etime desc')->page('1, 2')->select();

		$cateList[2] = '行业资讯';
		$cateList[3] = '最新动态';

		$cookie_us=$_COOKIE["cookie_us"] ? $_COOKIE["cookie_us"] : "";

		$this->assign('cookie_us', $cookie_us);

		$this->assign('list',$list);
		$this->assign('cateList',$cateList);
		$this->display("index");
    }

	function ces() {
		$dd = GrabImage("http://cdn.ikcest.cekasp.cn/images/2750/2758/4758/052897579dad6f207cf7ce9e0a8b7960_0_0.jpg");
		echo $dd;
		exit;
		$today = (int)strtotime(date("Y-m-d"));
		$addnum = 0;
		$setlist = M("jd_newspapers_category_set")->table(C('DB_PREFIX')."jd_newspapers_category_set as a")->field("a.*, b.jdid as jdid, b.defaultpic as defaultpic")
			->join("left join ".C("DB_PREFIX")."jd_newspapers_category as b on (a.categoryid=b.id)")
			->where("a.issuccess=-2")->order("a.id desc")->page('1, 3')->select();
		//print_r($setlist);exit;

		foreach($setlist as $key => $row) {
			$insertdata = '';
			$data["publisher"] = "无锡日报";
			$data["pageTitle"] = "无锡要闻";
			$data["province"]  = "江苏";
			$data["city"]      = "无锡市";
			//$data["date"]      = date("Y-m-d");
			$data["date"]      = "2017-03-01";

			$data["indexPage"] = 1;
			$data["pageSize"]  = 50;

			$defaultpicd = M("jd_newspapers_category_set_defaultpic")->where("`name`='".$row["newspapername"]."'")->find();
			//print_r($defaultpicd);exit;

			$url = "http://newspaper.cekasp.jteam.cn/data/content/pc/newspaper_article.json";
			$retjson = vpost($url, http_build_query($data));
			$arr = json_decode($retjson, true);
			//print_r($arr);exit;
			$articlelist = $arr['messages']['data']['articleList'];
			//print_r($articlelist);exit;
			if($arr['ok']==1 && $arr['code']==200 && $arr['messages']['data']['articleList']) {
				foreach($articlelist as $akey => $arow) {
					$temp = '';
					$temp["isusedefaultpic"]   = -1;
					if($arow["thumb"]) {
						//$datathumb = $arow["thumb"];
						$thumbname = GrabImage($arow["thumb"]);
					}else {
						$thumbname = $row["defaultpic"];
						$temp["isusedefaultpic"]   = 1;
					}
					//$thumbname = GrabImage($arow["thumb"]);

					$temp["jdid"]    = $row["jdid"];
					$temp["cid"]     = $row["categoryid"];
					$temp["setid"]   = $row["id"];
					$temp["acqid"]   = $arow["id"];
					$temp["title"]   = $arow["title"];
					$temp["titlelen"]= mb_strlen($arow["title"]);
					$temp["thumb"]   = $thumbname;
					$temp["acqdate"] = $arow["date"];
					$temp["intime"]  = time();
					$temp["etime"]   = time();
					$temp["ctime"]   = time();

					$insertdata[]    = $temp;
					$addnum++;
				}
			}

			$savedata["lasttime"]  = time();
			$savedata["etime"]     = time();

			if($insertdata) {
				//echo 333;
				$savedata["issuccess"] = 1;
				//print_r($insertdata);//exit;
				$isd = M("newspapers_lists12")->addAll($insertdata);
				$this->display("index");
				exit;
			}else {
				$savedata["issuccess"] = -2;
			}

			M("jd_newspapers_category_set")->where("id='".$row['id']."'")->save($savedata);
		}
		echo $addnum;
		exit;
	}

	function tianqi($n)
    {
		echo $n;
        $name = implode('', $n);
		echo $name;
        @$str = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=' . urlencode('天气');
		echo $str;
		
        $json = json_decode(file_get_contents($str),true);
		print_r($json);
        $str  = str_replace('{br}', "\n", $json->content);
        return str_replace('菲菲', 'AI9', $str);
    }

	function resxml() {
		$xml = '<xml>
<ToUserName><![CDATA[gh_bb1727b4f16b]]></ToUserName>
<FromUserName><![CDATA[oHo_ujqErygg9Mk3GbbMR6GMy3rM]]></FromUserName>
<CreateTime>1492745901</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[MASSSENDJOBFINISH]]></Event>
<MsgID>1000000008</MsgID>
<Status><![CDATA[send success]]></Status>
<TotalCount>51</TotalCount>
<FilterCount>51</FilterCount>
<SentCount>51</SentCount>
<ErrorCount>0</ErrorCount>
<CopyrightCheckResult><Count>0</Count>
<ResultList></ResultList>
<CheckState>1</CheckState>
</CopyrightCheckResult>
</xml>';
        $GLOBALS['HTTP_RAW_POST_DATA'] = $xml;
		Vendor("Linwechat");
		$para['isdebug'] = true;
		$wechatObj = new Linwechat(Token, $para);
		$this->Linwechat = $wechatObj;
		$data  = $this->Linwechat->Receive()->_get();

		$temp = json_encode($data);
		$retarr = json_decode($temp, true);
		$MsgID = $retarr["MsgID"];

		$detail = M("npv3_sendqueues")->where("msg_id='".$MsgID."' and status=2")->find();		

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
		
		exit;
	}
}
