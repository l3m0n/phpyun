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
class index_controller extends common
{
	function index_action()
	{
		if((int)$_GET['uid']){
			$UserinfoM=$this->MODEL('userinfo');
			$cert=$UserinfoM->GetCompanyCert(array("uid"=>(int)$_GET['uid'],"type"=>6));
			$member=$UserinfoM->GetMemberOne(array("uid"=>(int)$_GET['uid']),array("field"=>"`claim`"));
			if($member['claim']=="1"){
				$this->ACT_msg($this->config['sy_weburl'],"���û��Ѿ������죡");
			}
		}
		if($cert['check2']!=$_GET['code'] || $cert['check2']=="")
		{
			$this->ACT_msg($this->config['sy_weburl'],"��������ȷ��");
		}
		$this->seo("claim");
		$this->yun_tpl(array('index'));
	}
	function save_action()
	{
		if($_POST['submit'])
		{
			$UserinfoM=$this->MODEL('userinfo');
			$member=$UserinfoM->GetMemberOne(array("uid"=>(int)$_POST['uid']),array("field"=>"`claim`"));
			if($member['claim']=="1"){
				$this->ACT_layer_msg("���û��Ѿ������죡",8,$_SERVER['HTTP_REFERER']);
			}
			$cert=$UserinfoM->GetCompanyCert(array("uid"=>(int)$_POST['uid'],"type"=>6));
			if($cert['check2']!=$_POST['code'] || $cert['check2']=="")
			{
				$this->ACT_layer_msg("��������ȷ��",8,$_SERVER['HTTP_REFERER']);
			}
			$row=$UserinfoM->GetMemberOne(array("username"=>$_POST['username']),array("field"=>"`uid`"));
			if($row['uid']>0){
				$this->ACT_layer_msg("�û����Ѵ��ڣ����������룡",8,$_SERVER['HTTP_REFERER']);
			}
			$salt = substr(uniqid(rand()), -6);
			$pass = md5(md5($_POST['password']).$salt);
			$data['username']=$_POST['username'];
			$data['salt']=$salt;
			$data['password']=$pass;
			$data['claim']=1;
			$data['source']=1;
			$UserinfoM->UpdateMember($data,array("uid"=>(int)$_POST['uid']));
			$this->ACT_layer_msg("����ɹ���",8,Url("login"));
		}
    }
}