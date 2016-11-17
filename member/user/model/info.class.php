<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class info_controller extends user{
	function index_action(){
		$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		$save=$this->obj->DB_select_once("lssave","`uid`='".$this->uid."'and `savetype`='1'");
		$save=unserialize($save['save']);
		if($save['lastupdate']){
			$save['time']=date('H:i',ceil(($save['lastupdate'])));
		} 
		$this->yunset("save",$save);
		$this->yunset("row",$row);
		$this->public_action();
		$this->city_cache();
		$this->user_cache();
		$this->user_tpl('info');
	}
	function save_action(){
		if($_POST['submitBtn']){
			$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
			$_POST=$this->post_trim($_POST);
			if($row['email_status']!=1&&$_POST['email']){
				$is_exist_email=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `email`='".$_POST['email']."'","`uid`");
				if($is_exist_email['id']){
					$this->ACT_layer_msg("邮箱已存在！",8);
				}
			}else{
				unset($_POST['email']);
			}

			if($row['moblie_status']!=1){
				$is_exist_mobile=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['telphone']."'","`uid`");
				if($is_exist_mobile){
					$this->ACT_layer_msg("手机已存在！",8);
				}
			}else{
				unset($_POST['telphone']);
			}
			unset($_POST['submitBtn']);
			delfiledir("../data/upload/tel/".$this->uid);
			$where['uid']=$this->uid;
			if($row['moblie_status']!=1){

				$mvalue['moblie']=$_POST['telphone'];
			}
			if($row['email_status']!=1){
			
				$mvalue['email']=$_POST['email'];
			} 
			$_POST['lastipdate']=time();
			$nid=$this->obj->update_once('resume',$_POST,$where);
			$this->obj->update_once("resume_expect",array("edu"=>$_POST['edu'],"exp"=>$_POST['exp'],"uname"=>$_POST['name'],"sex"=>$_POST['sex'],"birthday"=>$_POST['birthday']),$where);
			if(!empty($mvalue)){
				$this->obj->update_once('member',$mvalue,$where);
			}
			$member_statis=$this->obj->DB_select_once("member_statis","`uid`='".$this->uid."'","`resume_num`");
			if($member_statis['resume_num']<1){
				$url="index.php?c=expect";
			}else{
				$url=$_SERVER['HTTP_REFERER'];
			}
			if($nid){
				$this->obj->DB_delete_all("lssave","`uid`='".$this->uid."'and `savetype`='1'");
				$this->obj->member_log("修改基本信息",7);
				if($row['name']==""||$row['living']==""){ 
					$this->company_invtal($this->uid,$this->config['integral_userinfo'],true,"首次填写基本资料",true,2,'integral',25);
				}
				$this->ACT_layer_msg("信息更新成功！",9,$url);
			}else{
				$this->ACT_layer_msg("信息更新失败！",8,$url);
			}
		}
	}
}
?>