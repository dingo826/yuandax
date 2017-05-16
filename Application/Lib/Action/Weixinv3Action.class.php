<?php
/* 系统项目组可以共用的基类库，继承则可，自动加载 */
class Weixinv3Action extends Action {

	public $weixin;
	public $Base_type, $Base_eventtype, $Base_businesstype, $Base_theme, $Base_themplate, $Base_module;     //Base_:模型全局函数命名模式

	public function __construct() {
		parent::__construct();
		$this->checklogin();
		$this->ChoiceTemplate();
		$this->loadAuth();
    $this->head();
	}

	// 控制器初始化处理 可以让所有项目组共同使用
	function checklogin() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		if($uid<1) {
			header("Location: /index");exit;
		}

		$weixin = M('Weixin')->where("id='".$wid."' and uid='".$uid."'")->find();

		if(empty($weixin)) {
			header("Location: ".U('user/index/index'));
		}
		$this->weixin = $weixin;

		if($weixin['packid']!=2) {
			if($weixin['etime']<time()) {
				echo "<script>alert('该试用账号已到期，请联系客服。');location.href='".U('account/index')."';</script>";
			}
		}

		if($weixin['sysversion']==1) {
			header("location: ".U("weixin/index/index"));exit;
		}
		session("weixin", $weixin);
		$this->assign('weixin', $weixin);
	}

	function ChoiceTemplate() {
		$template = cookie("theme");
		if(!$template) $template = "Weixinv3";

		$this->Base_module    = $template;
		$this->Base_theme     = $template."@".MODULE_NAME;
		$this->Base_themplate = $this->Base_theme."/".strtolower(ACTION_NAME);

		$this->selectcolumn();		
	}

	function selectcolumn() {
		$actarray = array(
			
			"editing"          => "one",
			"sendqueue"        => "one",
			"worker"           => "one",
			"masshistory"      => "one",
			

			"index"       => "two",				
			"article"     => "two",
			"huodong"          => "two",
			"vote"             => "two",
			"survey"           => "two",
			"communityservice" => "two",
			"wereserve"        => "two",
			"cbus"             => "two",
			"circle"           => "two",
			"wxqun"            => "two",
			"weialbums"        => "two",
			"games"            => "two",

			"account"           => "three",
			"sys"               => "three",
			"shequ"             => "three",
			"replysubscribe"    => "three",
			"nomatch"           => "three",
			"position"          => "three",
			"employee"          => "three",
			"customreply"       => "three",
			"custommenu"        => "three",
			"category"    => "three",
			"template"    => "three",
			"plugmenu"    => "three",
			"webslide"    => "three",
		);

		$this->assign('selctactive',$actarray[strtolower(MODULE_NAME)]);
	}

	function selectcolumnWeixin2() {
		$actarray = array(
			"index"   => "one",
			"article"   => "one",
			"mass"      => "one",

			"huodong"   => "two",
			"vote"      => "two",
			"survey"    => "two",

			"communityservice"  => "three",
			"wereserve"         => "three",
			"cbus"              => "three",

			"circle"        => "four",
			"wxqun"         => "four",

			"weialbums"     => "five",
			"games"         => "five",

			"sys"           => "six",
			"shequ"         => "six",
			"position"      => "six",
			"employee"      => "six",

			"custommenu"    => "seven",
			"category"      => "seven",
			"webslide"      => "seven",
			"template"      => "seven",
			"plugmenu"      => "seven",
			
			"replysubscribe" => "eight",
			"customreply"    => "eight",
		);

		$this->assign('selctactive',$actarray[strtolower(MODULE_NAME)]);
	}

	function loadAuth() {
		$uid = intval(session("uid"));
		$wid = intval(session('wid'));
		//$list = M("function")->->where();

		/*$list = M("function")->table(C('DB_PREFIX')."function as f")->field("f.*, a.status as auth")
			->join("left join ".C("DB_PREFIX")."weixin_function_auths as a on (a.funid=f.id)")->where("a.wid='".$wid."'")->select();*/

		$funlist = M("function")->select();
		foreach($funlist as $key=>$val) {
			if(intval($val["status"])!=1) {
				$fun[$val["sysname"]] = -1;
			}else {
				$fun[$val["sysname"]] = 1;
				$auth = M("weixin_function_auths")->where("wid='".$wid."' and funid='".$val['id']."'")->find();

				/*if($auth['status']==1) {
					$fun[$val["sysname"]] = 1;
				}else {
					if($val['isalluser']==1) {
						$fun[$val["sysname"]] = 1;
					}else {
						$fun[$val["sysname"]] = -1;
					}					
				}*/
				if($auth['status']==-1 || (empty($auth) && $val['isalluser']==-1)) {
					$fun[$val["sysname"]] = -1;
				}
			}
		}
		$this->assign('fun', $fun);
	}

	Public function _upload( $savepath = './data/upload/', $thumb = true, $thumbMaxWidth = '220,150', $thumbMaxHeight = '220,150', $arrexts = array('jpg', 'gif', 'png', 'jpeg', 'doc', 'zip', 'rar', '7z', 'swf', 'txt') ) {
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 3292200;
		//设置上传文件类型
		//$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->allowExts = $arrexts;
		//设置附件上传目录
		$savepath = $savepath.date('Ym').'/';
		if(!is_dir($savepath)) {
			if(!mkdir($savepath)){
				echo '上传目录'.$savepath.'不存在';
				exit;
			}
		}
		$upload->savePath = $savepath.date('d').'/';
		//$upload->autoSub = true;
		//$upload->subType = "data";
		//设置需要生成缩略图，仅对图像文件有效
		$upload->thumb = true;
		// 设置引用图片类库包路径
		$upload->imageClassPath = 'ORG.Util.Image';
		//设置需要生成缩略图的文件后缀
		$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
		//设置缩略图最大宽度
		$upload->thumbMaxWidth = $thumbMaxWidth;
		//设置缩略图最大高度
		$upload->thumbMaxHeight = $thumbMaxHeight;
		$upload->thumbType = 2;
		//设置上传文件规则
		$upload->saveRule = uniqid;
		//删除原图
		//$upload->thumbRemoveOrigin = false;
		if (!$upload->upload()) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		//$uplist['savepath'] = $savepath;
		//$uplist['list']     = $uploadList;
		return $uploadList;
	}

	public function loadType() {
		$this->Base_type = array("isempty"=>"空", "article"=>"图文", "link"=>"链接", "tel"=>"电话", "map"=>"导航", "activity"=>"活动", "business"=>"业务模块", 'text'=>'文本', "column"=>"栏目");

	    $this->Base_eventtype = array("article"=>"article_id", "link"=>"link", "tel"=>"tel", "map"=>"", "activity"=>"activity_type", "business"=>"business_type", "car"=>"car_type", "estate"=>"estate_type", "food"=>"food_type", "shop"=>"shop_type");
		
		$this->Base_businesstype = array("official"=>"微官网", "vipcard"=>"居员卡", "juweihui"=>"居委会", "communityservice"=>"社区服务", "circle"=>"圈子", "resevsection"=>"微预约栏目", "reservation"=>"微预约", "message"=>"微留言", "albums"=>"微相册", "survey"=>"调研", "wxqun"=>"微信群", "huodong"=>"活动报名", "huodonglist"=>"活动报名项目", "vote"=>"投票");

		$this-> assign("type", $this->Base_type);
		$this-> assign("eventtype", $this->Base_eventtype);
		$this-> assign("businesstype", $this->Base_businesstype);
	}
  public function head(){
   $wid = intval(session('wid'));
   $sevenday          = strtotime(date("Y-m-d"))-(7*24*60*60);
   //$head['board']     = M("app_board_topic")->where("status=0")->count();
   $head['board']     = M("board_message")->where("msg_st=1")->count();
	 $head['message']   = M("member_message")->where("weixinid='".$wid."' and istype=1 and AddTime>".$sevenday)->count();
   $head['number'] = M("app_mcard_member")->where("wid='".$wid."'")->count();
   $this->assign('head', $head);
  }
}
?>
