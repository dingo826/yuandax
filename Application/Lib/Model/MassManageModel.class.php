<?php
/**
 * 群发管理model
 */
class MassManageModel extends Model {
	private	$_model;
	private $_infomation;	// 资料库model
	private $_newspaper;	// 资料库model
	private $_thump;		// 缩略图生成管理

	public function __construct(){
		parent::__construct();
		$this->_pageSize = 12;
		$this->_model      = M('app_massrecord');
		$this->_thump      = D('ImagickManage');
		$this->_infomation = D('Infomation');
		$this->_newspaper  = D('Newspaper');
	}

	/**
	 * 获取记录详情
	 *
	 * @param	int
	 * @return	array
	 */
	public function getDetail($id) {
		return $this->_model->where('id='.$id)->find();
	}

	/**
	 * 获取群发记录
	 *
	 * @param	int
	 * @param	int
	 * @param	str
	 * @return	array
	 */
	public function getRecordList($wid, $page=1, $status=null) {
		// 设置查询条件
		$where = 'wid='.$wid;
		if( !empty($status) && is_string($status) )
			$where .= " and status='$status'";
		$list = $this->_model
			->where($where)
			->page($page,$this->_pageSize)
			->order('id desc')
			->select();

		// 根据material_ids查询图文详情
		foreach($list as $key => $val){
			if($val['type'] == 'mpnews'){
				$arr = explode(',', $val['material_ids']);
				//print_r($arr);exit;
				if($arr) {
					$mateIds = implode(',', $arr);
					$newsList = $this->_infomation->getDetailedList($mateIds, true);
					foreach($arr as $row) {
						//$list[$key]['news'] = $newsList;
						$list[$key]['news'][$row] = $newsList[$row];
					}
					
				}
				//$mateIds .= $val['material_ids'].',';
			}
		}

		// 获取资讯详情
		/*$mateIds = explode(',', trim($mateIds, ","));
		$mateIds = implode(',', array_unique($mateIds));
		$newsList = $this->_infomation->getDetailedList($mateIds, true);*/

		/** 
		 * 重组数据
		 * 通过material_ids获取的图文信息合并入列表中
		 */
		/*foreach($mpnews as $key => $val){
			foreach($val as $mateid){
				$list[$key]['news'][$mateid] = $newsList[$mateid];
			}
		}*/

		return $list;
	}

	/**
	 * 获取分页信息
	 *
	 * @param	int		社区id
	 * @param	str		链接地址
	 * @param	str		发送状态
	 * @return	str
	 */
	public function getPageInfo($wid, $status=null) {
		if(!empty($status) && is_string($status))
			$where = "wid=$wid and status='$status'";
		else
			$where = 'wid='.$wid;

		// 获取记录总数
		$count = $this->_model->where($where)->count();

		// 设置分页
		import("ORG.Util.Page");
		$page = new Page($count, $this->_pageSize);
		return $page->show();
	}

	/**
	 * 记录历史
	 *
	 * @param	array
	 * @param	int
	 * @param	str
	 * @return	int/boole
	 */
	public function addMassRecord($post, $wid, $status) {
		$ctime = $etime = time();
		$data = array(
			'wid'	   => $wid,
			'type'	   => $post['type'],
			'sendtime' => $post['sendtime'],
			'status'   => $status,
			'ctime'	   => $ctime,
			'etime'	   => $etime,
		);

		// 图文类型记录所用资讯id跟封面资讯id
		if($post['type'] == 'mpnews'){
			$data['material_ids'] = $post['material_ids'];
			$data['cover_id']     = $post['cover_id'];
		}elseif($post['type'] == 'text'){
			$data['content']      = $post['content'];
		}

		return $this->_model->data($data)->add();
	}

	/**
	 * 删除群发记录
	 *
	 * @param	int
	 * @return	int/boole
	 */
	public function delMassRecord($id) {
		if( isset($id) && is_numeric($id) )
			return $this->_model->where('id='.$id)->delete();
		else
			return false;
	}

	/**
	 * 组装图文获取mediaId
	 *
	 * @param	int		社区id
	 * @param	str		资料id
	 * @param	int		封面id
	 * @return	str/boole
	 */
	private function _assemblyNews($wid, $material_ids, $cover_id) {
		$materids = $material_ids;

		$materidsarr = explode(",", $material_ids);
		//print_r($materidsarr);
		foreach($materidsarr as $key => $row) {
			$sortorder[$row] = $key;
		}
		//print_r($sortorder);
		//exit;
		foreach($materidsarr as $mval) {
			if((string)intval($mval)==(string)$mval) {
				$newsid["article"][] = $mval;
			}else {
				$newsid["paper"][]   = $mval;
			}
		}
		//print_r($newsid);//exit;

		// 获取图文详情
		if($newsid["article"]) {
			//$newsList = $this->_infomation->getDetailedList($materids);
			$newsList = $this->_infomation->getDetailedList(implode(",", $newsid["article"]));
			//print_r($newsList);exit;
		}

		if($newsid["paper"]) {
			foreach($newsid["paper"] as $key => $row) {
				$temp2_arr  = explode("_", $row);
				$tempdetail = $this->_newspaper->getInfoDetailed($temp2_arr[1]);
				$tempdetail["npid"] = $row;
				$newsList[] = $tempdetail;
			}
		}
		//print_r($newsList);//exit;

		// 上传图片获取图片mediaId，组装图文消息
		$weixin = D("Weixin");
		$token  = $weixin->getToken($wid);
		//echo $access_token;exit;


		//$token  = getAccessToken(session('weixin'));
		$mpnewsJson = array();

		$sortarticles = $sortorder;

		foreach($newsList as $key => $val){
			//print_r($val);exit;
			$droot = $_SERVER['DOCUMENT_ROOT'];
			$file = $droot.$val['picurl'];

			$show_cover_pic = 1;
			if($val['show_cover_pic']==-1) $show_cover_pic = 0;
			
			$sourceurl = '';
			if($val['type']) {
				if($val['type']=='business' && $val['business_type']=='huodonglist') $sourceurl = D("Busitype")->getoneBusi($val);
			    else $sourceurl = getBusurl($val);
			}

			// 使用缩略图
			$crfile = $this->checkissysimg($val['picurl']);
			if(!$crfile) {
				$file = $droot.$val['picurl'];
				$file = $this->_createThump($file);
			}else {
				$file = $droot.$crfile;
			}
			$data = array('media' => '@'.$file);
			//echo $file;
			//$data = array('media' => new \CURLFile($file));
			$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token."&type=thumb";

			//$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token."&type=image";
			$res = curl_post($url, $data);
			//echo $res;//exit;
			$res = json_decode($res, true);
			if( !isset($res['thumb_media_id']) ) {
				alert(L("226wx_".$res['errcode']));
			}

			/*if( !isset($res['media_id']) ) {
				alert(L("226wx_".$res['errcode']));
			}*/

			$media_id = $res['thumb_media_id'];

			//$media_id = $res['media_id'];

			if((string)intval($cover_id)==(string)$cover_id) {
				if( $val['id'] == $cover_id ){
					$coverKey = $key;
				}
			}else {
				if( $val['npid'] == $cover_id ){
					$coverKey = $key;
				}
			}
			//echo htmlspecialchars_decode($val['content']);exit;
			

			$content = $this->_replaceStr(htmlspecialchars_decode($val['content']));
			$content = preg_replace("/(src=[\"|\'])(http:\/\/".C('ODOMIN').")(\/[^\"|\']+)([\"|\'])/isU", "$1$3$4", $content);
			
			$content = contentHandle($content, $token);

			if($val["npid"]) {
				$nowkey = $val["npid"];
			}else {
				$nowkey = $val["id"];
			}

			/*$articles[$key] = array(
				'thumb_media_id'     => $res['thumb_media_id'],
				'author'	         => '社区',
				'title'		         => $val['title'],
				'content_source_url' => $sourceurl,
				'content'	         => $content,
				'digest'	         => $val['desc'],
				'show_cover_pic'     => (string)$show_cover_pic,
			);*/
			
			$sortarticles[$nowkey] = array(
				'thumb_media_id'     => $media_id,
				'author'	         => '社区',
				'title'		         => $val['title'],
				'content_source_url' => $sourceurl,
				'content'	         => $content,
				'digest'	         => $val['desc'],
				'show_cover_pic'     => (string)$show_cover_pic,
			);
		}
		//print_r($articles);
		//echo $coverKey;
		//print_r($sortarticles);
		//exit;

		foreach($sortarticles as $key => $row) {
			$articles[] = $row;
		}
		//print_r($articles);exit;

		// 将封面换到首位
		//$coverItem = $articles[$coverKey];
		//unset($articles[$coverKey]);
		//array_unshift($articles, $coverItem);
		$mpnewsJson['articles'] = $articles;
		//$mpnewsJson['articles'] = $sortarticles;
		$mpnewsJson = json_encode($mpnewsJson, JSON_UNESCAPED_UNICODE);
		//echo $mpnewsJson;exit;

		//上传图文消息
		$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$token;
		$res = curl_post($url, $mpnewsJson);
		//echo $res;exit;
		$res = json_decode($res, true);

		if(!isset($res['media_id'])) {
			//print_r($res);exit;
			alert(L("wx_".$res['errcode']));
		}

		return $res['media_id'];
	}

	/**
	 * 立即群发信息
	 *
	 * @param	array
	 * @param	int
	 * @param	boole
	 * @param	str
	 * @return	str
	 */
	public function immediatelySend($post, $wid, $debug=false, $openID=null) {
		
		$this->_mass = D('Mass');
		if($debug){
			$this->_mass->setPreview($openID);
		}else{
			$this->_mass
				->setMassType('group')	// 设置发送方式
				->setIsToAll(true);		// 设置发送范围
		}

		if($post['type'] == 'text'){
			// 文本类型
			$this->_mass
				->setMsgType('text')	// 设置内容类型
				->setContents($post['content']);	// 设置内容
		}elseif($post['type'] == 'mpnews'){
			$mediaId = $this->_assemblyNews($wid, $post['material_ids'], $post['cover_id']);
			// 图文类型
			$this->_mass
				->setMsgType('mpnews')	// 设置内容类型
				->setMediaId($mediaId);	// 设置内容
		}
		$data = $this->_mass->getPostData();

		$weixin = D("Weixin");
		$token  = $weixin->getToken($wid);

		//$token  = getAccessToken(session('weixin'));

		// 发送
		if($debug)
			$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$token;
		else
			$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$token;
		$res = curl_post($url, $data);
		$res = json_decode($res, true);
		if($res['errcode'])
			alert(L("351wx_".$res['errcode']));

		return 't';
	}

	/**
	 * 检查并生成缩略图
	 * 
	 * @param	string	原图地址
	 * @return	mix		缩略图地址/false 失败
	 */
	private function _createThump($file)
	{
		// 检查是否存在缩略图，存在则使用
		$status = $this->_thump->checkThump($file);
		if(false !== $status) return $status;
	
		// 缩略图不存在生成后返回
		$file = $this->_thump->createThump($file);
		return $file;
	}

	/**
	 * 处理content，过去图片src中的=号
	 *
	 * @param	string
	 * @return	string
	 */
	private function _replaceStr($content)
	{
		$newUrl = array();
		$content = htmlspecialchars_decode($content);
		preg_match_all('/<img.* src=(\'|")(.*?)(\'|")/', $content, $matchArr);
		foreach($matchArr[2] as $key => $url){
			$newUrl[$key] = str_replace('=', '', $url);
		}
		$content = str_replace($matchArr[2], $newUrl, $content);

		return $content;
	}

	/**
	 * 组装json格式数据
	 *
	 * @param	array
	 * @param	string
	 */
	private function _customJsonencode($articles)
	{
		$json = '';
		foreach($articles as $news){
			$json .= '{"thumb_media_id":"'.$news['thumb_media_id'].'","author":"'.
				$news['author'].'","title":"'.$news['title'].'","content_source_url":"'.
				$news['content_source_url'].'","content":"'.addslashes($news['content']).
				'","digest":"'.$news['digest'].'","show_cover_pic":"'.$news['show_cover_pic'].'"},';
		}
		$json = trim($json, ',');
		$json = '{"articles":['.$json.']}';
		return $json;
	}


	function checkissysimg($file) {
		/*$arr["/Public/images/baokandefault/wxrb.jpg"] = "https://mmbiz.qlogo.cn/mmbiz_jpg/P4v50ZTrHOvDIdPz2HP7F1VPLWTByphxBm1zO6QF8LnCCStBs1W9Et1sqS01DMCwBDl0wf98lCUBt3iaH27MSrw/0";
		$arr["/Public/images/baokandefault/jnbjb.jpg"] = "https://mmbiz.qlogo.cn/mmbiz_jpg/P4v50ZTrHOvDIdPz2HP7F1VPLWTByphxDM8f3fcN124eiaLnNqF7mz8af2LDNIS3yBF2OD65UU8R2yql06hcz8Q/0";
		$arr["/Public/images/baokandefault/jnwb.jpg"] = "https://mmbiz.qlogo.cn/mmbiz_jpg/P4v50ZTrHOvDIdPz2HP7F1VPLWTByphxGiaTlhNHicr8vkuTlDeoic2BB0OcohFa09jtDvnZpK5plU3RjGW1iaT7pQ/0";
		$arr["/Public/images/baokandefault/wxsb.jpg"] = "https://mmbiz.qlogo.cn/mmbiz_jpg/P4v50ZTrHOvDIdPz2HP7F1VPLWTByphxVpDtQxuD2Z9VU0HqcO58MEQ7y0ysbfZy6NrzpNRmFUQQj06HwibxUhw/0";*/

		$arr["/Public/images/baokandefault/wxrb.jpg"] = "/Public/images/baokandefault/wxrb_min.jpg";
		$arr["/Public/images/baokandefault/jnbjb.jpg"] = "/Public/images/baokandefault/jnbjb_min.jpg";
		$arr["/Public/images/baokandefault/jnwb.jpg"] = "/Public/images/baokandefault/jnwb_min.jpg";
		$arr["/Public/images/baokandefault/wxsb.jpg"] = "/Public/images/baokandefault/wxsb_min.jpg";

		$arr["/Public/images/baokandefault/suzhou/szrb.jpg"] = "/Public/images/baokandefault/suzhou/szrb_min.jpg";
		$arr["/Public/images/baokandefault/suzhou/gswb.jpg"] = "/Public/images/baokandefault/suzhou/gswb_min.jpg";
		$arr["/Public/images/baokandefault/suzhou/cszbd.jpg"] = "/Public/images/baokandefault/suzhou/cszbd_min.jpg";

		//if($arr[$file]) $tempfile = $arr[$file];
		//else $tempfile = $file;
		$tempfile = $arr[$file];

		return $tempfile;
	}
}
