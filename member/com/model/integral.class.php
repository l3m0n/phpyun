<?php
/* *
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
*/
class integral_controller extends company{
	function index_action(){
		$baseInfo			= false;
		$logo				= false;
		$emailChecked		= false;
		$phoneChecked		= false;
		$pay_remark         =false;
		$question        	=false;
		$answer       		=false;
		$answerpl           =false;
		$friend         	=false;
		$friend_reply       =false;
		$map				= false;
		$banner				= false;
		$yyzz				= false;
		
		
		$row = $this->obj->DB_select_once("company",'`uid` = '.$this->uid,
			"`name`,`hy`,
			`logo`,`email_status`,`moblie_status`,
			`x`,`y`,
			`firmpic`,
			`yyzz_status`");
		$ban= $this->obj->DB_select_once("banner","`uid`='".$this->uid."'","`pic`");
		$row['firmpic']=$ban['pic'];
		if(is_array($row) && !empty($row)){
			if($row['name'] != '' && $row['hy'] != '' )
				$baseInfo = true;
			
			if($row['logo'] != '') $logo = true;
			if($row['email_status'] != 0) $emailChecked = true;
			if($row['moblie_status'] != 0) $phoneChecked = true;
			if($row['x'] != 0 && $row['y'] != 0) $map = true;
			if($row['firmpic'] != '') $banner = true;
			if($row['yyzz_status'] != 0) $yyzz = true;
			
		}
		if($this->config['integral_question_type']=="1"){
			$question=$this->max_time('��������');
		}
		if($this->config['integral_answer_type']=="1"){
			$answer=$this->max_time('�ش�����');
		}
		if($this->config['integral_answerpl_type']=="1"){
			$answerpl=$this->max_time('�����ʴ�'); 
		}
		if($this->config['integral_friend_msg_type']=="1"){
			$friend=$this->max_time('����Ȧ����');
		}
		if($this->config['integral_friend_reply_type']=="1"){
			$friend_reply=$this->max_time('����Ȧ�ظ�');
		}
		$statusList = array(
			'baseInfo'		=>$baseInfo,
			'logo'			=>$logo,
			'emailChecked'	=>$emailChecked,
			'phoneChecked'	=>$phoneChecked,
			'question'	    =>$question,
			'answer'	    =>$answer,
			'answerpl'	    =>$answerpl,
			'friend'	    =>$friend,
			'friend_reply'	=>$friend_reply,
			'map'			=> $map,
			'banner'		=> $banner,
			'yyzz'			=> $yyzz
		);
		
		$this->yunset("statusList",$statusList);
		
		
		$this->yunset("js_def",4);
		$this->com_tpl('integral');
	}
	
}
?>