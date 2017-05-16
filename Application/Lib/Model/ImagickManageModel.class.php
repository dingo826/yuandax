<?php
/*
 * 图片压缩管理
 * 
 * @version 2015-03-09
 * @author xujiang@cekasp.cn
 */
class ImagickManageModel
{
	//Imagick对象实例
	protected $_model = null;
	
	public function __construct()
	{
		$this->_model = D('Imagick');
	}

	/**
	 * 设置图像处理实例
	 *
	 * @param	object 图像实例
	 */
	public function setImagick($obj)
	{
		if( !empty($obj) && is_object($obj) ) $this->_model = $obj;
	}

	/**
	 * 封装生成缩略图过程
	 *
	 * @param	string	图片
	 * @return	mix		thump_file 缩略图地址/false 失败
	 */
	public function createThump($file) {
		$index = 0;
		$file_info = pathinfo($file);
		if(strtolower($file_info['extension'])=='png') {
			$dest_img = $file_info['dirname'] . '/' . $file_info['filename'] . '.jpg';
			$file = $this->_model->png2jpg($file, $dest_img);
		}

		// 去除profile信息
		$this->_model->stripProfile($file);

		$file = $this->_model->thumpsize($file);
		return $file;

		// 获取基本信息
		$imgInfo = $this->_model->getImgBasicInfo($file);

		if($imgInfo['width'] > 800){
			// 如果宽度超过900，等比例缩放至900

			$index ++;
			if($imgInfo['height'] > 500) {
				$file = $this->_model->thump($file, 800, 500);
			}else {
				$file = $this->_model->thump($file, 800);
			}
			
			if(false === $file) return false;
		}
	
		// 再次获取基本信息
		$imgInfo = $this->_model->getImgBasicInfo($file);
		if($imgInfo['size'] > 60440){
			// 如果文件大于60K，降低图片质量

			$index ++;
			$quality = round(60440 / $imgInfo['size'], 3) * 100;
			$file = $this->_model->slimming($file, $quality);
			return $file;
		}

		return $file;
	}

	/**
	 * 检查缩略图是否存在
	 * 
	 * @param	string	原图地址
	 * @param	mix		false 无缩略图 / filename 缩略图地址
	 */
	public function checkThump($file)
	{
		$imgInfo = $this->_model->getImgBasicInfo($file);
		if($imgInfo['size']<63480) {//如果原文件小于64KB，无需生成缩略图直接使用。
			return $file;
		}
		$file_info = pathinfo($file);
		//生成缩略图名称
		$file_name = substr($file_info['basename'],0,strrpos($file_info['basename'],'.'));
		if(strtolower($file_info['extension'])=='png') $extension = "jpg";
		else $extension = $file_info['extension'];
		$file = $file_info['dirname'] . '/' . $file_name . '_thump.' . $extension;
		if( file_exists($file) ){
			$imgInfo = $this->_model->getImgBasicInfo($file);
			if($imgInfo['size']>63480) {
				return false;
			}
			return $file;
		}else{
			return false;
		}
	}
}
