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
class reward_list_controller extends company
{
	function index_action()
	{
		$this->public_action();
		$urlarr['c']='reward_list';
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("change"," `uid`='".$this->uid."' order by id desc",$pageurl,"13");
		$this->yunset("js_def",4);
		$this->com_tpl('reward_list');
	}
	
 function del_action(){
		if($this->usertype!='2' || $this->uid==''){
			$this->layer_msg('��¼��ʱ��',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$rows=$this->obj->DB_select_once("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			if($rows['id']){
				$this->obj->DB_update_all("reward","`num`=`num`-".$rows['num'].",`stock`=`stock`+".$rows['num']."","`id`='".$rows['gid']."'");
				$this->company_invtal($this->uid,$rows['integral'],true,"ȡ����Ʒ�һ�",true,2,'integral',24);
				$this->obj->DB_delete_all("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			}
			$this->obj->member_log("ȡ���һ�");
			$this->layer_msg('ȡ���ɹ���',9,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>