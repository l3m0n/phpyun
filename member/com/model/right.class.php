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
class right_controller extends company{
	function index_action(){
		$this->public_action();
		$this->company_satic();
		$rows=$this->obj->DB_select_all("company_rating","`category`='1' and `display`='1' order by `type` asc,`sort` desc");
		if(is_array($rows)){
			 
			foreach($rows as $k=>$v){
				$list=array();
				if($v['type']==1){
					if($v['job_num']>0){
						$list[]='����ְλ:<b>'.$v[job_num].'</b>��';
					}
					if($v['resume']>0){
						$list[]='���ؼ���:<b>'.$v[resume].'</b>��';
					}
					if($v['interview']>0){
						$list[]='��������:<b>'.$v[interview].'</b>��';
					}
					if($v['editjob_num']>0){
						$list[]='�޸�ְλ:<b>'.$v[editjob_num].'</b>��';
					}
					if($v['breakjob_num']>0){
						$list[]='ˢ��ְλ:<b>'.$v[breakjob_num].'</b>��';
					}
					if($v['part_num']>0){
						$list[]='������ְ:<b>'.$v[part_num].'</b>��';
					}
					if($v['editpart_num']>0){
						$list[]='�޸ļ�ְ:<b>'.$v[editpart_num].'</b>��';
					}
					if($v['breakpart_num']>0){
						$list[]='ˢ�¼�ְ:<b>'.$v[breakpart_num].'</b>��';
					}
					if($v['zph_num']>0){
						$list[]='��Ƹ�ᱨ��:<b>'.$v[zph_num].'</b>��';
					}
				} 
				$list=@implode("+",$list);
				$rows[$k]['content']=$list;
			}
		}
		$this->yunset("rows",$rows);
		$this->yunset("js_def",4);
		$this->com_tpl('member_right');
	}
	function buyvip_action(){
		$this->public_action();
		$this->company_satic();
		$this->yunset("js_def",4);
		if($_GET['vipid']==0)
		{
			$this->com_tpl('buypl');
		}else{
			$row=$this->obj->DB_select_once("company_rating","`id`='".(int)$_GET['vipid']."' and display='1'");
			$this->yunset("row",$row);
			if($row['time_start']<time() && $row['time_end']>time()){
				$price=$row['integral_buy']-$row['yh_integral'];
			}else{
				$price=$row['integral_buy'];
			}
			$this->com_tpl('buyvip');
		}
	} 
}
?>