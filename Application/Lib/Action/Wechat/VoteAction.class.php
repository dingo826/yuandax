<?php
class VoteAction extends WechatAction {
	
	public function __construct() {
		parent::__construct();
	}

  public function index() {
		$wid    = intval(I("get.wid"));
		$token  = I("get.token", '', 'htmlspecialchars');
    
    $m     = M("app_vote");
    $where = "wid=".$wid;
    
    $pagesize = 25;
    $p        = 1;
    
    if($_POST){
      $post = I("post.");
      $p = $post["p"];
      
      $list = $m->where($where)->order("id DESC")->page($p,$pagesize)->select();
      $time = time();
      foreach($list as $k=>$v){
        if($time<=$v["start"]){
          $list[$k]["status"] = 1;
        }else if($time<$v["end"] && $time>$v["start"]){
          $list[$k]["status"] = 2;
        }else{
          $list[$k]["status"] = 3;
        }
        $list[$k]["username"] = $this->basic['abbr'];
        $list[$k]["avatar"]   = $this->basic['logo'];
        
      }
      
      $this->ajaxReturn($list,"JSON");
      exit;
    }
    
    $list = $m->where($where)->order("id DESC")->page($p,$pagesize)->select();
    
		$this->assign('list', $list);
		$this->assign('nowtime', time());
		$this->display();
  }
  
  public function detail(){
    $wid    = intval(I("get.wid"));
		$token  = I("get.token", '', 'htmlspecialchars');
    $vid    = I("get.vid");
    
    
    $m      = M("app_vote");
    $m_opts = M("app_vote_options");
    
    $detail = $m->where("id=".$vid." AND wid=".$wid)->find();
    if(!$detail){
      exit("投票不能存在");
    }
    
    $options = $m_opts->where("vid=".$vid)->select();
    
    $submited = 0;
    if(empty($token)){
      if(cookie('voted_'.$vid)){
        $submited = 1;
      }
    }else{
      $member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
      if($member){
        $uid      = $member["id"];
        $m_record = M("app_vote_records");
        $records  = $m_record->where("uid=".$uid." AND vid=".$vid)->find();
        if($records==false){
          $submited=1;
        }
      }
    }
    
    $nowtime = time();
    $over    = 0;
    if($nowtime > $detail['start'] && $nowtime < $detail['end']){
      $over = 0;
    }else if($nowtime < $detail['start']){
      $over = 1;
    }else{
      $over = 2;
    }
    
    $this->assign('submited',$submited);
    $this->assign("list",$options);
    $this->assign("detail",$detail);
    $this->assign('over',$over);
    $this->display();
  }
  
  public function submit(){
    $wid    = intval(I("get.wid"));
		$token  = I("get.token", '', 'htmlspecialchars');
    $vid    = I("get.vid");
    $post   = I("post.");
    
    $m          = M("app_vote");
    $m_opts     = M("app_vote_options");
    $m_record   = M("app_vote_records");
    $uid        = -1;
    
    if(empty($token)){
      if(cookie('voted_'.$vid)){
        exit('您已参与投票');
      }
    }else{
      $member = M("app_mcard_member")->where("wid='".$wid."' and wechatid='".$token."'")->find();
      if($member){
        $uid = $member["id"];
        
        $records  = $m_record->where("uid=".$uid." AND vid=".$vid)->find();
        if($records){
          exit('您已参与投票');
        }
      }
    }
    
    
    $time = time();
    for($i=0; $i<count($post['optids']); $i++){
      $_data = array();
      $_data["vid"]   = $vid;
      $_data["optid"] = $post['optids'][$i];
      $_data["uid"]   = $uid;
      $_data["ctime"] = $time;
      
      $data_records[] = $_data;
    }
    
    $m_record->addAll($data_records);
    $m->where("id=".$vid)->setInc('total',1);
    $m_opts->where("id in(". implode(',',$post['optids']) .")")->setInc('ticket',1);
    
    if($uid==-1){
      cookie("voted_".$vid,$vid,157680000);
    }
    echo 1;
  }
}
?>