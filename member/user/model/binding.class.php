<?php
/* *
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ����������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
*/
class binding_controller extends user{
	function index_action(){
		$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		$this->yunset("member",$member);
		if(($member['qqid']!=""||$member['wxid']!=""||$member['unionid']!=""||$member['sinaid']!="") && $member['restname']=="0"){
			$this->yunset("setname",1);
		}
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		$this->yunset("resume",$resume);
		$this->public_action();
 		$this->user_tpl('binding');
	}
	function save_action(){
		if($_POST['moblie']){
			$row=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and `check`='".$_POST['moblie']."'");
			if(!empty($row)){
				if($row['check2']!=$_POST['code']){
					echo 3;die;
				}
				$this->obj->DB_update_all("member","`moblie`=''","`moblie`='".$row['check']."'");
				$this->obj->DB_update_all("resume","`moblie_status`='0',`telphone`=''","`telphone`='".$row['check']."'");
				$this->obj->DB_update_all("company","`moblie_status`='0',`moblie`=''","`linktel`='".$row['check']."'");
				$this->obj->DB_update_all("member","`moblie`='".$row['check']."'","`uid`='".$this->uid."'");
				$this->obj->DB_update_all("resume","`telphone`='".$row['check']."',`moblie_status`='1'","`uid`='".$this->uid."'");
				$this->obj->DB_update_all("company_cert","`status`='1'","`uid`='".$this->uid."' and `check2`='".$_POST['code']."'");
				$this->obj->member_log("�ֻ���");
				$pay=$this->obj->DB_select_once("company_pay","`pay_remark`='�ֻ���' and `com_id`='".$this->uid."'");
				if(empty($pay)){
					$this->get_integral_action($this->uid,"integral_mobliecert","�ֻ���");
				}
				echo 1;die;
			}else{
				echo 2;die;
			}
		}
		if($_POST['upfile']){
			$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","idcard_pic"); 
			$upload=$this->upload_pic("../data/upload/user/",false,$this->config['user_pickb']);
			$pictures=$upload->picture($_FILES['pic']);
			$this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
			$pictures = str_replace("../data/upload/user","../data/upload/user",$pictures);
			if($this->config['user_idcard_status']=="1"){
				$status='0';
			}else{
				$status='1';
				$this->obj->DB_update_all('friend_info',"`iscert`='".$status."'","`uid`='".$this->uid."'");
			} 
			$id=$this->obj->DB_update_all('resume',"`idcard_pic`='".$pictures."',`idcard`='".$_POST['idcard']."',`idcard_status`='".$status."',`cert_time`='".time()."'","`uid`='".$this->uid."'");
			$this->obj->DB_update_all('resume_expect',"`idcard_status`='".$status."'","`uid`='".$this->uid."'");

			if($id){
				unlink_pic($resume['idcard_pic']);
				$this->obj->member_log("�ϴ�������֤ͼƬ");
				$this->ACT_layer_msg("�ϴ��ɹ�",9,1);
			}else{
				$this->ACT_layer_msg("�ϴ�ʧ��",8,1);
			}
		}
	}
	function del_action(){
		if($_GET['type']=="moblie"){
			$this->obj->DB_update_all("resume","`moblie_status`='0'","`uid`='".$this->uid."'");
		}
		if($_GET['type']=="email"){
			$this->obj->DB_update_all("resume","`email_status`='0'","`uid`='".$this->uid."'");
		}
		if($_GET['type']=="qqid"){
			$this->obj->DB_update_all("member","`qqid`=''","`uid`='".$this->uid."'");
		}
		if($_GET['type']=="sinaid"){
			$this->obj->DB_update_all("member","`sinaid`=''","`uid`='".$this->uid."'");
		}
		if($_GET['type']=="wxid")
		{
			$this->obj->DB_update_all("member","`wxid`='',`wxopenid`='',`unionid`=''","`uid`='".$this->uid."'");
		}
		$this->layer_msg("����󶨳ɹ���",9,0,$_SERVER['HTTP_REFERER']);
	}
}
?>