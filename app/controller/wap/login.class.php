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
class login_controller extends common{
	function index_action(){
		if(preg_match("/^[a-zA-Z0-9_-]+$/",$_GET['wxid'])){

			setcookie("wxid",$_GET['wxid'],time() + 86400, "/");
		}
		if(preg_match("/^[a-zA-Z0-9_-]+$/",$_GET['unionid'])){
			setcookie("unionid",$_GET['unionid'],time() + 86400, "/");
		}

		$this->get_moblie();
		
		if($this->uid || $this->username){
			if((int)$_GET['bind']=='1'){
				$this->unset_cookie();
				$data['msg']='���°�������ְ�˻���';
			}elseif($_GET['wxid']){

				$this->unset_cookie();
			}else{
				$this->wapheader('member/index.php');
			}
		}

		if($_POST['submit']){ 
		
            $UserinfoM=$this->MODEL('userinfo');
			if($_POST['wxid']){
				$wxparse = '&wxid='.$_POST['wxid'];
			}
			$username = yun_iconv("utf-8","gbk",$_POST['username']);

			if(!$this->CheckRegUser($username) && !$this->CheckRegEmail($username)){
				$data['msg']='��Ч���û�����';
				$this->layer_msg($data['msg'],9,0,'',2);
			}

			if($username!=''){
				$userinfo = $UserinfoM->GetMemberOne(array('username'=>$username),array('field'=>"username,usertype,password,uid,salt,status,did,login_date"));
				if(!is_array($userinfo)){
					$data['msg']='�û������ڣ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if($userinfo['usertype'] =='3' ){
					$data['msg']='�ֻ�������ֻ֧����ְ����Ƹ�û���';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				$pass = md5(md5($_POST['password']).$userinfo['salt']);

				if($pass!=$userinfo['password']){
					$data['msg']='���벻��ȷ��';
				}else{
					if($userinfo['status']=="2"){
						$this->layer_msg('',9,0,url('wap',array('c'=>'login','a'=>'loginlock','type'=>1)));
					}
					if($userinfo['usertype']=="2" && $this->config['com_status']!="1" && $userinfo['status']!="1"){ 
						$this->layer_msg('',9,0,url('wap',array('c'=>'login','a'=>'loginlock','type'=>2)));
					}
					if($userinfo['usertype']=='1'){
						$Resume=$this->MODEL("resume");
						$info=$Resume->SelectResumeOne(array("uid"=>$userinfo['uid']),"`email_status`");
						if($this->config['user_status']=="1" &&$info['email_status']!="1"){
							$data['msg']='�����˻���δ������ȼ��';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					$ip = fun_ip_get();
					$UserinfoM->UpdateMember(array("login_ip"=>$ip,"login_date"=>time(),"`login_hits`=`login_hits`+1"),array("uid"=>$userinfo['uid']));
					if($_COOKIE['wxid']){
						$UserinfoM->UpdateMember(array('wxid'=>'','unionid'=>'','wxopenid'=>''),array('wxid'=>$_COOKIE['wxid']));
						$UserinfoM->UpdateMember(array('wxid'=>$_COOKIE['wxid'],'unionid'=>$_COOKIE['unionid'],'wxbindtime'=>time()),array('uid'=>$userinfo['uid']));
						setcookie("wxid",'',time() - 86400, "/");
						setcookie("unionid",'',time() - 86400, "/");
					}
					
					$logdate=date("Ymd",$userinfo['login_date']);
					$nowdate=date("Ymd",time());
					if($logdate!=$nowdate){
						$this->get_integral_action($userinfo['uid'],"integral_login","��Ա��¼");
					}
					$this->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);  
					if($_COOKIE['wxid']){
						 
						 
						 $this->layer_msg('�󶨳ɹ����밴���Ϸ����ؽ���΢�ſͻ���',9,0,Url('wap').'member/',2);
					}else if($_POST['job']){
						$this->layer_msg('',9,0,Url('wap').'index.php?c=job&a=view&id='.$_POST['job'],2);
					}else{
                        $this->layer_msg('',9,0,Url('wap').'member/',2);
					}
				}
			}else{
				$data['msg']='���ݴ���';
			}
            $this->layer_msg($data['msg'],9,0,'',2);
		}
		if($_GET['usertype']=="2"){
			$this->yunset("headertitle","��Ա��¼");
		}else{
			$this->yunset("headertitle","��Ա��¼");
		}
		$this->seo("login");
		
		$this->yuntpl(array('wap/login'));
	}
	function loginlock_action(){
		$this->seo("login"); 
		$this->yuntpl(array('wap/loginlock'));
	}
}
?>