<?php
class NewsAction extends Action {
	public function __construct() {
		parent::__construct();
		$this->assign('controller', 'news');
	}

    public function index(){
    	$p = intval($this->_get('p'));
    	$tid = intval($this->_get('tid'));
    	if ($tid<1){$tid=1;}
    	if ($tid==1){
    		$where = "1=1";
    	}else {
    		$where = "type=".$tid;
    	}
    	
    	$size = 15;
		$where = "status=1";
    	$list = M('News_center')->where($where)->order('etime desc')->page($p.', '.$size)->select();
    	import("ORG.Util.Page");
    	$count = M('News_center')->where($where)->count();
    	$page  = new Page($count, $size);
		$pages = $page->show();

		/*$category = M('news_category')->field('id,name')->select();
		foreach($category as $val){
			$cateList[$val['id']] = $val['name'];
		}*/
    	
		$cateList[2] = '行业资讯';
		$cateList[3] = '最新动态';

    	$this->assign('pages',$pages);
		$this->assign('list',$list);
		$this->assign('cateList',$cateList);
    
    	$this->display();
    }
    public function detail(){
    	$id= intval($this->_get('id'));
		$detail = M('News_center')->where("id='".$id."'")->find();
		//$category = M('news_category')->where('id='.$detail['type'])->getField('name');

		$cateList[2] = '行业资讯';
		$cateList[3] = '最新动态';

    	$this->assign('detail',$detail);
    	$this->assign('category',$cateList[$detail['type']]);
    	$this->display();
    }
}
