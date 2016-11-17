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
class comapply_controller extends job_controller{
	function index_action(){
		$id=(int)$_GET['id'];
		$M=$this->MODEL('job');
		$companyM=$this->MODEL('company');
        $UserinfoM=$this->MODEL('userinfo');
		$ResumeM=$this->MODEL('resume');
        $AskM=$this->MODEL('ask');
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('job','city','com','user','hy'));
        $this->yunset($CacheList);
		$JobInfo=$M->GetComjobOne(array('id'=>$id));
		if($JobInfo['id']==''){
			$this->ACT_msg($this->config['sy_weburl'],"û�и�ְλ��");
		}else if($JobInfo['r_status']=='2'){
			$this->ACT_msg($this->config['sy_weburl'],"��ҵ�ѱ�������");
		}
		
		if($this->usertype=="1"&&$this->uid){
			$resume=$ResumeM->GetResumeExpectNum(array('uid'=>$this->uid,"`status`<>'2' and `r_status`<>'2' and `job_classid`<>''","open"=>'1'));

			if($resume!='0'){

				

				$look_job=$M->GetLookJobOne(array('uid'=>$this->uid,'jobid'=>$JobInfo['id']));
				if(!empty($look_job)){
					$M->UpdateLookJob(array('datetime'=>time()),array('uid'=>$this->uid,'jobid'=>$JobInfo['id']));
				}else{
					
					$M->AddLookJob(array('uid'=>$this->uid,'jobid'=>$JobInfo['id'],'com_id'=>$JobInfo['uid'],'datetime'=>time(),'did'=>$this->userdid)); 
					$historyM = $this->MODEL('history');
					$historyM->addHistory('lookjob',$JobInfo['id']);
				}
			}

		}

		if($this->uid!=$JobInfo['uid']){
			if($this->config['com_login_link']=="3"){
				if($this->uid=="" && $this->username==""){
					$look_msg=1;
				}else{
					if($this->usertype!="1"){
						$look_msg=2;
					}
				}
			}elseif($this->config['com_login_link']=="2"){
				$look_msg=4;
			}
			if($this->config['com_resume_link']=="1"&&$this->usertype=='1'){ 
				$row=$ResumeM->GetResumeExpectNum(array("uid"=>$this->uid)); 
				if($row<1){
					$look_msg=3;
				}
			}
			if($this->usertype=='1'){
				$favjob=$M->GetFavJobOne(array("uid"=>$this->uid,"type"=>1,"job_id"=>$JobInfo['id']));
				$userid_job=$M->GetUseridJobOne(array('uid'=>$this->uid,'job_id'=>$JobInfo['id']),array('field'=>'id'));
				$this->yunset(array('userid_job'=>$userid_job['id'],'usertype'=>1,"favjob"=>$favjob));
			}
		} 
		$ComInfo=$UserinfoM->GetUserinfoOne(array('uid'=>$JobInfo['uid']),array('field'=>"`ant_num`,`linkqq`,`logo`,`address`,`busstops`,`linktel`,`linkman`,`linkphone`,`email_status`,`moblie_status`,`yyzz_status`,`content`,`x`,`y`",'usertype'=>2));
		$JobInfo['jobname']=$JobInfo['name'];unset($JobInfo['name']);
		$JobInfo['jobrec']=$JobInfo['rec'];unset($JobInfo['rec']);
        $Info=array_merge_recursive($JobInfo,$ComInfo); 
		if($Info['linkman']){
			$operatime=time()-$Info['operatime'];
			if($Info['operatime']==''){
				$Info['operatime']='0';
			}else if($operatime<3600){
				$Info['operatime']='һСʱ����';
			}else if($operatime>=3600&&$operatime<86400){
				$Info['operatime']=floor($operatime/3600).'Сʱ';
			}else if($operatime>=86400){
				$Info['operatime']=floor($operatime/86400).'��';
			}  
			$allnum=$M->GetUserJobNum(array("com_id"=>$Info['uid'],"job_id"=>$Info['id']));
			$replynum=$M->GetUserJobNum(array("com_id"=>$Info['uid'],"job_id"=>$Info['id'],"`is_browse`>'1'")); 
			$pre=round(($replynum/$allnum)*100); 
			$this->yunset("pre",$pre);
		}
		$this->yunset("look_msg",$look_msg);
		if($Info['state']=="0"){
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"ְλ����У�");
		}elseif($Info['state']=="2"){
			$this->yunset('entype','1');

		}elseif($Info['state']=="3"){
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"��ְλδͨ����ˣ�");

		}elseif($Info['status']=="1"){
			$this->yunset('stop','1');
		}
		if(is_array($Info)){
            $cache_array = $this->db->cacheget();
			$Job = $this->db->array_action($Info,$cache_array);
			
			if($Job['is_link']=="1"){ 
				if($Job['link_type']==1){
					$Job['linkman']=$Info['linkman'];
					$Job['linktel']=$Info['linktel'];
				}else{
					$link=$M->GetComjoblinkOne(array('jobid'=>$Job['id']),array('field'=>'`link_man`,`link_moblie`'));
					$Job['linkman']=$link['link_man'];
					$Job['linktel']=$link['link_moblie'];
				}
			}
		}

		$com=$UserinfoM->GetMemberOne(array('uid'=>$Job['uid']),array('field'=>'`username`'));
		$Job['cert_n'] = @explode(",",$Job['cert']);
		$Job['uid'] = $Job['uid'];
		$Job['com_url'] = Url("company",array("c"=>"show","id"=>$Job[uid]));
		$Job['username'] = $com['username'];
		$data['job_name']=$Job['jobname'];
		$data['company_name']=$Job['com_name'];
		$data['industry_class']=$Job['job_hy'];
		$data['job_class']=$Job['job_class_one'].",".$Job['job_class_two'].",".$Job['job_class_three'];
		$data['job_desc']=$this->GET_content_desc($Job['description']);
		$this->data=$data;
		$this->yunset("Info",$Job);
		$this->seo("comapply");
		$this->yun_tpl(array('comapply'));
	}
	
	function qrcode_action(){
		$wapUrl = Url('wap');
		if( isset($_GET['id']) && $_GET['id'] != '')
			$wapUrl = Url('wap',array('c'=>'job','a'=>'share','id'=>(int)$_GET['id']));
		include_once LIB_PATH."yunqrcode.class.php";
		YunQrcode::generatePng2($wapUrl,4);
	}

	function msg_action(){
		if($_POST['submit']){
			if($this->uid==''||$this->username==''){
				$this->ACT_layer_msg("���ȵ�¼��",8,$_SERVER['HTTP_REFERER']);
			}
			if($this->usertype!="1"){
				$this->ACT_layer_msg("ֻ�и����û��ſ������ԣ�",8,$_SERVER['HTTP_REFERER']);
			}
			$M=$this->MODEL("job");
			$black=$M->GetBlackOne(array('p_uid'=>$this->uid,'c_uid'=>(int)$_POST['job_uid']));
			if(!empty($black)){
				$this->ACT_layer_msg("����ҵ�ݲ����������ѯ��",8,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['content'])==""){
				$this->ACT_layer_msg( "�������ݲ���Ϊ�գ�",2,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['authcode'])==""){
				$this->ACT_layer_msg( "��֤�벻��Ϊ�գ�",2,$_SERVER['HTTP_REFERER']);
			}
			session_start();
			if(md5($_POST['authcode'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
				$this->ACT_layer_msg("��֤�����", 8,$_SERVER['HTTP_REFERER']);
			}
			$id=$M->AddMsg(array('uid'=>$this->uid,'username'=>$this->username,'jobid'=>trim($_POST['jobid']),'job_uid'=>trim($_POST['job_uid']),'content'=>trim($_POST['content']),'com_name'=>trim($_POST['com_name']),'job_name'=>trim($_POST['job_name']),'type'=>'1','datetime'=>time()));
			isset($id)?$this->ACT_layer_msg( "���Գɹ�����ȴ��ظ���",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("����ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
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