<?php
/*
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
 */
class part_model extends model{
	
    function GetPartJobOne($Where=array(),$Options=array()){
		$WhereStr=$this->FormatWhere($Where);
		$FormatOptions=$this->FormatOptions($Options);
        return $this->DB_select_once('partjob',$WhereStr,$FormatOptions['field']);
    }
   
    function AddPartJobHits($id){
        $this->DB_update_all("partjob","`hits`=`hits`+1","`id`='".$id."'");
    }
	
    function GetPartCollectOne($Where=array(),$Options=array()){
		$WhereStr=$this->FormatWhere($Where);
		$FormatOptions=$this->FormatOptions($Options);
        return $this->DB_select_once('part_collect',$WhereStr,$FormatOptions['field']);
    }
    
    function AddPartCollect($Values=array()){
        return $this->insert_into('part_collect',$Values);
    }
	
    function GetPartApplyOne($Where=array(),$Options=array()){
		$WhereStr=$this->FormatWhere($Where);
		$FormatOptions=$this->FormatOptions($Options);
        return $this->DB_select_once('part_apply',$WhereStr,$FormatOptions['field']);
    }
    
    function AddPartApply($Values=array()){
        return $this->insert_into('part_apply',$Values);
    }
}
?>