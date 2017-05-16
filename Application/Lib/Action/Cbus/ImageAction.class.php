<?php
class ImageAction extends CbusAction {

	public function __construct() {
		parent::__construct();
		$this->_business();
	}

    public function up(){
		$savepath = "./data/upload/";
		$savepath_2 = "/data/upload/";

		$savepath = $savepath.date('Y').'/';
		$savepath_2 = $savepath_2.date('Y').'/';
		if(!is_dir($savepath)) {
			if(!mkdir($savepath)){
				$data["status"] = -3;
				$data["message"]  = "目录不存在";
				echo json_encode($data);
				exit;
			}
		}
		$savepath = $savepath.date('m').'/';
		$savepath_2 = $savepath_2.date('m').'/';
		if(!is_dir($savepath)) {
			if(!mkdir($savepath)){
				$data["status"] = -3;
				$data["message"]  = "目录不存在";
				echo json_encode($data);
				exit;
			}
		}
		$savepath = $savepath.date('d').'/';
		$savepath_2 = $savepath_2.date('d').'/';
		if(!is_dir($savepath)) {
			if(!mkdir($savepath)){
				$data["status"] = -3;
				$data["message"]  = "目录不存在";
				echo json_encode($data);
				exit;
			}
		}
		$uniqid = uniqid();
		$thumbExt = $_POST["filetype"];

		$filename = $savepath.$uniqid.'.'.$thumbExt;
		$filename_2 = $savepath_2.$uniqid.'.'.$thumbExt;

		$f=fopen($filename, "a");
		$filedata = base64_decode(explode(',',$_POST["data"],2)[1]);
		fwrite($f,$filedata);

		import("ORG.Util.Image");
		Image::thumb($filename,$savepath.$uniqid.'_s.'.$thumbExt,'',"100", "100",true);
		$data["status"] = 1;
		$data["path"]  = $filename_2;

		echo json_encode($data);
		exit;
    }
}