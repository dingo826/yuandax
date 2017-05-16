<?php
/**
 * 商户系统用户
 */
class CbusUserModel extends Model
{
	private	$_model;	// 模型

	public function __construct() {
		parent::__construct();
		$this->_model = M('cbus_account');
	}

	/**
	 * 获取基础信息
	 *
	 * @param	string	mphone
	 * @return	array
	 */
	public function _find($mphone) {
		$minfo = $this->_model->where('mphone='.$mphone)->find();
		return $minfo;
	}

}
