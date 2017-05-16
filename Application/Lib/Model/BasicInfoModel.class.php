<?php
/**
 * 基本信息 model
 * 2016/3/31
 */
class BasicInfoModel extends Model
{
	private	$_model;

	public function __construct() {
		parent::__construct();
		$this->_model = M('app_basicinfo');
	}

	/**
	 * 获取基本信息
	 *
	 * @param	int		社区id
	 * @return	array
	 */
	public function getBasicInfo($wid, $field='*')
	{
		$res = $this->_model
			->field($field)
			->where('wid='.$wid)
			->find();
		return $res;
	}

	/**
	 * 添加基本信息
	 *
	 * @param	array
	 * @return	int/boole	成功返回lastid或1，失败返回false	
	 */
	public function addBasicInfo($data = array())
	{
		$lastId = false;
		if( !empty($data) ){
			$lastId = $this->_model->add($data);
			//$this->_createQrCode($data);	// 生成二维码
		}
		return $lastId;
	}

	/**
	 * 修改基本信息
	 *
	 * @param	array
	 * @param	int
	 * @return	int/boole	成功返回影响记录数量，失败返回false
	 */
	public function editBasicInfo($data = array(), $id)
	{
		$affrow = $this->_edit($data, $id);		// 修改数据库内容
		//$this->_createQrCode($data);	// 根据修改内容重新生成二维码
		return $affrow;
	}

	/**
	 * 基础信息初始化
	 *
	 * @param	int		社区id
	 * @return	boole
	 */
	public function initializeBasic($wid)
	{
		$data = array(
			'aid'	=> $wid,
			'qr'	=> '/file/'.$wid.'.com.qr.png',
			'qr2'	=> '/file/'.$wid.'.com.qr2.png',
		);
		$lastId = $this->_model->add($data);
		if($lastId){
			// 初始化成功后生成二维码
			$save_path	= '.' . $data['qr2'];
			$demodata	= 'http://'.$_SERVER['SERVER_NAME'].'/?g=app&m=mingpian&wid='.$wid.'&token=&wxref=mp.weixin.qq.com';
			QRcode::png($demodata, $save_path, 'H', 430, 2);
			return true;
		}
		return false;
	}

	/**
	 * 设置居委会简介和摘要
	 *
	 * @param	array
	 * @param	int	
	 * @return	int/boole
	 */
	public function setIntroduce($data = array(), $id)
	{
		$affrow = $this->_edit($data, $id);		// 修改数据库内容
		return $affrow;
	}

	/**
	 * 生成二维码
	 *
	 * @param	array
	 */
	private function _createQrCode($data)
	{
		$addr = json_decode($data['address'], true);
		$demodata	 = '';
		$demodata	.= 'BEGIN:VCARD'."\n";
		$demodata	.= 'VERSION:3.0'."\n"; 
		$demodata	.='N:'.$data['name_cn']."\n";	// 全称
		$demodata	.='ABBR:'.$data['abbr']."\n";	// 简称
		$demodata	.='EMAIL:'.$data['email']."\n";		// 邮箱
		$demodata	.='TEL:'.$data['phone']."\n";	// 电话
		//$demodata	.='TEL;FAX:'.$data['fax']."\n"; 
		$demodata	.='ADR;WORK:'.$addr['province'].' '.$addr['city'].
			' '.$addr['area'].' '.$data['address_detail']."\n";	// 详细地址
		//$demodata	.='URL:'.$data['site']."\n"; 
		$demodata	.='END:VCARD'."\n";

		$save_path	= './file/'.$data['aid'].'.com.qr.png';	// 图片路径
		QRcode::png($demodata, $save_path, 'H', 430, 2);
	}

	/**
	 * 修改信息
	 *
	 * @param	int
	 * @param	array
	 * @return	int/boole
	 */
	private function _edit($data = array(), $id)
	{
		$affrow = false;
		if( !empty($data) && (isset($id) && is_numeric($id)) ){
			$data['introduce'] = htmlspecialchars($data['introduce']);
			$affrow = $this->_model->where('id='.$id)->save($data);
		}
		return $affrow;
	}
}
