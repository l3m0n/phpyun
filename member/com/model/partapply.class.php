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
class partapply_controller extends company{
	function index_action(){
		$where='1';
		if($_GET['jobid']){
			$where.=" and `jobid`=".intval($_GET['jobid'])."  ";
			$urlarr['jobid']=$_GET['jobid'];
		}
        if($_GET['status']){
			$where.=" and `status`=".intval($_GET['status'])."  ";
			$urlarr['status']=$_GET['status'];
		}
		$this->public_action();
		$urlarr['c']=$_GET['c'];
		$urlarr['page']="{{page}}";
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("part_apply",$where." and  `comid`='".$this->uid."' order by id desc",$pageurl,"10");
		$joblist=$this->obj->DB_select_all("partjob","`uid`='".$this->uid."'","`id`,`name`");
		if(!empty($rows)){
			foreach($rows as $v){
				$uid[]=$v['uid'];
			}
			$resume=$this->obj->DB_select_all("resume","`uid` in (".@implode(",",$uid).")","`uid`,`name`,`telphone`,`sex`,`edu`");
			foreach($rows as $k=>$v){
				foreach($joblist as $val){
					if($v['jobid']==$val['id']){
						$rows[$k]['jobname']=$val['name'];
					} 
				}
				include PLUS_PATH."user.cache.php";
				foreach($resume as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['name']=$val['name'];
						$rows[$k]['telphone']=$val['telphone'];
						$rows[$k]['sex']=$userclass_name[$val['sex']];
						$rows[$k]['edu']=$userclass_name[$val['edu']];
					}
				}
			}
		}
		if($joblist&&is_array($joblist)){
			foreach($joblist as $val){ 
				if($val['id']==$_GET['jobid']){
					$jobname=$val['name'];
				}
			}
		}
		
		$this->yunset("jobname",$jobname);
        $this->yunset("JobList",$joblist);
        $this->yunset("rows",$rows);
		$this->yunset(array('StateList'=>array(array('id'=>1,'name'=>'δ�鿴'),array('id'=>2,'name'=>'�Ѳ鿴'),array('id'=>3,'name'=>'����ϵ'))));
		$this->company_satic();
		$this->yunset("js_def",6);
		$this->com_tpl('partapply');
	}
	function status_action(){
		if($_POST['id']&&$_POST['status']){
			$this->obj->DB_update_all("part_apply","`status`='".(int)$_POST['status']."'","`id`='".(int)$_POST['id']."' and `comid`='".$this->uid."'");
		}
	}
	function del_action(){
		if($_POST['delid']||$_GET['del']){
			if(is_array($_POST['delid'])){
				$delid=pylode(",",$_POST['delid']);
				$layer_type='1';
			}else{
				$delid=(int)$_GET['del'];
				$layer_type='0';
			}
			$nid=$this->obj->DB_delete_all("part_apply","`id` in (".$delid.") and `comid`='".$this->uid."'","");
			if($nid){
				$this->obj->member_log("ɾ����ְ����");
				$this->layer_msg('ɾ���ɹ���',9,$layer_type,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_type,$_SERVER['HTTP_REFERER']);
			}
		}
	}
}
?>