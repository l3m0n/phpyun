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
class record_controller extends company{
	function index_action(){
		$this->public_action();
		$urlarr['c']="record";
		$urlarr['page']="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("user_entrust_record","`comid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $v){
				$jobid[]=$v['jobid'];
				$eid[]=$v['eid'];
			}
			$job=$this->obj->DB_select_all("company_job","`id` in (".pylode(",",$jobid).")");
			$resume=$this->obj->DB_select_all('resume_expect','`id` in ('.pylode(',',$eid).')','`id`,`name`,`uname`,`salary`,`exp`,`edu`,`hy`,`job_classid`');
			include(PLUS_PATH."user.cache.php");
			include(PLUS_PATH."job.cache.php");
			foreach($rows as $k=>$v){
				foreach($job as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['job_name']=$val['name'];
					}
				}
				foreach($resume as $val){
					if($v['eid']==$val['id']){
						$rows[$k]['resume_name']=$val['name'];
						$rows[$k]['user_name']=$val['uname'];
						$rows[$k]['salary']=$userclass_name[$val['salary']];
						$rows[$k]['exp']=$userclass_name[$val['exp']];
						$rows[$k]['edu']=$userclass_name[$val['edu']];
						$rows[$k]['hy']=$job_name[$val['hy']];
						if($val['job_classid']!=""){
							$job_classid=@explode(',',$val['job_classid']);
							$rows[$k]['jobclassidname']=$job_name[$job_classid['0']];
						}
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset("js_def",5);
		$this->com_tpl('record');
	}
	function del_action(){
		if($_POST['delid']||$_GET['del']){
			if(is_array($_POST['delid'])){
				$id=pylode(",",$_POST['delid']);
				$layer_type='1';
			}else{
				$id=(int)$_GET['del'];
				$layer_type='0';
			}
			$nid=$this->obj->DB_delete_all("user_entrust_record","`id` in (".$id.") and `comid`='".$this->uid."'"," ");
			if($nid){
				$this->obj->member_log("ɾ�����͵��˲�",6,3);
				$this->layer_msg('ɾ���ɹ���',9,$layer_type,"index.php?c=record");
			}else{
				$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_type,"index.php?c=record");
			}
		}
	}
}
?>