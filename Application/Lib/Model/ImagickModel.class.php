<?php
/*
 * ͼƬѹ����  ���·�װ��Imagick
 * 
 * @version 2015-03-09
 * @author xujiang@cekasp.cn
 */
class ImagickModel
{
	//Imagick����ʵ��
	public $obj = null;
	
	public function __construct()
	{
		//�ж��Ƿ�����˸���չ
		if(!extension_loaded('Imagick'))
		{
			return false;
		}
		$this->obj = new Imagick();
	}

	/**
	 * ��ȡͼƬ��Ϣ
	 *
	 * @param string src_img ԴͼƬ·��
	 * @return array ������Ϣ
	 */
	public function getImgBasicInfo($src_img)
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$this->obj->readImage($src_img);
			$basicInfo = array(
				'width'	=> $this->obj->getImageWidth(),
				'height'=> $this->obj->getImageHeight(),
				'size'	=> $this->obj->getImageLength(),
			);
			return $basicInfo;

		}catch (ImagickException $e){
			return false;
		}
	}

	/*
	 * png2jpgת��ͼƬ��ʽ
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param string dest_img Ҫ���ɵ�ͼƬ��·��
	 * @return boolean ת���ɹ�����true  ����false
	 */
	public function png2jpg($src_img,$dest_img)
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$this->obj->readImage($src_img);
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}

	/*
	 * convertformatת��ͼƬ��ʽ
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param string dest_img Ҫ���ɵ�ͼƬ��·��
	 * @return boolean ת���ɹ�����true  ����false
	 */
	public function convertformat($src_img, $dest_img) {
		if(!is_object($this->obj)) {
			return false;
		}try {
			$this->obj->readImage($src_img);
			$istrue = $this->obj->writeImage($dest_img);
			$this->obj->clear();
			$this->destory();
			if(!$istrue) {
				return false;
			}
			return $dest_img;
		}
		catch (ImagickException $e) {
			return false;
		}
	}
	
	/*
	 * ȥ��ͼƬ��profile��Ϣ
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @return string src_img ͼƬ���� ���򷵻�false
	 */
	public function stripProfile($src_img,$dest_img = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{

			$dest_img = empty($dest_img) ? $src_img : $dest_img;
			$this->obj->readImage($src_img);
			$this->obj->stripImage();
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $src_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * ����jpgͼƬ����
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param string dest_img Ҫ���ɵ�ͼƬ��·��
	 * @return boolean ת���ɹ�����true  ����false
	 */
	public function setQuality($src_img,$quality = 70,$dest_img = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$dest_img = empty($dest_img) ? $src_img : $dest_img;
			$this->obj->readImage($src_img);
			$this->obj->setImageCompression(Imagick::COMPRESSION_JPEG);
			$this->obj->setImageCompressionQuality($quality);
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * ͼƬ����
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param int quality ����ͼƬѹ������
	 * @param string dest_img Ҫ���ɵ�ͼƬ��·��
	 * @return boolean ת���ɹ�����true  ����false
	 */
	public function slimming($src_img,$quality = 60,$dest_img = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$dest_img = empty($dest_img) ? $src_img : $dest_img;
			$this->obj->readImage($src_img);
			$this->obj->setImageFormat('jpeg');
			$this->obj->setImageCompression(Imagick::COMPRESSION_JPEG);
			//��ͼƬ���������͵�ԭ����60%
			$quality = $this->obj->getImageCompressionQuality() * $quality / 100;
			$this->obj->setImageCompressionQuality($quality);
			$this->obj->stripImage();
			 
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}

	function resize($src_img) {
		//echo $src_img;exit;
		//echo $src_img;
		$this->obj->readImage($src_img);
		$this->obj->thumbnailImage(720, 400, true);
		//$this->obj->resizeImage(720, 400, Imagick::FILTER_CATROM, 1, false);
		//$this->obj->cropImage(276, 220, 0, 0);
		//header("Content-Type: image/jpg");
		//echo $this->obj->getImageBlob();exit;
		$this->obj->writeImage("resxx.jpg");
		exit;
	}

	/*
	 * ��������ͼ
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param int size ����С��ͼƬС�ڵ��������С
	 */
	function thumpsize($src_img, $size=63480) {
		if(!is_object($this->obj)) {
			return false;
		}
		try {
			$file_info = pathinfo($src_img);
			//��������ͼ����
			$file_name = substr($file_info['basename'],0,strrpos($file_info['basename'],'.'));
			$dest_img = $file_info['dirname'] . '/' . $file_name . '_thump.' . $file_info['extension'];

			$this->obj->readImage($src_img);
			$basicInfo = $this->getImgBasicInfo($src_img);

			$flag = 1;
			if($basicInfo['width']<$basicInfo['height']) $flag = 2;

			if($basicInfo['size']>$size) {
				while($basicInfo['size']>$size) {
					if($flag==1) {
						$width = $basicInfo['width']-50;
						$this->obj->resizeImage($width, 0, Imagick::FILTER_CATROM, 1, false);
					}else {
						$height = $basicInfo['height']-50;
						$this->obj->resizeImage(0, $height, Imagick::FILTER_CATROM, 1, false);
					}
					$this->obj->writeImage($dest_img);
					$basicInfo = $this->getImgBasicInfo($dest_img);
				}
			}
			$this->destory();
			return $dest_img;
		}catch (ImagickException $e) {
			return false;
		}
	}
	
	/*
	 * ��������ͼ
	 * 
	 * @param string src_img ԴͼƬ·��
	 * @param int quality ����ͼƬѹ������
	 * @param string dest_img Ҫ���ɵ�ͼƬ��·��
	 * @return boolean ת���ɹ�����true  ����false
	 */
	public function thump($src_img,$width = 250,$height = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			
			$file_info = pathinfo($src_img);
			//��������ͼ����
			$file_name = substr($file_info['basename'],0,strrpos($file_info['basename'],'.'));
			$dest_img = $file_info['dirname'] . '/' . $file_name . '_thump.' . $file_info['extension'];
			$this->obj->readImage($src_img);
			//����Ҫ�������ͼ�ĸ߶�
			$img_width = $this->obj->getImageWidth();
			$img_height = $this->obj->getImageHeight();
			$dest_height = (!empty($height) && is_numeric($height)) 
				? $height : $img_height * ($width / $img_width);
			$this->obj->resizeImage($width, $dest_height, Imagick::FILTER_CATROM, 1, false);
			//����ͼƬ
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * �ͷ���Դ
	 * 
	 */
	function destory()
	{
		if(is_object($this->obj))
		{
			$this->obj->clear();

			$this->obj->destroy();
		}
	}
	
}
