<?php


error_reporting(0);
require_once("class/alipay_notify.php");
require_once("alipay_config.php");
require_once(dirname(dirname(dirname(__FILE__)))."/global.php");
//����ó�֪ͨ��֤���
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//��֤�ɹ�
	if(!preg_match('/^[0-9]+$/', $_POST['out_trade_no'])){
		die;
	}
    //��֤�ɹ�
    //��ȡ֧�����ķ�������
    $dingdan           = $_POST['out_trade_no'];	    //��ȡ֧�������ݹ����Ķ�����
	if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
	//���ύ��������δ�����������

        echo "success";

    }else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {//���жϱ�ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ�

		
		
		require_once(APP_PATH.'app/public/common.php');
		require_once(LIB_PATH.'ApiPay.class.php');

		$apiPay = new apipay($phpyun,$db,$db_config['def'],'index');
		
		$apiPay->payAll($dingdan,$total_fee,'alipay');

    }
	else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {//���жϱ�ʾ�����Ѿ����˻�������һ�û��

        echo "success";
    }
	else if($_POST['trade_status'] == 'TRADE_FINISHED') {//���жϱ�ʾ����Ѿ�ȷ���ջ�����ʽ������




        echo "success";


    }
    else {
		//����״̬�ж�
        echo "success";

    }


}
else {

    echo "fail";

}
?>