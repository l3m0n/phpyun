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

if($_GET['yunurl']){
	$var=@explode('-',str_replace('/','-',$_GET['yunurl']));
	foreach($var as $p){
	
		$param=@explode('_',$p);
		$_GET[$param[0]]=$param[1];
	}
	unset($_GET['yunurl']);
}


if($_GET['c'] && !ereg('^[0-9a-zA-Z\_]*$',$_GET['c'])){
	$_GET['c'] = 'index';
}
if($_GET['a'] && !ereg('^[0-9a-zA-Z\_]*$',$_GET['c'])){
	$_GET['a'] = 'index';
}

if($config['webcache']=='1' && !in_array($_GET['m'],array('ajax','wxconnect','qqconnect','sinaconnect')) && !in_array($_GET['c'],array('clickhits'))){
	include_once(LIB_PATH.'web.cache.php');
	$cache=new Phpyun_Cache('./cache',DATA_PATH,$config['webcachetime']);
	$cache->read_cache();
}

global $ModuleName,$DirName;

$Loaction = wapJump($config);
if (!empty($Loaction)){

	header('Location: '.$Loaction);exit;
}


$ControllerName = $_GET['c'];
$ActionName = $_GET['a'];
if($ControllerName=='')	$ControllerName='index';
if($ActionName=='')	$ActionName = 'index';

if($config['sy_'.$ModuleName.'_web']==2){
    header('Location: '.Url("error"));exit;
}

$ControllerPath=APP_PATH.'app/controller/'.$ModuleName.'/';
require(APP_PATH.'app/public/common.php');
if(file_exists($ControllerPath.$ModuleName.'.controller.php')){
    require($ControllerPath.$ModuleName.'.controller.php');
}
if(file_exists($ControllerPath.$ControllerName.'.class.php')){
    require($ControllerPath.$ControllerName.'.class.php');
}else{
    $ActionName=$ControllerName;$ControllerName='index';
    if(!file_exists($ControllerPath.$ControllerName.'.class.php')){
        header('Location: '.Url("error"));exit;
    }else{
        require($ControllerPath.'index.class.php');
    }
}

if($ModuleName=='siteadmin'){$model='admin';}elseif($ModuleName=='wap'){$model='wap';}else{$model='index';}

$conclass=$ControllerName.'_controller';
$actfunc=$ActionName.'_action';

$views=new $conclass($phpyun,$db,$db_config['def'],$model,$ModuleName);
$views->m=$ModuleName;
if(!method_exists($views,$actfunc)){
	$views->DoException();
}
$views->$actfunc();

if($cache){
	$cache->CacheCreate();
}
?>