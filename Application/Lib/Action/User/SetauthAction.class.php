<?php
class SetauthAction extends UserAction {

    function index() {
		$uid     = session("uid");
		$Dmodel  = D("Weixin");
		if ($_POST){
    		$post = I('post.', '', 'htmlspecialchars');
			$id   = $post['id'];
    		$data['appid']     = $post['appid'];
    		$data['appsecret'] = $post['appsecret'];
			$data['istuog']    = intval($post['istuog']);
			$data['pinlv']     = $post['pinlv'];

			if($data['istuog']==1) {
				$data['dhour']     = $post['hour'];
			    $data['dmin']      = $post['min'];
			}
			
    		if (empty($data['appid'])||empty($data['appsecret'])){
				echo "<script>alert('应用ID和密钥必填');location.href='".U('setauth/index')."'</script>";
				exit;
    		}
    		$Dmodel->where("id='".$id."'")->save($data);
			header("location: ".U('setauth/index'));exit;
    	}
		$arr     = $Dmodel->getWeixinUArr($uid);
		$this->assign('info',$arr[0]);
		$this->display($this->Base_themplate);
	}
}
