<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class sendresume_controller extends resume_controller{
	function index_action(){
		if((int)$_GET['id']){
			$M=$this->MODEL('resume');
			$id=(int)$_GET['id'];
			$user=$M->resume_select($id);
			$JobM=$this->MODEL('job');
			$time=strtotime("-14 day");
			$allnum=$JobM->GetUserJobNum(array("uid"=>$user['uid'],"eid"=>$user['id'],"`datetime`>'".$time."'"));
			$replynum=$JobM->GetUserJobNum(array("uid"=>$user['uid'],"eid"=>$user['id'],"`datetime`>'".$time."' and `is_browse`>'1'"));
			$pre=round(($replynum/$allnum)*100);
			$this->yunset("pre",$pre);
			$this->yunset("Info",$user);
		}
		$this->yun_tpl(array('sendresume'));
    }
}
?>