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
class register_controller extends common{
	function index_action(){ 
		$this->get_moblie();
		if($this->uid || $this->username){
			echo "<script>location.href='member/index.php';</script>";die;
		}
		if($this->config['reg_user_stop']!=1){
			$data['msg']='��վ�ѹر�ע�ᣡ';
			$data['url']='index.php';
			$this->layer_msg($data['msg'],9,0,$data['url'],2);
		}
		if($_POST['submit']){
			$_POST['username']=iconv("utf-8","gbk",$_POST['username']);
			
			$UserinfoM=$this->MODEL('userinfo');
			$usertype=$_POST['usertype']?$_POST['usertype']:1;
			$ip = fun_ip_get();
			session_start();
			if($this->config['sy_reg_interval']>0&&$this->config['sy_reg_interval']!=''){
				$intervaltime=time()-3600*$this->config['sy_reg_interval'];
				$regnum=$UserinfoM->GetMemberNum(array('reg_ip'=>$ip,"`reg_date`<'".$intervaltime."'"));
				if($regnum){
					$data['msg']='����Ƶ��ע�ᣡ';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
			}elseif($_POST['regway']=='1'){
				$username=$_POST['username'];
				$usernameNum = $UserinfoM->GetMemberNum(array("username"=>$username));
				if($username==''){
					$data['msg']='�û�������Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(strlen($username)<2||strlen($username)>20){
					$data['msg']='�û���Ӧ��2-20λ�ַ�֮�䣡';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif($this->CheckRegUser($username)==false){
					$data['msg']='�û�����ʽ����';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif($usernameNum>0){
					$data['msg']='�û����Ѵ��ڣ����������룡';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif($_POST['password']==''){
					$data['msg']='���벻��Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(strlen($_POST['password'])<6||strlen($_POST['password'])>20){
					$data['msg']='���볤��Ӧ��6-20λ��';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if($this->config['reg_passconfirm'] =='1'){
					if($_POST['passconfirm']==''){
						$data['msg']='ȷ�����벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(strlen($_POST['passconfirm'])<6||strlen($_POST['passconfirm'])>20){
						$data['msg']='ȷ�����볤��Ӧ��6-20λ��';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif($_POST['password']!=$_POST['passconfirm']){
						$data['msg']='�����������벻һ�£�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				if($usertype=='1'){
					$_POST['name']    = iconv("utf-8","gbk",$_POST['name']);
					if($this->config['reg_useremail'] =='1'){
						$emailNum = $UserinfoM->GetMemberNum(array("email"=>$_POST['email']));
						if($_POST['email']==""){
							$data['msg']='���䲻��Ϊ�գ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif(!$this->CheckRegEmail($_POST['email'])){
							$data['msg']='�����ʽ����';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif($emailNum>0){
							$data['msg']='�����Ѵ��ڣ����������룡';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					if($this->config['reg_usertel'] =='1'){
						$moblieNum = $UserinfoM->GetMemberNum(array("moblie"=>$_POST['moblie']));
						if($_POST['moblie']==""){
							$data['msg']='�ֻ����벻��Ϊ�գ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif(!$this->CheckMoblie($_POST['moblie'])){
							$data['msg']='�ֻ���ʽ����';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif($moblieNum>0){
							$data['msg']='�ֻ��Ѵ��ڣ����������룡';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					if($this->config['reg_username'] =='1'){
						
						if(!$this->CheckRegUser($_POST['name']) || $_POST['name']==""){
							$data['msg']='��ʵ������ʽ���淶��';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
				}else{
					if($this->config['reg_comemail'] =='1'){
						$emailNum = $UserinfoM->GetMemberNum(array("email"=>$_POST['email']));
						if($_POST['email']==""){
							$data['msg']='���䲻��Ϊ�գ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif(!$this->CheckRegEmail($_POST['email'])){
							$data['msg']='�����ʽ����';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif($emailNum>0){
							$data['msg']='�����Ѵ��ڣ����������룡';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					if($this->config['reg_comtel'] =='1'){
						$moblieNum = $UserinfoM->GetMemberNum(array("moblie"=>$_POST['moblie']));
						if($_POST['moblie']==""){
							$data['msg']='�ֻ����벻��Ϊ�գ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif(!$this->CheckMoblie($_POST['moblie'])){
							$data['msg']='�ֻ���ʽ����';
							$this->layer_msg($data['msg'],9,0,'',2);
						}elseif($moblieNum>0){
							$data['msg']='�ֻ��Ѵ��ڣ����������룡';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					$_POST['name']=iconv("utf-8","gbk",$_POST['comname']);
					$_POST['linkman']=iconv("utf-8","gbk",$_POST['linkman']);
					$_POST['address']=iconv("utf-8","gbk",$_POST['address']);
					if($this->config['reg_comname'] =='1'){
						if(!$this->CheckRegUser($_POST['name']) || $_POST['name']==""){
							$data['msg']='����ȷ��д��ҵ���ƣ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					if($this->config['reg_comaddress'] =='1'){
						if(!$this->CheckRegUser($_POST['address']) || $_POST['address']==""){
							$data['msg']='����ȷ��д��ҵ��ַ��';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
					if($this->config['reg_comlink'] =='1') {
						if(!$this->CheckRegUser($_POST['linkman']) || $_POST['linkman']==""){
							$data['msg']='����ȷ��д��ҵ��ϵ�ˣ�';
							$this->layer_msg($data['msg'],9,0,'',2);
						}
					}
				}
				if(strpos($this->config['code_web'],'ע���Ա')!==false){
					if($_POST['checkcode1']==''){
						$data['msg']='��֤�벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(md5($_POST['checkcode1'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
						$data['msg']='��֤�����';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
			}elseif($_POST['regway']=='2'){
				$moblieNum = $UserinfoM->GetMemberNum(array("moblie"=>$_POST['moblie']));
				if($_POST['moblie']==""){
					$data['msg']='�ֻ����벻��Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(!$this->CheckMoblie($_POST['moblie'])){
					$data['msg']='�ֻ���ʽ����';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif($moblieNum>0){
					$data['msg']='�ֻ��Ѵ��ڣ����������룡';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if(strpos($this->config['code_web'],'ע���Ա')!==false){
					if($_POST['checkcode2']==''){
						$data['msg']='��֤�벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(md5($_POST['checkcode2'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
						$data['msg']='��֤�����';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				if($this->config['sy_msg_regcode']=="1"){
					$regCertMobile = $UserinfoM->GetCompanyCert(array("type"=>'2',"check"=>$_POST['moblie']),array('field'=>'check2'));
					if($_POST['moblie_code']==''){
						$data['msg']='������֤�벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif($regCertMobile['check2']!=$_POST['moblie_code']){
						$data['msg']='������֤�����';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				if($_POST['password2']==''){
					$data['msg']='���벻��Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(strlen($_POST['password2'])<6||strlen($_POST['password2'])>20){
					$data['msg']='���볤��Ӧ��6-20λ��';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if($this->config['reg_passconfirm'] =='1'){
					if($_POST['passconfirm2']==''){
						$data['msg']='ȷ�����벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(strlen($_POST['passconfirm2'])<6||strlen($_POST['passconfirm2'])>20){
						$data['msg']='ȷ�����볤��Ӧ��6-20λ��';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif($_POST['password2']!=$_POST['passconfirm2']){
						$data['msg']='�����������벻һ�£�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				$username =  $_POST['moblie'];
				$_POST['password']=$_POST['password2'];
					
			}elseif($_POST['regway']=='3'){
				$emailNum = $UserinfoM->GetMemberNum(array("email"=>$_POST['email']));
				if($_POST['email']==""){
					$data['msg']='���䲻��Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(!$this->CheckRegEmail($_POST['email'])){
					$data['msg']='�����ʽ����';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif($emailNum>0){
					$data['msg']='�����Ѵ��ڣ����������룡';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if(strpos($this->config['code_web'],'ע���Ա')!==false){
					if($_POST['checkcode3']==''){
						$data['msg']='��֤�벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(md5($_POST['checkcode3'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
						$data['msg']='��֤�����';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				if($_POST['password3']==''){
					$data['msg']='���벻��Ϊ�գ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}elseif(strlen($_POST['password3'])<6||strlen($_POST['password3'])>20){
					$data['msg']='���볤��Ӧ��6-20λ��';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if($this->config['reg_passconfirm'] =='1'){
					if($_POST['passconfirm3']==''){
						$data['msg']='ȷ�����벻��Ϊ�գ�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif(strlen($_POST['passconfirm3'])<6||strlen($_POST['passconfirm3'])>20){
						$data['msg']='ȷ�����볤��Ӧ��6-20λ��';
						$this->layer_msg($data['msg'],9,0,'',2);
					}elseif($_POST['password3']!=$_POST['passconfirm3']){
						$data['msg']='�����������벻һ�£�';
						$this->layer_msg($data['msg'],9,0,'',2);
					}
				}
				$username =  $_POST['email'];
				$_POST['password']=$_POST['password3'];
			}
			if($this->config['sy_uc_type']=="uc_center"){
				$this->uc_open();
				$uid=uc_user_register($username,$_POST['password'],$_POST['email'],$_POST['moblie']);
				if($uid<=0){
					$data['msg']='���û��������Ѵ��ڣ�';
					$this->layer_msg($data['msg'],9,0,'',2);
				}else{
					list($uid,$username,$password,$email,$salt)=uc_user_login($_POST['username'],$_POST['password']);
					$pass = md5(md5($_POST['password']).$salt);
					$ucsynlogin=uc_user_synlogin($uid);
				}
			}elseif($this->config['sy_pw_type']=="pw_center"){
				include(APP_PATH."/api/pw_api/pw_client_class_phpapp.php");
				$password=trim($_POST['password']);
				$email=$_POST['email'];
				$pw=new PwClientAPI($username,$password,$email);
				$pwuid=$pw->register();
				$salt = substr(uniqid(rand()), -6);
				$pass = md5(md5($password).$salt);
			}else{
				$salt = substr(uniqid(rand()), -6);
				$pass = md5(md5(trim($_POST['password'])).$salt);
			}
			if($_POST['usertype']=='1'){
				$status = 1;
			}elseif($_POST['usertype']=='2'){
				$status = $this->config['com_status'];
			}
			$idata=array('username'=>$username,'password'=>$pass,'email'=>$_POST['email'],'moblie'=>$_POST['moblie'],'usertype'=>$usertype,'status'=>$status,'salt'=>$salt,'source'=>2,'reg_date'=>time(),'reg_ip'=>$ip);
			$userid=$UserinfoM->AddMember($idata);
			if(!$userid){
				$user_id = $UserinfoM->GetMemberOne(array("username"=>$username),array("field"=>"uid"));
				$userid = $user_id['uid'];
			}else{
				if($_COOKIE['wxid']){
					$UserinfoM->UpdateMember(array('wxid'=>''),array('wxid'=>$_COOKIE['wxid']));
					$UserinfoM->UpdateMember(array('wxid'=>$_COOKIE['wxid']),array('uid'=>$userid));
					setcookie("wxid",'',time() - 86400, "/");
				}
				if($_COOKIE['unionid']){
					$UserinfoM->UpdateMember(array('unionid'=>''),array('unionid'=>$_COOKIE['unionid']));
					$UserinfoM->UpdateMember(array('unionid'=>$_COOKIE['unionid']),array('uid'=>$userid));
					setcookie("unionid",'',time() - 86400, "/");
				}
				if($this->config['sy_pw_type']=="pw_center"){
					$UserinfoM->UpdateMember(array('pwuid'=>$pwuid),array('uid'=>$userid));
				}
				$UserinfoM->RegisterMember($_POST,array('uid'=>$userid,'usertype'=>$usertype));
				if($this->config['integral_reg']>0){
					$UserinfoM->company_invtal($userid,$this->config['integral_reg'],true,"ע��",true,2,'integral','26');
				}
				if($usertype==1){
					$UserinfoM->UpdateMember(array("login_date"=>time(),"login_ip"=>$ip),array("uid"=>$userid));
					$this->add_cookie($userid,$username,$salt,$_POST['email'],$pass,$usertype);
					$url='member/index.php?c=addresume';
				}else{
					if($this->config['com_status']!="1"){
						$data['msg']='ע��ɹ����ȴ�����Ա��ˣ�';
						$data['url']=url('wap',array('c'=>'register','a'=>'regok'));
						$this->layer_msg($data['msg'],9,0,$data['url'],2);
					}else{
						$UserinfoM->UpdateMember(array("login_date"=>time(),"login_ip"=>$ip),array("uid"=>$userid));
						$this->add_cookie($userid,$username,$salt,$_POST['email'],$pass,$usertype);
						$url='member/index.php?c=info';
					}
				} 
				$data['msg']='��ϲ�����ѳɹ�ע���Ա��';
				$data['url']=$url;
				$this->layer_msg($data['msg'],9,0,$data['url'],2);
			}
		}
	    $this->yunset("headertitle","��Աע��");
		$this->seo("register");
		if($_GET['usertype']==2){
		    $this->yuntpl(array('wap/comreg'));
		}else{
		    $this->yuntpl(array('wap/register'));
		}
	}
	function regok_action(){ 
		$this->yunset("headertitle","��Աע��");
		$this->seo("register");
		$this->yuntpl(array('wap/registerok'));
	} 
	function ajax_reg_action(){
	    $post = array_keys($_POST);
	    $key_name = $post[0];
	    if(!in_array($key_name,array('username','email'))){
	        exit();
	    }
	    $Member=$this->MODEL("userinfo");
	    if($key_name=="username"){
	        $username=yun_iconv("utf-8","gbk",$_POST['username']);
	        if(!$this->CheckRegUser($username) && !$this->CheckRegEmail($username)){
	            echo 2;die;
	        }
	        if($this->config['sy_uc_type']=="uc_center"){
	            $this->uc_open();
	            $user = uc_get_user($username);
	        }else{
	            $user = $Member->GetMemberNum(array("username"=>$username));
	        }
	        if($this->config['sy_regname']!=""){
	            $regname=@explode(",",$this->config['sy_regname']);
	            if(in_array($username,$regname)){
	                echo 3;die;
	            }
	        }
	    }elseif($key_name=="email"){
	        if(!$this->CheckRegEmail($_POST['email'])){
	            echo 2;die;
	        }
	        $user = $Member->GetMemberNum(array("`email`='".$_POST['email']."' or `username`='".$_POST['email']."'"));
	    }
	    if($user){echo 1;}else{echo 0;}
	}
	function regmoblie_action(){
	    if($_POST['moblie']){
	        $Member=$this->MODEL("userinfo");
	        $num = $Member->GetMemberNum(array("moblie='".$_POST['moblie']."' or `username`='".$_POST['moblie']."'"));
	        echo $num;die;
	    }
	}
}
?>