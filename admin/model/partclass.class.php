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
class partclass_controller extends common{
	function index_action(){
		$position=$this->obj->DB_select_all("partclass","`keyid`='0'");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_partclass'));

	} 
	function save_action(){
	    $_POST=$this->post_trim($_POST);
	    $position=explode('-',$_POST['name']);
		foreach ($position as $val){
			if($val){
				$name[]=iconv('utf-8','gbk',$val);
			}
		}
		$partclass=$this->obj->DB_select_all("partclass","`name` in ('".implode("','", $name)."')");
		if(empty($partclass)){
		    $variable=explode('-',$_POST['str']);
		    if($_POST['ctype']=='1'){ 
		        foreach ($name as $key=>$val){
		            foreach ($variable as $k=>$v){
		                if($k==$key){
		                    $value="`name`='".$val."',`variable`='".trim($v)."'";
		                    $add=$this->obj->DB_insert_once("partclass",$value);
		                }
		            }
		        }
		    }else{
		        foreach ($name as $key=>$val){
                    $value="`name`='".$val."',`keyid`='".intval($_POST['nid'])."'";
                    $add=$this->obj->DB_insert_once("partclass",$value);
		        }
		    }
			$this->cache_action();
			$add?$msg=2:$msg=3;
			$this->admin_log("��ҵ��Ա����(ID:".$add.")��ӳɹ�");
		}else{
			$msg=1;
		}
		echo $msg;die;
	}
 	function up_action(){
 		if($_GET['id']){
			$id=$_GET['id'];
			$class1=$this->obj->DB_select_once("partclass","`id`='".$_GET['id']."'");
			$class2=$this->obj->DB_select_all("partclass","`keyid`='".$_GET['id']."'");
			$this->yunset("id",$id);
			$this->yunset("class1",$class1);
			$this->yunset("class2",$class2);
		}
		$position=$this->obj->DB_select_all("partclass","`keyid`='0'");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/admin_partclass'));

	}
 	function upp_action(){
		if($_POST['update']){
			if(!empty($_POST['position'])){
				if(preg_match("/[^\d-., ]/",$_POST['sort'])){
					$this->ACT_layer_msg("����ȷ��д�����������֣�",8,$_SERVER['HTTP_REFERER']);
				}else{
					if($_POST['sort']){
						$value.="`sort`='".$_POST['sort']."',";
					}
					$value="`name`='".$_POST['position']."'";
					$where="`id`='".$_POST['id']."'";
					$up=$this->obj->DB_update_all("partclass",$value,$where);
					$this->cache_action();
				   $up?$this->ACT_layer_msg("����(ID:".$_POST['id'].")���³ɹ���",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("����ʧ�ܣ����������ԣ�",8,$_SERVER['HTTP_REFERER']);
				}
			}else{
				$this->ACT_layer_msg("����ȷ��д��Ҫ���µķ��࣡",8,$_SERVER['HTTP_REFERER']);
			}
		}
	} 
	function del_action()
	{
		if($_GET['delid'])
		{
			$this->check_token();
			$id=$this->obj->DB_delete_all("partclass","`id`='".$_GET['delid']."' or `keyid`='".$_GET['delid']."'","");
			$this->cache_action();
		    isset($id)?$this->layer_msg('ɾ���ɹ���',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
		}
		if($_POST['del'])
		{
			$del=@implode(",",$_POST['del']);
			$id=$this->obj->DB_delete_all("partclass","`id` in (".$del.") or `keyid` in (".$del.")","");
			$this->cache_action();
			isset($id)?$this->layer_msg('ɾ���ɹ���',9,1,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,1,$_SERVER['HTTP_REFERER']);
		}
	}
	function cache_action()
	{
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->part_cache("part.cache.php");
	}
	function ajax_action()
	{
		if($_POST['sort'])
		{
			$this->obj->DB_update_all("partclass","`sort`='".$_POST['sort']."'","`id`='".$_POST['id']."'");
		}
		if($_POST['name'])
		{
			$_POST['name']=iconv("UTF-8","gbk",$_POST['name']);
			$this->obj->DB_update_all("partclass","`name`='".$_POST['name']."'","`id`='".$_POST['id']."'");
		}
		$this->cache_action();
	}
}
?>