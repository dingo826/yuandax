<?php
/**
 * 文本回复model
 * xujiang@cekasp.cn
 * 2015-01-13
 */
class TextResponseModel extends Model
{
	private	$_model;
	private $_infomation;	// 资料库model
	private $_baseCfg;		// 基本配置model

	public function __construct() {
		parent::__construct();
		$this->_model = M('app_text_response');
	}

	/**
	 * 查找指定类型的文本回复
	 *
	 * @param	int
	 * @param	str	
	 *	subscribe：关注时回复，nomatch：无匹配回复
	 *	mass：群发回复，ordinary：普通回复
	 * @return	array
	 */
	public function findSpecifiedText($wid, $category) {
		$row = $this->_model
			->where("wid=$wid and category='$category'")
			->limit('0,1')
			->find();
		$row['content'] = htmlspecialchars_decode($row['content']);
		return $row;
	}
}
?>
