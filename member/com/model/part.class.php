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
class part_controller extends company{
	function index_action(){
		include PLUS_PATH."part.cache.php";
		if(trim($_GET['keyword'])){
			$where.=" and name like '%".trim($_GET['keyword'])."%'";
			$urlarr['keyword']=trim($_GET['keyword']);
		}
		$urlarr['c']=$_GET['c'];
		$urlarr["page"]="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("partjob","`uid`='".$this->uid."'".$where,$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['salary_type']=$partclass_name[$v['salary_type']];
				$rows[$k]['type']=$partclass_name[$v['type']];
			}
		}
		$this->yunset("rows",$rows);
		$this->company_satic();
		$this->yunset("js_def",6);
		$this->com_tpl('partlist');
	}
	function del_action(){
		if($_GET['del']||$_GET['id']){
			if(is_array($_GET['del'])){
				$del=@implode(",",$_GET['del']);
				$layer_type=1;
			}else{
				$del=(int)$_GET['id'];
				$layer_type=0;
			}
		}
		$oid=$this->obj->DB_delete_all("partjob","`id` in (".$del.") and `uid`='".$this->uid."'","");
		if($oid){
			$this->obj->DB_delete_all("part_collect","`jobid` in (".$del.") and `comid`='".$this->uid."'","");
			$this->obj->DB_delete_all("part_apply","`jobid` in (".$del.") and `comid`='".$this->uid."'","");
			$this->obj->member_log("ɾ����ְְλ");
			$this->layer_msg('ɾ���ɹ���',9,$layer_type,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_type,$_SERVER['HTTP_REFERER']);
		}
	}
	function opera_action(){
		$this->part();
	}
}
?>