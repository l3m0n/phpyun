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
include(dirname(dirname(__FILE__)).'/global.php');
include_once(LIB_PATH.'public.wapdomain.php');
$pageType = 'wap';
$Dir = str_replace("/","\\",dirname(__FILE__));
$DirNameList=explode('\\',$Dir);
$ModuleName=end($DirNameList);
$DirName   = end($DirNameList);


include(LIB_PATH.'init.php');
?>