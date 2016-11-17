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
class crm_order_controller extends common
{
 	function set_search(){
		$pro=$this->obj->DB_select_all("crm_product","1","`id`,`name`");
		if(!empty($pro)){
			foreach($pro as $k=>$v){
				$carr[$v['id']]=$v['name'];
			}
		}
		$search_list[]=array("param"=>"status","name"=>'���״̬',"value"=>array("1"=>"�����","2"=>"�Ѹ���","3"=>"�����","4"=>"���˿�"));
		$search_list[]=array("param"=>"product","name"=>'��Ʒ����',"value"=>$carr);
		$lo_time=array('1'=>'����','3'=>'�������','7'=>'�������','15'=>'�������','30'=>'���һ����');
		$search_list[]=array("param"=>"o_time","name"=>'����ʱ��',"value"=>$lo_time);
		$this->yunset("pro", $pro);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		$this->set_search();
		$where.="`nid` in (".$this->config['crmpronid'].") and (`status`='2' or `status`='3')";
		if ($_GET['product']!=""){
			$where.=" and `pid`='".$_GET['product']."'";
			$urlarr['product']=$_GET['product'];
		}
		if($_GET['status'])
		{
			$where="`status`='".$_GET['status']."'";
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['o_time']){
			if($_GET['o_time']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.$_GET['o_time'].'day')."'";
			}
			$urlarr['o_time']=$_GET['o_time'];
		}
		if($_GET['news_search']){

			if ($_GET['keyword']!=""){
					if ($_GET['type']=='2'){
				$where.=" and `orderid` like '%".$_GET['keyword']."%'";
			}elseif ($_GET['type']=='1'){
				$orderinfo=$this->obj->DB_select_all("crm_client","`name` like '%".$_GET['keyword']."%'","`id`");
				if (is_array($orderinfo)){
					foreach ($orderinfo as $val){
						$orderuids[]=$val['id'];
					}
					$oruids=@implode(",",$orderuids);
				}
				$where.=" and `client_id` in (".$oruids.")";

			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
			}
			$urlarr['news_search']=$_GET['news_search'];
		}
		if($_GET['order'])
		{
			if($_GET['order']=="desc")
			{
				$order=" order by `".$_GET['t']."` desc";
			}else{
				$order=" order by `".$_GET['t']."` asc";
			}
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$order=" order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("crm_order",$where.$order,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows))
		{
			foreach($rows as $v)
			{
				$pid[]=$v['pid'];
				$cuid[]=$v['client_id'];
			}
			$plist=$this->obj->DB_select_all("crm_product","`id` in (".@implode(",",$pid).")");
			$clist=$this->obj->DB_select_all("crm_client","`id` in (".@implode(",",$cuid).")");
			foreach($rows as $k=>$v)
			{
				foreach($plist as $val)
				{
					if($v['pid']==$val['id'])
					{
						$rows[$k]['pname']=$val['name'];
					}
				}
				foreach($clist as $val)
				{
					if($v['client_id']==$val['id'])
					{
						$rows[$k]['username']=$val['name'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/crm_order'));
	}
	function show_action()
	{
		$info=$this->obj->DB_select_once("crm_order","`id`='".$_GET['id']."'");
		$this->yunset("info",$info);
		$product=$this->obj->DB_select_once("crm_product","`id`='".$info['pid']."'");
		$this->yunset("product",$product);
		$this->yuntpl(array('admin/crm_order_show'));
	}
	function status_action() 
	{
		if(is_array($_POST['del']))
		{
			$del=@implode(",",$_POST['del']);
			$layer_type=1;
		}else{
			$del=$_GET['del'];
			$layer_type=0;
		}
		$nid=$this->obj->DB_update_all("crm_order","`status`='3'","`id` in (".$del.")");
		isset($nid)?$this->layer_msg('crm����(ID:".$del.")ȷ����ɳɹ���',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('ȷ�����ʧ�ܣ�',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
}
?>