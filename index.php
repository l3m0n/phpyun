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

include(dirname(__FILE__).'/global.php');
if($_GET['yunurl']){

	$var=@explode('-',str_replace('/','-',$_GET['yunurl']));
	foreach($var as $p){
		$param=@explode('_',$p);
		$_GET[$param[0]]=$param[1];
	}
	unset($_GET['yunurl']);
}

if($_GET['m'] && !ereg('^[0-9a-zA-Z\_]*$',$_GET['c'])){
	$_GET['m'] = 'index';
}
$ModuleName = $_GET['m'];
if($ModuleName=='')	$ModuleName='index';
include(LIB_PATH.'init.php');


?>