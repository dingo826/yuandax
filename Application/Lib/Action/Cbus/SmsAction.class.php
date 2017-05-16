<?php
class SmsAction extends CbusAction {

	public function __construct() {
		parent::__construct();
	}

    public function send() {
		$mobile = I("post.mphone");

		if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){  
			$retjson['status'] = -1;
			$retjson['message'] = "手机号码不正确";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$detail = M("cbus_account")->where("mphone='".$mobile."'")->find();
		if($detail) {  
			$retjson['status'] = -1;
			$retjson['message'] = "该手机号码已被使用";
			echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
			exit;
		}

		$vercode = mt_rand(100000, 999999);
		$content = "{code:'".$vercode."',product:'社区商户'}";

		$data['mobile']  = $mobile;
		$data['vercode'] = $vercode;
		$data['content'] = $content;
		$data['ctime']   = time();

		$insertid = M("sms_records")->add($data);
		$smstemplate = C("alidayu.dayu_template_identity");
		Vendor("TopSdk");
		$c            = new TopClient();
		$c->format    = 'json';
		$c->appkey    = C("alidayu.dayu_appkey");
		$c->secretKey = C("alidayu.dayu_secretKey");
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend();
		$req->setSmsType("normal");
		$req->setSmsFreeSignName($smstemplate['signname']);
		$req->setSmsParam($content);
		$req->setRecNum($mobile);
		$req->setSmsTemplateCode($smstemplate['templatecode']);
		$resp = $c->execute($req);

		$smsstr     = json_encode($resp, JSON_UNESCAPED_UNICODE);
		$dayuretarr = json_decode($smsstr, true);

		$updata['retinfo'] = $smsstr;
		$updata['rtime']   = time();

		if($dayuretarr['result']['success']==1) {
			$updata['status']    = 2;

			$sdata['code']       = $vercode;
		    $sdata['time']       = time();
			$_SESSION['vercode'] = $sdata;

			$retjson['status']   = 1;
		    $retjson['message']  = "发送成功";
		}else {
			$updata['status']   = -1;
			$retjson['status']  = -1;
		    $retjson['message'] = "发送失败，请联系客服";
		}
		M("sms_records")->where("id='".$insertid."'")->save($updata);

		echo json_encode($retjson, JSON_UNESCAPED_UNICODE);
		exit;
    }
}
