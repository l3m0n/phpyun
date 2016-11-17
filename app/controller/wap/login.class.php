<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
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
				$data['msg']='重新绑定您的求职账户！';
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
				$data['msg']='无效的用户名！';
				$this->layer_msg($data['msg'],9,0,'',2);
			}

			if($username!=''){
				$userinfo = $UserinfoM->GetMemberOne(array('username'=>$username),array('field'=>"username,usertype,password,uid,salt,status,did,login_date"));
				if(!is_array($userinfo)){
					$data['msg']='用户不存在！';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				if($userinfo['usertype'] =='3' ){
					$data['msg']='手机访问暂只支持求职与招聘用户！';
					$this->layer_msg($data['msg'],9,0,'',2);
				}
				$pass = md5(md5($_POST['password']).$userinfo['salt']);

				if($pass!=$userinfo['password']){
					$data['msg']='密码不正确！';
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
							$data['msg']='您的账户还未激活，请先激活！';
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
						$this->get_integral_action($userinfo['uid'],"integral_login","会员登录");
					}
					$this->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);  
					if($_COOKIE['wxid']){
						 
						 
						 $this->layer_msg('绑定成功，请按左上方返回进入微信客户端',9,0,Url('wap').'member/',2);
					}else if($_POST['job']){
						$this->layer_msg('',9,0,Url('wap').'index.php?c=job&a=view&id='.$_POST['job'],2);
					}else{
                        $this->layer_msg('',9,0,Url('wap').'member/',2);
					}
				}
			}else{
				$data['msg']='数据错误！';
			}
            $this->layer_msg($data['msg'],9,0,'',2);
		}
		if($_GET['usertype']=="2"){
			$this->yunset("headertitle","会员登录");
		}else{
			$this->yunset("headertitle","会员登录");
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