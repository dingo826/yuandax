<?php
/**
 * 发送短信
 */
class SmsmsgModel extends Model {

	protected $autoCheckFields = false;
	public $url      = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";
	public $template = array("boundphone" => "ZS5SAZnjwha0Ntsdq4SpqxGEU5Fs6kh41ELo5dZJORI", "notice" => "9iOUwqlkL6ywFk76END55anUVbyeSJpniZNfK0nUSag");

	function __construct() {
		parent::__construct();
		$token     = D("weixin")->getToken();
		$this->url = $this->url.$token;
	}
	
}
?>
