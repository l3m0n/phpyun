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
class company_order_controller extends common
{
 	function set_search(){
		$search_list[]=array("param"=>"typezf","name"=>'֧������',"value"=>array("alipay"=>"֧����","tenpay"=>"�Ƹ�ͨ","bank"=>"����ת��"));
		$search_list[]=array("param"=>"typedd","name"=>'��������',"value"=>array("1"=>"��Ա��ֵ","2"=>"���ֳ�ֵ","3"=>"����ת��","4"=>"����ֵ"));
		$search_list[]=array("param"=>"order_state","name"=>'����״̬',"value"=>array("0"=>"֧��ʧ��","1"=>"�ȴ�����","2"=>"֧���ɹ�","3"=>"�ȴ�ȷ��"));
		$lo_time=array('1'=>'����','3'=>'�������','7'=>'�������','15'=>'�������','30'=>'���һ����');
		$search_list[]=array("param"=>"time","name"=>'��ֵʱ��',"value"=>$lo_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
 		$this->set_search();
		$where="1 ";
		if($_GET['typezf']){
			$where .=" and `order_type`='".$_GET['typezf']."'";
			$urlarr['typezf']=$_GET['typezf'];
		}
		if($_GET['typedd']){
			$where .=" and `type`='".$_GET['typedd']."'";
			$urlarr['typedd']=$_GET['typedd'];
		}
		if($_GET['news_search']){
			if ($_GET['keyword']!=""){
				if ($_GET['typeca']=='1'){
					$where .=" and `order_id` like '%".$_GET['keyword']."%'";				   
			     }elseif($_GET['typeca']=='2'){
				    $orderinfo=$this->obj->DB_select_all("member","`username` like '%".trim($_GET['keyword'])."%'","`uid`");
					if (is_array($orderinfo))
					{
						foreach ($orderinfo as $val)
						{
							$orderuids[]=$val['uid'];
						}
						$oruids=@implode(",",$orderuids);
					}
					$where.=" and `uid` in (".$oruids.")";
			     }
			     $urlarr['typeca']=$_GET['typeca'];
			     $urlarr['keyword']=$_GET['keyword'];				 
			}
			 $urlarr['news_search']=$_GET['news_search'];
		}
		if($_GET['time']){
			if($_GET['time']=='1'){
				$where.=" and `order_time` >='".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where .=" and `order_time`>'".strtotime('-'.intval($_GET['time']).' day')."'";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['order_state']!=""){
            $where.=" and `order_state`='".$_GET['order_state']."'";
			$urlarr['order_state']=$_GET['order_state'];
	    }
		if($_GET['fb']!=""){
            $where.=" and `is_invoice`='".$_GET['fb']."'";
			$urlarr['fb']=$_GET['fb'];
	    }
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		} 
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$rows=$this->get_page("company_order",$where,$pageurl,$this->config['sy_listnum']);
		include (APP_PATH."/config/db.data.php");
		if(is_array($rows))
		{
			foreach($rows as $k=>$v)
			{
				$rows[$k]['order_state_n']=$arr_data['paystate'][$v['order_state']];
				$rows[$k]['order_type_n']=$arr_data['pay'][$v['order_type']];
				$classid[]=$v['uid'];
			}
			$group=$this->obj->DB_select_all("member","uid in (".@implode(",",$classid).")","`uid`,`username`");
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			$resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$classid).")","`uid`,`name`");
			foreach($rows as $k=>$v)
			{
				foreach($company as $val)
				{
					if($v['uid']==$val['uid'])
					{
						$rows[$k]['comname']=$val['name'];
					}
				}				
				foreach($group as $val){
					
						if($v['uid']==$val['uid']){
							$rows[$k]['username']=$val['username'];
						}					
				}
				

				foreach($resume as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['comname']=$val['name'];
					}
				}
			}
		}
        $this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_company_order'));
	}
	function edit_action(){
		$id=(int)$_GET['id'];
		$row=$this->obj->DB_select_once("company_order","`id`='".$id."'");	
		 
		
		if(is_array($row)){	
			$member=$this->obj->DB_select_once('member',"`uid`='".$row['uid']."'","username,usertype");	
			$row['username']=$member['username']; 
			
		if($member['usertype']=='1'){
				$resume=$this->obj->DB_select_once("resume","`uid`='".$member['uid']."'","`uid`,`name`");
				$row['comname']=$resume['name'];
			}else if($member['usertype']=='2'){
				$company=$this->obj->DB_select_once("company","`uid`='".$member['uid']."'","`uid`,`name`");
				$row['comname']=$company['name'];
			}
			$orderbank=@explode("@%",$row['order_bank']);
			if(is_array($orderbank)){
				foreach($orderbank as $key){
					$orderbank[]=$key;
				}
				$row['bankname']=$orderbank[0];
				$row['bankid']=$orderbank[1];
			} 
		} 
		$this->yunset("row",$row);
		$this->yuntpl(array('admin/admin_company_order_edit'));
	}
	function save_action(){ 
		if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
			$upload=$this->upload_pic("../data/upload/order/");
			$pictures=$upload->picture($_FILES['order_pic']);
			$this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
			$pictures = str_replace("../data/upload/order","./data/upload/order",$pictures);
		}else{
			$order=$this->obj->DB_select_once("company_order","`id`='".(int)$_POST['id']."'");		
			$pictures=$order['order_pic'];
		}
		$r_id=$this->obj->DB_update_all("company_order","`order_price`='".$_POST['order_price']."',`order_remark`='".$_POST['order_remark']."',`is_invoice`='".$_POST['is_invoice']."',`order_pic`='".$pictures."'","id='".$_POST['id']."'");
		isset($r_id)?$this->ACT_layer_msg("��ֵ��¼(ID:".$_POST['id'].")�޸ĳɹ���",9,"index.php?m=company_order",2,1):$this->ACT_layer_msg("�޸�ʧ��,���������ԣ�",8,"index.php?m=company_order");
	}
	function setpay_action(){
		$del=(int)$_GET['id'];
		$this->check_token();
		$row=$this->obj->DB_select_once("company_order","`id`='$del'");
		if($row['order_state']=='1'||$row['order_state']=='3'){
			$nid=$this->upuser_statis($row);
			isset($nid)?$this->layer_msg("��ֵ��¼(ID:".$del.")ȷ�ϳɹ���",9):$this->layer_msg("ȷ��ʧ��,���������ԣ�",8);
		}else{
			$this->layer_msg("������ȷ�ϣ������ظ�������",8);
		}
	}
 	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if(is_array($del)){
				$company_order=$this->obj->DB_select_all("company_order","`id` in(".@implode(',',$del).")","`order_id`");
				if($company_order&&is_array($company_order)){
					foreach($company_order as $val){
						$order_ids[]=$val['order_id'];
					}
					$this->obj->DB_delete_all("invoice_record","`order_id` in(".@implode(',',$order_ids).")","");
					$this->obj->DB_delete_all("company_order","`id` in(".@implode(',',$del).")","");
				}
				$this->layer_msg( "��ֵ��¼(ID:".@implode(',',$del).")ɾ���ɹ���",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "��ѡ����Ҫɾ������Ϣ��",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
	    if(isset($_GET['id'])){
			$company_order=$this->obj->DB_select_once("company_order","`id`='".$_GET['id']."'" ,"`order_id`");
			$this->obj->DB_delete_all("invoice_record","`order_id`='".$company_order['order_id']."'" );
			$result=$this->obj->DB_delete_all("company_order","`id`='".$_GET['id']."'" );
			isset($result)?$this->layer_msg('��ֵ��¼(ID:'.$_GET['id'].')ɾ���ɹ���',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>