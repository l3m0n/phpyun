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

///////////ҳ�湦��˵��///////////////
//��ҳ����ڱ������Բ���
//��ҳ�����������ҳ��������֧����������ͬ�����ã��ɵ�����֧����ɺ����ʾ��Ϣҳ���硰����ĳĳĳ���������ٽ����֧���ɹ�����
//�ɷ���HTML������ҳ��Ĵ���Ͷ���������ɺ�����ݿ���³������
//��ҳ�����ʹ��PHP�������ߵ��ԣ�Ҳ����ʹ��д�ı�����log_result���е��ԣ��ú����ѱ�Ĭ�Ϲرգ���alipay_notify.php�еĺ���return_verify
//TRADE_FINISHED(��ʾ�����Ѿ��ɹ�������Ϊ��ͨ��ʱ���ʵĽ���״̬�ɹ���ʶ);
//TRADE_SUCCESS(��ʾ�����Ѿ��ɹ�������Ϊ�߼���ʱ���ʵĽ���״̬�ɹ���ʶ);
///////////////////////////////////
error_reporting(0);
require_once("class/alipay_notify.php");
require_once("alipay_config.php");
require_once(dirname(dirname(dirname(__FILE__)))."/global.php");

//����֪ͨ������Ϣ
$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
//����ó�֪ͨ��֤���
$verify_result = $alipay->return_verify();

if($verify_result) {

    //��֤�ɹ�
    //��ȡ֧������֪ͨ���ز���
    $dingdan           = $_GET['out_trade_no'];    //��ȡ������
    $total_fee         = $_GET['total_fee'];	    //��ȡ�ܼ۸�
	if(!preg_match('/^[0-9]+$/',$dingdan))
	{
		die;
	}

    /*���裺
	sOld_trade_status="0";��ʾ����δ����
	sOld_trade_status="1";��ʾ���׳ɹ���TRADE_FINISHED/TRADE_SUCCESS����
    */
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		
		
		
        //���붩��������ɺ�����ݿ���³�����룬����ر�֤echo��������Ϣֻ��success
        //Ϊ�˱�֤�����ظ����ã����ظ�ִ�����ݿ���³������жϸñʽ���״̬�Ƿ��Ƕ���δ����״̬

        if ($sOld_trade_status < 1) {
			
            //���ݶ����Ÿ��¶������Ѷ�������ɽ��׳ɹ�
			require_once(APP_PATH.'app/public/common.php');
			require_once(LIB_PATH.'ApiPay.class.php');
			$apiPay = new apipay($phpyun,$db,$db_config['def'],'index');

			$apiPay->payAll($dingdan,$total_fee,'alipay');
        }
		header("Location:".$config['sy_weburl']."/member/index.php?c=paylog");
    }else {
      echo "trade_status=".$_GET['trade_status'];	
    }
}else {
    //��֤ʧ��
    //��Ҫ���ԣ��뿴alipay_notify.phpҳ���return_verify�������ȶ�sign��mysign��ֵ�Ƿ���ȣ����߼��$veryfy_result��û�з���true
    //echo "fail";
}
?>