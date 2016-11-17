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
class expect_controller extends user{
	function index_action(){
		$resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");

		if($this->config['user_enforce_identitycert']=="1"&&$resume['idcard_status']!="1"){ 
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"请先完成身份认证！"); 
		} 
		if($_POST['shell']==1&&$resume['name']==""){ 
			echo 1; die;
		}
		if(!$_GET['e']){
			$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
			if($num>=$this->config['user_number']){
				$this->ACT_msg("index.php?c=resume","你的简历数已经超过系统设置的简历数了");
			}
		}
		include PLUS_PATH."/job.cache.php";
		include PLUS_PATH."/city.cache.php";
		$job_area = $this->city_info($job_index,$job_name);
		$this->yunset("job_area",$job_area);
		$this->industry_cache();
		$save=$this->obj->DB_select_once("lssave","`uid`='".$this->uid."'and `savetype`='2'");
		$save=unserialize($save['save']);
		if($save['lastupdate']){
			$save['time']=date('H:i',ceil(($save['lastupdate'])));
		}
		$this->yunset("save",$save);
		if($_GET['e']){
			$eid=(int)$_GET['e'];
			$row=$this->obj->DB_select_once("resume_expect","id='".$eid."' AND `uid`='".$this->uid."'");
			if(!is_array($row) || empty($row)){
				$this->ACT_msg($_SERVER['HTTP_REFERER'],"无效的简历！");
			}
			$job_classid=@explode(",",$row['job_classid']);
			if(is_array($job_classid)){
				foreach($job_classid as $key){
					$job_classname[]=$job_name[$key];
				}
				$this->yunset("job_classname",@implode(' ',$job_classname));
				$this->yunset("job_classname2",@implode(',',$job_classname));
			}
			$this->yunset("job_classid",$job_classid);
			$skill = $this->obj->DB_select_all("resume_skill","`eid`='".$eid."' AND `uid` = '".$this->uid."'");
			$this->yunset("skill",$skill);
			$work = $this->obj->DB_select_all("resume_work","`eid`='".$eid."' AND `uid` = '".$this->uid."' order by `sdate` desc");
			$this->yunset("work",$work);
			$project = $this->obj->DB_select_all("resume_project","`eid`='".$eid."' AND `uid` = '".$this->uid."' order by `sdate` desc");
			$this->yunset("project",$project);
			$edu = $this->obj->DB_select_all("resume_edu","`eid`='".$eid."' AND `uid` = '".$this->uid."' order by `sdate` desc");
			$this->yunset("edu",$edu);
			$training = $this->obj->DB_select_all("resume_training","`eid`='".$eid."' AND `uid` = '".$this->uid."' order by `sdate` desc");
			$this->yunset("training",$training);
			$cert = $this->obj->DB_select_all("resume_cert","`eid`='".$eid."' AND `uid` = '".$this->uid."'");
			$this->yunset("cert",$cert);
			$other = $this->obj->DB_select_all("resume_other","`eid`='".$eid."' AND `uid` = '".$this->uid."'");
			$this->yunset("other",$other);
			if(!trim($row['name'])){
				$DateStr=date('Y_m_d');
				$RowNum=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."' and `name` like '%$DateStr%'");
				$row['name']='我的简历'.(date('Y_m_d')).($RowNum?'_'.($RowNum+1):'');
			}
		} 
		if($resume['birthday']){
			$a=date('Y',strtotime($resume['birthday']));
			$resume['age']=date("Y")-$a;
		}
        $ResumeList=$this->obj->DB_select_all("resume_expect","`uid`='".$this->uid."'","id,name,lastupdate,height_status,doc,tmpid,integrity,hits,statusbody,`topdate`");
		$this->yunset(array('row'=>$row,'resume'=>$resume,'ResumeList'=>$ResumeList));
		$this->public_action();
		$this->user_left();
		$this->yunset($this->MODEL('cache')->GetCache(array('job','city','hy','user')));
		$this->yunset("layerv",5);
		$this->yunset("js_def",2);
	    if($_GET['e']){
	        $this->user_tpl('expect');
	    }else{
	        $this->user_tpl('expect_add');
	    }
	}
	function saveexpect_action(){
		if($_POST['submit']){
			$eid=(int)$_POST['eid'];
			unset($_POST['submit']);
			unset($_POST['eid']);
			unset($_POST['urlid']);
			$where['id']=$eid;
			$where['uid']=$this->uid;
			$_POST['lastupdate']=time();
			$_POST['height_status']="0";
			if(!preg_match("/^[0-9,]+$/",$_POST['classid'])){
				unset($_POST['classid']);
			}
			$resumearr=array('lastupdate'=>time());
			if($eid==""){
				$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
				if($num>=$this->config['user_number']){
					echo 1;die;
				}
				$_POST['did']=$this->userdid;
				$_POST['uid']=$this->uid;
				$_POST['ctime']=time();
                $_POST['defaults']=$num<=0?1:0;
				$nid=$this->obj->insert_into("resume_expect",$_POST);
				if ($nid){
					if($num==0){
						$resumearr['def_job']=$nid; 
					}
					$data['uid'] = $this->uid;
					$data['eid'] = $nid;
					$this->obj->insert_into("user_resume",$data);
					$resume_num=$num+1;
					$this->obj->DB_update_all('member_statis',"`resume_num`='".$resume_num."'","`uid`='".$this->uid."'");
					$state_content = "发布了 <a href=\"".Url('resume',array("c"=>'show',"id"=>$nid))."\" target=\"_blank\">新简历</a>。";

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
				}
				$eid=$nid;
			}else{
				$nid=$this->obj->update_once("resume_expect",$_POST,$where);
				$this->obj->member_log("修改简历",2,2);
			}
			$this->obj->update_once('user_resume',array('expect'=>1),array('eid'=>$eid,'uid'=>$this->uid));
			if($nid){
				$this->obj->update_once('resume',$resumearr,array('uid'=>$this->uid));
				$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
				$numresume=$this->complete($resume_row);
				$resume=$this->obj->DB_select_once("resume_expect","`id`='".$eid."'");
				$resume['numresume']=$numresume;
				include PLUS_PATH."/user.cache.php";
				include PLUS_PATH."/job.cache.php";
				include PLUS_PATH."/city.cache.php";
				include PLUS_PATH."/industry.cache.php";
				$resume['report']=$userclass_name[$resume['report']];
				$resume['hy']=$industry_name[$resume['hy']];
				$resume['city']=$city_name[$resume['provinceid']]." ".$city_name[$resume['cityid']]." ".$city_name[$resume['three_cityid']];
				$resume['salary']=$userclass_name[$resume['salary']];
				$resume['type']=$userclass_name[$resume['type']];
				$resume['jobstatus']=$userclass_name[$resume['jobstatus']];
				if($resume['job_classid']!=""){
					$job_classid=@explode(",",$resume['job_classid']);
					foreach($job_classid as $v){
						$job_classname[]=$job_name[$v];
					}
					$resume['job_classname']=implode(" ",$job_classname);
				}
				$resume['three_cityid']=$city_name[$resume['three_cityid']];
				if(is_array($resume)){
					foreach($resume as $k=>$v){
						$arr[$k]=iconv("gbk","utf-8",$v);
					}
				}
				echo json_encode($arr);die;
			}else{
				echo 0;die;
			}
		}
	}

	function add_action(){
		if($_POST['submit']){
			$num=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
			if($num>=$this->config['user_number']){
				$this->ACT_layer_msg("简历数量超限！",8);
			}
			$_POST=$this->post_trim($_POST);
			$row=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
			if($row['moblie_status']!='1'){
				$is_exist_mobile=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['telphone']."'","`uid`");
				if($is_exist_mobile){
					$this->ACT_layer_msg("手机已存在！",8);
				}
			}else{
				unset($_POST['telphone']);
			}
			if($row['email_status']!='1'){
				$is_exist_email=$this->obj->DB_select_num("member","`uid`<>'".$this->uid."' and `email`='".$_POST['email']."'","`uid`");
				if($is_exist_email){
					$this->ACT_layer_msg("邮箱已存在！",8);
				}
			}else{
				unset($_POST['email']);
			}
			unset($_POST['submit']);
			delfiledir("../data/upload/tel/".$this->uid);
			$where['uid']=$this->uid;
			$infoDate = array(
				"name"		=>	$_POST['uname'],
				"sex"		=>	$_POST['sex'],
				"birthday"	=>	$_POST['birthday'],
				"edu"		=>	$_POST['edu'],
				"exp"		=>	$_POST['exp'],
				"living"	=>	$_POST['living'],
				"lastupdate"=>time()
			);

			if($row['moblie_status']!=1){
				$infoDate['telphone']=$_POST['telphone'];
				$mvalue['moblie']=$_POST['telphone'];
			}
			if($row['email_status']!=1){
				$infoDate['email']=$_POST['email'];
				$mvalue['email']=$_POST['email'];
			}
			$this->obj->update_once('resume',$infoDate,$where);
			if(!empty($mvalue)){
				$this->obj->update_once('member',$mvalue,$where);
			}
			unset($where);
			$where['uid']=$this->uid;
			$_POST['height_status']="0";
			if(!preg_match("/^[0-9,]+$/",$_POST['classid'])){
				unset($_POST['classid']);
			}

			$_POST['uid']=$this->uid;
			$_POST['ctime']=time();
			$_POST['defaults']=$num<=0?1:0;

			$expectDate = array(
				"lastupdate"	=>	time(),
				"height_status"	=>	0,
				"uid"			=>	$this->uid,
				"did"			=>	$this->userdid,
				"defaults"		=>	$num<=0?1:0,
				"ctime"			=>	time(),
				"name"			=>	$_POST['name'],
				"hy"			=>	$_POST['hy'],
				"job_classid"	=>	$_POST['job_class'],
				"salary"		=>	$_POST['salary'],
				"provinceid"	=>	$_POST['provinceid'],
				"cityid"		=>	$_POST['citysid'],
				"three_cityid"	=>	$_POST['three_cityid'],
				"type"			=>	$_POST['type'],
				"report"		=>	$_POST['report'],
				"jobstatus"		=>	$_POST['jobstatus'],
				"integrity"		=>	55
			);
			$nid=$this->obj->insert_into("resume_expect",$expectDate);
			if ($nid){
				$this->obj->DB_delete_all("lssave","`uid`='".$this->uid."'and `savetype`='2'");
				if($num==0){
					
					$this->obj->update_once('resume',array('def_job'=>$nid,'resumetime'=>time()),array('uid'=>$this->uid));
				}else{
                    $this->obj->update_once('resume',array('resumetime'=>time()),array('uid'=>$this->uid));
                }
				$resume_num=$num+1;
				$data['uid'] = $this->uid;
				$data['eid'] = $nid;
				$data['expect'] ='1';
				$this->obj->insert_into("user_resume",$data);
				$this->obj->DB_update_all('member_statis',"`resume_num`='".$resume_num."'","`uid`='".$this->uid."'");
				$state_content = "发布了 <a href=\"".Url("resume",array("c"=>"show","id"=>$nid))."\" target=\"_blank\">新简历</a>。";
				$fdata['uid']	  = $this->uid;
				$fdata['content'] = $state_content;
				$fdata['ctime']   = time();
				$fdata['type']   = 2;
				$this->obj->insert_into("friend_state",$fdata);
				$this->obj->member_log("创建一份简历",2,1); 
        		if($row['name']==""||$row['living']==""){
					$this->company_invtal($this->uid,$this->config['integral_userinfo'],true,"首次填写基本资料",true,2,'integral',25);
				} 
				$num=$this->obj->DB_select_num("company_pay","`com_id`='".$this->uid."' AND `pay_remark`='发布简历'");
				if($num<1){
					$this->get_integral_action($this->uid,"integral_add_resume","发布简历");
				}
				$this->warning("3");
			}
			$resume = $this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`name`,`edu`,`exp`,`sex`,`birthday`,`idcard_status`,`status`,`r_status`");
			$this->obj->update_once("resume_expect",array(
				"edu"=>$resume['edu'],
				"exp"=>$resume['exp'],
				"uname"=>$resume['name'],
				"sex"=>$resume['sex'],
				"birthday"=>$resume['birthday'],
				"idcard_status"=>$resume['idcard_status'],
				"status"=>$resume['status'],
				"r_status"=>$resume['r_status'],
				"photo"=>$resume['photo']
				),$where);
			$this->ACT_layer_msg("简历创建成功，继续完善！",9,'index.php?c=expect&act=success&e='.$nid);
		}

	}
	function success_action(){
		if($_GET['e']){
			$this->yunset('id',$_GET['e']);
			$info=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
			$this->yunset("info",$info);
			$this->user_tpl('expect_success');
		}else{
			header("Location:index.php?c=expect");
		}
	}
    function save_resume_name_action(){
        if(!is_numeric($_POST['eid'])){
			 $this->ACT_layer_msg("简历编号格式不正确！",8,$_SERVER['HTTP_REFERER'],2,0);die;
        }
        if(!$this->CheckRegUser($_POST['name'])){
            $this->ACT_layer_msg("简历名格式错误！",8,$_SERVER['HTTP_REFERER'],2,0);die;
        }
        $IsSuccess=$this->obj->update_once('resume_expect',array('name'=>$_POST['name']),array('uid'=>$this->uid,'id'=>$_POST['eid']));
        $this->obj->update_once('resume',array('lastupdate'=>time()),array('uid'=>$this->uid));
        if($IsSuccess){
            $this->ACT_layer_msg("修改成功！",9,$_SERVER['HTTP_REFERER'],2,0);die;
        }else{
            $this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER'],2,0);die;
        }
    }
	function savedescription_action(){
		if(!is_numeric($_POST['eid'])){
             $this->ACT_layer_msg("简历编号格式不正确！",8,$_SERVER['HTTP_REFERER'],2,0);die;
        }
        $IsSuccess=$this->obj->update_once('resume',array('description'=>yun_iconv('utf-8','gbk',$_POST['description'])),array('uid'=>$this->uid));
        if($IsSuccess){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
	function work_action(){
		$this->resume("resume_work","work","expect","填写工作经验");
		$this->public_action();
	}
	function edu_action(){
		$this->resume("resume_edu","edu","training","填写教育经历");
		$this->public_action();
		$this->user_tpl('edu');
	}
	function training_action(){
		$this->resume("resume_training","training","cert","填写培训经历");
		$this->public_action();
		$this->user_tpl('training');
	}
	function project_action(){
		$this->resume("resume_project","project","edu","填写项目经历");
		$this->public_action();
		$this->user_tpl('project');
	}
	function skill_action(){
		$this->resume("resume_skill","skill","expect","填写专业技能");
		$this->public_action();
	}
	function cert_action(){
		$this->resume("resume_cert","cert","other","填写证书信息");
		$this->public_action();
		$this->user_tpl('cert');
	}
	function other_action(){
		$this->resume("resume_other","other","resume","返回简历管理");
		$this->public_action();
		$this->user_tpl('other');
	}
	function saveall_action(){
	    $_POST=$this->post_trim($_POST);
	    $files=$this->buildInfo();
	    if($_POST['submit']){
	        unset($_POST['submit']);
	        $eid=intval($_POST['eid']);
	        if(!is_numeric($eid)){
	            $this->ACT_layer_msg("简历编号格式不正确！",8,$_SERVER['HTTP_REFERER'],2,0);die;
	        } 
	        $table="resume_".$_POST['table'];
	        if($_POST['table']=='description'){
	            if($_POST['description']!=''){
	                $num=10;
	                $nid=$this->obj->update_once('resume',array('description'=>$_POST['description'],'lastupdate'=>time()),array('uid'=>$this->uid));
	            }
	        }else{
	            for($i=0;$i<count($_POST['name']);$i++){
	                if($_POST['name'][$i]==''){
	                    $wname[]=$_POST['nameid'][$i];
	                }
	                if($_POST['sdate'][$i] && $_POST['edate'][$i]){   
					    if($_POST['totoday'][$i]==2){
							if(strtotime($_POST['sdate'][$i])>time()){
								$rtime[]=$_POST['timeid'][$i];
							}
						}else{
							if(strtotime($_POST['sdate'][$i])>strtotime($_POST['edate'][$i])){
								$rtime[]=$_POST['timeid'][$i];
							}
						} 
	                }
	               if($_POST['sdate'][$i]==''){          
	                    if($_POST['totoday'][$i]==2){                
	                        $rtime[]=$_POST['timeid'][$i];
	                    }else{                                  
	                        $wsdate[]=$_POST['sdateid'][$i];
	                    }
	                }
	            }

	            if(!empty($wname)){
	                $wrong=1;
	                $checkname=pylode('-', $wname);
	                echo '<input id="namenum" type="hidden" value="'.$checkname.'">';
	            }
	            if(!empty($wsdate)){
	                $checksdate=pylode('-', $wsdate);
	                echo '<input id="sdatenum" type="hidden" value="'.$checksdate.'">';
	            }
	            if(!empty($rtime)){
	                $wrong=1;
	                $checktime=pylode('-', $rtime);
	                echo '<input id="timenum" type="hidden" value="'.$checktime.'">';
	            }
	            if($wrong==1){
	                echo '<input id="wrong" type="hidden" value="'.$_POST['table'].'">';
	                exit();
	            }
	            for($i=0;$i<count($_POST['name']);$i++){
	                if($_POST['totoday'][$i]==2){
	                    $_POST['edate'][$i]=='';
	                }
	                $value="`eid`=".$eid.",`uid`=".$this->uid.",`name`='".$_POST['name'][$i]."',`title`='".$_POST['title'][$i]."',`content`='".$_POST['content'][$i]."',`sdate`='".strtotime($_POST['sdate'][$i])."',`edate`='".strtotime($_POST['edate'][$i])."'";
	                if($_POST['name'][$i]=='' && $_POST['title'][$i]=='' && $_POST['content'][$i]=='' && $_POST['sdate'][$i]=='' && $_POST['edate'][$i]==''){
	                    $value=1;
	                }
	                if($_POST['table']=='work'){
	                    $num=3;
	                }
	                if($_POST['table']=='training'){
	                    $num=5;
	                }
	                if($_POST['table']=='edu'){
	                    $num=4;
	                    $value="`eid`=".$eid.",`uid`=".$this->uid.",`name`='".$_POST['name'][$i]."',`title`='".$_POST['title'][$i]."',`specialty`='".$_POST['specialty'][$i]."',`education`='".intval($_POST['education'][$i])."',`sdate`='".strtotime($_POST['sdate'][$i])."',`edate`='".strtotime($_POST['edate'][$i])."'";
	                    if($_POST['name'][$i]=='' && $_POST['title'][$i]=='' && $_POST['sdate'][$i]=='' && $_POST['edate'][$i]=='' && $_POST['specialty'][$i]=='' && $_POST['education'][$i]==''){
	                        $value=1;
	                    }
	                }
	                if($_POST['table']=='skill'){
	                    $num=6;
	                    if($files[$i]['tmp_name']!=''){
	                        $upload=$this->upload_pic("../data/upload/user/",false);
	                        $pictures=$upload->picture($files[$i]);
	                        $this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
	                        $pictures = str_replace("../data/upload/user","./data/upload/user",$pictures);
	                    }
	                    if($files[$i]['tmp_name']==''){
	                        $value="`eid`=".$eid.",`uid`=".$this->uid.",`name`='".$_POST['name'][$i]."',`longtime`='".$_POST['longtime'][$i]."'";
	                    }else{
	                        $value="`eid`=".$eid.",`uid`=".$this->uid.",`name`='".$_POST['name'][$i]."',`pic`='".$pictures."',`longtime`='".$_POST['longtime'][$i]."'";
	                    }
	                    if($_POST['name'][$i]=='' && $pictures=='' && $_POST['longtime'][$i]==''){
	                        $value=1;
	                    }
	                }
	                if($_POST['table']=='project'){
	                    $num=7;
	                }
	                if($_POST['table']=='other'){
	                    $num=9;
	                    $value="`eid`=".$eid.",`uid`=".$this->uid.",`name`='".$_POST['name'][$i]."',`content`='".$_POST['content'][$i]."'";
	                    if($_POST['name'][$i]=='' && $_POST['content'][$i]==''){
	                        $value=1;
	                    }
	                }
	                if($_POST['id'][$i]){
	                    if($value==1){
	                        $this->obj->DB_delete_all($table,'`id`='.$_POST['id'][$i].'');
	                    }else{
	                        $nid=$this->obj->DB_update_all($table,$value,'`id`='.$_POST['id'][$i].'');
	                        $this->obj->DB_update_all('resume', '`lastupdate`="'.time().'"','`uid`="'.$this->uid.'"');
	                    }
	                }else{
	                    if($value!=1 && $_POST['usedid'][$i]==''){
	                        $nid=$this->obj->DB_insert_once($table,$value);
	                        $nids[]=$nid;
	                        if($_POST['timeid'][$i]==(substr($_POST['table'],0,1).'h')){
	                            $delids[]=$_POST['timeid'][$i];
	                        }else{
	                            $delids[]=substr($_POST['timeid'][$i], 2);
	                        }
	                        $this->obj->DB_update_all('resume', '`lastupdate`="'.time().'"','`uid`="'.$this->uid.'"');
	                        $this->obj->DB_update_all("user_resume","`".$_POST['table']."`=`".$_POST['table']."`+1","`eid`='$eid' and `uid`='".$this->uid."'");
	                    }elseif($_POST['usedid'][$i]!=''){
	                        $nid=1;
	                    }
	                }
	                if($value==1){
	                    if($_POST['timeid'][$i]==(substr($_POST['table'],0,1).'h')){
	                        $values[]=$_POST['timeid'][$i];
	                    }else{
	                        $values[]=substr($_POST['timeid'][$i], 2);
	                    }
	                }else{
	                    $little[]=$_POST['timeid'][$i];
	                }
	            }
	            if(!empty($nids)){
	                $newids=pylode('-', $nids);
	                echo '<input id="newids" type="hidden" value="'.$newids.'">';
	            }
	            if(!empty($delids)){
	                $dels=pylode('-', $delids);
	                echo '<input id="dels" type="hidden" value="'.$dels.'">';
	            }
	        }
	        
	        if(!empty($values)){
	            $emptyids=pylode('-', $values);
	            echo '<input id="emptynum" type="hidden" value="'.$emptyids.'">';
	        }
	        if(!empty($little)){ 
	            $littleid=pylode('-', $little);
	            echo '<input id="littlenum" type="hidden" value="'.$littleid.'">';
	        }
	        if($nid){
	            $resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
	            $numresume=$this->complete($resume_row);
	            echo '<input id="resumeAll" type="hidden" value="'.$num.'"><input id="integrity" type="hidden" value="'.$numresume.'"><input id="upnum" type="hidden" value="'.$resume_row[$_POST['table']].'">';
	        }else{
	            echo '<input id="resumeAll" type="hidden" value="2">';
	        }
	    }
	}
	
	function showresume_action(){
	    $_POST=$this->post_trim($_POST);
	    if($_POST['resume']=='description'){
	        $info=$this->obj->DB_select_once('resume',"`uid`='".$this->uid."'","`description`");
	        $html='<li class=" yun_resume_exp_list"><i class="resume_skill_icon"></i><div class="yun_resume_exp_p"><em id="description">'.$info['description'].'</em></div></li>';
	        echo $html;die();
	    }
	    $table='resume_'.$_POST['resume'];
	    if($_POST['eid']){
	        $eid=intval($_POST['eid']);
	        include(PLUS_PATH."user.cache.php");
	        $tables=array("resume_expect","resume_skill","resume_work","resume_project","resume_edu","resume_training","resume_cert","resume_other");
	        if(!in_array($table,$tables)){
	            echo $table;
	            exit();
	        }
	        if($table=='resume_work'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."' order by `sdate` desc","`id`,`name`,`title`,`content`,`sdate`,`edate`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $v['sdate']=date("Y.m",$v['sdate']);
	                    if($v['edate']>0){
	                        $v['edate']=date("Y.m",$v['edate']);
	                    }else{
	                        $v['edate']='至今';
	                        $v['totoday']='1';
	                    }
	                    $html.="<li class='yun_resume_exp_list' id='work".$v['id']."'><div class='yun_resume_exp_timt'>".$v['sdate']."-".$v['edate']."</div><div class='yun_resume_exp_r'><div class='yun_resume_exp_name'>".$v['name']."<span class='yun_resume_exp_name_job'>".$v['title']."</span></div><div class='yun_resume_exp_p'>".$v['content']."</div></div></li>";
	                }
	                echo $html;die;
	            }
	        }
	        if($table=='resume_edu'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."' order by `sdate` desc","`id`,`name`,`title`,`content`,`sdate`,`edate`,`specialty`,`education`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $v['sdate']=date("Y.m",$v['sdate']);
	                    if($v['edate']>0){
	                        $v['edate']=date("Y.m",$v['edate']);
	                    }else{
	                        $v['edate']='';
	                    }
	                    $html.="<li class='yun_resume_exp_list' id='edu".$v['id']."'><div class='yun_resume_exp_timt'>".$v['sdate']."-".$v['edate']."</div><div class='yun_resume_exp_r'><div class='yun_resume_exp_name'>".$v['name']."<span class='yun_resume_exp_name_job'>".$userclass_name[$v['education']]."</span></div><div class='yun_resume_exp_p'>".$v['specialty']."<span class='yun_resume_exp_name_job'>".$v['title']."</span></div></div></li>";
	                }
	                echo $html;die;
	            }
	        }
	        if($table=='resume_training'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."' order by `sdate` desc","`id`,`name`,`title`,`content`,`sdate`,`edate`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $v['sdate']=date("Y.m",$v['sdate']);
	                    if($v['edate']>0){
	                        $v['edate']=date("Y.m",$v['edate']);
	                    }else{
	                        $v['edate']='';
	                    }
	                    $html.="<li class='yun_resume_exp_list' id='training".$v['id']."'><div class='yun_resume_exp_timt'>".$v['sdate']."-".$v['edate']."</div><div class='yun_resume_exp_r'><div class='yun_resume_exp_name'>".$v['name']."<span class='yun_resume_exp_name_job'>".$v['title']."</span></div><div class='yun_resume_exp_p'>".$v['content']."</div></div></li>";
	                }
	                echo $html;die;
	            }
	        }
	        if($table=='resume_skill'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."'","`id`,`name`,`longtime`,`pic`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $html.="<li class='yun_resume_exp_list' id='skill".$v['id']."'><i class='resume_skill_icon'></i>技能名称：<span class='resume_table_blod'>".$v['name']."</span> <span class='yun_resume_exp_name_job'>掌握时间：".$v['longtime']."年</span><div class=''>";
	                          if($v['pic']){
	                              $html.="技能证书： <img src='.".$v['pic']."' width='95' height='70' style='vertical-align:middle'> </div></li>";
	                          }else{
	                              $html.="</div></li>";
	                          }
	                                                       
	                                                             
	                }
	                echo $html;die;
	            }
	        }
	        if($table=='resume_project'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."' order by `sdate` desc","`id`,`name`,`title`,`content`,`sdate`,`edate`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $v['sdate']=date("Y.m",$v['sdate']);
	                    if($v['edate']>0){
	                        $v['edate']=date("Y.m",$v['edate']);
	                    }else{
	                        $v['edate']='';
	                    }
	                    $html.="<li class='yun_resume_exp_list' id='project".$v['id']."'><div class='yun_resume_exp_timt'>".$v['sdate']."-".$v['edate']."</div><div class='yun_resume_exp_r'><div class='yun_resume_exp_name'>".$v['name']."<span class='yun_resume_exp_name_job'>".$v['title']."</span></div><div class='yun_resume_exp_p'>".$v['content']."</div></div></li>";
	                }
	                echo $html;die;
	            }
	        }
	        if($table=='resume_other'){
	            $info=$this->obj->DB_select_all($table,"`eid`='".$eid."' and `uid`='".$this->uid."'","`id`,`name`,`content`");
	            if(is_array($info)){
	                foreach ($info as $v){
	                    $html.="<li class='yun_resume_exp_list' id='other".$v['id']."'><i class='resume_skill_icon'></i>标题：<span class='resume_table_blod'>".$v['name']."</span><div class='yun_resume_exp_p'>内容：".$v['content']."</div></li>";
	                }
	                echo $html;die;
	            }
	        }
	    }
	}
	function buildInfo(){
	    if($_FILES){
	        $i=0;
	        foreach($_FILES as $v){
	            if(is_string($v['name'])){
	                $files[$i]=$v;
	                $i++;
	            }else{
	                foreach($v['name'] as $key=>$val){
	                    $files[$i]['name']=$val;
	                    $files[$i]['size']=$v['size'][$key];
	                    $files[$i]['tmp_name']=$v['tmp_name'][$key];
	                    $files[$i]['error']=$v['error'][$key];
	                    $files[$i]['type']=$v['type'][$key];
	                    $i++;
	                }
	            }
	        }
	        return $files;
	    }else{
	        return ;
	    }

	}
}
?>