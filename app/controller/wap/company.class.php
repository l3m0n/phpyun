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
class company_controller extends common{
	function index_action(){
		$this->rightinfo();
		$this->get_moblie();
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','hy','com'));
		$this->yunset($CacheList);
		if((int)$_GET['three_cityid']){
			$this->yunset("cityname",$CacheList['city_name'][(int)$_GET['three_cityid']]);
		}
        foreach($_GET as $k=>$v){
			if($k!=""){
				$searchurl[]=$k."=".$v;
			}
		}
		$this->seo("firm");
		$searchurl=@implode("&",$searchurl);
		$this->yunset("searchurl",$searchurl);
		$this->yunset("topplaceholder","��������ҵ�ؼ���,�磺���޹�˾...");
		$this->yunset("headertitle","��˾����");
		$this->yuntpl(array('wap/company'));
	}
	function show_action(){
		$this->rightinfo();
		$this->get_moblie();
        $UserinfoM=$this->MODEL('userinfo');
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('job','com','city','hy'));
		$this->yunset($CacheList);
		if($this->config['com_login_link']=="2"){
			$look_msg="��ҵû�п�����ϵ��Ϣ��";
		}elseif($this->config['com_login_link']=="3"||$this->config['com_resume_link']=='1'){
            if((int)$_GET['id']!=$this->uid&&$this->uid){
                if($this->config['com_resume_link']=="1"){
					$Resume=$this->MODEL('resume');
                    $rows=$Resume->GetResumeExpectNum(array("uid"=>$this->uid));
                    if($rows<1){
                        $look_msg="��ȱ��һ����ʽ�ĸ��˼�������ʱ�޷��鿴����ҵ��ϵ��ʽ��";
                    }
                }
            }
        }
        $this->yunset("look_msg",$look_msg);
		$row=$UserinfoM->GetUserinfoOne(array('uid'=>(int)$_GET['id']),array('usertype'=>2));
		
		$rows=$UserinfoM->GetUserstatisOne(array('uid'=>(int)$row['uid']),array("field"=>"`rating`","usertype"=>2));		
		$comrat=$UserinfoM->GetRatinginfoOne(array("id"=>$rows['rating']));
		$row['lastupdate']=date("Y-m-d",$row['lastupdate']);
		$this->yunset("row",$row);
		$this->yunset("comrat",$comrat);
		$data['company_name']=$row['name'];
		$data['company_name_desc']=$row['content'];
		$this->data=$data;
		$this->seo("company_index");
		$this->yunset("headertitle","��˾����");
		$this->yuntpl(array('wap/company_show'));
	}

	function share_action(){
		$this->get_moblie();
		$M=$this->model("company");
		$row=$M->GetCompanyInfo(array('uid'=>(int)$_GET['id']));
		$row['content']=strip_tags($row['content']);
		if($row['logo']){
			$row['logo']=str_replace("./",$this->config['sy_weburl']."/",$row['logo']);
		}  
		if($this->uid!=$row['uid']){
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
					$num=$ResumeM->GetResumeExpectNum(array("uid"=>$this->uid)); 
					if($num<1){
						$look_msg=3;
					}
				}
			}
		}
		$this->yunset("look_msg",$look_msg);
		$this->yunset("row",$row);
		$show=$M->GetShowAll(array('uid'=>(int)$_GET['id']),array("field"=>"`picurl`"));
		if(is_array($show)){
			foreach($show as $k=>$v){
				$show[$k]['picurl']=str_replace("./",$this->config['sy_weburl']."/",$v['picurl']);
			}
		}
		$this->yunset("show",$show);
		$product=$M->GetProductAll(array('uid'=>(int)$_GET['id'],"status"=>"1"));
		$this->yunset("product",$product);
		$CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('job','com','city','hy'));

		$this->yunset($CacheList);
		$this->yunset("headertitle",$row['name'].'-'.$CacheList['city_name'][$row['provinceid']].' '.$CacheList['city_name'][$row['cityid']].' '.$CacheList['city_name'][$row['threecityid']].'-'.$this->config['sy_webname']);
		$this->yunset("company_style",$this->config['sy_weburl']."/app/template/wap/company");
		$this->yuntpl(array('wap/company/index'));
	}
}
?>