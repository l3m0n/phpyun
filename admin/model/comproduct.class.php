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
class comproduct_controller extends common
{
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'���״̬',"value"=>array("1"=>"�����","3"=>"δ���","2"=>"δͨ��"));
		$ad_time=array('1'=>'����','3'=>'�������','7'=>'�������','15'=>'�������','30'=>'���һ����');
		$search_list[]=array("param"=>"time","name"=>'����ʱ��',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$where=1;
		$this->set_search();
		$keyword=trim($_GET['keyword']);
		$_GET['time']=(int)$_GET['time'];
		$status=(int)$_GET['status'];
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['time'].'day')."'";
			}
			$urlarr['time']=$_GET['time'];
		} 
		if($status){
			if($status=='3'){
				$where.=" and `status`='0'";
			}else{
				$where.=" and `status`='".$status."'";
			}
			
			$urlarr['status']=$status;
		}
		if($keyword){
			if($_GET['type']=="1"){ 
				$company=$this->obj->DB_select_all("company","`name` LIKE '%".$_GET['keyword']."%'","uid");
				
				foreach($company as $v){
					$comid[]=$v['uid'];
				}
				$where.=" and `uid` in (".@implode(",",$comid).")"; 
			}elseif ($_GET['type']=="2"){
				$where.=" and `title` LIKE '%".$keyword."%'";
			} 
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		$where.=" order by `id` ";
		if($_GET['order']){
			$where.=$_GET['order'];
		}else{
			$where.="desc";
		}
		$urlarr['page']="{{page}}";
		$urlarr=Url($_GET['m'],$urlarr,'admin');
		$rows = $this->get_page("company_product",$where,$urlarr,$this->config['sy_listnum']);
		if($rows&&is_array($rows)){			
			$uids=array();
			foreach($rows as $val){
				if(in_array($val['uid'],$uids)==false){
					$uids[]=$val['uid'];
				}
			}
			$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$uids).")","`uid`,`name`");			
			foreach($rows as $key=>$val){
				foreach($company as $v){
					if($val['uid']==$v['uid']){
						$rows[$key]['name']=$v['name'];
					}
				} 
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset("get_type", $_GET);
		$this->yuntpl(array('admin/admin_comproduct'));
	}
	function statusbody_action(){
		$userinfo = $this->obj->DB_select_once("company_product","`id`=".$_GET['id'],"`statusbody`");
		echo $userinfo['statusbody'];die;
	}
	function status_action(){
		extract($_POST);
		$id = @explode(",",$id);
		if(is_array($id)){
			foreach($id as $value){
				$idlist[] = $value;
			}
			$aid = @implode(",",$idlist);
			$id=$this->obj->DB_update_all("company_product","`status`='$status',`statusbody`='".$statusbody."'","`id` IN ($aid)");
 			$id?$this->ACT_layer_msg("��Ʒ���(ID:".$aid.")���óɹ���",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("����ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function statuss_action(){
		$this->obj->DB_update_all("company_product","`status`='".$_POST['status']."'","`id` IN (".$_POST['allid'].")");
		$this->admin_log("��ҵ��Ʒ(ID:".$_POST['allid'].")��˳ɹ�");
		echo $_POST['status'];die;
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
	    		if(is_array($del)){
			    	foreach($del as $v){
			    	    $this->del_com($v);
			    	}
					$del_id=@implode(',',$del);
		    	}else{
		    		$this->del_com($del);
					$del_id=$del;
		    	}
				$this->layer_msg('��Ʒ(ID:'.$del_id.')ɾ���ɹ���',9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
	    		$this->layer_msg( "��ѡ����Ҫɾ������Ϣ��",8,1);
	    	}
	    }
	    if(isset($_GET['id'])){
	    	extract($_GET);
	    	$id_a=explode("-",$id);
			$result=$this->del_com($id);
			isset($result)?$this->layer_msg('��Ʒ(ID:'.$id_a[0].')ɾ���ɹ���',9,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('�Ƿ�������',3);
		}
	}
	function del_com($id)
	{
		$id_arr = explode("-",$id);
		if($id_arr[0])
		{
			$product=$this->obj->DB_select_once("company_product","`id`='".$id_arr[0]."'");
 			unlink_pic("../".$product['pic']);
			$result=$this->obj->DB_delete_all("company_product","`id`='".$id_arr[0]."'" );
		}
		return $result;
	}
}
?>