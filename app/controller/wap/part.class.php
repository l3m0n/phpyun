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
class part_controller extends common{
	function index_action(){
		$this->rightinfo();
		if($this->config['sy_part_web']=="2"){
			$data['msg']='很抱歉！该模块已关闭！';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->get_moblie();
		$CacheM=$this->MODEL('cache');
        $CacheArr=$CacheM->GetCache(array('part','city'));
		$this->yunset($CacheArr);
		foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$searchurl=@implode("&",$searchurl);
		$this->yunset("searchurl",$searchurl);
		$this->seo("part_index");
		$this->yunset("topplaceholder","请输入兼职关键字,如：小时工...");
		$this->yunset("headertitle","兼职");
		$this->yuntpl(array('wap/part'));
	}
	function show_action(){
		if($this->config['sy_part_web']=="2"){
			$data['msg']='很抱歉！该模块已关闭！';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->rightinfo();
		$this->get_moblie();
		if((int)$_GET['id']){
			$id=(int)$_GET['id'];
			$M=$this->MODEL("part");
			$job=$M->GetPartJobOne(array("id"=>$id,"state"=>"1","`deadline`>'".time()."'"));
			if($job['id']){
				$this->yunset("job",$job);
				$M->AddPartJobHits($id);
				if($this->usertype==1){
					$apply=$M->GetPartApplyOne(array("uid"=>$this->uid,"jobid"=>$id));
					$this->yunset("apply",$apply);
					$collect=$M->GetPartCollectOne(array("uid"=>$this->uid,"jobid"=>$id));
					$this->yunset("collect",$collect);
				}
				$this->yunset($this->MODEL('cache')->GetCache(array('city','part')));
			}else{
				$data['msg']='很抱歉！未找到兼职！';
				$data['url']='index.php';
				$this->yunset("layer",$data);
			}
			
		}
		$data['part_name']=$job['name'];
		$this->data=$data;
		$this->seo("part_show");
		$this->yunset("headertitle","兼职");
		$this->yuntpl(array('wap/part_show'));
	}
	function collect_action()
	{
		if($this->usertype!=1){
			echo 1;die;
		}else{
			$M=$this->MODEL("part");
			$row=$M->GetPartCollectOne(array("uid"=>$this->uid,"jobid"=>(int)$_POST['jobid']));
			if(!empty($row)){
				echo 2;die;
			}else{
				$data['uid']=$this->uid;
				$data['jobid']=(int)$_POST['jobid'];
				$data['comid']=(int)$_POST['comid'];
				$data['ctime']=time();
				$M->AddPartCollect($data);
				echo 0;die;
			}
		}
	}
	function apply_action()
	{
		if($this->usertype!=1){
			echo 1;die;
		}else{
			if($this->config['com_resume_partapply']==1)
			{
				$Resume=$this->MODEL("resume");
				$arr=$Resume->SelectExpectOne(array("uid"=>$this->uid));
				if(empty($arr)){
					echo 3;die;
				}
			}
			$M=$this->MODEL("part");
			$job=$M->GetPartJobOne(array("id"=>(int)$_POST['jobid']));
			if($job['deadline']<time()){
				echo 4;die;
			}
			$row=$M->GetPartApplyOne(array("uid"=>$this->uid,"jobid"=>(int)$_POST['jobid']));
			if(!empty($row)){
				echo 2;die;
			}else{
				$data['uid']=$this->uid;
				$data['jobid']=(int)$_POST['jobid'];
				$data['comid']=(int)$_POST['comid'];
				$data['ctime']=time();
				$M->AddPartApply($data);
				
				$Member=$this->MODEL("userinfo");
				$user=$Member->GetMemberOne(array("uid"=>$this->uid));
				$fdata=$this->forsend(array("uid"=>$this->uid,"usertype"=>"1"));
				$data['type']="apply";
				$data['name']=$fdata['name'];
				$data['uid']=$this->uid;
				$data['username']=$this->username;
				$data['email']=$user['email'];
				$data['moblie']=$user['moblie'];
				$data['jobname']=yun_iconv("utf-8","gbk",$_POST['jobname']);
				$this->send_msg_email($data);
				echo 0;die;
			}
		}
	}
}
?>