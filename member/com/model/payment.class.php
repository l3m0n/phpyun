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
class payment_controller extends company{
	function index_action(){
		$rows=$this->obj->DB_select_all("bank");
		$this->yunset("rows",$rows);
		$order=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."'");
		if(is_array($order)){
			$orderbank=@explode("@%",$order['order_bank']);
			if(is_array($orderbank)){
				foreach($orderbank as $key){
					$orderbank[]=$key;
				}
				$order['bank_name']=$orderbank[0];
				$order['bank_fname']=$orderbank[1];
				$order['bank_number']=$orderbank[2];
			}
		}
		if(empty($order)){ 
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"���������ڣ�"); 
		}elseif($order['order_state']!='1'){ 
			header("Location:index.php?c=paylog"); 
		}else{
			$statis=$this->company_satic();
			$company=$this->obj->DB_select_once("company","`uid`='".$this->uid."'","`linkman`,`linktel`,`address`");
			$order_remark="�����������У�\n�һ�����ʺţ�\n����\n���ʱ�䣺\n������\n";
			if($company['linkman']==''||$company['linktel']==''||$company['address']==''){
				$company['link_null']='1';
			} 
			if($order['order_time']>strtotime("-7 day")){
				$order['invoice']='1';
			}
			 
			$this->yunset("company",$company);
			$this->yunset("order",$order);
			$this->yunset("order_remark",$order_remark);
			$this->yunset("statis",$statis);
			$this->yunset("js_def",4);
			$this->public_action();
			$this->com_tpl('payment');
		}
	}

	function paybank_action(){
		$order=$this->obj->DB_select_once("company_order","`id`='".(int)$_POST['oid']."' and `uid`='".$this->uid."'");
		if($order['id']){ 
			if($_POST['bank_name']==""){
				$this->ACT_layer_msg("����д������У�");
			}
			if($_POST['bank_number']==""){
				$this->ACT_layer_msg("����д�����˺ţ�");
			}
			if($_POST['bank_price']==""){
				$this->ACT_layer_msg("����д����");
			}
			if($_POST['bank_time']==""){
				$this->ACT_layer_msg("����д���ʱ�䣡");
			}
			if($_POST['nextstep']){
				if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
					$upload=$this->upload_pic("../data/upload/order/",false,$this->config['com_uppic']);
					$pictures=$upload->picture($_FILES['order_pic']);
					$this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
					$pictures = str_replace("../data/upload/order","./data/upload/order",$pictures);				
				}else{
					$pictures=$order['order_pic'];
				}
			}
			$orderbank=$_POST['bank_name'].'@%'.$_POST['bank_number'].'@%'.$_POST['bank_price'];
			if($_POST['bank_time']){
				$banktime=strtotime($_POST['bank_time']);
			}else{
				$banktime="";
			}
			$company_order="`order_type`='bank',`order_state`='3',`order_remark`='".$_POST['order_remark']."',`order_pic`='".$pictures."',`order_bank`='".$orderbank."',`bank_time`='".$banktime."'";
			$this->obj->DB_update_all("company_order",$company_order,"`order_id`='".$order['order_id']."'");
			$this->ACT_layer_msg("�����ɹ�����ȴ�����Ա��ˣ�",9,"index.php?c=payment&id=".$_POST['oid']);
		}else{
			$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
		}
	}


}
?>