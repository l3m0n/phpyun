<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class email_controller extends common{ 
	function index_action(){  
		$this->yuntpl(array('admin/admin_send_email'));  
	} 
	function msg_action(){
		$this->yuntpl(array('admin/information'));
	}
	
	
 	function send_action(){ 
		extract($_POST);
		if($email_title==''||$content==''){
			$this->ACT_layer_msg("邮件标题均不能为空！",8,$_SERVER['HTTP_REFERER']);
		} 
		$emailarr=$user=$com=$userinfo=array();
		if(@in_array(1,$all)){
			$userrows=$this->obj->DB_select_all("member","`usertype`='1'","email,`uid`,`usertype`");
		}
		if(@in_array(2,$all)){
			$userrows=$this->obj->DB_select_all("member","`usertype`='2'","email,`uid`,`usertype`");
		}
		
		if(@in_array(3,$all)){
			$email_user=@explode(',',$_POST['email_user']); 
			$userrows=$this->obj->DB_select_all("member","`email` in('".@implode("','",$email_user)."')","email,`uid`,`usertype`");  
		}  
		if(is_array($userrows)&&$userrows){
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				
				$emailarr[$v['uid']]=$v["email"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}

		} 
		if(@in_array(3,$all)){
			foreach($email_user as $v){
				if($this->CheckRegEmail($v)){
					$emailarr[]=$v;
				}
			}
		}
		if(!count($emailarr)){ 
			$this->ACT_layer_msg("没有符合条件的邮箱，请先检查！",8,$_SERVER['HTTP_REFERER']);
		}
		set_time_limit(10000); 
		
		$emailid=$this->send_email($emailarr,$email_title,$content,true,$userinfo); 
	}
	function save_action(){
		extract($_POST);
		if(trim($content)==''){
			$this->ACT_layer_msg("请输入短信内容！",8,$_SERVER['HTTP_REFERER']);
		}
		if($userarr==''){
			$this->ACT_layer_msg("手机号码不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		$uidarr=array();
		if($all==4){
			$mobliesarr=@explode(',',$userarr);
			$userrows=$this->obj->DB_select_all("member","`moblie` in(".$userarr.")","`moblie`,`uid`,`usertype`");		 
			$moblies=array();
			foreach($userrows as $v){
				$moblies[]=$v['moblie'];
			}  
		}else{
			$userrows=$this->obj->DB_select_all("member","`usertype`='".$all."'","`moblie`,`uid`,`usertype`");
		}
		if(is_array($userrows)&&$userrows){
			$user=$com=$userinfo=array();
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				
				$uidarr[$v['uid']]=$v["moblie"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
		}
		if($all==4){
			foreach($mobliesarr as $v){
				if(in_array($v,$moblies)==false&&$this->CheckMoblie($v)){
					$uidarr[]=$v;
				}
			}
		}
		if(is_array($uidarr)&&$uidarr){
			if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
				$this->ACT_layer_msg("还没有配置短信！",8,$_SERVER['HTTP_REFERER']);
			}
			foreach($uidarr as $key=>$v){
				if($userinfo[$key]==''){
					$key='';
				}
				$msguser=$this->config["sy_msguser"];
				$msgpw=$this->config["sy_msgpw"];
				$msgkey=$this->config["sy_msgkey"];
				$result=$this->sendSMS($msguser,$msgpw,$msgkey,$v,$content,'','',array('uid'=>$key,'name'=>$userinfo[$key]));
			}
		}
		$this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	}
	function getinfos($userrows){
		foreach($userrows as $v){
			if($v['usrtype']=='1'){
				$user[]=$v['uid'];
			}else if($v['usrtype']=='2'){
				$com[]=$v['uid'];
			}
		}
		if($user&&$user){
			$resume=$this->obj->DB_select_all("resume","`uid` in(".pylode(',',$user).")","`uid`,`name`"); 
		}
		if($com&&$com){
			$company=$this->obj->DB_select_all("company","`uid` in(".pylode(',',$com).")","`uid`,`name`"); 
		}

		foreach($userrows as $k=>$v){
			foreach($resume as $val){
				if($v['uid']==$val['uid']){
					$userrows[$k]['name']=$v['name'];
				}
			}
			foreach($company as $val){
				if($v['uid']==$val['uid']){
					$userrows[$k]['name']=$v['name'];
				}
			}

		}
		return $userrows;
	} 
	function msgsave_action(){
		extract($_POST);
		if(trim($content)==''){
			$this->ACT_layer_msg("请输入短信内容！",8,$_SERVER['HTTP_REFERER']);
		}
		if($userarr==''){
			$this->ACT_layer_msg("手机号码不能为空！",8,$_SERVER['HTTP_REFERER']);
		}
		$uidarr=array();
		if($all==4){
			$mobliesarr=@explode(',',$userarr);
			$userrows=$this->obj->DB_select_all("member","`moblie` in(".$userarr.")","`moblie`,`uid`,`usertype`");		 
			$moblies=array();
			foreach($userrows as $v){
				$moblies[]=$v['moblie'];
			}  
		}else{
			$userrows=$this->obj->DB_select_all("member","`usertype`='".$all."'","`moblie`,`uid`,`usertype`");
		}
		if(is_array($userrows)&&$userrows){
			$user=$com=$userinfo=array();
			foreach($userrows as $v){
				if($v['usertype']=='1'){$user[]=$v['uid'];}
				if($v['usertype']=='2'){$com[]=$v['uid'];}
				$uidarr[$v['uid']]=$v["moblie"];
			}
			if($user&&is_array($user)){
				$resume=$this->obj->DB_select_all("resume","`uid` in(".@implode(',',$user).")","`name`,`uid`");
				foreach($resume as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}
			if($com&&is_array($com)){
				$company=$this->obj->DB_select_all("company","`uid` in(".@implode(',',$com).")","`name`,`uid`");
				foreach($company as $val){
					$userinfo[$val['uid']]=$val['name'];
				}
			}

		}
		if($all==4){
			foreach($mobliesarr as $v){
				if(in_array($v,$moblies)==false&&$this->CheckMoblie($v)){
					$uidarr[]=$v;
				}
			}
		}
		if(is_array($uidarr)&&$uidarr){
			if($this->config["sy_msguser"]=="" || $this->config["sy_msgpw"]=="" || $this->config["sy_msgkey"]==""||$this->config['sy_msg_isopen']!='1'){
				$this->ACT_layer_msg("还没有配置短信！",8,$_SERVER['HTTP_REFERER']);
			}
			foreach($uidarr as $key=>$v){
				if($userinfo[$key]==''){
					$key='';
				}
				$msguser=$this->config["sy_msguser"];
				$msgpw=$this->config["sy_msgpw"];
				$msgkey=$this->config["sy_msgkey"];
				$result=$this->sendSMS($msguser,$msgpw,$msgkey,$v,$content,'','',array('uid'=>$key,'name'=>$userinfo[$key]));
			}
		}
		$this->ACT_layer_msg($result,14,$_SERVER['HTTP_REFERER'],2,1);
	}
}

?>