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
class index_controller extends resume_controller{
	function usersearch(){
		
		if($_GET[job]){
			$job=explode("_",$_GET[job]);
			$_GET['job1']=$job[0];
			$_GET['job1_son']=$job[1];
			$_GET['job_post']=$job[2];
		}
		if($_GET[city]){
			$city=explode("_",$_GET[city]);
			$_GET['provinceid']=$city[0];
			$_GET['cityid']=$city[1];
			$_GET['three_cityid']=$city[2];
		}
		
		if($_GET[tp]==1){
			$_GET['pic']=1;
		}
		
		if($_GET[all]){
			$allurl=explode("_",$_GET[all]);
			$_GET['salary']=$allurl[0];
			$_GET['hy']=$allurl[1];
			$_GET['edu']=$allurl[2];
			$_GET['exp']=$allurl[3];
			$_GET['sex']=$allurl[4];
			$_GET['report']=$allurl[5];
			$_GET['uptime']=$allurl[6];
			$_GET['idcard']=$allurl[7];
			$_GET['work']=$allurl[8];
		}
		
        
        $uptime=array(1=>'����',3=>'���3��',7=>'���7��',30=>'���һ����','90'=>'���������');
        $this->yunset("uptime",$uptime);
        
        $FinderParams=array('keyword','hy','job1','job1_son','job_post','provinceid','cityid','three_cityid','salary','edu','exp','sex','type','report','adtime','uptime');
        
		$adtime=array('1'=>'һ����',"3"=>'������','7'=>'������',"15"=>'ʮ������','30'=>'һ������',"60"=>'��������');
		$this->yunset("adtime",$adtime);
		if($this->config['province']){
			$_GET['provinceid'] = $this->config['province'];
		}
		if($this->config['cityid']){
			$_GET['cityid'] = $this->config['cityid'];
		}
		if($this->config['three_cityid']){
			$_GET['three_cityid'] = $this->config['three_cityid'];
		}
		$CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('job','city','user','hy'));
        $this->yunset($CacheList);
		
		foreach($_GET as $k=>$v){
			if(in_array($k,$FinderParams)){
				if($v!=""){
					$finder[$k]=$v;
				}
			}
		}
		 
		if($this->config['cityid']){
			unset($finder['cityid']);
		} 
		if($finder&&is_array($finder)){
			foreach($finder as $key=>$val){
				$para[]=$key."=".$val;
			}
			$paras=@implode('##',$para);
			$this->yunset("paras",$paras);
		}
			
		if($this->uid && $this->usertype==2){
			
			$historyM = $this->MODEL('history'); 
			$lookResume = $historyM->lookResumeHistory($this->uid); 
			$talentpool  = $historyM->talentpoolHistory($this->uid); 
			$useridmsg  = $historyM->useridmsgHistory($this->uid); 
			if($this->config['sy_web_site']=="1"){
				if($this->config['sy_onedomain']!=""){
					$weburl=get_domain($this->config['sy_onedomain']);
				}elseif($this->config['sy_indexdomain']!=""){
					$weburl=get_domain($this->config['sy_indexdomain']);
				}else{
					$weburl=get_domain($this->config['sy_weburl']);
				}
				SetCookie("lookresume",$lookResume,time() + 86400,"/",$weburl);
				SetCookie("talentpool",$talentpool,time() + 86400,"/",$weburl);
				SetCookie("useridmsg",$useridmsg,time() + 86400,"/",$weburl);

			}else{

				SetCookie("lookresume",$lookResume,time() + 86400,"/");
				SetCookie("talentpool",$talentpool,time() + 86400,"/");
				SetCookie("useridmsg",$useridmsg,time() + 86400,"/");
			}

			$this->yunset(array('lookresume'=>@explode(',',$lookResume),'talentpool'=>@explode(',',$talentpool),'useridmsg'=>@explode(',',$useridmsg)));
		}
		

		
		
		if((int)$_GET['three_cityid']){
			foreach($CacheList['city_type'] as $k=>$v)
			{
				if(in_array((int)$_GET['three_cityid'],$v)){
					$zpthreecityid=$k;
				}
			}
			$this->yunset("zpthreecityid",$zpthreecityid);
		}elseif((int)$_GET['cityid']){
			foreach($CacheList['city_type'] as $k=>$v)
			{
				if(in_array((int)$_GET['cityid'],$v)){
					$zpcityid=$k;
				}
			}
			$this->yunset("zpcityid",$zpcityid);
		}
		if((int)$_GET['job_post']){
			foreach($CacheList['job_type'] as $k=>$v)
			{
				if(in_array((int)$_GET['job_post'],$v)){
					$zpjobpost=$k;
				}
			}
			$this->yunset("zpjobpost",$zpjobpost);
		}elseif((int)$_GET['job1_son']){
			foreach($CacheList['job_type'] as $k=>$v)
			{
				if(in_array((int)$_GET['job1_son'],$v)){
					$zpjob1son=$k;
				}
			}
			$this->yunset("zpjob1son",$zpjob1son);
		}
		
    include PLUS_PATH."keyword.cache.php";
    if(is_array($keyword)){
      foreach($keyword as $k=>$v){
        if($v['type']=='5'&&$v['tuijian']=='1'){
          $resumekeyword[]=$v;
        }
      }
    }
    $this->yunset("resumekeyword",$resumekeyword);
    
		$this->seo("user_search");
		$this->yun_tpl(array('search'));
	}
	function search_action(){
		$this->usersearch();
	}
	function index_action(){
	   
		if($this->config['sy_default_userclass']=='1'){
	        if($this->config['sy_resumedir']!=""){
				$resumeclassurl=$this->config['sy_weburl']."/resume/?c=search&";
			}else{
				$resumeclassurl=$this->config['sy_weburl']."/index.php?m=resume&c=search&";
			}
			$this->yunset("resumeclassurl",$resumeclassurl);
			$CacheM=$this->MODEL('cache');
            $CacheList=$CacheM->GetCache(array('job','city','hy'));
            $this->yunset($CacheList);
			$this->yunset(array('gettype'=>$_SERVER["QUERY_STRING"],'getinfo'=>$_GET));
			$this->seo("user");
			$this->yun_tpl(array('index'));
		}else{
			$this->usersearch();
		}
	}
}
?>