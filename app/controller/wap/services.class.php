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
class services_controller extends common{
	function index_action(){
		$this->rightinfo();
		$this->get_moblie();
		$M=$this->MODEL('article');
		$row=$M->GetDescriptionOnce(array('id'=>'5'),array('field'=>'content'));
		$this->yunset("row",$row);
		$this->yunset("title","����Э��"); 
		$this->yuntpl(array('wap/services'));
	}	
}
?>