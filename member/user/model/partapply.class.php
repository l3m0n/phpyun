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
class partapply_controller extends user{
	function index_action(){
		$this->public_action();
		$urlarr=array("c"=>"partapply","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("part_apply","`uid`='".$this->uid."' order by id desc",$pageurl,"20");
		if($rows&&is_array($rows)){
			include PLUS_PATH."part.cache.php";
			foreach($rows as $val){
				$jobids[]=$val['jobid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['comid']=$val['comid'];
						$rows[$key]['jobname']=$v['name'];
						$rows[$key]['com_name']=$v['com_name'];
						$rows[$key]['salary']=$v['salary'].$partclass_name[$v['salary_type']];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$StateList=array("1"=>"δ�鿴","2"=>"�Ѳ鿴","3"=>"����ϵ");
		$this->yunset("StateList",$StateList);
		$this->user_tpl('partapply');
	}
	function del_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$nid=$this->obj->DB_delete_all("part_apply","`id`='".$del."' and `uid`='".$this->uid."'");
			if($nid){
				$this->obj->member_log("ɾ����ְ����",5,3);
				$this->layer_msg('ɾ���ɹ���',9,0,"index.php?c=partapply");
			}else{
				$this->layer_msg('ɾ��ʧ�ܣ�',8,0,"index.php?c=partapply");
			}
		}
	}
}
?>