<?php
class Tp {
	/**
	 * 投票
	 * @param $token 投票人
	 * @param $id 被投id
	 */
	function add_tp($token,$id){
		$getvote 		= M('nian_log')->where("token='".$token."'")->select();
		if (count($getvote)>9){
			return '您已经投满10票，不能再投啦~';
			exit;
		}
		foreach ($getvote as $v){
			if ($v['tpid']==$id) {
				return '您已经为Ta投过票啦，如果您真的喜欢Ta那就赶紧上台送上您的鲜花、拥抱或热吻吧~';
				exit;
			}
		}
		$data['token']	= $token;
		$data['tpid']	= $id;
		$data['ctime']	= time();
		$add 			= M('nian_log')->data($data)->add();
		if (!$add){
			return '很抱歉投票失败了，请您过一会再试吧';
			exit;
		}
		return '投票成功，感谢您对此节目的支持!';
	}
	
	
	/**
	 * 获取投票结果
	 */
	function result_tp(){
		$list 		= M('nian_tp')->alias('t')->join('wx_nian_log as l on l.tpid=t.id')->field('t.*,count(l.id) as counts')->group('t.id')->order('counts desc')->select();
		$str 		= '截至'.date('m/d H:i')."投票结果如下：\n";
		foreach ($list as $v){
			$str 	.= "\n节目".$v['id'].".\n".$v['name'].'('.$v['auth'].")\n票数：".$v['counts']."\n";
		}
		return 		$str;
	}
	
	function ticket_api($tpid,$num){
		if ($tpid<1||$num<1){
			return 'fales';
			exit;
		}
		
		for ($i=0;$i<$num;$i++){
			$tmp['token']	= 'phpapi';
			$tmp['tpid'] 	= $tpid;
			$tmp['ctime']	= time();
			$data[]			= $tmp;
		}
		$save 				= M('nian_log')->addAll($data);
		if (!$save){
			return 'false';
			exit;
		}
		return 'true';
	}
	
}
?>