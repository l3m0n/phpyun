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
class partok_controller extends company{
	function index_action(){
		$this->public_action();
		include PLUS_PATH."part.cache.php";
		$urlarr=array("c"=>"partok","page"=>"{{page}}");
		if($_GET["w"]>=0){
			$where .= " and `state`='".$_GET['w']."'";
			$urlarr['w']=$_GET['w'];
		}else{
			$where .= " and `edate`<'".time()."'";
			$urlarr['w']=0;
		}
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("partjob","`uid`='".$this->uid."' ".$where,$pageurl,'10');
		if(is_array($rows)){
			foreach($rows as $k=>$v){
				$rows[$k]['salary_type']=$partclass_name[$v['salary_type']];
				$rows[$k]['type']=$partclass_name[$v['type']];
				if(intval($_GET['w'])==1){
					$rows[$k]['applynum']=$this->obj->DB_select_num("part_apply","`jobid`='".$v['id']."'");
				}
			}
		}
		$urgent=$this->config['com_urgent'];
		$this->yunset("rows",$rows);
		$this->company_satic();
		$this->yunset("js_def",6);
		if(intval($_GET['w'])==1){
			$this->com_tpl('part');
		}else{
			$this->com_tpl('partok');
		}
	}
	function opera_action(){
		$this->part();
	}
}
?>