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
class special_controller extends company{
	function index_action(){
		$this->company_satic();
		$this->public_action();
		$urlarr["c"]="special";
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$where="`uid`='".$this->uid."'";
		$rows=$this->get_page("special_com",$where,$pageurl,"10");
		if($rows&&is_array($rows)){
			$uid=array();
			foreach($rows as $val){
				$sid[]=$val['sid'];
			}
			$special=$this->obj->DB_select_all("special","`id` in(".pylode(',',$sid).")","id,title,intro");
			foreach($rows as $key=>$val){
				foreach($special as $v){
					if($val['sid']==$v['id']){
						$rows[$key]['title']=$v['title'];
						$rows[$key]['intro']=$v['intro'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset("js_def",3);
		$this->com_tpl("special");
	}
	function del_action(){
		$id=$this->obj->DB_select_once("special_com","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."' and `status`=0","uid,integral");
		if($id&&$id['integral']>0){
			$this->company_invtal($id['uid'],$id['integral'],true,"ȡ��ר����Ƹ�������˻�".$this->config['integral_pricename'],true,2,'integral');
		}
		$delid=$this->obj->DB_delete_all("special_com","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'"," ");
		if($delid){
			$this->obj->member_log("ɾ��ר�ⱨ��");
			$this->layer_msg('ɾ���ɹ���',9,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('ɾ��ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>