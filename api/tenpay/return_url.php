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
//---------------------------------------------------------
//�Ƹ�ͨ��ʱ����֧��Ӧ�𣨴���ص���ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------
error_reporting(0);
require_once ("./classes/PayResponseHandler.class.php");
require_once(dirname(dirname(dirname(__FILE__)))."/data/api/tenpay/tenpay_data.php");
require_once(dirname(dirname(dirname(__FILE__)))."/global.php");
/* ��Կ */
$key =$tenpaydata[sy_tenpaycode];

/* ����֧��Ӧ����� */
$resHandler = new PayResponseHandler();
$resHandler->setKey($key);

//�ж�ǩ��
if($resHandler->isTenpaySign()) {

	//���׵���
	$transaction_id = $resHandler->getParameter("transaction_id");

	//��վ����
	$sp_billno = $resHandler->getParameter("sp_billno");

	//���,�Է�Ϊ��λ
	$total_fee = $resHandler->getParameter("total_fee");

	//֧�����
	$pay_result = $resHandler->getParameter("pay_result");
	//����
	$attach = $resHandler->getParameter("attach");

	if( "0" == $pay_result ) {

		//------------------------------
		//����ҵ��ʼ
		//------------------------------

		//ע�⽻�׵���Ҫ�ظ�����
		//ע���жϷ��ؽ��
//����վ��Ϣ��ʼ
	if(!preg_match('/^[0-9]+$/',$sp_billno))
	{
		die;
	}
	require_once(APP_PATH.'app/public/common.php');
	require_once(LIB_PATH.'ApiPay.class.php');

	$apiPay = new apipay($phpyun,$db,$db_config['def'],'index');
	
	$apiPay->payAll($sp_billno,$total_fee,'tenpay');
	

//����վ��Ϣ����




		//------------------------------
		//����ҵ�����
		//------------------------------

		//����doShow, ��ӡmetaֵ��js����,���߲Ƹ�ͨ����ɹ�,�����û��������ʾ$showҳ��.
		//$show = $tenpaydata[sy_weburl]."/app/tenpay/show.php";
		//$resHandler->doShow($show);
		header("Location:".$config['sy_weburl']."/member/index.php?c=paylog");
	} else {
		//�������ɹ�����
		echo "<br/>" . "֧��ʧ��" . "<br/>";
	}

} else {
	echo "<br/>" . "��֤ǩ��ʧ��" . "<br/>";
}

//echo $resHandler->getDebugInfo();

?>