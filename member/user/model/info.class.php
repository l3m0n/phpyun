<?php
/* *
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
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
					$this->ACT_layer_msg("�����Ѵ��ڣ�",8);
				}
			}else{
				unset($_POST['email']);
			}

			if($row['moblie_status']!=1){
				$is_exist_mobile=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['telphone']."'","`uid`");
				if($is_exist_mobile){
					$this->ACT_layer_msg("�ֻ��Ѵ��ڣ�",8);
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
				$this->obj->member_log("�޸Ļ�����Ϣ",7);
				if($row['name']==""||$row['living']==""){ 
					$this->company_invtal($this->uid,$this->config['integral_userinfo'],true,"�״���д��������",true,2,'integral',25);
				}
				$this->ACT_layer_msg("��Ϣ���³ɹ���",9,$url);
			}else{
				$this->ACT_layer_msg("��Ϣ����ʧ�ܣ�",8,$url);
			}
		}
	}
}
?>