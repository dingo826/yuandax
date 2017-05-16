<?php
/**
 * 高级群发模型
 * xujiang@ceasp.cn
 * 2014-12-22
 */
class MassModel
{
	protected	$data;		// 推送数据
	protected	$massType;	// 群发类型	group：按群组发送，openid：自定义
	protected	$isToAll;	// 发送范围	true：所有用户，false：指定用户
	protected	$groupId;	// 用户组
	protected	$openidList;	// 自定义用户列表
	protected	$msgType;	// 消息类型
	protected	$mediaId;	// 多媒体id
	protected	$contents;	// 文本内容
	protected	$videoInfo;	// 视频信息
	private		$_msgTypeList;	// 消息类型列表
	private		$_debug;	// 预览模式
	private		$_debugOpenId;	// 预览用户



	public function __construct()
	{
		//parent::__construct();
		$this->_msgTypeList = array('mpnews','text','image','voice','music','mpvideo');
		$this->data = array();
	}

	/**
	 * 设置发送类型
	 *
	 * @param	string
	 * @return	$this
	 */
	public function setMassType($type)
	{
		if( isset($type) && is_string($type) ){
			if( $type != 'group' && $type != 'openid' ) die('无效的群发类型！');
			$this->massType = $type;
		}else{
			die('发送类型不符预期！');
		}

		return $this;
	}

	/**
	 * 设置发送范围
	 *
	 * @param	boolean
	 * @return	$this
	 */
	public function setIsToAll($status)
	{
		if( isset($status) && is_bool($status) ){
			$this->isToAll = $status;
		}else{
			die('范围设定值不符预期！');
		}
		return $this;
	}

	/**
	 * 设置groupid
	 *
	 * @param	int
	 * @return	$this
	 */
	public function setGroupId($groupId)
	{
		if( isset($groupId) && is_numeric($groupId) )
			$this->groupId = $groupId;
		else
			die('用户组id不符合预期！');
		return $this;
	}

	/**
	 * 设置自定义用户列表
	 *
	 * @param	array
	 * @return	$this
	 */
	public function setOpenidList($openidList)
	{
		if( isset($openidList) && is_array($openidList) )
			$this->openidList = $openidList;
		else
			die('自定义用户列表不符合预期！');
		return $this;
	}

	/**
	 * 设置发送的消息类型
	 *
	 * @param	string
	 * @return	$this
	 */
	public function setMsgType($type) {
		if( isset($type) && is_string($type) ){

			// 设定值在预设列表中不存在，则为无效值
			if( false !== array_search($type, $this->_msgTypeList) )
				$this->msgType = $type;
			else
				die('发送消息类型不符合预期');
		}else{
			die('发送消息类型不符合预期');
		}
		return $this;
	}

	/**
	 * 设置media_id
	 *
	 * @param	int
	 * @return	$this
	 */
	public function setMediaId($mediaId)
	{
		if( isset($mediaId) && is_string($mediaId) )
			$this->mediaId = $mediaId;
		else
			die('media_id不符预期！');
		return $this;
	}

	/**
	 * 设置文本内容
	 *
	 * @param	
	 * @return	$this
	 */
	public function setContents($contents)
	{
		if( isset($contents) && is_string($contents) )
			$this->contents = $contents;
		else
			die('文本内容不符预期！');
		return $this;
	}

	/**
	 * 设置视频内容
	 *
	 * @param	array
	 * @return	$this
	 */
	public function setVideo($videoInfo)
	{
		if( isset($videoInfo) && is_array($videoInfo) )
			$this->videoInfo = $videoInfo;
		else
			die('视频信息不符预期！');
		return $this;
	}

	/**
	 * 预览模式,设置预览用户
	 * 
	 * @param	str
	 */
	public function setPreview($openID)
	{
		$this->massType = 'debug';
		$this->_debugOpenId = $openID;
	}

	/**
	 * 设置推送数据
	 */
	private	function _setPostData()
	{
		// 检查并设置
		$this->_checkWrite();

		// 根据消息类型设置消息内容
		switch ($this->msgType){
			case "text":	// 文本消息
				$this->_massText();
				break;
			case "mpnews":	// 图文消息
				$this->_massMediaId($this->msgType);
				break;
			case "image":	// 图片消息
				$this->_massMediaId($this->msgType);
				break;
			case "music":	// 音乐消息
				$this->_massMediaId($this->msgType);
				break;
			case "voice":	// 语音消息
				$this->_massMediaId($this->msgType);
				break;
			case "mpvideo":	// 视频消息
				$this->_massVideo();
				break;
		}

		$this->data = json_encode($this->data, JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE 是php 5.4 才支持的
		//$this->data = htmlspecialchars_decode(urldecode(json_encode($this->data)));
	}

	/**
	 * 群发设置检查并写入发送流
	 *
	 * @return	null
	 */
	private function _checkWrite()
	{
		// 检查群发类型设置情况
		if( !isset($this->massType) ) die('请设置群发类型！');

		if( $this->massType == 'group' ){

			if( $this->isToAll ){
				$this->data['filter']['is_to_all'] = true;
			}else{
				// 如果发送方式为按群组发送，并且有指定范围时检查群组id是否有设置
				if( !isset($this->groupId) ) die('请设置群组id！');
				$this->data['filter']['is_to_all'] = false;
				$this->data['filter']['group_id'] = $this->groupId;
			}
		}elseif($this->massType == 'openid'){

			// 检查自定义用户列表设置情况
			if( isset($this->openidList) )
				$this->data['touser'] = $this->openidList;
			else
				die('请设置用户列表！');
		}elseif($this->massType == 'debug'){
			$this->data['touser'] = $this->_debugOpenId;
		}

		// 检查消息类型
		if( isset($this->msgType) )
			$this->data['msgtype'] = $this->msgType;
		else
			die('请设置消息类型');
	}
	
	
	/**
	 * 检查文本设置情况写入发送数据
	 *
	 * @return	null
	 */
	private function _massText()
	{
		if( !isset($this->contents) ) die('请设置文本内容！');
		//$this->data['text'] = array('content'=>urlencode($this->contents));
		$this->contents=str_replace('"',"'",$this->contents);
		//$this->data['text'] = array('content'=>urlencode($this->contents));
		$this->data['text'] = array('content'=>$this->contents);
		return;
	}

	/**
	 * 检查media_id设置情况写入发送数据
	 *
	 * @param	string
	 * @return	null
	 */
	private function _massMediaId($type)
	{
		if( !isset($this->mediaId) ) die('请设置media_id！');
		$this->data[$type] = array('media_id'=>$this->mediaId);
		$this->data["send_ignore_reprint"] = 1;
		return;
	}

	/**
	 * 检查视频信息写入发送数据
	 *
	 * @return	null
	 */
	private function _massVideo()
	{
		if( !isset($this->videoInfo) ) die('请设置视频消息信息！');
		$this->data['video'] = $this->videoInfo;
		return;
	}

	/**
	 * 获取推送数据
	 *
	 * @param	json
	 */
	public function getPostData()
	{
		// 推送数据已设置时直接返回，否则先设置再返回
		if( empty($this->data) ) $this->_setPostData();
		return $this->data;
	}
}
?>
