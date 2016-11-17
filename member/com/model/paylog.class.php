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
class paylog_controller extends company
{
	function index_action(){
		include(CONFIG_PATH."db.data.php");
		$this->yunset("arr_data",$arr_data);
		$this->public_action();
		if($_GET['consume']=="ok")
		{
			$urlarr=array("c"=>"paylog","consume"=>"ok","page"=>"{{page}}");
			$pageurl=Url('member',$urlarr);
			$where="`com_id`='".$this->uid."'";

			$where.="  order by pay_time desc";
			$rows = $this->get_page("company_pay",$where,$pageurl,"10");
			if(is_array($rows)){
				foreach($rows as $k=>$v){
					$rows[$k]['pay_time']=date("Y-m-d H:i:s",$v['pay_time']);
					$rows[$k]['order_price']=str_replace(".00","",$rows[$k]['order_price']);
				}
			} 
			$this->yunset("rows",$rows);
			$this->yunset("ordertype","ok");
		}else{
			$urlarr=array("c"=>"paylog","page"=>"{{page}}");
			$pageurl=Url('member',$urlarr);
			$where="`uid`='".$this->uid."'  order by order_time desc";
			$rows=$this->get_page("company_order",$where,$pageurl,"10");
			foreach($rows as $v){
				$ord[]=$v['order_id'];
			}	
			$ords=@implode(',',$ord);
			$order=$this->obj->DB_select_all("invoice_record","`order_id` in(".$ords.") and `uid`='".$this->uid."'","`status`,`order_id`");
			if($rows&&is_array($rows)&&$this->config['sy_com_invoice']=='1'){
				$last_days=strtotime("-7 day");
				foreach($rows as $key=>$val){
					if($val['order_time']>=$last_days && $val['order_remark']!="ʹ�ó�ֵ��"){
						$rows[$key]['invoice']='1';
					
					}
					foreach($order as $k=>$v){
						if($val['order_id']==$v['order_id']){
							$rows[$key]['status']=$v['status'];
						}
					}
				}
				$this->yunset("rows",$rows);
			}
		} 
		if($_POST['submit']){
			if(trim($_POST['order_remark'])==""){
				$this->ACT_layer_msg("��ע����Ϊ�գ�",8,$_SERVER['HTTP_REFERER']);
			}
			$nid=$this->obj->DB_update_all("company_order","`order_remark`='".trim($_POST['order_remark'])."'","`id`='".(int)$_POST['id']."' and `uid`='".$this->uid."'");
			if($nid)
			{
				$this->obj->member_log("�޸Ķ�����ע");
				$this->ACT_layer_msg("�޸ĳɹ���",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("�޸�ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
			}
		}

		$this->yunset("js_def",4);
		$this->com_tpl('paylog');
	}

	function del_action(){
		if($this->usertype!='2' || $this->uid==''){
			echo '0';die;
		}else{
			$oid=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' and `order_state`='1'");
			if(empty($oid)){
				echo '0';die;
			}else{
				$this->obj->DB_delete_all("company_order","`id`='".$oid['id']."' and `uid`='".$this->uid."'");
				$this->obj->DB_delete_all("invoice_record","`oid`='".$oid['id']."'  and `uid`='".$this->uid."'");
				echo '1';die;
			}
		}
	}

}
?>