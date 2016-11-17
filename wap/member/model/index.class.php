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
class index_controller extends wap_controller{
	function waptpl($tpname){
		$this->yuntpl(array('wap/member/user/'.$tpname));
	}
	function index_action(){
		$this->rightinfo();
		$looknum=$this->obj->DB_select_num("look_resume","`uid`='".$this->uid."' and `status`='0'");
		$look_jobnum=$this->obj->DB_select_num("look_job","`uid`='".$this->uid."' and `status`='0'");
		$this->yunset("looknum",$looknum);
		$this->yunset("look_jobnum",$look_jobnum);
		$yqnum=$this->obj->DB_select_num("userid_msg","`uid`='".$this->uid."'");
		$this->yunset("yqnum",$yqnum);
		$wkyqnum=$this->obj->DB_select_num("userid_msg","`uid`='".$this->uid."' and `is_browse`=1");
		$this->yunset("wkyqnum",$wkyqnum);
		$statis=$this->obj->DB_select_once("member_statis","`uid`='".$this->uid."'");
		$resume_num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
		$this->yunset("resume_num",$resume_num);
		$sq_nums=$this->obj->DB_select_num("userid_job","`uid`='".$this->uid."' ");
		$statis['sq_jobnum']=$sq_nums;
		$expect=$this->obj->DB_select_once("resume_expect","`uid`='".$this->uid."' and `defaults`='1'","integrity,id");
		$fav_jobnum=$this->obj->DB_select_num("fav_job","`uid`='".$this->uid."'");
		$statis['fav_jobnum']=$fav_jobnum;
		$this->yunset("expect",$expect);
		$fav_jobnum=$this->obj->DB_select_num("fav_job","`uid`='".$this->uid."'");
		$statis['fav_jobnum']=$fav_jobnum;
		$this->yunset("statis",$statis);
		$this->waptpl('index');
	}
	function photo_action(){

		if($_POST['submit']){
			preg_match('/^(data:\s*image\/(\w+);base64,)/', $_POST['uimage'], $result);
			$uimage=str_replace($result[1], '', str_replace('#','+',$_POST['uimage']));

			
			if(in_array(strtolower($result[2]),array('jpg','png','gif','jpeg'))){
				
				$new_file = time().rand(1000,9999).".".$result[2];
			
			}else{
				$new_file = time().rand(1000,9999).".jpg";
			}
			
			$im = imagecreatefromstring(base64_decode($uimage));
			
			if ($im === false) {
				echo 2;die;
			}
			if (!file_exists(DATA_PATH."upload/user/".date('Ymd')."/")){

				mkdir(DATA_PATH."upload/user/".date('Ymd')."/");
				chmod(DATA_PATH."upload/user/".date('Ymd')."/",0777);

			}
			$re=file_put_contents(DATA_PATH."upload/user/".date('Ymd')."/".$new_file, base64_decode($uimage));
			chmod(DATA_PATH."upload/user/".date('Ymd')."/".$new_file,0777);
			if($re){
				$user=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`photo`,`resume_photo`");
				if($user['photo']||$user['resume_photo']){
					unlink_pic(APP_PATH.$user['photo']);
					unlink_pic(APP_PATH.$user['resume_photo']);
				}
				$photo="./data/upload/user/".date('Ymd')."/".$new_file;
				$this->obj->DB_update_all("resume","`resume_photo`='".$photo."',`photo`='".$photo."'","`uid`='".$this->uid."'");
				$this->obj->DB_update_all("resume_expect","`photo`='".$photo."'","`uid`='".$this->uid."'");
				echo 1;die;
			}else{
				unlink_pic("../data/upload/user/".date('Ymd')."/".$new_file);
				echo 2;die;
			}
		} else{
			$user=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`photo`");
			if($user['photo']==""){
				$user['photo']='/'.$this->config['sy_member_icon'];
			}
			$this->yunset("user",$user);
			$this->waptpl('photo');
		}
	}
	function sq_action(){
		$this->rightinfo();
		$urlarr=array("c"=>"sq","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("userid_job","`uid`='".$this->uid."'",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $v){
				$com_id[]=$v['com_id'];
			}
			$company=$this->obj->DB_select_all("company","`uid` in (".pylode(",",$com_id).")","cityid,uid,name");
			include PLUS_PATH."/city.cache.php";
			foreach($rows as $k=>$v){
				foreach($company as $val){
					if($v['com_id']==$val['uid']){
						$rows[$k]['city']=$city_name[$val['cityid']];
                        $rows[$k]['com_name']=$val['name'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->waptpl('sq');
	}
	function partcollect_action(){
		$this->rightinfo();
		if($_GET['del']){
			$id=$this->obj->DB_delete_all("part_collect","`id`='".(int)$_GET['del']."' and `uid`='".$this->uid."'");
			if($id){
				$data['msg']="删除成功!";
				$this->member_log("删除收藏的兼职");
			}else{
				$data['msg']="删除失败！";
			}
			$data['url']='index.php?c=partcollect';
			$this->yunset("layer",$data);
		}
		$urlarr=array("c"=>"partcollect","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("part_collect","`uid`='".$this->uid."'",$pageurl,"10");
		if($rows&&is_array($rows)){
			foreach($rows as $val){
				$jobids[]=$val['jobid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")","`id`,`name`,`com_name`");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['job_name']=$v['name'];
						$rows[$key]['com_name']=$v['com_name'];

					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->waptpl('partcollect');
	}
	
	function partapply_action(){
		$this->rightinfo();
		if($_GET['del']){
			$nid=$this->obj->DB_delete_all("part_apply","`id`='".(int)$_GET['del']."' and `uid`='".$this->uid."'");
			if($nid){
				$data['msg']="删除成功!";
				$this->member_log("删除报名的兼职");
			}else{
				$data['msg']="删除失败！";
			}
			$data['url']='index.php?c=partapply';
			$this->yunset("layer",$data);
		}
		$urlarr=array("c"=>"partapply","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("part_apply","`uid`='".$this->uid."'",$pageurl,"10");
		if($rows&&is_array($rows)){
			include PLUS_PATH."/city.cache.php";
			foreach($rows as $val){
				$jobids[]=$val['jobid'];
			}
			$joblist=$this->obj->DB_select_all("partjob","`id` in(".pylode(',',$jobids).")","`id`,`name`,`cityid`,`com_name`,`linktel`");
			foreach($rows as $key=>$val){
				foreach($joblist as $v){
					if($val['jobid']==$v['id']){
						$rows[$key]['job_name']=$v['name'];
						$rows[$key]['city']=$city_name[$v['cityid']];
						$rows[$key]['com_name']=$v['com_name'];
						$rows[$key]['linktel']=$v['linktel'];
					}
				}
			}
		}

		$this->yunset("rows",$rows);
		$this->waptpl('partapply');
	}
	function delsq_action(){
		if($_GET['id']){
			$userid_job=$this->obj->DB_select_once("userid_job","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
			$id=$this->obj->DB_delete_all("userid_job","`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
			if($id){
				$this->obj->DB_update_all('company_statis',"`sq_job`=`sq_job`-1","`uid`='".$userid_job['com_id']."'");
				$this->obj->DB_update_all('member_statis',"`sq_jobnum`=`sq_jobnum`-1","`uid`='".$userid_job['uid']."'");
				$this->member_log("删除申请的职位");
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}

	function collect_action(){
		$this->rightinfo();
		if($_GET['del']){
			$id=$this->obj->DB_delete_all("fav_job","`id`='".$_GET['del']."' and `uid`='".$this->uid."'");
			if($id){
				$data['msg']="删除成功!";
				$this->obj->DB_update_all("member_statis","`fav_jobnum`=`fav_jobnum`-1","uid='".$this->uid."'");
				$this->member_log("删除收藏的职位");
			}else{
				$data['msg']="删除失败！";
			}
			$data['url']='index.php?c=collect';
			$this->yunset("layer",$data);
		}
		$urlarr=array("c"=>"collect","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$this->get_page("fav_job","`uid`='".$this->uid."'",$pageurl,"10");
		$this->waptpl('collect');
	}

	function password_action(){
		if($_POST['submit']){
			$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
			$pw=md5(md5($_POST['oldpassword']).$member['salt']);
			if($pw!=$member['password']){
				$data['msg']="旧密码不正确，请重新输入！";
			}else if(strlen($_POST['password1'])<6 || strlen($_POST['password1'])>20){
				$data['msg']="密码长度应在6-20位！";
			}else if($_POST['password1']!=$_POST['password2']){
				$data['msg']="新密码和确认密码不一致！";
			}else if($this->config['sy_uc_type']=="uc_center" && $member['name_repeat']!="1"){
				$this->uc_open();
				$ucresult= uc_user_edit($member['username'], $_POST['oldpassword'], $_POST['password1'], "","1");
				if($ucresult == -1){
					$data['msg']="旧密码不正确，请重新输入！";
				}
			}else{
				$salt = substr(uniqid(rand()), -6);
				$pass2 = md5(md5($_POST['password1']).$salt);
				$this->obj->DB_update_all("member","`password`='".$pass2."',`salt`='".$salt."'","`uid`='".$this->uid."'");
				SetCookie("uid","",time() -286400, "/");
				SetCookie("username","",time() - 86400, "/");
				SetCookie("salt","",time() -86400, "/");
				SetCookie("shell","",time() -86400, "/");
				$this->member_log("修改密码");
				$data['msg']="修改成功，请重新登录！";
				$data['url']=$this->config['sy_weburl'].'/wap/index.php?m=login';
			}
            $this->waplayer_msg($data['msg'],$data['url']);
			
		}
		$this->rightinfo();
		$this->waptpl('password');
	}
	function invitecont_action(){
		$this->rightinfo();
		$id=(int)$_GET['id'];
		$info=$this->obj->DB_select_once("userid_msg","`id`='".$id."' and `uid`='".$this->uid."'");
		if($info['is_browse']==1){
			$this->obj->update_once("userid_msg",array('is_browse'=>2),array("id"=>$info['id']));
		}
		$this->yunset("info",$info);
		$this->waptpl('invitecont');
	}
	function inviteset_action(){
		$id=(int)$_GET['id'];
		$browse=(int)$_GET['browse'];
		if($id){
			$nid=$this->obj->update_once("userid_msg",array('is_browse'=>$browse),array("id"=>$id,"uid"=>$this->uid));
			$this->unset_remind("userid_msg",'1');
			$comuid=$this->obj->DB_select_once("userid_msg","`id`='".$id."'",'fid,jobid');
			$comarr=$this->obj->DB_select_once("company_job","`id`='".$comuid['jobid']."' and `r_status`<>'2' and `status`<>'1'");
			$uid=$this->obj->DB_select_once("company","`uid`='".$comuid['fid']."'","linkmail,linktel,name");
			$name=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","name");
			$data['uid']=$comuid['fid'];
			$data['cname']=$this->username;
			$data['name']=$uid['name'];
			$data['type']="yqmshf";
			$data['cuid']=$this->uid;
			$data['cusername']=$name['name'];
			if($browse==3){
				$data['typemsg']='同意';
			}elseif($browse==4){
				$data['typemsg']='拒绝';
			}
			if($this->config['sy_msg_yqmshf']=='1'&&$uid["linktel"]&&$this->config["sy_msguser"]&&$this->config["sy_msgpw"]&&$this->config["sy_msgkey"]&&$this->config['sy_msg_isopen']=='1'){$data["moblie"]=$uid["linktel"]; }
			if($this->config['sy_email_yqmshf']=='1'&&$uid["linkmail"]&&$this->config["sy_smtpserver"]&&$this->config["sy_smtpemail"] &&$this->config["sy_smtpuser"]){$data["email"]=$uid["linkmail"]; }
			if($data["email"]||$data['moblie']){
				$this->send_msg_email($data);
			}
			$nid?$this->waplayer_msg("操作成功！"):$this->waplayer_msg("操作失败！");
		}
	}
	function invite_action(){
		$this->rightinfo();
		if($_GET['del']){
			$id=$this->obj->DB_delete_all("userid_msg","`id`='".(int)$_GET['del']."' and `uid`='".$this->uid."'");
			if($id){
				$this->member_log("删除邀请信息");
				$data['msg']="删除成功!";
			}else{
				$data['msg']="删除失败!";
			}
			$data['url']='index.php?c=invite';
			$this->yunset("layer",$data);
		}
		$urlarr=array("c"=>"invite","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$this->get_page("userid_msg","`uid`='".$this->uid."'",$pageurl,"10");
		$this->waptpl('invite');
	}
	function look_action(){
		$this->rightinfo();
		if($_GET['del']){
			$id=$this->obj->DB_delete_all("look_resume","`id`='".(int)$_GET['del']."' and `uid`='".$this->uid."'");
			if($id){
				$data['msg']="删除成功!";
				$this->member_log("删除简历浏览记录");
			}else{
				$data['msg']="删除失败!";
			}
			$data['url']='index.php?c=look';
			$this->yunset("layer",$data);
		}
		$urlarr=array("c"=>"look","page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("look_resume","`uid`='".$this->uid."'",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $v){
				$uid[]=$v['com_id'];
				$eid[]=$v['resume_id'];
			} 
			$company=$this->obj->DB_select_all("company","`uid` IN (".pylode(",",$uid).")","uid,name");
			$resume=$this->obj->DB_select_all("resume_expect","`id` in (".pylode(",",$eid).")","`id`,`name`"); 
			foreach($rows as $k=>$v){
				foreach($company as $val){
					if($v['com_id']==$val['uid']){
						$rows[$k]['com_name']=$val['name'];
					}
				} 
				foreach($resume as $val){
					if($v['resume_id']==$val['id']){
						$rows[$k]['resume_name']=$val['name'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->waptpl('look');
	}

	function addresume_action(){
		$this->rightinfo();
		$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		if($this->config['user_enforce_identitycert']=="1"){
			if($row['idcard_status']!="1"){
				$data['msg']='请先登录电脑客户端完成身份认证！';
				$data['url']='index.php';
			}
		}
		$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
		if($num>=$this->config['user_number']){
		    $data['msg']='你的简历数已经超过系统设置的简历数了！';
		    $data['url']='index.php?c=resume';
		    $this->yunset("layer",$data);
		}
		if($_POST['submit']){
		    $email=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `email`='".$_POST['email']."'","`uid`");
		    if($email && $data['msg']==""){
		        $data['msg']='邮箱已存在！';
		    }
		    $mobile=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['telphone']."'","`uid`");
		    if($mobile && $data['msg']==""){
		        $data['msg']='手机已存在！';
		    }
		    if($_POST['uname']=="" && $data['msg']==""){
		        $data['msg']='姓名不能为空！';
		    }
		    if(($_POST['birthday']=="") && $data['msg']==""){
		        $data['msg']='出生年月不能为空！';
		    }
		    if($_POST['living']=="" && $data['msg']==""){
		        $data['msg']='现居住地不能为空！';
		    }
		    if($_POST['name']=="" && $data['msg']==""){
		        $data['msg']='简历名称不能为空！';
		    }
		    if($_POST['job_classid']=="" && $data['msg']==""){
		        $data['msg']='期望从事职位不能为空！';
		    }
		    if($_POST['cityid']=="" && $data['msg']==""){
		        $data['msg']='期望工作地点不能为空！';
		    }
		    if($data['msg']==""){
		        unset($_POST['submit']);
		        delfiledir("../data/upload/tel/".$this->uid);
		        $where['uid']=$this->uid;
		        $data['idcard']=$_POST['idcard'];
		        $data['edu']=$_POST['edu'];
		        $data['exp']=$_POST['exp'];
		        $data['name']=$_POST['uname'];
		        $data['sex']=$_POST['sex'];
		        $data['birthday']=$_POST['birthday'];
		        $data['living']=$_POST['living'];
		        if($row['moblie_status']==0){
		            $data['telphone']=$_POST['telphone'];
		            $mvalue['moblie']=$_POST['telphone'];
		        }
		        if($row['email_status']==0){
		            $data['email']=$_POST['email'];
		            $mvalue['email']=$_POST['email'];
		        }
		        $data['lastupdate']=time();
		        $nid=$this->obj->update_once("resume",$data,$where);
		        if($nid){
		            if(!empty($mvalue)){
		                $this->obj->update_once('member',$mvalue,$where);
		            }
		            if($row['name']==""||$row['living']==""){
		                $this->MODEL('userinfo')->company_invtal($this->uid,$this->config['integral_userinfo'],true,"首次填写基本资料",true,2,'integral',25);
		            }
		            $edata=array();
		            $edata['idcard_status']=$data['idcard_status'];
		            $edata['status']=$data['status'];
		            $edata['r_status']=$data['r_status'];
		            $edata['photo']=$data['photo'];
		            $edata['edu']=$data['edu'];
		            $edata['exp']=$data['exp'];
		            $edata['uname']=$data['name'];
		            $edata['sex']=$data['sex'];
		            $edata['birthday']=trim($data['birthday']);
		            $edata['name']=trim($_POST['name']);
		            $edata['jobstatus']=(int)$_POST['jobstatus'];
		            $edata['report']=(int)$_POST['report'];
		            $edata['hy']=(int)$_POST['hy'];
		            $edata['type']=(int)$_POST['type'];
		            $edata['job_classid']=$_POST['job_classid'];
		            $edata['salary']=(int)$_POST['salary'];
		            $edata['provinceid']=(int)$_POST['provinceid'];
		            $edata['cityid']=(int)$_POST['cityid'];
		            $edata['three_cityid']=(int)$_POST['three_cityid'];
		            $edata['uid']=$this->uid;
		            $edata['did']=$this->userdid;
		            $edata['integrity']=55;
		            $edata['lastupdate']=time();
		            $edata['ctime']=time();
		            $edata['source']=2;
		            $edata['defaults']=$num<=0?1:0;
		            $eid=$this->obj->insert_into("resume_expect",$edata);
		            if($eid){
		                if($num==0){
		                    $this->obj->update_once('resume',array('def_job'=>$eid,'resumetime'=>time()),array('uid'=>$this->uid));
		                }else{
		                    $this->obj->update_once('resume',array('resumetime'=>time()),array('uid'=>$this->uid));
		                }
		                $this->obj->insert_into("user_resume",array("eid"=>$eid,"uid"=>$this->uid,"expect"=>1));
		                $resume_num=$num+1;
		                $this->obj->DB_update_all('member_statis',"`resume_num`='".$resume_num."'","`uid`='".$this->uid."'");
		                $resume_url=Url("resume",array("c"=>"show","id"=>$eid));
		                $state_content = "发布了 <a href=\"".$resume_url."\" target=\"_blank\">新简历</a>。";
		                $fdata['uid']	  = $this->uid;
		                $fdata['content'] = $state_content;
		                $fdata['ctime']   = time();
		                $fdata['type']   = 2;
		                $this->obj->insert_into("friend_state",$fdata);
		                $this->obj->member_log("创建一份简历",2,1);
		                $num=$this->obj->DB_select_num("company_pay","`com_id`='".$this->uid."' AND `pay_remark`='发布简历'");
		                if($num<1){
		                    $this->get_integral_action($this->uid,"integral_add_resume","发布简历");
		                }
		                $this->warning("3");
		                $data['msg']='添加成功！';
		                $data['url']='index.php?c=resume';
		            }else{
		                $data['msg']='添加失败！';
		                $data['url']='index.php?c=resume';
		            }
		        }else{
		            $data['msg']='保存失败！';
		        }
		    }
		    $this->yunset("layer",$data);
		}
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		$this->yunset("resume",$resume);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user','hy','job')));
		$this->waptpl('addresume');
	}
	function addresumeson_action(){
		$this->rightinfo();
		if(!in_array($_GET['type'],array('expect','desc','cert','doc','edu','other','project','show','skill','tiny','training','work'))){
			unset($_GET['type']);
		}
		if($_GET['type']=="desc"){
			$desc=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`description`");
			$this->yunset("description",$desc['description']);
		}
		if($_GET['id'] && $_GET['type']){

			$row=$this->obj->DB_select_once("resume_".$_GET['type'],"`id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'"); 
			$this->yunset("row",$row);
		}
		$this->user_cache();
       
		if($_POST['submit']){
		   $_POST=$this->post_trim($_POST);
		   if($_POST['eid']>0){
		       if($_POST['table']=='skill'){
		           $id=(int)$_POST['id'];
		           $url=$_POST['table'];
		           if(is_uploaded_file($_FILES['file']['tmp_name'])){
		               $resume=$this->obj->DB_select_once("resume_skill","`id`='".$id."',","pic");
		               $upload=$this->upload_pic(APP_PATH."data/upload/user/",false);
		               $pictures=$upload->picture($_FILES['file']);
		               $this->picmsg($pictures,$_SERVER['HTTP_REFERER'],'wap');
		               $pictures = str_replace(APP_PATH."data/upload/user","./data/upload/user",$pictures);
		           }
		           if(strlen($pictures)!=1){  
		               if($id){
		                   if($pictures==''){
		                       $nid=$this->obj->DB_update_all("resume_skill", "`uid`='".$this->uid."',`eid`='".$_POST['eid']."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."'","`id`='".$id."'");
		                   }else{
		                       $nid=$this->obj->DB_update_all("resume_skill", "`uid`='".$this->uid."',`eid`='".$_POST['eid']."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."',`pic`='".$pictures."'","`id`='".$id."'");
		                   }
		               }else{
		                   $nid=$this->obj->DB_insert_once("resume_skill", "`uid`='".$this->uid."',`eid`='".$_POST['eid']."',`name`='".$_POST['name']."',`longtime`='".$_POST['longtime']."',`pic`='".$pictures."'");
		                   $this->obj->DB_update_all("user_resume","`$url`=`$url`+1","`eid`='".(int)$_POST['eid']."' and `uid`='".$this->uid."'");
		                   $resume_row=$this->obj->DB_select_once("user_resume","`eid`='".(int)$_POST['eid']."'");
		                   $this->complete($resume_row);
		               }
		               $nid?$data['msg']='保存成功！':$data['msg']='保存失败！';
		               $data['url']="index.php?c=rinfo&eid=".$_POST['eid']."&type=".$url;
		               $this->yunset("layer",$data);
		           }
		       }
		   }
		}
		$this->waptpl('addresumeson');
	}
	function saveresumeson_action(){
		if($_POST['submit']){
		    $_POST=$this->post_trim_iconv($_POST);
			if($_POST['table']=="resume"){
				$this->obj->DB_update_all("resume","`description`='".$_POST['description']."' , `lastupdate`='".time()."'","`uid`='".$this->uid."'");
				$data['url']="index.php?c=modify&eid=".$_POST['eid'];
				$data['msg']=iconv('gbk','utf-8',"保存成功！");
				echo json_encode($data);die;
			}
			if($_POST['eid']>0){
				$table="resume_".$_POST['table'];
				$id=(int)$_POST['id'];
				$url=$_POST['table'];
				unset($_POST['submit']);
				unset($_POST['table']);
				unset($_POST['id']);
				$_POST['sdate']=strtotime($_POST['sdate']);
				$_POST['edate']=strtotime($_POST['edate']);
				if($id){
			        $where['id']=$id;
				    $where['uid']=$this->uid;
				    $nid=$this->obj->update_once($table,$_POST,$where);
				}else{
			        $_POST['uid']=$this->uid;
				    $nid=$this->obj->insert_into($table,$_POST);
					$this->obj->DB_update_all("user_resume","`$url`=`$url`+1","`eid`='".(int)$_POST['eid']."' and `uid`='".$this->uid."'");
					$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".(int)$_POST['eid']."'");
					$this->complete($resume_row);
				}
				$nid?$data['msg']='保存成功！':$data['msg']='保存失败！';
				$data['url']="index.php?c=rinfo&eid=".$_POST['eid']."&type=".$url;
			    $data['msg']=iconv('gbk','utf-8',$data['msg']);
			    echo json_encode($data);die;
			}
		}
	}
	function post_trim_iconv($data){
		foreach($data as $d_k=>$d_v){
			if(is_array($d_v)){
				$data[$d_k]=$this->post_trim_iconv($d_v);
			}else{
				$data[$d_k]=iconv("UTF-8","GBK",trim($data[$d_k]));
			}
		}
		return $data;
	}
	function get_email_moblie_action(){
		$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`email_status`,`moblie_status`");
			 
		$data=array('msg'=>1);
		if($row['email_status']!=1){
			$email=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `email`='".$_POST['email']."'","`uid`");
			if($email){
				$data['msg']='邮箱已存在！';
			}
		}
		if($row['moblie_status']!=1){
			$mobile=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['moblie']."'","`uid`");
			if($mobile){
				$data['msg']='手机已存在！';
			}
		} 
		$data['msg']=iconv('gbk','utf-8',$data['msg']);
		echo json_encode($data);die;
	}
	function info_action(){
		$this->rightinfo();
		if($_POST['submit']){
			$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`email_status`,`moblie_status`"); 
			if($row['email_status']!='1'){
				$email=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `email`='".$_POST['email']."'");
				if($email>0){
					$data['msg']='邮箱已存在！';
				}else{
					$mvalue['email']=$_POST['email'];
				}
			} 
			if($row['moblie_status']!='1'){
				 $mobile=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['telphone']."'");
				if($mobile>0 && $data['msg']==""){
					$data['msg']='手机已存在！';
				}else{
					$mvalue['moblie']=$_POST['telphone'];
				}
			}

			if($_POST['name']=="" && $data['msg']==""){
				$data['msg']='姓名不能为空！';
			}
			if(($_POST['birthday']=="") && $data['msg']==""){
				$data['msg']='出生年月不能为空！';
			}
			if($_POST['living']=="" && $data['msg']==""){
				$data['msg']='现居住地不能为空！';
			}
			if($data['msg']==""){
				unset($_POST['submit']);
				delfiledir("../data/upload/tel/".$this->uid);
				$_POST['lastupdate']=time();
				$where['uid']=$this->uid;  
				$nid=$this->obj->update_once("resume",$_POST,$where);
				if($nid){
					if(!empty($mvalue)){
						$this->obj->update_once('member',$mvalue,$where);
					}
					$this->member_log("保存基本信息");
					$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
					if($resume['name']==""||$resume['living']=="")
					{
						$this->MODEL('userinfo')->company_invtal($this->uid,$this->config['integral_userinfo'],true,"首次填写基本资料",true,2,'integral',25);
					}else{
						$this->obj->update_once("resume_expect",array("edu"=>$_POST['edu'],"exp"=>$_POST['exp'],"uname"=>$_POST['name'],"sex"=>$_POST['sex'],"birthday"=>$_POST['birthday']),$where);
					}
					$data['msg']='保存成功！';
				}else{
					$data['msg']='保存失败！';
				}
				if($_POST['eid']){
					$data['url']="index.php?c=modify&eid=".$_POST['eid'];
				}else{
					$data['url']="index.php?c=info";
				}
			}

			$this->yunset("layer",$data);
		}
		$year=date('Y',time());
		for($i=$year-70;$i<=$year;$i++){
			$years[]=$i;
		}
		$this->yunset("years",$years);
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'"); 
		$this->yunset("resume",$resume);
        $this->yunset($this->MODEL('cache')->GetCache(array('user')));
		$this->waptpl('info');
	}
    function addexpect_action(){
    	$CacheArr=$this->MODEL('cache')->GetCache(array('city','user','hy','job'));
        $this->yunset($CacheArr);
		if($_GET['eid']){
			$row=$this->obj->DB_select_once("resume_expect","`id`='".(int)$_GET['eid']."' and `uid`='".$this->uid."'");
			if($row['job_classid']){
				$job_classid=@explode(',',$row['job_classid']);
				$jobname=array();
				foreach($job_classid as $val){
					$jobname[]=$CacheArr['job_name'][$val];
				}
			} 
			$this->yunset("jobname",@implode('+',$jobname));
			$this->yunset("row",$row);
		}
		$this->waptpl('addexpect');
	}
	function expect_action(){
		$eid=(int)$_POST['eid'];
		unset($_POST['submit']);
		unset($_POST['eid']);
		$where['id']=$eid;
		$where['uid']=$this->uid;
		$_POST['lastupdate']=time();
		$_POST['height_status']="0";
		if($eid==""){
            $num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
			$_POST['uid']=$this->uid;
			$_POST['did']=$this->userdid;
			$_POST['source']=2;
            $_POST['defaults']=$num<=0?1:0;
			$nid=$this->obj->insert_into("resume_expect",$_POST);
			if ($nid){
				$num=$this->obj->DB_select_once("member_statis","`uid`='".$this->uid."'");
				if($num['resume_num']==0){
					
					$this->obj->update_once('resume',array('def_job'=>$nid,'resumetime'=>time(),'lastupdate'=>time()),array('uid'=>$this->uid));
				}
				$data['uid'] = $this->uid;
				$data['eid'] = $nid;
				$this->obj->insert_into("user_resume",$data);
				$resume_num=$num+1;
				$this->obj->DB_update_all('member_statis',"`resume_num`='".$resume_num."'","`uid`='".$this->uid."'");
				$state_content = "发布了 <a href=\"".$this->config['sy_weburl']."/index.php?m=resume&id=$nid\" target=\"_blank\">新简历</a>。";
				$fdata['uid']	  = $this->uid;
				$fdata['content'] = $state_content;
				$fdata['ctime']   = time();
				$this->obj->insert_into("friend_state",$fdata);
				$this->member_log("发布了新简历");
			}
			$eid=$nid;
		}else{
			$nid=$this->obj->update_once("resume_expect",$_POST,$where);
			$this->member_log("更新了简历");
		}
		echo $nid;die;
	}
	function resume_action(){
		$this->rightinfo();
		$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
		$maxnum=$this->config['user_number']-$num;
		if($maxnum<0){$maxnum='0';}
		$this->yunset("maxnum",$maxnum);
		$rows=$this->obj->DB_select_all("resume_expect","`uid`='".$this->uid."'","id,name,lastupdate,height_status,open,hits,defaults");
		$this->yunset("rows",$rows);
		$defjob=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","def_job");
		$this->yunset("defjob",$defjob);
		$this->waptpl('resume');
	}
	function modify_action(){
		if($_POST['submit']){
			if(trim($_POST['name'])==""){
				$data['msg']='简历名称不能为空！';
				$data['url']='index.php?c=modify&eid='.(int)$_POST['eid'];
				$this->yunset("layer",$data);
			}else{
				$this->obj->DB_update_all("resume_expect","`name`='".trim($_POST['name'])."'","`uid`='".$this->uid."' and `id`='".(int)$_POST['eid']."'");
				$data['msg']='简历名称修改成功！';
				$data['url']='index.php?c=modify&eid='.$_POST['eid'];
				$this->member_log("修改简历名称");
			}
			$this->yunset("layer",$data);
		}else{
			$expect=$this->obj->DB_select_once("resume_expect","`uid`='".$this->uid."' and `id`='".(int)$_GET['eid']."'");
			if($expect['id']){
				$this->yunset("expect",$expect);
				$resume=$this->obj->DB_select_once("user_resume","`eid`='".$_GET['eid']."'");
				$this->yunset("resume",$resume);
				$desc=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`description`");
				$this->yunset("description",$desc['description']);
			}else{
				$data['msg']="非法操作！";
				$data['url']="index.php?c=resume";
			}
		}
		$this->yunset("layer",$data);
		$this->rightinfo();
		$this->waptpl('modify');
	}
    function rinfo_action(){
		$_GET['id']=intval($_GET['id']);
		if(!in_array($_GET['type'],array('expect','cert','doc','edu','other','project','show','skill','tiny','training','work'))){
			unset($_GET['type']);
		}

		if($_GET['type']&&intval($_GET['id'])){
			$nid=$this->obj->DB_delete_all("resume_".$_GET['type'],"`eid`='".(int)$_GET['eid']."' and `id`='".(int)$_GET['id']."' and `uid`='".$this->uid."'");
			if($nid){
				$url=$_GET['type'];
				$this->obj->DB_update_all("user_resume","`$url`=`$url`-1","`eid`='".(int)$_GET['eid']."' and `uid`='".$this->uid."'");
				$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".(int)$_GET['eid']."'");
				$this->complete($resume_row);
				$data['msg']='删除成功！';
			}else{
				$data['msg']='删除失败！';
			}
            $data['url']="index.php?c=rinfo&eid=".(int)$_GET['eid']."&type=".$_GET['type'];
			$this->yunset("layer",$data);
		}
		$this->rightinfo();
		$this->yunset($this->MODEL('cache')->GetCache(array('city','user','hy','job')));
		$rows=$this->obj->DB_select_all("resume_".$_GET['type'],"`eid`='".(int)$_GET['eid']."' and `uid`='".$this->uid."'");
		$this->yunset("backurl","index.php?c=modify&eid=".intval($_GET['eid']));
		$this->yunset("rows",$rows);
		$this->yunset("type",$_GET['type']);
		$this->yunset("eid",$_GET['eid']);
		$this->waptpl('rinfo');
	}
	function resumeset_action(){
		if($_GET['del']){
			$del=(int)$_GET['del'];
			$del_array=array("resume_cert","resume_edu","resume_other","resume_project","resume_skill","resume_training","resume_work","resume_doc","user_resume","down_resume");
			if($this->obj->DB_delete_all("resume_expect","`id`='".$del."' and `uid`='".$this->uid."'")){
				foreach($del_array as $v){
					$this->obj->DB_delete_all($v,"`eid`='".$del."'' and `uid`='".$this->uid."'","");
				}
				$this->obj->DB_delete_all("look_resume","`resume_id`='".$del."'","");
				$defid=$this->obj->DB_select_once("resume","`uid`='".$this->uid."' and `def_job`='".$del."'");
			    if(is_array($defid)){
					$row=$this->obj->DB_select_once("resume_expect","`uid`='".$this->uid."'","`id`");
					if($row['id']!=''){
					    $this->obj->update_once('resume_expect',array('defaults'=>1),array('id'=>$row['id']));
					    $this->obj->update_once('resume',array('def_job'=>$row['id']),array('uid'=>$this->uid));
					}
			    } 
				$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
				$num=$num+1;
				$this->obj->DB_update_all('member_statis',"`resume_num`='".$num."'","`uid`='".$this->uid."'"); 
				$this->member_log("删除简历");
				$this->waplayer_msg("删除成功！");
			}else{
				$this->waplayer_msg("删除失败！");
			}
		}else if($_GET['update']){
			$id=(int)$_GET['update'];
			$nid=$this->obj->update_once('resume_expect',array('lastupdate'=>time()),array('id'=>$id,'uid'=>$this->uid));
			$nid?$this->waplayer_msg("刷新成功！"):$this->waplayer_msg("刷新失败！");
		}else if($_GET['def']){
			$nid=$this->obj->DB_update_all("resume","`def_job`='".(int)$_GET['def']."'","`uid`='".$this->uid."'");
            $nid=$this->obj->DB_update_all("resume_expect","`defaults`=''","`uid`='".$this->uid."'");
            $nid=$this->obj->DB_update_all("resume_expect","`defaults`='1'","`uid`='".$this->uid."' and `id`='".$_GET['def']."'");
			$nid?$this->waplayer_msg("设置成功！"):$this->waplayer_msg("设置失败！");
		}else if($_GET['open']){
			if(!in_array($_GET['type'],array('expect','cert','doc','edu','other','project','show','skill','tiny','training','work'))){
				unset($_GET['type']);
			}
			$_GET['type']?$type='1':$type='0';
			$nid=$this->obj->DB_update_all("resume_expect","`open`='".$type."'","`uid`='".$this->uid."' and `id`='".(int)$_GET['open']."'");
            $nid=$this->obj->DB_update_all("resume_expect","`defaults`=''","`uid`='".$this->uid."'");
			$nid?$this->waplayer_msg("设置成功！"):$this->waplayer_msg("设置失败！");
		}
	}
	function loginout_action(){
		SetCookie("uid","",time() -86400, "/");
		SetCookie("username","",time() - 86400, "/");
		SetCookie("usertype","",time() -86400, "/");
		SetCookie("salt","",time() -86400, "/");
		SetCookie("shell","",time() -86400, "/");
		$this->wapheader('index.php');
	}
	function lookjobdel_action(){
		$this->rightinfo();
		if($_GET['id']){
			$nid=$this->obj->DB_update_all("look_job","`status`='1'","`id`='".$_GET['id']."' and `uid`='".$this->uid."'");
			if($nid){
				$this->member_log("删除职位浏览记录(ID:".$_GET['id'].")");
				$this->waplayer_msg('删除成功！');
			}else{
				$this->waplayer_msg('删除失败！');
			}
		}
	}
	function look_job_action(){
		$this->rightinfo();
		$urlarr=array("c"=>$_GET['c'],"page"=>"{{page}}");
		$pageurl=Url('wap',$urlarr,'member');
		$look=$this->get_page("look_job","`uid`='".$this->uid."' and `status`='0' order by `datetime` desc",$pageurl,"10");
		if(is_array($look)){
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/com.cache.php";
			foreach($look as $v){
				$jobid[]=$v['jobid'];
			}
			$job=$this->obj->DB_select_all("company_job","`id` in (".pylode(",",$jobid).")","`id`,`name`,`com_name`,`salary`,`provinceid`,`cityid`,`uid`,`edate`,`status`,`state`");

			foreach($look as $k=>$v){
				foreach($job as $val){
					if($v['jobid']==$val['id']){
						if($val['edate']<time()){
							$look[$k]['jobstate']=2;
						}else if($val['status']=='1'||$val['state']!='1'){
							$look[$k]['jobstate']=3;
						}else{
							$look[$k]['jobstate']=1;
						}
						$look[$k]['jobname']=$val['name'];
						$look[$k]['com_id']=$val['uid'];
						$look[$k]['job_id']=$val['id'];
						$look[$k]['comname']=$val['com_name'];
						$look[$k]['salary']=$comclass_name[$val['salary']];
						$look[$k]['provinceid']=$city_name[$val['provinceid']];
						$look[$k]['cityid']=$city_name[$val['cityid']];
					}
				}
			}
		}
		$this->yunset("js_def",2);
		$this->yunset("look",$look);
		$this->waptpl('look_job');
	}
	function getYears($startYear=0,$endYear=0){
		$list=array();
		$month_list=array();
		if($endYear>0){
			if($startYear<=0){
				$startYear=	$endYear-150;
			}
			for($i=$endYear;$i>=$startYear;$i--){
				$list[]=$i;
			}
		}
		for($i=12;$i>=1;$i--){
			$month_list[]=$i;
		}
		$this->yunset("year_list",$list);
		$this->yunset("month_list",$month_list);
		return $list;
	}
	function binding_action(){
		if($_POST['moblie']){
			$row=$this->obj->DB_select_once("company_cert","`uid`='".$this->uid."' and `check`='".$_POST['moblie']."'");
			if(!empty($row)){
				session_start();
				if($row['check2']!=$_POST['code']){
					echo 3;die;
				}else if(!$_POST['authcode']){
					echo 4;die;
				}elseif(md5($_POST['authcode'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
					echo 5;die;
				}else{
					$this->obj->DB_update_all("resume","`moblie_status`='0',`lastupdate`='".time()."'","`telphone`='".$row['check']."'");
					$this->obj->DB_update_all("company","`moblie_status`='0'","`linktel`='".$row['check']."'"); 
					$this->obj->DB_update_all("member","`moblie`='".$row['check']."'","`uid`='".$this->uid."'");
					$this->obj->DB_update_all("resume","`telphone`='".$row['check']."',`moblie_status`='1'","`uid`='".$this->uid."'");
					$this->obj->DB_update_all("company_cert","`status`='1'","`uid`='".$this->uid."' and `check2`='".$_POST['code']."'");
					$this->obj->member_log("手机绑定");
					$pay=$this->obj->DB_select_once("company_pay","`pay_remark`='手机绑定' and `com_id`='".$this->uid."'");
					if(empty($pay)){
						$this->get_integral_action($this->uid,"integral_mobliecert","手机绑定");
					}
					echo 1;die;
				}
			}else{
				echo 2;die;
			}
		}
		if($_GET['type']){
			if($_GET['type']=="moblie"){
				$this->obj->DB_update_all("resume","`moblie_status`='0'","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="email"){
				$this->obj->DB_update_all("resume","`email_status`='0'","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="qqid"){
				$this->obj->DB_update_all("member","`qqid`=''","`uid`='".$this->uid."'");
			}
			if($_GET['type']=="sinaid"){
				$this->obj->DB_update_all("member","`sinaid`=''","`uid`='".$this->uid."'");
			}
			$this->waplayer_msg('解除绑定成功！');
		}
		$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		$this->yunset("member",$member);
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		$this->yunset("resume",$resume);
		if(($member['qqid']!=""||$member['wxid']!=""||$member['unionid']!=""||$member['sinaid']!="") && $member['restname']=="0"){
			$this->yunset("setname",1);
		}
		$this->rightinfo();
		$this->waptpl('binding');
	}
	function idcard_action(){
		if($_POST['submit']){
			if($_POST['idcard']==''){
				$data['msg']='请输入身份证号';   
			}else if($_FILES['file']==''){
				$data['msg']='请上传证件照！';
			}else{
				$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","idcard_pic");  
				if(is_uploaded_file($_FILES['file']['tmp_name'])){
				    $upload=$this->upload_pic(APP_PATH."/data/upload/order/");
				    $pictures=$upload->picture($_FILES['order_pic']);
				    $this->picmsg($pictures,$_SERVER['HTTP_REFERER'],'wap');
				    $pictures = str_replace(APP_PATH."/data/upload/order","./data/upload/order",$pictures);
				}
				if(strlen($pictures)!=1){
				    if($this->config['user_idcard_status']=="1"){
				        $status='0';
				    }else{
				        $status='1';
				        $this->obj->DB_update_all('friend_info',"`iscert`='".$status."'","`uid`='".$this->uid."'");
				    }
				    $dataarr=array(
				        'idcard'=>$_POST['idcard'],
				        'idcard_pic'=>$pictures,
				        'idcard_status'=>$status,
				        'lastupdate'=>time()
				    );
				    $nid=$this->obj->update_once('resume',$dataarr,array('uid'=>$this->uid));
				    if($nid){
				        unlink_pic($resume['idcard_pic']);
				        $data['msg']='上传成功！';
				        $data['url']="index.php?c=binding";
				    }else{
				        $data['msg']='上传失败！';
				    }
				    
				}
			}
			if($data){
			    $this->yunset("layer",$data);
			}
		}
		$this->rightinfo();
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'",'idcard');
		$this->yunset("resume",$resume);
		$this->waptpl('idcard');
	}
	function bindingbox_action(){
		$member=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		$this->yunset("member",$member);
		$this->rightinfo();
		$this->waptpl('bindingbox');
	}
	function setname_action(){
		if($_POST['username']){
			$user=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
			if(($user['qqid']==""&&$user['wxid']==""&&$user['unionid']==""&&$user['sinaid']=="") || $user['restname']=="1"){
				echo "您无权修改账户！";die;
			}
			$username=yun_iconv("utf-8","gbk",$_POST['username']);
			$num = $this->obj->DB_select_num("member","`username`='".$username."'");
			if($num>0){
				echo "用户名已存在！";die;
			}
			if($this->config['sy_regname']!=""){
				$regname=@explode(",",$this->config['sy_regname']);
				if(in_array($username,$regname)){
					echo "该用户名禁止使用！";die;
				}
			}
			$salt = substr(uniqid(rand()), -6);
		    $password = md5(md5($_POST['password']).$salt);
		    $data['password']=$password;
		    $data['salt']=$salt;
		    $data['username']=$username;
		    $data['restname']=1;
			$this->obj->update_once('member',$data,array('uid'=>$this->uid));
			$this->unset_cookie();
			$this->obj->member_log("修改账户",8);
			echo 1;die;
		}
		$user=$this->obj->DB_select_once("member","`uid`='".$this->uid."'");
		if(($user['qqid']==""&&$user['wxid']==""&&$user['unionid']==""&&$user['sinaid']=="") || $user['restname']=="1"){
			$this->wapheader('member/index.php');
		}
		$this->rightinfo();
		$this->waptpl('setname');
	}
	
	function reward_list_action(){
		$urlarr['c']='reward_list';
		$urlarr["page"]="{{page}}";
		$pageurl=Url('wap',$urlarr,'member');
		$rows=$this->get_page("change","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$gid[]=$val['gid'];
			}
			$M=$this->MODEL('redeem');
			$gift=$M->GetReward(array('`id` in('.pylode(',', $gid).')'),array('field'=>'id,pic'));
			foreach($rows as $k=>$val){
				foreach ($gift as $v){
					if($val['gid']==$v['id']){
						$rows[$k]['pic']=$v['pic'];
					}
				}
			}
		}
		$this->yunset('rows',$rows);
		$this->waptpl('reward_list');
	}
	
	function delreward_action(){
		if($this->usertype!='1' || $this->uid==''){
			$this->waplayer_msg('登录超时！');
		}else{
			$rows=$this->obj->DB_select_once("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			if($rows['id']){
				$this->obj->DB_update_all("reward","`num`=`num`-".$rows['num'].",`stock`=`stock`+".$rows['num']."","`id`='".$rows['gid']."'");
				$this->company_invtal($this->uid,$rows['integral'],true,"取消兑换",true,2,'integral',24);
				$this->obj->DB_delete_all("change","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."' ");
			}
			$this->obj->member_log("取消兑换");
			$this->waplayer_msg('取消成功！');
		}
	}
}
?>