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
class qqconnect_controller extends common
{
	
	function qqlogin_action()
	{
		$code = $_GET['code'];
		if((int)$_GET['login']!="1")
		{
			if($this->uid!=""&&$this->username!="" && empty($code))
			{
				$this->ACT_msg($this->config['sy_weburl'], $msg = "���Ѿ���¼�ˣ�");
			}
		}
		if($this->config['sy_qqlogin']!="1")
		{
			if((int)$_GET['login']=="1")
			{
				$this->ACT_msg($this->config['sy_weburl'], $msg = "�Բ���QQ���ѹرգ�");
			}else{
				$this->ACT_msg($this->config['sy_weburl'], $msg = "�Բ���QQ��¼�ѹرգ�");
			}
		}
		$this->seo('qqlogin');
	    $app_id = $this->config['sy_qqappid'];
	    $app_secret = $this->config['sy_qqappkey'];
	    $my_url = $this->config['sy_weburl']."/qqlogin.php";
		
		session_start();
	    if(empty($code))
	    {

		     $_SESSION['state'] = md5(uniqid(rand(), TRUE));
		     $dialog_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
		        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
		        . $_SESSION['state'];
		     echo("<script> top.location.href='" . $dialog_url . "'</script>");
	    }
	 	if($_GET['state'] == $_SESSION['state'])
	  	{
		     $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
		     . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		     . "&client_secret=" . $app_secret . "&code=" . $code;
			 if(!function_exists('curl_init')) {

				echo "�뿪��CURL�����������޷�������һ��������";
				die;
			 }
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,$token_url);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			 $response=curl_exec ($ch);
			 curl_close ($ch);
		     if (strpos($response, "callback") !== false)
		     {
		        $lpos = strpos($response, "(");
		        $rpos = strrpos($response, ")");
		        $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
		        $msg = json_decode($response);
		        if (isset($msg->error))
		        {
		           echo "<h3>error:</h3>" . $msg->error;
		           echo "<h3>msg  :</h3>" . $msg->error_description;
		           exit;
		        }
		     }
		     $params = array();
		     parse_str($response, $params);
		     $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$params['access_token'];
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL,$graph_url);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			 $str=curl_exec ($ch);
			 curl_close ($ch);
		     if (strpos($str, "callback") !== false)
		     {
		        $lpos = strpos($str, "(");
		        $rpos = strrpos($str, ")");
		        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		     }
		     $user = json_decode($str);
		     if (isset($user->error))
		     {
		        echo "<h3>error:</h3>" . $user->error;
		        echo "<h3>msg  :</h3>" . $user->error_description;
		        exit;
		     }

	     if($user->openid!="")
	     {
			$userinfo = $this->obj->DB_select_once("member","`qqid`='$user->openid'");
			if(is_array($userinfo))
			{
				$this->obj->DB_update_all("member","`login_date`='".time()."'","`uid`='".$userinfo[uid]."'");
				if($this->config['sy_uc_type']=="uc_center"){
					$this->uc_open();
					$user = uc_get_user($userinfo['username']);
					$ucsynlogin=uc_user_synlogin($user[0]);
					$msg =  '��¼�ɹ���';

					$this->ACT_msg($this->config['sy_weburl'],"��¼�ɹ���",9);
				}else{
					$this->unset_cookie();
					$this->add_cookie($userinfo['uid'],$userinfo['username'],$userinfo['salt'],$userinfo['email'],$userinfo['password'],$userinfo['usertype'],1,$userinfo['did']);
					$this->ACT_msg($this->config['sy_weburl'],"��¼�ɹ���",9);
				}
			}else{
				$_SESSION['qq']["openid"] = $user->openid;
				$_SESSION['qq']["tooken"] = $params['access_token'];
				$_SESSION['qq']["logininfo"] = "���ѵ�½QQ����������ʻ���";
				if($this->uid){
					$this->obj->DB_update_all("member","`qqid`=''","`qqid`='".$_SESSION['qq']["openid"]."'");
					$this->obj->DB_update_all("member","`qqid`='".$_SESSION['qq']["openid"]."'","`uid`='".$this->uid."'");
					$this->ACT_msg($this->config['sy_weburl'].'/member/index.php?c=binding',"QQ ��¼�󶨳ɹ���",9);

				}else{

					$GetUrl = "https://graph.qq.com/user/get_user_info?oauth_consumer_key=".$this->config['sy_qqappid']."&access_token=".$_SESSION['qq']['tooken']."&openid=".$_SESSION['qq']['openid']."&format=json";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$GetUrl);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					$str=curl_exec ($ch);
					curl_close ($ch);
					$user = json_decode($str,true);

					$user['nickname'] = iconv("utf-8","gbk",$user['nickname']);

					if($user['nickname']){
						$_SESSION['qq']['nickname'] = $user['nickname'];
						$_SESSION['qq']['pic'] = $user['figureurl_qq_2'];
					}else{
						$this->ACT_msg(Url("wap"),"�û���Ϣ��ȡʧ�ܣ������µ�½QQ��",8);
					}
					header("location:".Url("wap",array("c"=>"qqconnect","a"=>"qqbind")));
				}
			}
	     }
	  }else{
		  echo("The state does not match. You may be a victim of CSRF.");
	  }
	}
	function qqbind_action()
	{
		session_start();

		if(((int)$_GET['usertype']=='1' || (int)$_GET['usertype']=='2') && $_SESSION['qq']['openid'])
		{
				$usertype = (int)$_GET['usertype'];
				$ip = fun_ip_get();
				$time = time();
				$salt = substr(uniqid(rand()), -6);
				$pass = md5(md5($salt).$salt);
  				$username = $this->checkuser($_SESSION['qq']['nickname'],$_SESSION['qq']['nickname']);

					$userid=$this->obj->DB_insert_once("member","`username`='$username',`password`='$pass',`usertype`='$usertype',`status`='1',`salt`='$salt',`reg_date`='$time',`reg_ip`='$ip',`qqid`='".$_SESSION['qq']['openid']."'");
 				if(!$userid)
				{
					$user = $this->obj->DB_select_once("member","`username`='".$username."'","`uid`,`email`");
					$userid = $user['uid'];
					$email = $user['email'];
				}
				$this->unset_cookie();
				$value="`did`='".$this->config['did']."',";
				if($usertype=="1"){
					$table = "member_statis";
					$table2 = "resume";
					$value.="`uid`='$userid'";
					$value2= "`uid`='$userid',`name`='$username'";

				}elseif($usertype=="2"){

					$table = "company_statis";
					$table2 = "company";
					$value.="`uid`='$userid',".$this->rating_info();
					$value2= "`uid`='$userid',`linktel`='$moblie'";
				}
				$this->obj->DB_insert_once($table,$value);
				$this->obj->DB_insert_once($table2,$value2);
				$this->obj->DB_insert_once("friend_info","`uid`='".$userid."',`nickname`='$username',`usertype`='$usertype',`did`='".$this->config['did']."'");
				$this->add_cookie($userid,$username,$salt,$email,$pass,$usertype,1,$this->config['did']);
 				unset($_SESSION['qq']);
				$this->ACT_msg("/member","��¼�ɹ���",9);
		}
		$this->seo("qqlogin");
		
		$this->yuntpl(array('wap/qqbind'));
	}

	function JsonArray($array)
	{
			if(is_object($array))
			{
			   $array = (array)$array;
			}
			 if(is_array($array))
			 {
			   foreach($array as $key=>$value)
			   {
				 $array[$key] = $this->JsonArray($value);
			   }
			 }
		 return $array;
	}

	function checkuser($username,$name)
	{

		$user = $this->obj->DB_select_once("member","`username`='".$username."'","`uid`");
		if($user['uid'])
		{
			$name.="_".rand(1000,9999);
			return $this->checkuser($name,$username);
		}else{

			return $username;
		}
	}
	function rating_info()
	{
		$id =$this->config['com_rating'];
		$row = $this->obj->DB_select_once("company_rating","`id`='$id'");
		$value="`rating`='$id',";
		$value.="`integral`='".$this->config[integral_reg]."',";
		$value.="`rating_name`='".$row["name"]."',";
		$value.="`rating_type`='".$row['type']."',";
		if($row[type]==1){
			$value.="`job_num`='".$row["job_num"]."',";
			$value.="`down_resume`='".$row["resume"]."',";
			$value.="`invite_resume`='".$row["interview"]."',";
			$value.="`editjob_num`='".$row["editjob_num"]."',";
			$value.="`breakjob_num`='".$row["breakjob_num"]."'";
			if($row[service_time])
			{
				$time=time()+86400*$row[service_time];
				$value.=",`vip_etime`='$time'";
			}
		}else{
			$time=time()+86400*$row[service_time];
			$value.="`vip_etime`='$time'";
		}
		return $value;
	}

}

