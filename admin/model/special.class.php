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
class special_controller extends common
{
	function index_action(){
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("special","1",$pageurl,$this->config['sy_listnum']);
		if(is_array($rows)){
			$zid=array();
			foreach($rows as $key=>$val){
				$rows[$key]['comnum']=$rows[$key]['booking']='0';
				$zid[]=$val['id'];
			}
			$all=$this->obj->DB_select_all("special_com","`sid` in(".pylode(",",$zid).") group by `sid` ","`sid`,count(id) as num");
			$status=$this->obj->DB_select_all("special_com","`sid` in(".pylode(",",$zid).") and `status`='0' group by `sid` ","`sid`,count(id) as num");
			foreach($rows as $key=>$v){
				foreach($all as $val){
					if($v['id']==$val['sid']){
						$rows[$key]['comnum']=$val['num'];
					}
				}
				foreach($status as $val){
					if($v['id']==$val['sid']){
						$rows[$key]['booking']=$val['num'];
					}
				}
			}
		}
		
		
		
		$this->yunset("rows",$rows);
		
		$this->yuntpl(array('admin/admin_special'));
	}

	function add_action(){
		if($_GET['id']){
			$row=$this->obj->DB_select_once("special","`id`='".$_GET['id']."'");
			$row['rating']=@explode(',',$row['rating']);
			$this->yunset("row",$row);
		}
		$qy_rows=$this->obj->DB_select_all("company_rating","`category`=1 order by sort desc");
		$this->yunset("qy_rows",$qy_rows);
		$publicdir = "../app/template/".$this->config['style']."/special/";
		$filesnames = @scandir($publicdir);
		if(is_array($filesnames)){
			foreach($filesnames as $key=>$value){
				if($value!='.' && $value!='..' ){
					 $typearr = explode('.',$hostdir.$value);
					 if(in_array(end($typearr),array('htm'))) {
						 if($value!="index.htm"){
						   	$file[] =$value;
						 }
					 }
				 }

			}
		}
		$this->yunset("file",$file);
		$this->yuntpl(array('admin/admin_special_add'));
	}
	function save_action(){
		if($_POST['save']){
			$id=(int)$_POST['id'];
			unset($_POST['save']);
			unset($_POST['id']);
			$_POST['sort']=(int)$_POST['sort'];
			$_POST['limit']=(int)$_POST['limit'];
			$_POST['etime']=strtotime($_POST['etime']);
			$_POST['ctime']=mktime();
			$_POST["intro"]= str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),html_entity_decode($_POST["intro"],ENT_QUOTES,"GB2312")); 
			if($_POST['rating']&&is_array($_POST['rating'])){
				$_POST['rating']=implode(',',$_POST['rating']);
			}else{
				$_POST['rating']='';
			}
			 
			if(!$id){ 
				$nid=$this->obj->insert_into("special",$_POST);
				$name="ר����Ƹ��ID��".$nid."�����";
			}else{
				if($_POST['pic']==''){
					unset($_POST['pic']);
				}
				if($_POST['pic']!=$_POST['ypic']&&$_POST['ypic']&&$_POST['pic']){
					unlink_pic('..'.$_POST['ypic']);
				}
				if($_POST['background']==''){
					unset($_POST['background']);
				}
				if($_POST['background']!=$_POST['ybackground']&&$_POST['ybackground']&&$_POST['background']){
					unlink_pic('..'.$_POST['ybackground']);
				} 
				unset($_POST['ybackground']);
				unset($_POST['ypic']);
				$where['id']=$id; 
				$nid=$this->obj->update_once("special",$_POST,$where);
				$name="ר����Ƹ��ID��".$id."������";
			}
		}
		$nid?$this->ACT_layer_msg($name."�ɹ���",9,"index.php?m=special",2,1):$this->ACT_layer_msg($name."ʧ�ܣ�",8,"index.php?m=special");
	}
	function com_action(){
		$_GET['id']=(int)$_GET['id'];
		$urlarr['c']=$_GET['c'];
		$urlarr['id']=$_GET['id'];
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("special_com","`sid`='".$_GET['id']."'",$pageurl,$this->config['sy_listnum']);
		if($rows&&is_array($rows)){
			$uid=array();
			foreach($rows as $val){
				$uid[]=$val['uid'];
			}
			$company=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$uid).")","uid,name");
			foreach($rows as $key=>$val){
				foreach($company as $v){
					if($val['uid']==$v['uid']){
						$rows[$key]['name']=$v['name'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_special_com'));
	}
	function statuscom_action(){
		$pid=intval($_POST['pid']);
		$status=(int)$_POST['status'];
		$statusbody=trim($_POST['statusbody']); 
		if($status=='2'){
			$rows=$this->obj->DB_select_all("special_com","`id` IN ($pid) and `status`<>'2'","`uid`,`integral`");
		}
		$id=$this->obj->DB_update_all("special_com","`status`='$status',`statusbody`='$statusbody'","`id` IN ($pid) and `status`<>'2'");
		if($id&&count($rows)){
			foreach($rows as $val){
				if($val['integral']>0){
					$this->company_invtal($val['uid'],$val['integral'],true,"ר����Ƹδͨ����ˣ��˻�".$this->config['integral_pricename'],true,2,'integral');
				}
			}
		}
		$id?$this->ACT_layer_msg("�����ɹ���",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("����ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
	}
	function getinfo_action(){
		$row=$this->obj->DB_select_once("special_com","`id`='".intval($_POST['id'])."' ","`statusbody`");
		echo $row['statusbody'];die;
	}
	function delcom_action(){
		$this->check_token();
	    if($_GET['del']||$_GET['id']){
    		if(is_array($_GET['del'])){
    			$layer_type=1;
				$del=@implode(',',$_GET['del']);
	    	}else{
	    		$layer_type=0;
	    		$del=$_GET['id'];
	    	}
			$rows=$this->obj->DB_select_all("special_com","`id` in (".$del.") and `status`=0","`uid`,`integral`");
			if(count($rows)){
				foreach($rows as $val){
					if($val['integral']>0){
						$this->company_invtal($val['uid'],$val['integral'],true,"ȡ��ר����Ƹ�������˻�".$this->config['integral_pricename'],true,2,'integral');
					}
				}
			}
			$this->obj->DB_delete_all("special_com","`id` in (".$del.")","");
			$this->layer_msg("��˾����(ID:".$del.")ɾ���ɹ���",9,$layer_type,$_SERVER['HTTP_REFERER']);
    	}else{
			$this->layer_msg("��ѡ����Ҫɾ������Ϣ��",8,1);
    	}
	}
	function del_action(){
		$_GET['id']=(int)$_GET['id'];
		if($_GET['id']){
			$this->check_token();
			$nid=$this->obj->DB_delete_all("special","`id`='".$_GET['id']."'");
			$rows=$this->obj->DB_select_all("special_com","`sid`='".$_GET['id']."' and `status`=0","`uid`,`integral`");
			if(count($rows)){
				foreach($rows as $val){
					if($val['integral']>0){
						$this->company_invtal($val['uid'],$val['integral'],true,"ȡ��ר����Ƹ���˻�".$this->config['integral_pricename'],true,2,'integral');
					}
				}
			}
			$this->obj->DB_delete_all("special_com","`sid`='".$_GET['id']."'");
			$nid?$this->layer_msg('ר����Ƹ��ID��'.$_GET['id'].'��ɾ���ɹ���',9):$this->layer_msg('ɾ��ʧ�ܣ�',8);
		}
		if($_GET['del']||$_GET['id']){
    		if(is_array($_GET['del'])){
    			$layer_type=1;
				$del=@implode(',',$_GET['del']);
	    	}else{
	    		$layer_type=0;
	    		$del=$_GET['id'];
	    	}
			$this->obj->DB_delete_all("special","`id` in (".$del.")","");
			$rows=$this->obj->DB_select_all("special_com","`sid` in (".$del.") and `status`=0","`uid`,`integral`");
			if(count($rows)){
				foreach($rows as $val){
					if($val['integral']>0){
						$this->company_invtal($val['uid'],$val['integral'],true,"ȡ��ר����Ƹ���˻�".$this->config['integral_pricename'],true,2,'integral');
					}
				}
			}
			$this->obj->DB_delete_all("special_com","`sid` in (".$del.")","");
			$this->layer_msg("ר��(ID:".$del.")ɾ���ɹ���",9,$layer_type,$_SERVER['HTTP_REFERER']);
    	}else{
			$this->layer_msg("��ѡ����Ҫɾ������Ϣ��",8,1);
    	}
	}
}
?>