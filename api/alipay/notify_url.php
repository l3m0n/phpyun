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

/////////////////////////////////////
error_reporting(0);
require_once("class/alipay_notify.php");
require_once("alipay_config.php");
require_once(dirname(dirname(dirname(__FILE__)))."/global.php");

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);    //����֪ͨ������Ϣ
$verify_result = $alipay->notify_verify();  //����ó�֪ͨ��֤���
if($verify_result) {
	if(!preg_match('/^[0-9]+$/', $_POST['out_trade_no'])){
		die;
	}
    //��֤�ɹ�
    //��ȡ֧�����ķ�������
    $dingdan           = $_POST['out_trade_no'];	    //��ȡ֧�������ݹ����Ķ�����
    $total_fee         = $_POST['total_fee'];	    //��ȡ֧�������ݹ������ܼ۸�

    /*���裺
	sOld_trade_status="0";��ʾ����δ����
	sOld_trade_status="1";��ʾ���׳ɹ���TRADE_FINISHED/TRADE_SUCCESS����
    */
    if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {    //���׳ɹ�����
         //���붩��������ɺ�����ݿ���³�����룬����ر�֤echo��������Ϣֻ��success
        //Ϊ�˱�֤�����ظ����ã����ظ�ִ�����ݿ���³������жϸñʽ���״̬�Ƿ��Ƕ���δ����״̬ 

			if($sOld_trade_status < 1) {
				//���ݶ����Ÿ��¶������Ѷ�������ɽ��׳ɹ�
				require_once(APP_PATH.'app/public/common.php');
				require_once(LIB_PATH.'ApiPay.class.php');

				$apiPay = new apipay($phpyun,$db,$db_config['def'],'index');
				
				$apiPay->payAll($dingdan,$total_fee,'alipay');
			}
			echo "success";

			//�����ã�д�ı�������¼������������Ƿ�����
			//log_result("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
		 
	} else {
        echo "success";		//����״̬�жϡ���ͨ��ʱ�����У�����״̬�����жϣ�ֱ�Ӵ�ӡsuccess��

        //�����ã�д�ı�������¼������������Ƿ�����
        //log_result ("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
    }
}else {
    //��֤ʧ��
    echo "fail";

    //�����ã�д�ı�������¼������������Ƿ�����
    //log_result ("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
}
?>