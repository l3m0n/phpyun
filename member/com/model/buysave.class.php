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
class buysave_controller extends company{
	function index_action(){ 
		$statis=$this->company_satic(); 
		if($_POST['type']=='ad'){
			$row=$this->obj->DB_select_once("ad_class","`id`='".(int)$_POST['aid']."' and `type`='1'");  
			if($row['id']){
				$integral=$row['integral_buy']*intval($_POST['buy_time']);
			}else{
				$this->ACT_layer_msg("�Ƿ�������",8,"index.php?c=ad"); 
			}
		}elseif($_POST['type']=='pl'){
			$integral=$this->config['integral_com_comments']*intval($_POST['time']);
		}
		if($statis['integral']<$integral){
			$this->ACT_layer_msg("���".$this->config['integral_pricename']."���㣬���ȳ�ֵ��",8,"index.php?c=pay");
		}
		if($_POST['type']=='pl'){
			$this->company_invtal($this->uid,$integral,false,"������ҵ���۹���",true,2,'integral',16);
			$company=$this->obj->DB_select_once("company","`uid`='".$this->uid."'","`pl_time`");
			if($company['pl_time']>time()){
				$pl_time=$company['pl_time']+86400*30*$_POST['time'];
			}else{
				$pl_time=time()+86400*30*intval($_POST['time']);
			}
			$oid=$this->obj->update_once("company",array("pl_time"=>$pl_time),array("uid"=>$this->uid));
			if($oid){
				$this->obj->member_log("�������۹���");
				$this->ACT_layer_msg("���ѹ���ɹ���",9,"index.php?c=pl");
			}else{
 				$this->ACT_layer_msg("����ʧ�ܣ����Ժ����ԣ�",8,$_SERVER['HTTP_REFERER']);
			}
		}
		if($_POST['type']=="ad"){
			
			$pay_integral = $integral;
			$nid=$this->company_invtal($this->uid,$pay_integral,false,"������λ",true,2,'integral',4);
			if($nid){
				$data['comid']=$this->uid;
				$data['did']=$this->userdid;
				$data['order_id']=mktime().rand(10000,99999);
		 		$upload = $this->upload_pic("../data/upload/adpic/");
		 		$pictures=$upload->picture($_FILES['pic_url']);
		 		$data['ad_name']=$_POST['ad_name'];
		 		$data['pic_url']=$pictures;
				$data['pic_src']=$_POST['pic_src'];
				$data['buy_time']=intval($_POST['buy_time']);
				$data['integral']=$pay_integral;
				$data['aid']=(int)$_POST['aid'];
				$data['adname']=$_POST['adname'];
				$data['order_state']=2;
				$data['datetime']=mktime();
				$oid=$this->obj->insert_into("ad_order",$data);
				if($oid)
				{
					$content="�����˹��λ ".$_POST['adname'];
					$this->addstate($content,2);
					$this->obj->member_log($content);
 					$this->ACT_layer_msg("���Ķ������ύ����ȴ�����Ա��ˣ�",9,"index.php?c=ad_order");
				}else{
 					$this->ACT_layer_msg("�ύʧ�ܣ����Ժ����ԣ�",8,$_SERVER['HTTP_REFERER']);
				}
			}else{
 				$this->ACT_layer_msg("ϵͳ��������ϵ����Ա��",8,"index.php");
			}
		}
	}
}
?>