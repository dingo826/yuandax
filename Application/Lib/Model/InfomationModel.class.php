<?php
/**
 * 资讯库model
 * xujiang@cekasp.cn
 * 2015-01-12
 */
class InfomationModel extends Model
{
	private	$_model;
	private $_pageSize;	// 每页记录数
	private $_fields;	// 需要入库的字段
	private $_massRecord;	// 发送记录model

	public function __construct()
	{
		parent::__construct();
		$this->_pageSize = 50;
		$this->_fields = array(
			'uid','wid','gzid','title',
			'picurl','cid','content','type',
			'link','business_type','desc',
			'business_value','top','news_reco'
		);
		$this->_model = M('app_article');
		$this->_massRecord = M('app_massrecord');
	}

	/**
	 * 设置每页数量
	 *
	 * @param	int
	 */
	public function setPageSize($num)
	{
		$this->_pageSize = $num;
	}

	/**
	 * 获取全部资讯
	 *
	 * @param	int		社区id
	 * @param	int		页码
	 * @param	str		标题
	 * @return	array
	 */
	public function getInfoAll($wid, $page=1, $title=null)
	{
		$where = 'wid='.$wid;
		if( $title != '') $where .= " and title like '%".$title."%'";
		return $this->_findResult($where, $page);
	}

	/**
	 * 根据分类查找资讯
	 *
	 * @param	int		社区id
	 * @param	int		分类id
	 * @param	int		页码
	 * @param	str		标题
	 * @return	array
	 */
	public function categoryFindInfo($wid, $cid, $page=1, $title=null)
	{
		$where = 'wid='.$wid.' and cid='.$cid;
		if( $title != '') $where .= " and title like '%".$title."%'";
		return $this->_findResult($where, $page);
	}

	/**
	 * 获取分页信息
	 *
	 * @param	int		社区id
	 * @param	str		链接地址
	 * @param	int		分类id
	 * @param	str		标题
	 * @return	str
	 */
	public function getPageInfo($wid, $url, $cid=null, $title=null)
	{
		if(!empty($cid) && is_numeric($cid))
			$where = 'wid='.$wid.' and cid='.$cid;
		else
			$where = 'wid='.$wid;

		if( $title != '') $where .= " and title like '%".$title."%'";

		// 获取记录总数
		$count = $this->_model->where($where)->count();

		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize,'',$url);
		return $page->show();
	}

	/**
	 * 通过id查询详情列表
	 *
	 * @param	str
	 * @param	boole
	 * @return	array
	 */
	public function getDetailedList($ids, $assoc = false)
	{
		$list = $this->_model->where('id in ('.$ids.')')->select();

		if($assoc){
			$newList = array();
			foreach($list as $val){
				$newList[$val['id']] = $val;
			}
			$list = $newList;
		}

		return $list;
	}

	/**
	 * 获取资讯详情
	 *
	 * @param	int/str		记录id
	 * @param	boole		返回数据格式 true:id关联数据格式，默认数字索引
	 * @return	array
	 */
	public function getInfoDetailed($id, $assoc = false)
	{
		$list = $this->_model->where('id in ('.$id.')')->find();

		if($assoc){
			$newList = array();
			foreach($list as $val){
				$newList[$val['id']] = $val;
			}
			$list = $newList;
		}

		return $list;
	}

	/**
	 * 添加资讯
	 *
	 * @param	array
	 * @return	int/boole
	 */
	public function addInfo($data)
	{
		$data = $this->_setSaveField($data);
		return $this->_model->data($data)->add();
	}

	/**
	 * 修改资讯
	 *
	 * @param	array
	 * @param	int		记录id
	 * @return	int/boole
	 */
	public function editInfo($set, $id)
	{
		$set = $this->_setSaveField($set, 'update');
		if(!empty($id) && is_numeric($id))
			return $this->_model->where('id='.$id)->data($set)->save();
		return false;
	}

	/**
	 * 删除资讯
	 *
	 * @param	int		记录id
	 * @return	int/boole
	 */
	public function delete($id)
	{
		if(!empty($id) && is_numeric($id))
			return $this->_model->where('id='.$id)->delete();
		return false;
	}

	/**
	 * 批量删除资讯
	 *
	 * @param	string	删除条件
	 * @return	mix
	 */
	public function batchDel($where)
	{
		if( !empty($where) && is_string($where) )
			return $this->_model->where($where)->delete();
		return false;
	}

	/**
	 * 修改置顶或推荐状态
	 *
	 * @param	int
	 * @param	int
	 * @param	str		默认修改置顶状态
	 * @return	boole
	 */
	public function setStatus($id, $status, $type="top")
	{
		$set[$type] = $status;
		return $this->_model->where('id='.$id)->data($set)->save();
	}

	/**
	 * 资讯发送次数统计
	 *
	 * @param	int		平台id
	 * @return	boole
	 */
	public function infoSendSta($wid)
	{
		// 获取发送记录
		$list = $this->_massRecord->where("wid=".$wid." and type='mpnews'")->select();
		
		// 统计出现次数
		$ids = '';
		foreach($list as $val){
			$ids .= $val['material_ids'].',';
		}
		$ids = trim($ids, ',');
		$ids = explode(',', $ids);
		$count = array_count_values($ids);
		foreach($count as $id => $num){
			$this->_model
				->where('id='.$id)
				->data(array('send_count'=>$num))
				->save();
		}

		return true;
	}

	/**
	 * 发送统计累计
	 *
	 * @param	str	资讯ids
	 * @param	int	平台id
	 * @return	mix
	 */
	public function sendCumulative($ids, $wid)
	{
		return $this->_model->where('id in ('.$ids.')')->setInc('send_count');
	}

	/**
	 * 查询记录
	 *
	 * @param	str		查询条件
	 * @param	int		页码
	 * @return	array
	 */
	private function _findResult($where, $page)
	{
		$list = $this->_model
			->where($where)
			->page($page,$this->_pageSize)
			->order('id desc')
			->select();

		return $list;
	}

	/**
	 * 入库字段设置
	 *
	 * @param	array
	 * @param	str		add/update
	 * @return	array
	 */
	private function _setSaveField($post, $type='add')
	{
		$data = array();
		foreach($this->_fields as $field){
			$data[$field] = $post[$field];
		}
		$data['content'] = htmlspecialchars($data['content']);

		if (count($post['votetouser'][0]) < 10)
			$data['otherid']	= implode(',', $post['votetouser'][0]);
		if (count($post['votetouser'][1]) < 10)
			$data['recommend']	= implode(',', $post['votetouser'][1]);

		if( !isset($data['top']) ) $data['top'] = 0;
		if( !isset($data['news_reco']) ) $data['news_reco'] = 0;
		// 添加/更新时间设置
		$data['etime'] = time();
		if( $type == 'add' ){
			$data['ctime'] = $data['etime'];
		}
		return $data;
	}

	/**
	 * 将选中图文置顶显示
	 *
	 * @param	array	原始图文
	 * @param	array	选中图文
	 * @return	array	重新排序结果
	 */
	public function selectedTop($oriMpnews, $selMpnews)
	{
		//$oriMap = array_column($oriMpnews, 'id', 'id');  // PHP 5 >= 5.5.0 适用
		$oriMap = array();
		foreach($oriMpnews as $key => $newVal)
		{
			$oriMap[$newVal['id']] = $key;
		}
	
		foreach($selMpnews as $key => $val){
			if( isset($oriMap[$key]) ){
				// 提取数据放到首位
				$tmp = $oriMpnews[$oriMap[$key]];
				unset($oriMpnews[$oriMap[$key]]);
				array_unshift($oriMpnews, $tmp);
			}
		}
		return $oriMpnews;
	}
}
