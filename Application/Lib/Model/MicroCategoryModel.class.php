<?php
/**
 * 资讯分类model
 * 2015-01-13
 */
class MicroCategoryModel extends Model
{
	private	$_model;
	private $_pageSize;	// 每页记录数
	private $_fields;	// 需要入库的字段

	public function __construct()
	{
		parent::__construct();
		$this->_model = M('app_category');
	}

	/**
	 * 获取资讯分类列表
	 *
	 * @param	int		社区id
	 * @param	str		类型
	 * @return	array
	 */
	public function getCategoryList($wid, $type=null, $category_id=-1, $customsql='')
	{
		$where = "wid='$wid'";
		if($type != '') $where .= " and type='".$type."'";	// 只要某一类
		if($category_id>-1) $where .= " and category_id='".$category_id."'";
		$list = $this->_model->where($where)->order('sort desc')->select();
		if( $list ) $list = $this->_dumpTreeList($list);

		return $list;
	}

	/**
	 * 获取分类详情
	 * 
	 * @param	int
	 * @return	array
	 */
	public function getCategoryDetail($id, $wid)
	{
		$where = "id=$id and wid=$wid";
		return $this->_model->where($where)->find();
	}

	/**
	 * 修改状态
	 *
	 * @param	int
	 * @param	str
	 * @param	int
	 * @return	int/boole
	 */
	public function setStatus($id, $field, $status)
	{
		$data = array($field => $status);
		return $this->_model->where('id='.$id)->save($data);
	}

	/**
	 * 处理分类
	 *
	 * @param	array
	 * @param	int
	 * @param	int
	 * @return	array
	 */
	private function &_dumpTreeList($arr, $parentId = 0, $lv = 0)
	{
		$lv++; $tree = array(); 
		foreach ((array)$arr as $row) {

			if ($row['category_id'] == $parentId ) {

				$row['level'] = $lv - 1;
				if($row['category_id']!=0) $row['sty']   = "|";
				for($i = 0; $i < $row['level']; $i++) {

					$row['sty'] .= "--";
				}                                                                                                               
				$tree[] = $row;
				if ( $children = $this->_dumpTreeList($arr, $row['id'], $lv)) {

					$tree = array_merge($tree, $children); 
				} 
			} 
		} 
		return $tree; 
	}
}
?>
