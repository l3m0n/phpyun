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
class payment_controller extends user{
	function index_action(){
		$rows=$this->obj->DB_select_all("bank");
		$this->yunset("rows",$rows);
		if($this->usertype!='1' || $this->uid==''){
			$this->ACT_msg("index.php","�Ƿ�������");
		}else{
			$order=$this->obj->DB_select_once("company_order","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' and `order_state`='1'");
			if(is_array($order)){
				$orderbank=@explode("@%",$order['order_bank']);
				$order['bank_name']=$orderbank[0];
				$order['bank_number']=$orderbank[1];				
			}
			if(empty($order)){
				header("Location:index.php?c=paylist");
			}else{ 
				$this->yunset("order",$order);
				$this->public_action();
				$this->yunset("comstyle","../app/template/member/com");
				$this->user_tpl('payment');
			}
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
			if(is_uploaded_file($_FILES['order_pic']['tmp_name'])){
				$upload=$this->upload_pic("../data/upload/order/",false,$this->config['com_uppic']);
				$pictures=$upload->picture($_FILES['order_pic']);
				$this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				$pictures = str_replace("../data/upload/order","./data/upload/order",$pictures);				
			}else{
				$pictures=$order['order_pic'];
			}
			$orderbank=$_POST['bank_name'].'@%'.$_POST['bank_number'].'@%'.$_POST['bank_price'];
			if($_POST['bank_time']){
				$banktime=strtotime($_POST['bank_time']);
			}else{
				$banktime="";
			}
			$company_order="`order_type`='bank',`order_state`='3',`order_remark`='".$_POST['order_remark']."',`order_pic`='".$pictures."',`order_bank`='".$orderbank."',`bank_time`='".$banktime."'";
			if($_POST['is_invoice']=='1'&&$this->config['sy_com_invoice']=='1'){
				$company_order.=",`is_invoice`='".intval($_POST['is_invoice'])."'";
				$this->add_invoice_record($_POST,$order['order_id'],$order['id']);
			}
			$this->obj->DB_update_all("company_order",$company_order,"`order_id`='".$order['order_id']."'");
			$this->ACT_layer_msg("�����ɹ�����ȴ�����Ա��ˣ�",9,"index.php?c=paylist");
		}else{
			$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
		}
	}

}
?>