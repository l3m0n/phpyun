<?php
/*
 * �ֳ��е���
 * ----------------------------------------------------------------------------
 *
 *
 * ============================================================================
*/
function smarty_function_listurl($paramer,&$smarty){
	global $config;
	
	$url = searchListRewrite($paramer,$config);
	
	return $url;
}
?>