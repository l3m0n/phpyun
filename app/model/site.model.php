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
class site_model extends model{
	
	function GetSiteDomian($keyword){
       $Site = $this->DB_select_all("domain","`title` LIKE '%".iconv("utf-8","gbk",$_POST['keyword'])."%'");
		if(is_array($Site) && !empty($Site)){
			foreach($Site as $value){
				$siteHtml .='<option value="'.$value['id'].'">'.$value['title'].'</option>'; 
			}
			return $siteHtml;
		}else{
			return 1;
		}
    }
	
    function UpDid($Table=array(),$Did,$Where){		
		foreach($Table as $value){		
			$this->DB_update_all($value,"`did`='".$Did."'",$Where);
		}        
    }	
}
?>