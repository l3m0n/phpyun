<?php
/*
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
 */
class admin_siteadmin_controller extends common
{
	function index_action(){
		$group=$this->obj->DB_select_once("admin_user_group","`group_type`='2'");
		if($group['id']==''){
			$this->yunset("nogroup",1);
		} 
		if(isset($_GET['uid'])){
			$adminuser=$this->obj->DB_select_once("admin_user","`uid`='".$_GET['uid']."'");
			$this->yunset("adminuser",$adminuser);
		}
		$domain=$this->obj->DB_select_all("domain","1 order by `id` desc","`id`,`title`");
		$this->yunset("domain",$domain);
		$this->yuntpl(array('admin/admin_siteadmin'));
	}  
	function pass_action(){
		if($_POST['useradd']){
			$_POST['oldpass']=trim($_POST['oldpass']);
			$_POST['password']=trim($_POST['password']);
			$where="`uid`='".$_SESSION['auid']."'";
			$row=$this->obj->DB_select_once("admin_user",$where);
			if($_POST['oldpass']==''||$_POST['password']==''){
				$this->ACT_layer_msg("ԭʼ���롢�����������Ϊ�գ�",8,$_SERVER['HTTP_REFERER']);
			}
			if($_POST['oldpass']==$_POST['password']){
				$this->ACT_layer_msg("�������ԭʼ����һ�£�����Ҫ�޸ģ�",8,$_SERVER['HTTP_REFERER']);
			}
			if(md5($_POST['oldpass'])!=$row['password']){
				$this->ACT_layer_msg("ԭʼ���벻��ȷ��",8,$_SERVER['HTTP_REFERER']);
			}
			if($_POST['password']!=$_POST['okpassword']){
				$this->ACT_layer_msg("�������������벻һ�£�",8,$_SERVER['HTTP_REFERER']);
			}
			$value.="`password`='".md5($_POST['password'])."'";
			$nbid=$this->obj->DB_update_all("admin_user",$value,$where);
			unset($_SESSION['authcode']);
			unset($_SESSION['auid']);
			unset($_SESSION['ausername']);
			unset($_SESSION['ashell']);
			$this->ACT_layer_msg("����Ա(ID:".$row['uid']."�ʺ�".$row['username'].")�����޸ĳɹ�,�����µ�¼��",9,$_SERVER['HTTP_REFERER'],2,1);
		}

		$this->yuntpl(array('admin/admin_mypass'));
	} 
	function save_action(){
		if(isset($_POST['useradd'])){
			if(!empty($_POST['username'])&&!empty($_POST['name'])){
				$group=$this->obj->DB_select_once("admin_user_group","`group_type`='2'");
				if($group['id']==''){
					$this->ACT_layer_msg("���޷�վ����Ա�飡",8,$_SERVER['HTTP_REFERER']);
				} 
				$value="`m_id`='".$group['id']."',`username`='".$_POST['username']."',`name`='".$_POST['name']."'";
				if($_POST['password']){
					$value.=",`password`='".md5($_POST['password'])."'";
				}
				if($_POST['did']){
					$value.=",`did`='".(int)$_POST['did']."'";
				}

				if(!$_POST[uid]){
				 	$nbid=$this->obj->DB_insert_once("admin_user","$value");
					$name="����Ա��ID:".$nbid."�����";
				 }else{ 
				 	$nbid=$this->obj->DB_update_all("admin_user",$value,"`uid`='".$_POST['uid']."'");
				 	if($_POST['uid']==$_SESSION['auid']){
						unset($_SESSION['authcode']);
						unset($_SESSION['auid']);
						unset($_SESSION['ausername']);
						unset($_SESSION['ashell']);
						$this->ACT_layer_msg( "����Ա(ID:".$_POST['uid'].")�޸ĳɹ�,�����µ�¼��",9,$_SERVER['HTTP_REFERER'],2,1);
				 	}
				 	$name="����Ա(ID:".$_POST['uid'].")����";
				 }
				isset($nbid)?$this->ACT_layer_msg($name."�ɹ���",9,"index.php?m=admin_user",2,1):$this->ACT_layer_msg($name."ʧ�ܣ�",8,"index.php?m=admin_user");
			}else{
				$this->ACT_layer_msg( "����д������",8,$_SERVER['HTTP_REFERER']);
			}
		}
	} 
}

?>