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
class index_controller extends common{    
	
	function index_action(){
		
		include PLUS_PATH.'cron.cache.php';
		
		if(!empty($cron) && !$start){

			$CronM=$this->MODEL('cron');
			$CronM->excron($cron);
		}
	}
	
}
?>