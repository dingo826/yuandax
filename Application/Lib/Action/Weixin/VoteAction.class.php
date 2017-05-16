<?php
class VoteAction extends WeixinAction {

	public function __construct() {
		parent::__construct();
	}

  public function index(){
    $uid = intval(session("uid"));
    $wid = intval(session('wid'));
    
    $get = I("get.");
    $m   = M("app_vote");
    
    $p          = $get['p'] ? $get['p'] : 1;
    $pagesize   = 25;
    $where      = "wid=".$wid;
    
    import('ORG.Util.Page');
		$count = $m->where('wid='.$wid)->count();
		$Page  = new Page($count, $pageSize);
		$show  = $Page->show();
    
    $list = $m->where($where)->order('id DESC')->page($p,$pagesize)->select();
    
    //echo $uid.'+'.$wid;
    $this->assign('nowtime', time());
    $this->assign('list', $list);
    $this->assign('pages',$show);
    $this->assign('wid',$wid);
    $this->display($this->Base_themplate);
  }
  
  public function add(){
    $uid = intval(session("uid"));
    $wid = intval(session('wid'));
    
    if($_POST){
      $post = I("post.");
      
      
      $data["wid"]        = $wid;
      $data["title"]      = $post["title"];
      $data["picurl"]     = $post["uploadcover"];
      $data["covershow"]  = intval($post["covershow"]);
      $data["describes"]  = $post["describes"];
      $data["start"]      = strtotime($post["start"]);
      $data["end"]        = strtotime($post["end"]);
      $data["options"]    = count($post["option_titles"]);
      $data["optselect"]  = intval($post["optselect"]);
      $data["resultshow"] = intval($post["resultshow"]);
      $data["etime"]      = time();
      $data["ctime"]      = $data["etime"];
      
      
      $m   = M("app_vote");
      $vid = $m->add($data);
      
      
      if($vid>0){
        for($i=0; $i<$data["options"]; $i++){
          $_data = array();
          
          $_data["vid"]     = $vid;
          $_data["title"]   = $post["option_titles"][$i];
          $_data["picurl"]  = $post["filepath"][$i] ? $post["filepath"][$i] : null;
		  $_data["time"]    = time();
          
          $optdata[]=$_data;
        }
      }
      
      $m_option = M("app_vote_options");
      $m_option->addAll($optdata);
      
      header("Location: ".U("index"));
      exit;
    }

	$detail['start'] = time();
	$detail['end']   = time()+259200;

	$this->assign('detail', $detail);    
    $this->display($this->Base_themplate);
  }
  
  public function edit(){
    $uid = intval(session("uid"));
    $wid = intval(session('wid'));
    $id  = intval(I('get.id'));
    
    if($_POST){
		$post = I("post.");

		$data["title"]      = $post["title"];
		$data["picurl"]     = $post["uploadcover"];
		$data["covershow"]  = intval($post["covershow"]);
		$data["describes"]  = $post["describes"];
		$data["start"]      = strtotime($post["start"]);
		$data["end"]        = strtotime($post["end"]);
		$data["options"]    = count($post["option_titles"]);
		$data["optselect"]  = intval($post["optselect"]);
		$data["resultshow"] = intval($post["resultshow"]);
		$data["etime"]      = time();
		M("app_vote")->where("id='".$id."'")->save($data);
		//echo "vid='".$id."' and id NOT IN ('".implode("', '", $post['oldid'])."')";exit;

		if($post['oldid'])
			M("app_vote_options")->where("vid='".$id."' and id NOT IN ('".implode("', '", $post['oldid'])."')")->delete();
        
		foreach($post['option_titles'] as $key => $val) {
			$temp = '';
			if(intval($post['oldid'][$key])>0) {
				$temp['title']  = $val;
				$temp['picurl'] = $post['filepath'][$key];
				$temp['etime']  = time();
				M("app_vote_options")->where("id='".intval($post['oldid'][$key])."'")->save($temp);
			}else {
				$temp['vid']    = $id;
				$temp['title']  = $val;
				$temp['picurl'] = $post['filepath'][$key];
				$temp['etime']  = time();
				M("app_vote_options")->add($temp);
			}
		}

		header("Location: ".U("index"));
		exit;
      
    }

    $detail  = M("app_vote")->where("id='".$id."' and wid='".$wid."'")->find();
    $optlist = M("app_vote_options")->where("vid='".$id."'")->order('id asc')->select();


    
    $this->assign('nowtime', time());
    $this->assign('detail', $detail);
    $this->assign('optlist', $optlist);
    $this->display($this->Base_theme."/"."add");
  }
  
  public function result(){
    $uid = intval(session("uid"));
    $wid = intval(session('wid'));
    $vid  = intval(I('get.vid'));
    
    $m = M("app_vote_options");
    
    $where  = "vid=".$vid;
    $list   = $m->where($where)->select();
    $detail  = M("app_vote")->where("id='".$vid."' and wid='".$wid."'")->find();
    
    $this->assign("list",$list);
    $this->assign("detail",$detail);
    $this->display($this->Base_themplate);
  }
}
?>