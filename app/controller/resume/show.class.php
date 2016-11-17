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
class show_controller extends resume_controller{
	function index_action(){ 
		
		$M=$this->MODEL('resume');
		if((int)$_GET['uid']){
			if((int)$_GET['type']=="2"){
				$user=$M->SelectExpectOne(array("uid"=>(int)$_GET['uid'],"height_status"=>"2"));
				$id=(int)$user['id'];
			}else{
				$def_job=$M->SelectResumeOne(array("uid"=>(int)$_GET['uid'],"`r_status`<>'2'"),"def_job");
				if(!is_array($def_job)){
	    			$this->ACT_msg($this->config['sy_weburl'],"没有找到该人才！");
	    		}else if($def_job['def_job']<'1'){
					$this->ACT_msg($this->config['sy_weburl'].'/member',"还没有创建简历！");
	    		}else if($def_job['def_job']){
					$id=(int)$def_job['def_job'];
				}
			}
		}else if((int)$_GET['id']){
			$id=(int)$_GET['id']; 
		} 
		$resume_expect=$M->SelectExpectOne(array("id"=>$id));
		if($resume_expect['id']){ 
			
			$UserinfoM=$this->MODEL('userinfo');
			$UserMember=$UserinfoM->GetMemberOne(array("uid"=>$resume_expect['uid']),array("field"=>"`source`,`email`,`claim`"));
			$this->yunset("UserMember",$UserMember);
			
			
			$time=strtotime("-14 day");
			$allnum=$M->SelectUserIdMsgNum(array("uid"=>$resume_expect['uid'],"`datetime`>'".$time."'"));
			$replynum=$M->SelectUserIdMsgNum(array("uid"=>$resume_expect['uid'],"`datetime`>'".$time."' and `is_browse`>'2'"));
			$pre=round(($replynum/$allnum)*100); 
			$this->yunset("pre",$pre); 
			if($this->usertype==2){
				$JobM=$this->MODEL('job');
				$jobnum=$JobM->GetComjobNum(array("uid"=>$this->uid));
				$this->yunset("jobnum",$jobnum);
			}
			$user=$M->resume_select($id,$resume_expect);
			$data['resume_username']=$user['username_n'];
			$data['resume_city']=$user['city_one'].",".$user['city_two'];
			$data['resume_job']=$user['hy'];
			$this->data=$data;
			$this->seo("resume");			
			$this->yunset("Info",$user);
			
			if(is_array($user)&&$user&&$this->uid){
				$usermsgnum=$M->SelectUserIdMsgNum(array('fid'=>$this->uid,'uid'=>$user['uid']));
				$this->yunset("usermsgnum",$usermsgnum);
			}
			

			if($_GET['type']=="word"){ 
				if($user['uid']==$this->uid){
					$this->yun_tpl(array('wordresume'));
					die;
				}else{
					$resume=$M->SelectDownResumeOne(array("eid"=>(int)$_GET['id'],"downtime"=>$_GET['downtime']));
					if(is_array($resume) && !empty($resume)){ 
						$this->yun_tpl(array('wordresume'));
					}
					die;
				}
			}
			if($this->usertype=="2" || $this->usertype=="3"){
				$this->yunset("uid",$this->uid);
				$M->RemindDeal("userid_job",array("is_browse"=>"2"),array("com_id"=>$this->uid,"eid"=>(int)$_GET['id']));
				if($this->usertype=="2"){
					$talent_pool=$M->SelectTalentPool(array("eid"=>$id,"cuid"=>$this->uid));
					$this->yunset("talent_pool",$talent_pool);
					$this->unset_remind("userid_job",'2');
				}else{
					$this->unset_remind("userid_job3",'3');
				}
				

				$look_resume=$M->SelectLookResumeOne(array("com_id"=>$this->uid,"resume_id"=>$id));

				if(!empty($look_resume)){
					$M->SaveLookResume(array("datetime"=>time()),array("resume_id"=>$id,"com_id"=>$this->uid));
				}else{
					$data['uid']=$resume_expect['uid'];
					$data['resume_id']=$id;
					$data['com_id']=$this->uid;
					$data['did']=$this->userdid;
					$data['datetime']=time();
					$M->SaveLookResume($data);
					$historyM = $this->MODEL('history');
					$historyM->addHistory('lookresume',$id);
				}
			}
			

			

				if($_GET['tmp']=='d'){
					$this->yun_tpl(array('resume'));
				}elseif(intval($_GET['tmp'])>0){
					$this->yun_tpl(array('jianli'.intval($_GET['tmp']).'/index'));
				}else{
					if($resume_expect['tmpid']){
						$this->yun_tpl(array('jianli'.intval($resume_expect['tmpid']).'/index'));
					}else{ 
						$this->yun_tpl(array('resume'));
					}
				}
			
		}else{
			$this->ACT_msg($this->config['sy_weburl'],"没有找到该人才！");
		}
    } 
    function GetHits_action() {
        if((int)$_GET['id']){
            $ResumeM=$this->MODEL('resume');
            $ResumeM->AddExpectHits((int)$_GET['id']);
            $hits=$ResumeM->SelectExpectOne(array('id'=>(int)$_GET['id']),'hits');
            echo 'document.write('.$hits['hits'].')';
        }
    }
}
?>