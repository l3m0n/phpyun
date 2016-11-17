<?php
/*
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
 */
class job_controller extends common{
	function index_action(){
		$CacheM=$this->MODEL('cache');
		$CacheArr=$CacheM->GetCache(array('job','city','hy','com'));
		if($_GET['jobin']){
			$job_classid=@explode(',',$_GET['jobin']);
			$jobname=$CacheArr['job_name'][$job_classid[0]];
			$this->yunset("jobname",mb_substr($jobname,0,6,'gbk'));
		}
		$uptime=array(1=>'����',3=>'���3��',7=>'���7��',30=>'���һ����',90=>'���������');
		$this->yunset("uptime",$uptime);
		$this->yunset($CacheArr);
		$this->yunset("headertitle","ְλ����");
		foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$this->seo("com_search");
		$searchurl=@implode("&",$searchurl);
		$this->yunset("topplaceholder","������ְλ�ؼ���,�磺���...");
		$this->yunset("searchurl",$searchurl);
		$this->yuntpl(array('wap/job'));
	}
	function search_action(){
		$this->index_action();
	}
	function view_action(){
		$JobM=$this->MODEL('job');
		$CacheM=$this->MODEL('cache');
		$ResumeM=$this->MODEL('resume');
		$UserinfoM=$this->MODEL('userinfo');
		$CacheArr=$CacheM->GetCache(array('job','city','hy','com'));
		$job=$JobM->GetComjobOne(array("id"=>(int)$_GET['id']));
		$welfare = @explode(",",$job['welfare']);
		  foreach($welfare as $k=>$v){
			if(!$v){
			  unset($welfare[$k]);
			}
		  }
		$job['welfare']=$welfare;
		if($job['lang']){
			$lang = @explode(",",$job['lang']);
			$job['lang']=$lang; 
		}
		$userid_job=$this->obj->DB_select_num("userid_job","`job_id`='".(int)$_GET['id']."'and `uid`='".$this->uid."'");
		$fav_job=$this->obj->DB_select_num("fav_job","`job_id`='".(int)$_GET['id']."'and `uid`='".$this->uid."'");
		$job['userid_job']=$userid_job;
		$job['fav_job']=$fav_job;
		$company=$UserinfoM->GetUserinfoOne(array("uid"=>$job['uid']),array("usertype"=>'2'));
		$comrat=$UserinfoM->GetRatinginfoOne(array("id"=>$job['rating']));
		if($this->usertype=="1"&&$this->uid){
			$ResumeM=$this->MODEL('resume');
			$resume=$ResumeM->GetResumeExpectNum(array('uid'=>$this->uid,"`status`<>'2' and `r_status`<>'2' and `job_classid`<>''","open"=>'1'));
			if($resume){
				
				$look_job=$JobM->GetLookJobOne(array("uid"=>$this->uid,"jobid"=>(int)$_GET['id']));
				if(!empty($look_job)){
					$JobM->UpdateLookJob(array("datetime"=>time()),array("uid"=>$this->uid,"jobid"=>(int)$_GET['id']));
				}else{
					
					$value['uid']=$this->uid;
					$value['did']=$this->userdid;
					$value['jobid']=(int)$_GET['id'];
					$value['com_id']=$job['uid'];
					$value['datetime']=time();
					$JobM->AddLookJob($value);
				}
			}
		}
		if($_GET['type']){
			if(!$this->uid || !$this->username ){
				$data['msg']='���ȵ�¼��';
				$data['url']='index.php?c=login';
				$data['msg']=iconv("GBK","UTF-8",$data['msg']);
				echo json_encode($data);die;
			}elseif($this->usertype!=1){
				$data['msg']='�����Ǹ����û���';
				$data['url']=$_SERVER['HTTP_REFERER'];
				$data['msg']=iconv("GBK","UTF-8",$data['msg']);
				echo json_encode($data);die;
			}else {
				if($_GET['type']=='sq'){
					$row=$JobM->GetUserJobNum(array("uid"=>$this->uid,"job_id"=>(int)$_GET['id']));
					$resume=$ResumeM->SelectExpectOne(array("uid"=>$this->uid,"defaults"=>1),"id");
					if(!$resume['id']){
						$data['msg']='����û�м�����������Ӽ�����';
						$data['url']='member/index.php?c=resume';
						$data['msg']=iconv("GBK","UTF-8",$data['msg']);
						echo json_encode($data);die;
					}else if(intval($row)>0){
						$data['msg']='���Ѿ�Ͷ�ݹ��ü������벻Ҫ�ظ�Ͷ�ݣ�';
						$data['url']=$_SERVER['HTTP_REFERER'];
						$data['msg']=iconv("GBK","UTF-8",$data['msg']);
						echo json_encode($data);die;
					}else{
						$info=$JobM->GetComjobOne(array("id"=>(int)$_GET['id']));
						$value['job_id']=$_GET['id'];
						$value['com_name']=$info['com_name'];
						$value['job_name']=$info['name'];
						$value['com_id']=$info['uid'];
						$value['uid']=$this->uid;
						$value['eid']=$resume['id'];
						$value['datetime']=mktime();
						$nid=$JobM->AddUseridJob($value);
						if($nid){
							$UserinfoM->UpdateUserStatis("`sq_job`=`sq_job`+1",array("uid"=>$value['com_id']),array('usertype'=>'2'));
							$UserinfoM->UpdateUserStatis("`sq_jobnum`=`sq_jobnum`+1",array("uid"=>$value['uid']),array('usertype'=>'1'));
							if($info['link_type']=='1'){
								$ComM=$this->MODEL("company");
								$job_link=$ComM->GetCompanyInfo(array("uid"=>$info['uid']),array("field"=>"`linkmail`"));
								$info['email']=$job_link['linkmail'];
							}
							if($this->config["sy_smtpserver"]!="" && $this->config["sy_smtpemail"]!="" &&	$this->config["sy_smtpuser"]!=""){
								if($info['email']){
									$contents=@file_get_contents(Url("resume",array("c"=>"sendresume","job_link"=>'1',"id"=>$resume['id'])));
									$smtp = $this->email_set();
									$smtpusermail =$this->config['sy_smtpemail'];
									$sendid = $smtp->sendmail($this->config['sy_smtpnickname'],$info['email'],$smtpusermail,"���յ�һ���µ���ְ����������".$this->config['sy_webname'],$contents);
								}
							}
							$JobM->UpdateComjob(array("`snum`=`snum`+1"),array("id"=>(int)$_GET['id']));
							$this->obj->member_log("��������ְλ��".$info['name'],6);
							$data['msg']='Ͷ�ݳɹ���';
							$data['url']=$_SERVER['HTTP_REFERER'];
							$data['msg']=iconv("GBK","UTF-8",$data['msg']);
							echo json_encode($data);die;
						}else{
							$data['msg']='Ͷ��ʧ�ܣ�';
							$data['url']=$_SERVER['HTTP_REFERER'];
							$data['msg']=iconv("GBK","UTF-8",$data['msg']);
							echo json_encode($data);die;
						}
					}
				}else if($_GET['type']=='fav'){
					$rows=$ResumeM->GetFavjobOne(array("uid"=>$this->uid,'job_id'=>(int)$_GET['id']));
					if($rows['id']){
						$data['msg']='���Ѿ��ղع���ְλ���벻Ҫ�ظ��ղأ�';
						$data['url']=$_SERVER['HTTP_REFERER'];
						$data['msg']=iconv("GBK","UTF-8",$data['msg']);
						echo json_encode($data);die;
					}
					$job=$JobM->GetComjobOne(array("id"=>(int)$_GET['id']));
					$value['job_id'] = $job['id'];
					$value['com_name'] = $job['com_name'];
					$value['job_name'] = $job['name'];
					$value['com_id'] = $job['uid'];
					$value['uid'] = $this->uid;
					$value['datetime'] = time();
					$nid=$JobM->AddFavJob($value);
					if($nid){
						$UserinfoM->UpdateUserStatis("`fav_jobnum`=`fav_jobnum`+1",array("uid"=>$this->uid),array('usertype'=>'1'));
						$data['msg']='�ղسɹ���';
						$data['url']=$_SERVER['HTTP_REFERER'];
						$data['msg']=iconv("GBK","UTF-8",$data['msg']);
						echo json_encode($data);die;
					}else{
						$data['msg']='�ղ�ʧ�ܣ�';
						$data['url']=$_SERVER['HTTP_REFERER'];
						$data['msg']=iconv("GBK","UTF-8",$data['msg']);
						echo json_encode($data);die;
					}
				}
			}
		}


		if($this->uid!=$job['uid']){
			if($this->config['com_login_link']=="2"){
				$look_msg=4;
			}elseif($this->config['com_login_link']=="3"){
				if($this->uid=="" && $this->username==""){
					$look_msg=1;
				}else{
					if($this->usertype!="1"){
						$look_msg=2;
					}
				}
			}
			if($this->config['com_resume_link']=="1"&&$this->usertype=='1'){
				$row=$ResumeM->GetResumeExpectNum(array("uid"=>$this->uid));
				if($row<1){
					$look_msg=3;
				}
			}
		}


		$data['job_name']=$job['name'];
		$data['company_name']=$job['com_name'];
		$data['industry_class']=$CacheArr['industry_name'][$job['hy']];
		$data['job_class']=$CacheArr['job_name'][$job['job1']].",".$CacheArr['job_name'][$job['job1_son']].",".$CacheArr['job_name'][$job['job_post']];
		$data['job_desc']=$this->GET_content_desc($job['description']);
		$this->data=$data;
		 
		if($job['is_link']=="1"){
			
		    if($job['link_type']==2){
		       $link=$JobM->GetComjoblinkOne(array('jobid'=>$job['id']),array('field'=>'`link_man`,`link_moblie`'));
		        $job['linkman']=$link['link_man'];
		        $job['linktel']=$link['link_moblie'];
		    }else{ 
				$job['linkman']=$company['linkman'];
				$job['linktel']=$company['linktel'];
			}
		}else{$look_msg=4;} 
		if($company['linkphone']){$company['phone']=str_replace('-','',$company['linkphone']);}
		$this->seo("comapply");
		$this->yunset("look_msg",$look_msg);
		$this->yunset("job",$job);
		$this->yunset("comrat",$comrat);
		$this->yunset($CacheArr);
		$this->yunset("company",$company);
		$this->yunset("headertitle","ְλ����");
		$this->yuntpl(array('wap/job_show'));
	}
	function report_action(){ 
		if($this->usertype!='1'){
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'ֻ�и��˻�Ա�ſɾٱ���');
			echo json_encode($data);die;
		}
		$M=$this->MODEL('job');
        $AskM=$this->MODEL('ask');
		$jobid=intval($_POST['id']);
        session_start();
		
		$job=$M->GetComjobOne(array("id"=>$jobid),array('field'=>'`uid`,`com_name`'));
		if($job['uid']==''){
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'�Ƿ�������');
			echo json_encode($data);die;
		}
		if($this->config['user_report']!='1'){
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'����Աδ�����ٱ����ܣ�');
			echo json_encode($data);die; 
		}
		if(md5($_POST['authcode'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
			unset($_SESSION['authcode']);
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'��֤�����');
			echo json_encode($data);die;  
		}
		$row=$AskM->GetReportOne(array('p_uid'=>$this->uid,'c_uid'=>$job['uid'],'usertype'=>$this->usertype));
		if(is_array($row)){
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'���Ѿٱ������û���');
			echo json_encode($data);die;  
		}
        $data=array('c_uid'=>$job['uid'],'inputtime'=>time(),'p_uid'=>$this->uid,'usertype'=>(int)$this->usertype,'eid'=>$jobid,'r_name'=>$job['com_name'],'username'=>$this->username,'r_reason'=>iconv("UTF-8","GBK",trim($_POST['reason'])),'did'=>$this->userdid);
		$nid=$AskM->AddReport($data);
		if($nid){
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'�ٱ��ɹ���');
			echo json_encode($data);die;  
		}else{
			$data['url']=$_SERVER['HTTP_REFERER'];
			$data['msg']=iconv("GBK","UTF-8",'�ٱ�ʧ�ܣ�');
			echo json_encode($data);die;  
		}
	}
	
	function share_action(){
		$this->get_moblie();
		$JobM=$this->MODEL('job');
		$CacheM=$this->MODEL('cache');

		$UserinfoM=$this->MODEL('userinfo');
		$CacheArr=$CacheM->GetCache(array('job','city','hy','com'));
		$job=$JobM->GetComjobOne(array("id"=>(int)$_GET['id']));
		$welfare = @explode(",",$job['welfare']);
		  foreach($welfare as $k=>$v){
			if(!$v){
			  unset($welfare[$k]);
			}
		  }
		$job['welfare']=$welfare;
		$lang = @explode(",",$job['lang']);
		$job['lang']=$lang;
		$company=$UserinfoM->GetUserinfoOne(array("uid"=>$job['uid']),array("usertype"=>'2'));
		if($company['linkphone']){$company['phone']=str_replace('-','',$company['linkphone']);}
		$company['content']=strip_tags($company['content']);
		$job['description']=strip_tags($job['description'],"<br>");
		if($this->uid!=$job['uid']){
			if($this->config['com_login_link']=="2"){
				$look_msg=4;
			}elseif($this->config['com_login_link']=="3"){
				if($this->uid=="" && $this->username==""){
					$look_msg=1;
				}else{
					if($this->usertype!="1"){
						$look_msg=2;
					}
				}
				if($this->config['com_resume_link']=="1"&&$this->usertype=='1'){
					$ResumeM=$this->MODEL('resume');
					$row=$ResumeM->GetResumeExpectNum(array("uid"=>$this->uid));
					if($row<1){
						$look_msg=3;
					}
				}
			}
			
		}
		$this->yunset("look_msg",$look_msg);
		$this->yunset("job",$job);
		$this->yunset($CacheArr);
		$this->yunset("company",$company);
		$this->yunset("headertitle",$job['name'].'-'.$job['com_name'].'-'.$this->config['sy_webname']);
		$this->yunset("job_style",$this->config['sy_weburl']."/app/template/wap/job");
		$this->yuntpl(array('wap/job/index'));
	}
	function ajax_url_action(){
		if($_POST){
			if($_POST['url']!=""){
				$urls=@explode("&",$_POST['url']);
				foreach($urls as $v){
					if($_POST['type']=="provinceid"||$_POST['type']=="cityid"){
						if(strpos($v,"provinceid")===false && strpos($v,"cityid")===false){
							$gourl[]=yun_iconv('utf-8','gbk',$v);
						}
					}else{
						if(strpos($v,$_POST['type'])===false){
							$gourl[]=yun_iconv('utf-8','gbk',$v);
						}
					}
				}
				if($_POST['id']>0){
					$gourl=@implode("&",$gourl)."&".$_POST['type']."=".$_POST['id'];
				}else{
					$gourl=@implode("&",$gourl);
				}
			}else{
				$gourl=$_POST['type']."=".$_POST['id'];
			}
			echo "?".$gourl;die;
		}
	}
	function GetHits_action() {
	    if(intval($_GET['id'])){
	        $JobM=$this->MODEL('job');
	        $JobM->UpdateComjob(array("`jobhits`=`jobhits`+1"),array("id"=>(int)$_GET['id']));
	        $hits=$JobM->GetComjobOne(array('id'=>intval($_GET['id'])),array('field'=>'jobhits'));
	        echo 'document.write('.$hits['jobhits'].')';
	    }
	}
}
?>