<?php
/*
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ����������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
 */
class cron_controller extends common{
	function public_act(){
		$arrweek[]="��һ";
		$arrweek[]="�ܶ�";
		$arrweek[]="����";
		$arrweek[]="����";
		$arrweek[]="����";
		$arrweek[]="����";
		$arrweek[]="����";
		for($i=1;$i<=31;$i++){
			$montharr[]=$i."��";
		}
		for($i=0;$i<=23;$i++){
			$hourarr[]=$i."ʱ";
		}
		$this->yunset("hourarr",$hourarr);
		$this->yunset("montharr",$montharr);
		$this->yunset("arrweek",$arrweek);
	}
	function index_action(){
		$rows=$this->obj->DB_select_all("cron");
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_cron_list'));
	}
	function add_action(){
		$this->public_act();
		if($_GET["id"]){
			$row=$this->obj->DB_select_once("cron","`id`='".$_GET["id"]."'");
			$this->yunset("row",$row);
		}
		$this->yuntpl(array('admin/admin_cron_add'));
	}
	function save_action(){

		if($_POST['msgconfig']){
			$id=$_POST["id"];
			unset($_POST["id"]);
			unset($_POST["msgconfig"]);
			$CronM=$this->MODEL('cron');
			$_POST['nexttime']  = strtotime($CronM->nextexe($_POST));

			if($_POST['dir'])
			{
				$dirArr = explode('.',$_POST['dir']);
				if(end($dirArr)!='php'){
					$this->ACT_layer_msg("��Ч��ִ���ļ���",8,"index.php?m=cron");
				}
			}else{
				$this->ACT_layer_msg("����д�ƻ�����ִ���ļ���",8,"index.php?m=cron");
			}
			if(!$id){
				$_POST["ctime"]=mktime();
				$nbid=$this->obj->insert_into("cron",$_POST);
				$alert="�ƻ�����(id:".$nbid.")���ӳɹ���";
				$this->croncache();

			}else{

				$nbid=$this->obj->update_once("cron",$_POST,array('id'=>$id));
				$alert="�ƻ�����(id:".$id.")�޸ĳɹ���";
				$this->croncache();
			}

 			isset($nbid)?$this->ACT_layer_msg($alert,9,"index.php?m=cron"):$this->ACT_layer_msg("����ʧ�ܣ�",8,"index.php?m=cron");

		}
	}
	function del_action(){
		$this->check_token();
		if($_GET["id"]){

			$ad=$this->obj->DB_delete_all("cron","`id`='".$_GET["id"]."'");
			$this->croncache();
			$this->layer_msg('�ƻ�����(ID:'.$_GET["id"].')ɾ���ɹ���',9,0,"index.php?m=cron");
		}


	}
	function croncache(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$cacheclass->cron_cache("cron.cache.php");
	}

	function run_action(){
		if($_GET['id'])
		{
			include PLUS_PATH.'cron.cache.php';
			$CronM=$this->MODEL('cron');
			$CronM->excron($cron,$_GET['id']);
		}

		$this->layer_msg('�ƻ�����(ID:'.$_GET["id"].')ִ�гɹ���',9,0,"index.php?m=cron");
	}


}

?>