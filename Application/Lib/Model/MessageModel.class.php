<?php
/**
 * 留言model
 * xujiang@cekasp.cn
 * 2015-01-16
 */
class MessageModel extends Model
{
	private	$_model;
	private $_topic;	// 话题model
	private $_dialogue;	// 对话model
	private $_member;	// 居民model
	private $_backlist;	// 黑名单model
	private $_boardSet;	// 留言板设置model
	private $_boardAdmin;	// 留言板管理员model
	private $_pageSize;	// 每页记录数

	public function __construct()
	{
		parent::__construct();
		$this->_pageSize = 10;
		$this->_model = M('board_message');
		$this->_topic = M('app_board_topic');
		$this->_backlist = M('app_board_backlist');
		$this->_dialogue = M('app_board_dialogue');
		$this->_boardSet = M('app_board');
		$this->_boardAdmin = M('app_board_admin');
	}

	/**
	 * 设置每页数量
	 *
	 * @param	int
	 */
	public function setPageSize($num)
	{
		if( !empty($num) && is_numeric($num) )
			$this->_pageSize = $num;
	}

	/**
	 * 增加话题
	 *
	 * @param	int	平台id
	 * @param	int 留言板id
	 * @param	str	openid
	 * @param	str	话题
	 * @param	int	用户设定是否可公开 0：不可公开，1：可以公开
	 * @return	mix
	 */
	public function addTopic($wid, $bid, $openid, $content, $userPublic)
	{
		$data = array(
			'bid'		=> $bid,
			'openid'	=> $openid,
			'contents'	=> $content,
			'user_public' => $userPublic,
			'createtime'  => time(),	
		);

		// 话题公开状态继承自留言板设置
		$set = $this->getBoardSet($wid);
		$data['public'] = $set['public'];
		return $this->_topic->data($data)->add();
	}

	/**
	 * 获取留言板设置
	 *
	 * @param	int	平台id
	 * @return	array
	 */
	public function getBoardSet($wid)
	{
		return $this->_boardSet->where('wid='.$wid)->find();
	}

	/**
	 * 保存留言板设置
	 *
	 * @param	int		平台id
	 * @param	array	待保存数据
	 * @return	boole
	 */
	public function saveBoardSet($wid, $data)
	{
		$adminlist = $data["openidlist"];
		$data['wid'] = $wid;
		unset($data['openidlist']);

		// 留言板设置存在则修改，不存在新增
		if($data['id'] > 0){
			$bid = $data['id'];
			$status = $this->_boardSet->where('id='.$bid)->data($data)->save();
			if(false === $status) return false;
		}else{
			$data['createtime'] = date('Y-m-d H:i:s', time());
			$bid = $this->_boardSet->data($data)->add();
			if(false === $bid) return false;
		}

		// 更新管理员列表
		foreach($adminlist as $v){
			$adminData[] = array(
				'bid'	=> $bid,
				'openid'=> $v,
			);
		}

		// 删除原有管理员
		if($data['id'] > 0){
			$status = $this->_boardAdmin->where('bid='.$bid)->delete();
		}
		// 加入新的管理员
		$this->_boardAdmin->addAll($adminData);
		return true;
	}

	/**
	 * 获取话题列表
	 *
	 * @param	int	留言板id
	 * @param	str	URL
	 * @param	str	请求来源	before 前台/after 后台
	 * @param	int 话题状态
	 * @param	int/str	页码
	 * @param	str	openid
	 * @return	array
	 */
	public function getTopicList($bid, $url, $type = 'after', $status = 'all', $p = 1, $openid=null)
	{
		$where[] = 't.bid='.$bid;

		// 设置话题状态查询类型
		if( !empty($status) && is_numeric($status) )
			$where[] = 't.status=' . $status;

		// 根据请求来源设置查询
		if($type == 'before'){
			// 过滤黑名单
			$openids = $this->_getBackListIds($bid);
			if( $openids != '' )
				$where[] = 't.openid not in (' .$openids. ')';

			// 过滤非公开话题
			if( !empty($openid) )
				$where[] = "((t.public=1 and t.user_public=1) or (t.openid='".$openid."'))";
			else
				$where[] = 't.public=1 and t.user_public=1';
		}
		$where = implode(' and ', $where);

		//查询记录
		$res['list'] = $this->_fetch($where, $p);
		$res['page'] = $this->_getPaging($where, $url);
		return $res;
	}

	/**
	 * 获取话题内容
	 *
	 * @param	int	话题id
	 * @return	array
	 */
	public function getTopicDetail($tid)
	{
		$where = 'id='.$tid;
		return $this->_topic->where($where)->find();
	}

	/**
	 * 获取由我发起的话题
	 *	无关公开与非
	 * 
	 * @param	int	留言板id
	 * @param	str	openid
	 * @param	str	URL
	 * @param	int/str	页码
	 * @return	array
	 */
	public function getMyTopic($bid, $openid, $url, $p = 1)
	{
		if( empty($openid) && !is_string($openid) )
			throw new Exception('无效的的openid');

		// 检查该用户是否在黑名单中，如果存在不显示其留言
		$id = $this->_backlist
			->where("bid=".$bid." and openid='".$openid."'")
			->getField('id');
		if($id > 0){
			// 在黑名单中
			$res['list'] = array();
		}else{
			// 设置查询条件
			$where = "t.bid=" . $bid . " and t.openid='" .$openid. "'";
			$res['list'] = $this->_fetch($where, $p);
			$res['page'] = $this->_getPaging($where, $url);
		}
	
		return $res;
	}

	/**
	 * 获取当前话题的对话内容
	 *
	 * @param	int	留言板id
	 * @param	int	话题id
	 * @param	str	请求来源	before 前台/after 后台
	 * @return	array
	 */
	public function getTopicContent($bid, $tid, $type = 'after')
	{
		$where[] = 'tid='.$tid;

		// 根据应用场景设置查询条件
		if($type == 'before'){
			// 微信端调用需要过滤黑名单成员的留言
			$openids = $this->_getBackListIds($bid);
			if( !empty($openids) )
				$where[] = 'openid not in (' .$openids. ')';
			$newReply = array('admin_new'=>0);	// 用户已查看管理员新回复
		}elseif($type == 'after'){
			// 后台管理员查看时更新话题状态
			$set = array('status'=>1);
			$newReply = array('user_new'=>0);	// 管理员已查看用户新回复
			$this->_topic->where('id='.$tid.' and status=0')->data($set)->save();
		}
		$this->_topic->where('id='.$tid)->data($newReply)->save();	// 修改话题是否有新回复标记

		// 获取对话过程
		$where = implode(' and ', $where);
		$res = $this->_dialogue->where($where)->order('createtime desc')->select();
		if( empty($res) ) $res = array();

		//获取话题本身
		$topic = $this->_topic->where('id='.$tid)->field('openid,contents,createtime,status')->find();
		$topic['identity'] = 0;
		array_push($res, $topic);
		return $res;
	}

	/**
	 * 获取话题的最终对话
	 *
	 * @param	str		话题
	 * @param	int		留言板id
	 * @param	str		请求来源	before 前台/after 后台
	 * @return	array
	 */
	public function getLastDialogue($topicId, $bid, $type = 'after')
	{
		$where[] = 'tid in ('.$topicId.')';

		// 根据应用场景设置查询条件
		if($type == 'before'){
			// 微信端调用需要过滤黑名单成员的留言
			$openids = $this->_getBackListIds($bid);
			$where[] = 'openid not in (' .$openids. ')';
		}

		// 查询语句
		$where = implode(' and ', $where);
		$sql = 'select * from (select * from wx_app_board_dialogue where '.
			$where.' order by id desc) as tmp group by tid order'.
			' by id desc';
		$list = $this->_dialogue->query($sql);

		foreach($list as $key => $val){
			$newList[$val['tid']] = $val;
		}
		return $newList;
	}

	/**
	 * 更新话题状态
	 *
	 * @param	int/str	话题id
	 * @param	int		状态
	 * @return	boole
	 */
	public function updateTopicStatus($id, $status)
	{
		if( !empty($id) ){
			$set = array('status'=>$status);
			return $this->_topic->where('id in ('.$id.')')->data($set)->save();
		}
		return false;
	}

	/**
	 * 回复话题
	 *
	 * @param	int	话题id
	 * @param	str	回复内容
	 * @param	str	回复人类型	ordinary：普通用户/admin：管理员
	 * @param	str	openid
	 * @return	boole
	 */
	public function replyTopic($tid, $contents, $rePeopleType='ordinary', $openid=null)
	{
		$data = array(
			'tid' => $tid,
			'contents' => $contents,
			'createtime' => time()
		);

		if( !empty($openid) && is_string($openid) )
			$data['openid'] = $openid;

		//身份设置
		if($rePeopleType == 'admin')
			$data['identity'] = 1;
		
		$affrow = $this->_dialogue->data($data)->add();
		if( false !== $affrow ){
			// 回复成功后更改话题状态
			$this->updateTopicStatus($tid, 2);

			$set["modifytime"] = date("Y-m-d H:i:s");
			// 更改话题新回复标记
			if($rePeopleType == 'ordinary')
				$set = array('user_new'=>1);	// 普通用户有新回复
			elseif($rePeopleType == 'admin')
				$set = array('admin_new'=>1);	// 管理员有新回复
			
			$affrow = $this->_topic->where('id='.$tid)->data($set)->save();
			return $affrow;
		}
		return $affrow;
	}

	/**
	 * 获取黑名单
	 *
	 * @param	int	留言板id
	 * @return	array
	 */
	public function getBackList($bid)
	{
		return $this->_backlist->where('bid='.$bid)->select();
	}

	/**
	 * 设置公开属性
	 *
	 * @param	int/str	话题id
	 * @param	int		is_public 0:不公开/1:公开
	 * @return	mix
	 */
	public function setPublic($tids, $is_public)
	{
		//前置条件：此话题发起人未设置不可公开
		$where = 'id in ('.$tids.') and user_public=1';
		$set = array('public'=>$is_public);
		return $this->_topic->where($where)->data($set)->save();
	}

	/**
	 * 删除话题
	 *
	 * @param	int/str	话题id
	 * @return	mix
	 */
	public function delTopic($tids)
	{
		if( !empty($tids) ){
			$where = 'id in ('.$tids.')';
			return $this->_topic->where($where)->delete();
		}
		return false;
	}

	/**
	 * 检查当前用户是否有历史话题
	 *
	 * @param	int	留言板id
	 * @param	str	openid
	 * @return	int	历史话题数量
	 */
	public function checkHistoryTopic($bid, $openid)
	{
		return $this->_topic->where("bid=".$bid." and openid='".$openid."'")->count();
	}

	/**
	 * 加入黑名单
	 *
	 * @param	int	留言板id
	 * @param	str	openid
	 * @return	mix
	 */
	public function joinBackList($bid, $openid)
	{
		$data = array('openid'=>$openid, 'bid'=>$bid);
		return $this->_backlist->data($data)->add();
	}

	/**
	 * 从黑名单恢复
	 *	 
	 * @param	int	留言板id
	 * @param	str	openid
	 * @return	mix
	 */
	public function undoBackList($bid, $openid)
	{
		$where = "bid=".$bid." and openid='".$openid."'";
		return $this->_backlist->where($where)->delete();
	}

	/**
	 * 获取管理员列表
	 *
	 * @param	int	留言板id
	 * @return	array
	 */
	public function getAdminList($bid)
	{
		return $this->_boardAdmin->where('bid='.$bid)->select();
	}

	/**
	 * 生成黑名单id
	 *
	 * @param	int	留言板id
	 * @return	str
	 */
	private function _getBackListIds($bid)
	{
		$backList = $this->getBackList($bid);
		$openids = "";
		foreach($backList as $val){
			$openids .= "'" . $val['openid'] . "',";
		}
		$openids = trim($openids, ',');
		return $openids;
	}

	/**
	 * 获取查询结果
	 *
	 * @param	str		查询条件
	 * @param	int/str	页码
	 * @return	array
	 */
	private function _fetch($where, $p)
	{
		$res = M()->table(C('DB_PREFIX') . 'app_board_topic t')
			->join('left join '.C('DB_PREFIX') . 'app_mcard_member m ON t.openid=m.wechatid')
			->field('t.*,m.name,m.headimgurl')
			->where($where)
			->order('t.id desc');
		if($p != 'all') $res->page($p, $this->_pageSize);
		return $res->select();
	}

	/**
	 * 设置分页信息
	 *
	 * @param	str	统计条件
	 * @param	str	URL
	 * @return	str
	 */
	private function _getPaging($where, $url)
	{
		$count = M()->table(C('DB_PREFIX') . 'app_board_topic t')
			->where($where)
			->count();
		
		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize,'',$url);
		return $page->show();
	}


	/**
	 * 统计新留言数量
	 *
	 * @param	int	留言板id
	 * @param	str	ordinary：普通用户/admin：管理员
	 * @return	int	数量
	 */
	public function countNewMsg($bid, $peopleType = 'ordinary')
	{
		$where[] = 'bid='.$bid;
		if( $peopleType == 'ordinary' )
			$where[] = 'admin_new=1';
		elseif( $peopleType == 'admin' )
			$where[] = 'user_new=1';

		$where = implode(' and ', $where);
		return $this->_topic->where($where)->count();
	}

	/**
	 * 统计未处理留言数量
	 *
	 * @param	int	社区id
	 * @return	int/boole
	 */
	public function countUntreatedMsg($wid)
	{
		if( !empty($wid) && is_numeric($wid) ){
			$where = "boardid=(select id from wx_board where wid=$wid)".
				" and msg_st=1 and openid is not null";
			return $this->_model->where($where)->count();
		}
		return false;
	}
}
