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
class com_controller extends company
{
	function index_action()
	{
		include(CONFIG_PATH."db.data.php");
		$this->yunset("arr_data",$arr_data);
		$this->public_action();
		$statis = $this->company_satic();
		$urlarr=array("c"=>"com","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		if($statis['vip_etime']>time())
		{
			$statis['vip_time'] = date("Y��m��d��",$statis['vip_etime']);
		}else{
			$statis['vip_time'] = "�ѹ���";
		} 
		$statis[integral]=number_format($statis[integral]);
		$this->yunset("statis",$statis);
		$rows = $this->get_page("company_order","uid='".$this->uid."' and `type`='1' order by id desc",$pageurl,"10");
		$this->yunset("rows",$rows);
		$allprice=$this->obj->DB_select_once("company_pay","`com_id`='".$this->uid."' and `type`='1' and `order_price`<0","sum(order_price) as allprice"); 
		if($allprice['allprice']==''){$allprice['allprice']='0';}
		$this->yunset("integral",number_format(str_replace("-","", $allprice['allprice'])));
		$this->yunset("js_def",4);
		$this->com_tpl('com');
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
				$this->obj->DB_update_all("prepaid_card","`uid`='".$this->uid."',`username`='".$this->username."',`utime`='".time()."'","`id`='".$info['id']."'");
				$this->company_invtal($this->uid,$integral,true,"��ֵ����ֵ",true,$pay_state=2,"integral");
				$this->ACT_layer_msg("��ֵ��ʹ�óɹ���",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("��ֵ��ʹ��ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
			}
			
		}
	}
}
?>