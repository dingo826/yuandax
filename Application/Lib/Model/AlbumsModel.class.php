<?php
/**
 * 相册模型
 * xujiang@ceasp.cn
 * 2015-01-23
 */
class AlbumsModel extends Model
{
	private	$_category;		// 分类模型
	private $_albums;		// 相册模型
	private $_albumsPic;	// 照片模型
	private $_pageSize;


	public function __construct()
	{
		parent::__construct();
		$this->_pageSize = 10;
		$this->_category = M('app_weialbums_albums_category');
		$this->_albums = M('app_weialbums_albums');
		$this->_albumsPic = M('app_weialbums_photos');
	}

	/**
	 * 获取分类
	 *
	 * @param	int
	 * @param	int/str
	 * @param	boole
	 * @return	array
	 */
	public function getCategoryAll($wid, $page = 1, $assoc=false)
	{
		$where = 'wid='.$wid;

		// 获取列表
		$list = $page == 'all' ? 
			$this->_category->where($where)->select() : 
			$this->_findResult($this->_category, $where, $page);

		if(true === $assoc){
			foreach($list as $val){
				$newList[$val['id']] = $val;
			}
			$list = $newList;
		}
		return $list;
	}

	/**
	 * 添加相册
	 *
	 * @param	array
	 * @return	int/boole
	 */
	public function addAlbums($data)
	{
		$data['ctime'] = $data['etime'] = time();
		$status = $this->_albums->data($data)->add();
		return $status;
	}

	/**
	 * 获取相册
	 *
	 * @param	int
	 * @return	array
	 */
	public function getAlbum($abid)
	{
		$album = $this->_albums->find($abid);
		return $album;
	}

	/**
	 * 修改相册
	 *
	 * @param	array
	 * @return	int/boole
	 */
	public function editAlbums($data)
	{
		$data['etime'] = time();
		$status = $this->_albums
			->where('id='.$data['id'].' and wid='.$data['wid'])
			->save($data);
		return $status;
	}

	/**
	 * 获取相册列表
	 *
	 * @param	int		社区id
	 * @param	int		页码
	 * @return	array
	 */
	public function getAlbumsAll($wid, $page=1)
	{
		//$where = 'aid='.$wid;
		$where = 'wid='.$wid.' and isadmin=1';
		return $this->_findResult($this->_albums, $where, $page);
	}
	
	/**
	 * 根据分类查找相册
	 *
	 * @param	int		社区id
	 * @param	int		分类id
	 * @param	int		页码
	 * @return	array
	 */
	public function categoryFindAlbums($wid, $cid, $page=1)
	{
		$where = 'wid='.$wid.' and cid='.$cid;
		return $this->_findResult($this->_albums, $where, $page);
	}

	/**
	 * 获取全部照片
	 *
	 * @param	int		社区id
	 * @param	int		页码
	 * @return	array
	 */
	public function getPhotosAll($wid, $page=1)
	{
		// 获取当前用户所有相册id
		$ids = $this->_albums->where('wid='.$wid.' and isadmin=1')->getField('id', true);
		$ids = "'" . implode("','", $ids) . "'";
		$where = 'abid in ('.$ids.')';
		return $this->_findResult($this->_albumsPic, $where, $page, 'photoid desc');
	}

	/**
	 * 获取当前相册照片
	 *
	 * @param	int		相册id
	 * @param	int		页码
	 * @return	array
	 */
	public function albumsFindPhotos($aid, $page=1)
	{
		$where = 'abid='.$aid;
		return $this->_findResult($this->_albumsPic, $where, $page, 'photoid desc');
	}

	/**
	 * 统计分类数量
	 *
	 * @param	int
	 * @return	array
	 */
	public function countCategory($wid)
	{
		$res = $this->_albums
			->field('cid, count(*) as total')
			->where('wid='.$wid.' and isadmin=1')
			->group('cid')
			->select();
		foreach($res as $val){
			$newsRes[$val['cid']] = $val;
		}
		$res = $newsRes;
		return $res;
	}

	/**
	 * 获取照片详情
	 *
	 * @param	int
	 * @return	array
	 */
	public function getPhotoDetail($id)
	{
		return $this->_albumsPic->where('photoid='.$id)->find();
	}

	/**
	 * 获取分页信息
	 *
	 * @param	str		数据类型
	 * @param	int		社区id
	 * @param	str		链接地址
	 * @param	int		分类id
	 * @return	str
	 */
	public function getPageInfo($type, $wid, $url, $cid=null)
	{
		// 查询条件
		$where = (!empty($cid) && is_numeric($cid)) ? 
			'wid='.$wid.' and cid='.$cid : 'wid='.$wid;

		// 数据表模型
		$tmp = '_'.$type;
		$model = $this->{$tmp};

		// 获取记录总数
		$count = $model->where($where)->count();

		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize,'',$url);
		return $page->show();
	}

	/**
	 * 设置照片分页
	 *
	 * @param	int
	 * @param	int
	 * @return	array
	 */
	public function getPhotoPage($url, $abid=null, $wid=null)
	{
		if( !empty($abid) ){
			$where = 'abid='.$abid;
		}else{
			// 获取当前用户所有相册id
			$ids = $this->_albums->where('wid='.$wid)->getField('id', true);
			$ids = "'" . implode("','", $ids) . "'";
			$where = 'abid in ('.$ids.')';
		}	
		
		// 获取记录总数
		$count = $this->_albumsPic->where($where)->count();

		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize,'',$url);
		return $page->show();
	}

	/**
	 * 修改分类
	 * 
	 * @param	array
	 * @return	mix
	 */
	public function editAlbumsCategory($data)
	{
		$status = $this->_albums
			->where('id in ('.$data['ids'].') and wid='.$data['wid'])
			->save($data);
		return $status;
	}

	/**
	 * 查询记录
	 *
	 * @param	obj		数据表模型
	 * @param	str		查询条件
	 * @param	int		页码
	 * @param	str		排序方式
	 * @return	array
	 */
	private function _findResult($model, $where, $page, $order = 'id desc')
	{
		$list = $model
			->where($where)
			->page($page,$this->_pageSize)
			->order($order)
			->select();

		return $list;
	}
}
?>
