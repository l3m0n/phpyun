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
class paylist_controller extends user{
	function index_action(){
		include(CONFIG_PATH."db.data.php");
		$this->yunset("arr_data",$arr_data);
		$this->public_action();
		$urlarr=array("c"=>"paylog","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$where.="`uid`='".$this->uid."' and `order_price`>0  order by order_time desc";
		$this->get_page("company_order",$where,$pageurl,"10");
		$this->user_tpl('paylist');
	}
	function del_action(){
		if($_GET['id']){
			$id=$this->obj->DB_delete_all("company_order","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
			if($id){
				$this->obj->member_log("ȡ������");
				$this->layer_msg('����ȡ���ɹ���',9,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('����ȡ��ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function card_action(){
		$info=$this->obj->DB_select_once("prepaid_card","`card`='".$_POST['card']."' and `password`='".$_POST['password']."'");
		if($_POST['card']==""){
			$this->ACT_layer_msg("�����뿨�ţ�",8,$_SERVER['HTTP_REFERER']);
		}elseif($_POST['password']==""){
			$this->ACT_layer_msg("���������룡",8,$_SERVER['HTTP_REFERER']);
		}elseif(empty($info)){
			$this->ACT_layer_msg("���Ż��������",8,$_SERVER['HTTP_REFERER']);
		}elseif($info['uid']>0){
			$this->ACT_layer_msg("�ó�ֵ����ʹ�ã�",8,$_SERVER['HTTP_REFERER']);
		}elseif($info['type']=="2"){
			$this->ACT_layer_msg("�ó�ֵ�������ã�",8,$_SERVER['HTTP_REFERER']);
		}elseif($info['stime']>time()){
			$this->ACT_layer_msg("�ó�ֵ����δ��ʹ��ʱ�䣡",8,$_SERVER['HTTP_REFERER']);
		}elseif($info['etime']<time()){
			$this->ACT_layer_msg("�ó�ֵ���ѹ��ڣ�",8,$_SERVER['HTTP_REFERER']);
		}else{
			$dingdan=mktime().rand(10000,99999);
			$integral=$info['quota']*$this->config['integral_proportion'];
			$data['order_id']=$dingdan;
			$data['order_price']=$info['quota'];
			$data['order_time']=mktime();
			$data['order_state']="2";
			$data['order_remark']="ʹ�ó�ֵ��";
			$data['uid']=$this->uid;
			$data['did']=$this->userdid;
			$data['integral']=$integral;
			$data['type']='2';
			$nid=$this->obj->insert_into("company_order",$data);
			if($nid){
				$this->company_invtal($this->uid,$integral,true,"��ֵ����ֵ",true,$pay_state=2,"integral");
				$this->obj->DB_update_all("prepaid_card","`uid`='".$this->uid."',`username`='".$this->username."',`utime`='".time()."'","`id`='".$info['id']."'"); 
				$this->ACT_layer_msg("��ֵ��ʹ�óɹ���",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("��ֵ��ʹ��ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
			}
		}
		
	}
}
?>