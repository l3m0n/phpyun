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
class partcollect_controller extends user{
	function index_action(){
		$this->public_action();
		$urlarr=array("c"=>"partcollect","page"=>"{{page}}");
        $StateNameList=array('0'=>'�ȴ����','1'=>'��Ƹ��','2'=>'�ѽ���','3'=>'δͨ��');
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("part_collect","`uid`='".$this->uid."' order by id desc",$pageurl,"20");
		if($rows&&is_array($rows)){
			include PLUS_PATH."city.cache.php";
			include PLUS_PATH."part.cache.php";
			foreach($rows as $val){
				$jobids[]=$val['jobid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['jobname']=$v['name'];
						$rows[$key]['com_name']=$v['com_name'];
						$rows[$key]['cityid']=$city_name[$v['cityid']];
						$rows[$key]['three_cityid']=$city_name[$v['three_cityid']];
						$rows[$key]['salary']=$v['salary'].$partclass_name[$v['salary_type']];
						$rows[$key]['billing_cycle']=$partclass_name[$v['billing_cycle']];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->user_tpl('partcollect');
	}
	function del_action(){
		if($_GET['id']){
			$del=(int)$_GET['id'];
			$nid=$this->obj->DB_delete_all("part_collect","`id`='".$del."' and `uid`='".$this->uid."'");
			if($nid){
				$this->obj->member_log("ɾ����ְ�ղ�",5,3);
				$this->layer_msg('ɾ���ɹ���',9,0,"index.php?c=partcollect");
			}else{
				$this->layer_msg('ɾ��ʧ�ܣ�',8,0,"index.php?c=partcollect");
			}
		}
	}
}
?>