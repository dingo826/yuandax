<?php
/**
 * 圈子前台App model
 */
class CircleModel extends Model
{
	private	$_model;
	private	$_memberModel;

	public function __construct()
	{
		parent::__construct();
		$this->_model = M('app_circle');
		$this->_memberModel = M('app_mcard_member');
	}

	/**
	 * 获取当前服务号所属圈子
	 *
	 * @param	int
	 * @return	array
	 */
	public function getCircleAll($wid) {
		$circleList = $this->_model->where('wid=' . $wid . ' and status=1')->select();
		return $circleList;
	}

	/**
	 * 获取我的圈子
	 *
	 * @param	int
	 * @param	int
	 * @return	array
	 */
	public function getMyCircle($user, $wid) {
		$uid      = $user['id'];
		$myCircle = array();

		$circleIds = $user['circle_ids'];

		// 已加入圈子id
		$circleIds = json_decode($circleIds, true);

		// 获取全部圈子
		$allCircleList = $this->getCircleAll($wid);

		// 圈子分类(已加入，未加入)
		foreach($allCircleList as $val){
			$circleMemberlist = json_decode($val['member_list'], true);
			if( isset($circleIds[$val['id']]) ) {
				$myCircle['join'][] = $val;	// 已加入
			}else if($circleMemberlist[$uid]==1) {
				$val['isverf']         = 2;
				$myCircle['notJoin'][] = $val;	// 已加入
				$myCircle['verf'][]    = $val;	// 等待审核
			}else {
				$val['isverf']         = 1;
				$myCircle['notJoin'][] = $val;	// 未加入
			}
		}

		return $myCircle;
	}

	/**
	 * 获取圈子信息
	 *
	 * @param	int
	 * @return	array
	 */
	public function getCircleDetails($cid) {
		return $details = $this->_model->where('id=' . $cid)->find();
	}

	/**
	 * 获取圈子关联新闻信息
	 *
	 * @param	int
	 * @param	int
	 * @return	array
	 */
	public function getAssociatedNews($cid, $wid) {
		$newsList = M('app_article')->where('cid=' . $cid . ' and wid=' . $wid)->order('id desc')->select();
		return $newsList;
	}

	/**
	 * 申请加入圈子
	 *
	 * @param	int
	 * @param	int
	 * @return	boole
	 */
	public function join($circleId, $uid)
	{
		$data = array();
		$memberList = $this->_getCircleMemberList($circleId);
		$memberList[$uid] = 1;
		$data['member_list'] = json_encode($memberList);

		// 保存
		$affrow = $this->_model->where('id='.$circleId)->save($data);
		return true;
	}

	/**
	 * 获取当前栏目关联圈子
	 *
	 * @param	int
	 * @param	int
	 * @return	array
	 */
	public function getAssociatedCircle($newsCid, $aid)
	{
		$circleList = $this->_model
			->field('id,name,logo,description')
			->where('aid=' . $aid . ' and status=1 and news_id=' . $newsCid)
			->select();

		return $circleList;
	}

	/**
	 * 获取当前用户列表
	 *
	 * @param	int
	 * @return	array
	 */
	public function _getCircleMemberList($circleId)
	{
		$memberList = $this->_model->where('id='.$circleId)->getField('member_list');
		$memberList = json_decode($memberList, true);
		return $memberList;
	}
}
?>
