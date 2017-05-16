<?php
/**
 * 用户发送的文本信息model
 * xujiang@cekasp.cn
 * 2015-01-13
 */
class ReceiveModel extends Model
{
	private	$_model;
	private $_pageSize;	// 每页记录数
	private $_where;	// 每页记录数

	public function __construct()
	{
		parent::__construct();
		$this->_pageSize = 10;
		$this->_model = M('member_message');
	}

	/**
	 * 设置每页数量
	 *
	 * @param	int
	 */
	public function setPageSize($num) {
		$this->_pageSize = $num;
		return $this;
	}

	/**
	 * 设置查询条件
	 *
	 * @param	array
	 */
	public function setWhere($where) {
		$this->_where = '';
		//$this->_where = "uid=".I("session.uid");
		$i = 0;
		if(is_array($where)) {
			foreach($where as $key => $val) {
				if($i>0) $this->_where .= " and ";
				$this->_where .= $key."='".$val."'";
				$i++;
			}
		}else {
			$this->_where = $where;
		}
		return $this;
	}

	/**
	 * 获取用户发送的文本信息列表
	 *
	 * @param	string		排序条件
	 * @return	array
	 */
	public function getList($page=1, $orderby="CreateTime desc") {

		//$sql="SELECT *,count(mid) as num FROM (select * from `".C("DB_PREFIX")."member_message` where ".$this->_where." order by ".$orderby.") as a group by `mid` order by ".$orderby." limit ".($page-1)*$this->_pageSize.",".$this->_pageSize;
$sql="SELECT
	a.*,b.*
FROM
	(
		SELECT
			mid,count(mid) num,
			max(CreateTime) time
		FROM
			wx_member_message
    where ".$this->_where."
		GROUP BY
			mid
		ORDER BY
			max(CreateTime) DESC
	) AS a
LEFT OUTER JOIN wx_member_message b ON a.mid = b.mid
AND a.time = b.CreateTime
  
ORDER BY
	a.time DESC limit ".($page-1)*$this->_pageSize.",".$this->_pageSize
;
 
		//echo $sql;exit;
		$m=M();
		$list=$m->query($sql);
		return $list;
	}

	/**
	 * 获取分页信息
	 *
	 * @param	str		链接地址
	 * @return	str
	 */
	public function getPageInfo($url) {
		// 获取记录总数
		$count = $this->_model->where($this->_where.$this->msghistory)->field("mid")->group("mid")->select();
		$count = count($count);

		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize,'',$url);
		return $page->show();
	}
}
?>
