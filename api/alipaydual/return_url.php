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
/* *
 * ���ܣ�֧����ҳ����תͬ��֪ͨҳ��
 * �汾��3.2
 * ���ڣ�2011-03-25
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���


 */
error_reporting(0);
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
require_once(dirname(dirname(dirname(__FILE__)))."/global.php");

//����ó�֪ͨ��֤���

$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//��֤�ɹ�
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//������������̻���ҵ���߼��������

	//�������������ҵ���߼�����д�������´�������ο�������
    //��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�
    $dingdan		= $_GET['out_trade_no'];	//��ȡ������
    $trade_no		= $_GET['trade_no'];		//��ȡ֧�������׺�
    $total_fee		= $_GET['price'];			//��ȡ�ܼ۸�
	if(!preg_match('/^[0-9]+$/', $_GET['out_trade_no'])){
		die;
	}
	
	require_once(APP_PATH.'app/public/common.php');
	require_once(LIB_PATH.'ApiPay.class.php');

	$apiPay = new apipay($phpyun,$db,$db_config['def'],'index');
	
	$apiPay->payAll($dingdan,$total_fee,'alipay');
	header("Location:".$config['sy_weburl']."/member/index.php?c=paylog");
/*
    if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
			//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
			//���������������ִ���̻���ҵ�����
    }
	else if($_GET['trade_status'] == 'TRADE_FINISHED') {
		//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
			//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
			//���������������ִ���̻���ҵ�����
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
*/


	//�������������ҵ���߼�����д�������ϴ�������ο�������

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //��֤ʧ��
    //��Ҫ���ԣ��뿴alipay_notify.phpҳ���verifyReturn�������ȶ�sign��mysign��ֵ�Ƿ���ȣ����߼��$responseTxt��û�з���true
    echo "��֤ʧ��";
}
?>
