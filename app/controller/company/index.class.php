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
class index_controller extends common{
	function index_action(){
		if($this->config['cityid']){
			$_GET['cityid'] = $this->config['cityid'];
		}
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','com','hy','job'));
		$this->yunset($CacheList);
		$this->seo("firm");
		$this->yunset(array("gettype"=>$_SERVER["QUERY_STRING"],"getinfo"=>$_GET));

		include PLUS_PATH."keyword.cache.php";
		if(is_array($keyword)){
			foreach($keyword as $k=>$v){
				if($v['type']=='4'&&$v['tuijian']=='1'){
					$comkeyword[]=$v;
				}
			}
		}
		$this->yunset("comkeyword",$comkeyword);
    
		$this->yun_tpl(array('index'));
	}
	function public_action(){
		$M=$this->MODEL("job");
		$UserinfoM=$this->MODEL('userinfo');
		$CompanyM=$this->MODEL('company');
        $sq_num=$M->GetUserJobNum(array('com_id'=>(int)$_GET['id']));
        $this->yunset("sq_num",$sq_num);
        $pl_num=$M->GetComMsgNum(array('cuid'=>(int)$_GET['id']));
        $this->yunset("pl_num",$pl_num);
        
        $ComMember=$UserinfoM->GetMemberOne(array("uid"=>(int)$_GET['id']),array("field"=>"`source`,`email`,`claim`,`status`"));
		$this->yunset("ComMember",$ComMember);
        $userinfo=$UserinfoM->GetUserinfoOne(array("uid"=>(int)$_GET['id']),array('usertype'=>2));
        $userstatis=$UserinfoM->GetUserstatisOne(array("uid"=>(int)$_GET['id']),array('usertype'=>2));
        $row=array_merge($userinfo,$userstatis);
        if(!is_array($row)){
            $this->ACT_msg($this->config['sy_weburl'],"没有找到该企业！");
        }elseif($ComMember[status]==0){
            $this->ACT_msg($this->config['sy_weburl'],"该企业正在审核中，请稍后查看！");
        }elseif($ComMember[status]==3){
            $this->ACT_msg($this->config['sy_weburl'],"该企业未通过审核！");
        }elseif($row[r_status]==2){
            $this->ACT_msg($this->config['sy_weburl'],"该企业暂被锁定，请稍后查看！");
        }
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('city','com','hy'));
        $this->yunset($CacheList);
        $city_name=$CacheList['city_name'];
        $comclass_name=$CacheList['comclass_name'];
        $industry_name=$CacheList['industry_name'];
        $row['provinceid']=$city_name[$row['provinceid']];
        $row['mun_info']=$comclass_name[$row['mun']];
        $row['pr_info']=$comclass_name[$row['pr']];
        $row['hy_info']=$industry_name[$row['hy']];
        $row['logo']=str_replace("./",$this->config['sy_weburl']."/",$row['logo']);
		$this->yunset("com",$row);
        $banner=$CompanyM->GetBannerOnes(array('uid'=>(int)$_GET['id']));
        if($banner['pic']){
        	$banner['pic']=str_replace("..",$this->config['sy_weburl'],$banner['pic']);
        }else{
        	$banner['pic']=$this->config['sy_weburl']."/".$this->config['sy_banner'];
        }
        $this->yunset("banner",$banner);
        $NewsList=$CompanyM->GetNewsAll(array('status'=>1,'uid'=>$_GET['id']));
        $this->yunset('NewsList',$NewsList);
        $ProductList=$CompanyM->GetProductAll(array('status'=>1,'uid'=>$_GET['id']));
        $this->yunset('ProductList',$ProductList);
		$data['company_name']=$row['name'];
		$data['company_name_desc']=$row['content'];
		$data['industry_class']=$row['hy_info'];
		return $data;
	}
    function show_action(){
    	$UserinfoM=$this->MODEL('userinfo');
    	$AskM=$this->MODEL('ask');
    	$CompanyM=$this->MODEL('company');
		$M=$this->MODEL('job');
		$CompanyM->UpdateCompany(array("`hits`=`hits`+1"),array("uid"=>(int)$_GET['id']));
        $msglist=$CompanyM->GetMsgList(array('cuid'=>(int)$_GET['id'],'status'=>'1'),array("limit"=>2,"orderby"=>"id","desc"=>"desc")); 
        if($msglist&&is_array($msglist)){
            foreach($msglist as $v){
                $UIDList[]=$v['uid'];
            }
            $userlist=$UserinfoM->GetMemberList(array("`uid` in (".implode(',',$UIDList).")"),array('usertype'=>1,'field'=>"`uid`,`username`"));
			 
            foreach($msglist as $k=>$v){
                foreach($userlist as $vv){
                    $vv['name']=mb_substr($vv['name'],0,1,'GBK').'**';
                    if($v['uid']==$vv['uid']){
                        $msglist[$k]['name']= mb_substr($vv['username'],0,1,'gbk')."******";
                    }
                }
            }
        }
        $this->yunset("msglist",$msglist);
        $msg_num=$CompanyM->GetMsgNum(array('cuid'=>(int)$_GET['id'],'status'=>1));
        if($msg_num>2){
            $this->yunset("msg_num",$msg_num);
        }
		if($this->config['com_login_link']=='2'){
			$look_msg="企业没有开放联系信息！";
            $looktype="3";
		}elseif($this->config['com_login_link']=='1'){
            $looktype="4";
		}elseif($this->config['com_login_link']=="3"||$this->config['com_resume_link']=='1'){
            if($this->uid=="" && $this->username==""){
                $look_msg="您还没有登录，登录后才可以查看联系方式！";
                $looktype="2";
            }else if((int)$_GET['id']!=$this->uid&&$this->uid){
                if($this->usertype!="1"){
                    $look_msg="您不是个人用户，不能查看联系方式！";
                }else{
                    if($this->config['com_resume_link']=="1"){
						$Resume=$this->MODEL('resume');
                        $rows=$Resume->GetResumeExpectNum(array("uid"=>$this->uid));
                        if($rows<1){
                            $look_msg="您缺少一份正式的个人简历，暂时无法查看该企业联系方式！";
                            $looktype="1";
                        }
                    }
                }
            }
        }
		$num=$M->GetComjobNum(array('uid'=>(int)$_GET['id'],'`r_status`<>2','`status`<>1','state'=>1));
		if($this->uid&&$this->usertype=='1'){
			$isatn=$AskM->GetAtnOne(array("uid"=>$this->uid,"sc_uid"=>(int)$_GET['id']));
			$this->yunset("isatn",$isatn);
		}
		$time=strtotime("-14 day");
		$allnum=$M->GetUserJobNum(array("com_id"=>(int)$_GET['id'],"`datetime`>'".$time."'"));
		$replynum=$M->GetUserJobNum(array("com_id"=>(int)$_GET['id'],"`is_browse`>'1' and `datetime`>'".$time."'"));
		$pre=round(($replynum/$allnum)*100);
		$limit=2;
		$pageurl=Url('company',array('id'=>(int)$_GET['id'],'c'=>'show','page'=>'{{page}}'));
		$jobs=$this->get_page("company_job","`uid`='".(int)$_GET['id']."' and `r_status`='1' and `status`='0' and `state`='1' order by lastupdate desc",$pageurl,$limit);
		$this->yunset('jobs',$jobs);
        $this->yunset("pre",$pre);
        $this->yunset("num",$num);
        $this->yunset("look_msg",$look_msg);
        $this->yunset("looktype",$looktype);
        $this->yunset("id",(int)$_GET['id']);
		$data=$this->public_action();
		$this->data=$data;
		$this->seo("company_index");
    	$this->comtpl("index");
    }
	function compl_action(){
		$M=$this->MODEL('job');
		$CompanyM=$this->MODEL('company');
		$UserinfoM=$this->MODEL('userinfo');
		 if($_POST['submit']){
			$black=$M->GetBlackOne(array('p_uid'=>$this->uid,'c_uid'=>(int)$_POST['id']));
			if(!empty($black)){
				$this->ACT_layer_msg("您已被该企业列入黑名单，不能评论该企业！",8,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['content'])==""){
				$this->ACT_layer_msg( "评论内容不能为空！",2,$_SERVER['HTTP_REFERER']);
			}
			if(trim($_POST['authcode'])==""){
				$this->ACT_layer_msg( "验证码不能为空！",2,$_SERVER['HTTP_REFERER']);
			}
			session_start();
			if(md5($_POST['authcode'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
				$this->ACT_layer_msg("验证码错误！", 8,$_SERVER['HTTP_REFERER']);
			}

			$qiye=$UserinfoM->GetUserinfoOne(array("uid"=>(int)$_POST['id']),array('field'=>"`pl_status`,`did`",'usertype'=>2));
			$data=array('uid'=>$this->uid,'content'=>$_POST['content'],'cuid'=>(int)$_POST['id'],'ctime'=>time(),'did'=>$qiye['did']);
			if ($qiye['pl_status']=='2'){
				$data['status']=0;
				$nid=$CompanyM->insert_into("company_msg",$data);
				isset($nid)?$this->ACT_layer_msg("评论成功，请等待企业审核！",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("评论失败，请稍后再试！",8,$_SERVER['HTTP_REFERER']);
			}else{
				$data['status']=1;
				$nid=$CompanyM->insert_into("company_msg",$data);
				isset($nid)?$this->ACT_layer_msg("评论成功！",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("评论失败，请稍后再试！",8,$_SERVER['HTTP_REFERER']);
			}
		}else if($_POST['submit2']){
			$data=array('reply'=>$_POST['content'],'reply_time'=>time());
			$where['id']=(int)$_POST['id'];
			$where['cuid']=$this->uid;
			$nid=$CompanyM->update_once('company_msg',$data,$where);
			isset($nid)?$this->ACT_layer_msg("回复成功！",9,$_SERVER['HTTP_REFERER']):$this->ACT_layer_msg("回复失败，请稍后再试！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function comtpl($tpl){
        if ($_GET['style'] && !preg_match('/^[a-zA-Z]+$/',$_GET['style'])){
            exit();
        }
        if ($_GET['tp'] && !preg_match('/^[a-zA-Z]+$/',$_GET['tp'])){
            exit();
        }
        $UserinfoM=$this->MODEL('userinfo');
        $statis=$UserinfoM->GetUserstatisOne(array("uid"=>(int)$_GET['id']),array('usertype'=>2));
        if($statis['comtpl'] && $statis['comtpl']!="default" && !$_GET['style']){
            $tplurl=$statis['comtpl'];
        }else{
            $tplurl="default";
        }
        if($_GET['style']){
            $tplurl=$_GET['style'];
        }
		$this->yunset(array("com_style"=>$this->config['sy_weburl']."/app/template/company/".$tplurl."/","comstyle"=>TPL_PATH."company/".$tplurl."/"));
		$this->yuntpl(array('company/'.$tplurl."/".$tpl));
	}
	function productshow_action(){
		$CompanyM=$this->MODEL("company");
		$AskM=$this->MODEL('ask');
        $Where['id']=(int)$_GET['pid'];
		session_start();
        if(!is_numeric($_SESSION['auid']) && (int)$_GET['id']!=$this->uid){
            $Where['status']=1;
        }

        $ProductInfo=$CompanyM->GetProductOne($Where);
        $this->yunset('ProductInfo',$ProductInfo);
		$data=$this->public_action();
		$data['company_product']=$ProductInfo['name'];
		$this->data=$data;
		if($this->uid&&$this->usertype=='1'){
			$isatn=$AskM->GetAtnOne(array("uid"=>$this->uid,"sc_uid"=>(int)$_GET['id']));
			$this->yunset("isatn",$isatn);
		}
	    $this->seo("company_productshow");
		$this->comtpl("productshow");
	}
	function newsshow_action(){

		$CompanyM=$this->MODEL('company');
		$AskM=$this->MODEL('ask');
        $Where['id']=$_GET['nid'];
		session_start();
        if(!is_numeric($_SESSION['auid']) && (int)$_GET['id']!=$this->uid){
            $Where['status']=1;
        }
        $NewsInfo=$CompanyM->GetNewsOne($Where);
        $this->yunset('NewsInfo',$NewsInfo);
		$data=$this->public_action();
		$data['company_news']=$NewsInfo['title'];
		$this->data=$data;
		if($this->uid&&$this->usertype=='1'){
			$isatn=$AskM->GetAtnOne(array("uid"=>$this->uid,"sc_uid"=>(int)$_GET['id']));
			$this->yunset("isatn",$isatn);
		}
	    $this->seo("company_newsshow");
		$this->comtpl("newsshow");
	}
	function msg_action(){
        $UserinfoM=$this->MODEL('userinfo');
        $AskM=$this->MODEL('ask');
        $M=$this->MODEL('job');
        $ResumeM=$this->MODEL('resume');
		$pageurl=Url('company',array('id'=>(int)$_GET['id'],'c'=>'msg','page'=>'{{page}}'));
		$msglist=$this->get_page("company_msg","`cuid`='".(int)$_GET['id']."' and`status`='1' order by id desc",$pageurl,"10");
		if(is_array($msglist)&&$msglist){
			foreach($msglist as $v){
				$uid[]=$v['uid'];
			}
			$uid=pylode(",",$uid);
			$user=$UserinfoM->GetUserinfoList(array("`uid` in (".$uid.")"),array('usertype'=>1,'field'=>"`uid`,`name`,`photo`,`def_job`"));
			foreach($msglist as $k=>$v){
				foreach($user as $key=>$val){
				    $val['name']=mb_substr($val['name'],0,1,'GBK').'**';
					if($v['uid']==$val['uid']){
                        $msglist[$k]=array_merge($v,$val);
					}
				}
			}
		}
		if($this->uid&&$this->usertype=='1'){
			$isatn=$AskM->GetAtnOne(array("uid"=>$this->uid,"sc_uid"=>(int)$_GET['id']));
			$this->yunset("isatn",$isatn);
		}
		$this->yunset("msglist",$msglist);
		$data=$this->public_action();
		$this->data=$data;
		$this->seo("company_msg");
		$this->comtpl("msg");
	}
	function prestr_action(){
	    if($_POST['page']){
	        $M=$this->MODEL('job');
	        $page=intval($_POST['page']);
	        $num=$M->GetComjobNum(array('uid'=>(int)$_POST['id'],'`r_status`<>2','`status`<>1','state'=>1));
	        $limit=intval($_POST['limit']);
	        $maxpage=intval(ceil($num/$limit));
	        if ($page>$maxpage){
	            $page=$maxpage;
	        }
	       if (intval($_POST['updown'])==1){
				$list['url'] = str_replace('compage','page',Url('company',array('c'=>'show','id'=>(int)$_POST['id'],'compage'=>max(1,($page-1)))));
	        }else if (intval($_POST['updown'])==2){
				$list['url'] = str_replace('compage','page',Url('company',array('c'=>'show','id'=>(int)$_POST['id'],'compage'=>min($maxpage,($page+1)))));
	        }
	        echo json_encode($list);
	    }
	}
}
?>