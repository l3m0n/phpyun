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
class admin_company_controller extends common{
	function set_search(){ 
		$rating=$this->obj->DB_select_all("company_rating","`category`='1' order by `sort` asc","`id`,`name`");
		if(!empty($rating)){
			foreach($rating as $k=>$v){
                 $ratingarr[$v['id']]=$v['name'];
			}
		}
		$source=array('1'=>'网页','2'=>'WAP','3'=>'APP','4'=>'微信','6'=>'采集','7'=>'Excel');
		$adtime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$lotime=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$status=array('1'=>'已审核','2'=>'已锁定','3'=>'未审核');
		$edtime=array('1'=>'7天内','2'=>'一个月内','3'=>'半年内','4'=>'一年内');
		$search_list[]=array('param'=>'source','name'=>'数据来源','value'=>$source);
		$search_list[]=array("param"=>"rec","name"=>'知名企业',"value"=>array("1"=>"是","2"=>"否"));
		$search_list[]=array("param"=>"status","name"=>'审核状态',"value"=>$status);
		$search_list[]=array("param"=>"rating","name"=>'会员等级',"value"=>$ratingarr);
		$search_list[]=array("param"=>"time","name"=>'到期时间',"value"=>$edtime);
		$search_list[]=array("param"=>"lotime","name"=>'最近登录',"value"=>$lotime);
		$search_list[]=array("param"=>"adtime","name"=>'最近注册',"value"=>$adtime);
		$this->yunset("ratingarr",$ratingarr);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where=$mwhere="1";
		$uids=array();
		if($_GET['status']){
			if($_GET['status']=='3'){
				$mwhere.=" and `status`='0'";
			}else if($_GET['status']){
				$mwhere.=" and `status`='".intval($_GET['status'])."'";
			}
			$urlarr['status']=intval($_GET['status']);
		}
		if($_GET['rating']){
			$swhere="`rating`='".$_GET['rating']."'";
			$urlarr['rating']=$_GET['rating'];
		}
		if($_GET['time']){
            if($_GET['time']=='1'){
            	$num="+7 day"; 
            }elseif($_GET['time']=='2'){
				$num="+1 month";
            }elseif($_GET['time']=='3'){
				$num="+6 month"; 
            }elseif($_GET['time']=='4'){
                $num="+1 year";
            }
			if($swhere){
				$swhere.=" and `vip_etime`>'".time()."' and `vip_etime`<'".strtotime($num)."'";
			}else{
				$swhere=" `vip_etime`>'".time()."' and `vip_etime`<'".strtotime($num)."'";
			}
			$urlarr['time']=$_GET['time'];
		}

		if($swhere){
			$list=$this->obj->DB_select_all("company_statis",$swhere,"`uid`,`pay`,`rating`,`rating_name`,`vip_etime`");
			foreach($list as $val){
				$uids[]=$val['uid'];
			}
			$where.=" and `uid` in (".@implode(',',$uids).")";
		}
		if($_GET['rec']){
       	   if($_GET['rec']=='1'){
 				$where.= "  and `rec`=1 ";
       	   }else{
 				$where.= "  and `rec`=0 ";
       	   }
			$urlarr['rec']=$_GET['rec'];
       }


	   if($_GET['hy']){
			$where .= " and `hy` = '".$_GET['hy']."' ";
			$urlarr['hy']=$_GET['hy'];
		}
	   if($_GET['provinceid']){
			$where .= " and `provinceid` = '".$_GET['provinceid']."' ";
			$urlarr['provinceid']=$_GET['provinceid'];
		}
		if($_GET['cityid']){
			$where .= " and `cityid` = '".$_GET['cityid']."' ";
			$urlarr['cityid']=$_GET['cityid'];
		}
		 if($_GET['pr']){
			$where .= " and `pr` = '".$_GET['pr']."' ";
			$urlarr['pr']=$_GET['pr'];
		}
		 if($_GET['mun']){
			$where .= " and `mun` = '".$_GET['mun']."' ";
			$urlarr['mun']=$_GET['mun'];
		}
	    if($_GET['keywords']){
			$where .= " and `name` like '%".$_GET['keywords']."%' ";
			$urlarr['keywords']=$_GET['keywords'];
		}
	   if(trim($_GET['keyword'])){
            if($_GET['com_type']=='1'){
				$where.= "  AND `name` like '%".$_GET['keyword']."%' ";
            }elseif($_GET['com_type']=='2'){
				$mwhere.=" and `username` like '%".$_GET['keyword']."%'";
            }elseif($_GET['com_type']=='3'){
				$where.= "  AND `linkman` like '%".$_GET['keyword']."%' ";
            }elseif($_GET['com_type']=='4'){
				$where.= "  AND `linktel` like '%".$_GET['keyword']."%' ";
            }elseif($_GET['com_type']=='5'){
				$where.= "  AND `linkmail` like '%".$_GET['keyword']."%' ";
            }
			$urlarr['com_type']=$_GET['com_type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['adtime']){
			if($_GET['adtime']=='1'){
				$mwhere .=" and `reg_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$mwhere .=" and `reg_date`>'".strtotime('-'.intval($_GET['adtime']).' day')."'";
			}
			$urlarr['adtime']=$_GET['adtime'];
		}
		if($_GET['lotime']){
			if($_GET['lotime']=='1'){
				$mwhere .=" and `login_date`>'".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$mwhere .=" and `login_date`>'".strtotime('-'.intval($_GET['lotime']).' day')."'";
			}
			$urlarr['lotime']=$_GET['lotime'];
		}
		if($_GET['source']){
			$mwhere .=" and `source` = '".$_GET['source']."'";
			$urlarr['source']=$_GET['source'];
		}
		if($mwhere!='1'){
			$username=$this->obj->DB_select_all("member",$mwhere." and `usertype`='2'","`username`,`uid`,`reg_date`,`login_date`,`status`,`source`");
			$uids=array();
			foreach($username as $val){
				$uids[]=$val['uid'];
			}
			$where.=" and `uid` in (".@implode(',',$uids).")";
		}
		if($_GET['order'])
		{
			if($_GET['t']=="time")
			{
				$where.=" order by `lastupdate` ".$_GET['order'];
			}else{
				$where.=" order by ".$_GET['t']." ".$_GET['order'];
			}
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `uid` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("company",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
 		if(is_array($rows)&&$rows){

			if($mwhere=='1'&&empty($username)){
				foreach($rows as $v){$uids[]=$v['uid'];}
				$username=$this->obj->DB_select_all("member","`uid` in (".@implode(",",$uids).")","`username`,`uid`,`reg_date`,`login_date`,`reg_ip`,`status`,`source`");
			}
			if(empty($list)){
				$list=$this->obj->DB_select_all("company_statis","`uid` in (".@implode(",",$uids).")","`uid`,`pay`,`integral`,`rating`,`rating_name`,`vip_etime`,`msg_num`");
			}
 			foreach($rows as $k=>$v){
 				if(strlen($v['name'])>24){
					$rows[$k]['name']=mb_substr($v['name'],"0","12","gbk")."...";
 				}
				if($v['did']<1)
				{
					$rows[$k]['did'] = 0;
				}
				foreach($username as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['username']=$val['username'];
						$rows[$k]['reg_date']=$val['reg_date'];
						$rows[$k]['reg_ip']=$val['reg_ip'];
						$rows[$k]['login_date']=$val['login_date'];
						$rows[$k]['status']=$val['status'];
						$rows[$k]['source']=$val['source'];
					}
				}
				foreach($list as $val){
					if($v['uid']==$val['uid']){
						$rows[$k]['rating']=$val['rating'];
						$rows[$k]['pay']=$val['pay'];
						$rows[$k]['rating_name']=$val['rating_name'];
						$rows[$k]['vip_etime']=$val['vip_etime'];
						$rows[$k]['integral']=$val['integral'];
					}
				}
			}
		}

		$nav_user=$this->obj->DB_select_alls("admin_user","admin_user_group","a.`m_id`=b.`id` and a.`uid`='".$_SESSION["auid"]."'");
		$power=unserialize($nav_user[0]["group_power"]);
		if(in_array('141',$power)){
			$this->yunset("email_promiss", '1');
			$this->yunset("moblie_promiss", '1');
		} 

		$where=str_replace(array("(",")"),array("[","]"),$where);
		$Domain = $this->obj->DB_select_all("domain","`type`='1'");
		$Dname[0] = '总站';
		if(is_array($Domain)){
			foreach($Domain as $key=>$value){
				$Dname[$value['id']]  =  $value['title'];
			}
		}
		$this->yunset("Dname", $Dname);
		$this->yunset("where", $where);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_company'));
	}

	function edit_action(){
		if((int)$_GET['id']){
			$com_info = $this->obj->DB_select_once("member","`uid`='".$_GET['id']."'");
			$row = $this->obj->DB_select_once("company","`uid`='".$_GET['id']."'");
			$statis = $this->obj->DB_select_once("company_statis","`uid`='".$_GET['id']."'");
			$rating_list = $this->obj->DB_select_all("company_rating","`category`=1");
			if($row['linkphone']){
				$linkphone=@explode('-',$row['linkphone']);
				$row['phoneone']=$linkphone[0];
				$row['phonetwo']=$linkphone[1];
				$row['phonethree']=$linkphone[2]; 
			}
			$this->yunset("statis",$statis);
			$this->yunset("row",$row);
			$this->yunset("rating_list",$rating_list);
			$this->yunset("rating",$_GET['rating']);
			$this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
			$this->yunset("com_info",$com_info);
			$this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com')));

		}
		if($_POST['com_update']){ 
			$mem = $this->obj->DB_select_once("member","`uid`='".$_POST['uid']."'");
			if($mem['username']!=$_POST['username'] && $_POST['username']!=""){
				$num = $this->obj->DB_select_num("member","`username`='".$_POST['username']."'");
				if($num>0){
					$this->ACT_layer_msg("用户名已存在！",8,$_SERVER['HTTP_REFERER'],2,1);
				}else{
					$this->obj->DB_update_all("member","`username`='".$_POST['username']."'","`uid`='".$_POST['uid']."'");
				}
			} 
			$email=$_POST['email'];
			$uid=$_POST['uid'];
			$user = $this->obj->DB_select_once("member","`email`='$email' AND  `email`<>'' and `uid`<>'$uid'",'uid');
			$company=$this->obj->DB_select_once("company","`uid`='".$_POST['uid']."'","name");
			if(is_array($user)){
				$msg = "邮箱已存在！";
				$this->ACT_layer_msg( $msg,8,$_SERVER['HTTP_REFERER'],2,1);
			}else{ 
				$value="`r_status`='".$_POST['status']."',";
				if($_POST['status']=='2'){

					$smtp = $this->email_set();
					if($mem['status']!='2'){
						$data=$this->forsend($mem);
						if($this->config['sy_smtpserver']!="" && $this->config['sy_smtpemail']!="" && $this->config['sy_smtpuser']!=""){
							$this->send_msg_email(array("email"=>$mem['email'],"lock_info"=>$_POST['lock_info'],"uid"=>$data['uid'],"name"=>$data['name'],"type"=>"lock"));
						}
						$this->obj->DB_update_all("member","`lock_info`='".$_POST['lock_info']."',`status`='2'","`uid`='".$_POST['uid']."'");
					}

				}
				unset($_POST['com_update']);
				$ratingid = (int)$_POST['ratingid'];
				unset($_POST['ratingid']);
				$post['uid']=$_POST['uid'];
				$post['password']=$_POST['password'];
				$post['email']=$_POST['email'];
				$post['moblie']=$_POST['moblie'];
				$post['status']=$_POST['status'];
				$post['address']=$_POST['address'];
				if(trim($post['password'])){
					$nid = $this->uc_edit_pw($post,1,"index.php?m=com_member");
				} 
				$linkphone=array();
				if($_POST['phoneone']){
					$linkphone[]=$_POST['phoneone'];
				}
				if($_POST['phonetwo']){
					$linkphone[]=$_POST['phonetwo'];
				} 
				if($_POST['phonethree']){
					$linkphone[]=$_POST['phonethree'];
				} 
				$_POST['linkphone']=pylode('-',$linkphone); 
				$value.="`address`='".$_POST['address']."',";
				$value.="`name`='".$_POST['name']."',";
				$value.="`hy`='".$_POST['hy']."',";
				$value.="`pr`='".$_POST['pr']."',";
				$value.="`provinceid`='".$_POST['provinceid']."',";
				$value.="`cityid`='".$_POST['cityid']."',";
				$value.="`mun`='".$_POST['mun']."',";
				$value.="`linkphone`='".$_POST['linkphone']."',";
				$value.="`linktel`='".$_POST['moblie']."',";
				$value.="`money`='".$_POST['money']."',";
				$value.="`zip`='".$_POST['zip']."',";
				$value.="`linkman`='".$_POST['linkman']."',";
				$value.="`linkjob`='".$_POST['linkjob']."',";
				$value.="`linkqq`='".$_POST['linkqq']."',";
				$value.="`website`='".$_POST['website']."',";
				$content=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),html_entity_decode($_POST['content'],ENT_QUOTES,"GB2312"));
				$value.="`content`='".$content."',";
				$value.="`busstops`='".$_POST['busstops']."',";
				$value.="`admin_remark`='".$_POST['admin_remark']."',";
				$value.="`linkmail`='".$_POST['email']."'";

				$this->obj->DB_update_all("company",$value,"`uid`='".$_POST['uid']."'");
				$this->obj->DB_update_all("member","`email`='".$_POST['email']."',`moblie`='".$_POST['moblie']."'","`uid`='".$_POST['uid']."'");
				$rat_arr = @explode("+",$rating_name);
				$statis = $this->obj->DB_select_once("company_statis","`uid`='".$_POST['uid']."'");
				if($ratingid != $statis['rating']){
					$newrating=$this->obj->DB_select_once("company_rating","`id`='".$ratingid."'","`name`");
					$rat_value=$this->rating_info($ratingid);
					$this->obj->DB_update_all("company_statis",$rat_value,"`uid`='".$_POST['uid']."'"); 
					$this->admin_log("企业会员(ID".$_POST['uid'].")更新为【".$newrating['name']."】"); 
				}else{
					if($_POST['vip_etime']){
						$value3.="`vip_etime`='".strtotime($_POST['vip_etime'])."',";
					}else{
						$value3.="`vip_etime`='0',";
					}
					$value3.="`job_num`='".$_POST['job_num']."',";
					$value3.="`down_resume`='".$_POST['down_resume']."',";
					$value3.="`editjob_num`='".$_POST['editjob_num']."',";
					$value3.="`invite_resume`='".$_POST['invite_resume']."',";
					$value3.="`breakjob_num`='".$_POST['breakjob_num']."',";
					$value3.="`part_num`='".$_POST['part_num']."',";
					$value3.="`editpart_num`='".$_POST['editpart_num']."',";
					$value3.="`breakpart_num`='".$_POST['breakpart_num']."',";
					$value3.="`zph_num`='".$_POST['zph_num']."',";
					$this->obj->DB_update_all("company_statis",$value3,"`uid`='".$_POST['uid']."'");
				}
				$value2.="`com_name`='".$_POST['name']."',";
				$value2.="`pr`='".$_POST['pr']."',";
				$value2.="`mun`='".$_POST['mun']."',";
				$value2.="`com_provinceid`='".$_POST['provinceid']."',";
				$value2.="`rating`='".$rat_arr[0]."',";
				$value2.="`r_status`='".$_POST['status']."'";
				$this->obj->DB_update_all("company_job",$value2,"`uid`='".$_POST['uid']."'");
				
				if($_POST['name']!=$company['name']){
					$this->obj->update_once("partjob",array("com_name"=>$_POST['name']),array("uid"=>$_POST['uid']));
					$this->obj->update_once("userid_job",array("com_name"=>$_POST['name']),array("com_id"=>$_POST['uid']));
					$this->obj->update_once("fav_job",array("com_name"=>$_POST['name']),array("com_id"=>$_POST['uid']));
					$this->obj->update_once("report",array("r_name"=>$_POST['name']),array("c_uid"=>$_POST['uid']));
					$this->obj->update_once("blacklist",array("com_name"=>$_POST['name']),array("c_uid"=>$_POST['uid']));
					$this->obj->update_once("msg",array("com_name"=>$_POST['name']),array("job_uid"=>$_POST['uid']));
					$this->obj->update_once("hotjob",array("username"=>$_POST['name']),array("uid"=>$_POST['uid']));
				}
				$lasturl=str_replace("&amp;","&",$_POST['lasturl']);
				$this->ACT_layer_msg( "企业会员(ID:".$_POST['uid'].")修改成功！",9,$lasturl,2,1);
			}
		}
		$this->yuntpl(array('admin/admin_member_comedit'));
	}
	function rating_action(){
		$ratingid = (int)$_POST['ratingid'];
		$statis = $this->obj->DB_select_once("company_statis","`uid`='".$_POST['uid']."'");
		if($ratingid!=$statis['rating']){
			if(is_array($statis) && !empty($statis)){
				$value=$this->rating_info($ratingid);
				$this->obj->DB_update_all("company_statis",$value,"`uid`='".$_POST['uid']."'");
				$newrating=$this->obj->DB_select_once("company_rating","`id`='".$ratingid."'","`name`");
				$this->admin_log("企业会员(ID".$_POST['uid'].")更新为【".$newrating['name']."】"); 
			}else{
				$value="`uid`='".$_POST['uid']."',";
				$value.=$this->rating_info($ratingid);
				$this->obj->DB_insert_once("company_statis",$value);
				$this->admin_log("企业会员(ID".$_POST['uid'].")添加会员等级");
			}
			echo "1";die;
		}else{
			echo 0;die;
		}
	}
	function add_action(){
		$rating_list = $this->obj->DB_select_all("company_rating","`category`=1");
		if($_POST['submit']){
			extract($_POST);
			if($username==""||strlen($username)<2||strlen($username)>15){
				$data['msg']= "会员名不能为空或不符合要求！";
				$data['type']='8';
			}elseif($password==""||strlen($username)<2||strlen($username)>15){
				$data['msg']= "密码不能为空或不符合要求！";
				$data['type']='8';
			}elseif($email==""){
				$data['msg']= "email不能为空！";
				$data['type']='8';
			}else{
				if($this->config['sy_uc_type']=="uc_center"){
					$this->uc_open();
					$user = uc_get_user($username);
				}else{
					$user = $this->obj->DB_select_once("member","`username`='$username' OR `email`='$email'");
				}
				if(is_array($user))
				{
					$data['msg']= "用户名或邮箱已存在！";
					$data['type']='8';
				}else{
					$ip = fun_ip_get();
					$time = time();
					if($this->config['sy_uc_type']=="uc_center")
					{
						$uid=uc_user_register($_POST['username'],$_POST['password'],$_POST['email']);
						if($uid<0)
						{
							$this->obj->get_admin_msg("index.php?m=com_member&c=add","该邮箱已存在！");
						}else{
							list($uid,$username,$email,$password,$salt)=uc_get_user($username);
							$value = "`username`='$username',`password`='$password',`email`='$email',`usertype`='2',`address`='$address',`status`='$status',`salt`='$salt',`moblie`='$moblie',`reg_date`='$time',`reg_ip`='$ip'";
						}
					}else{
						$salt = substr(uniqid(rand()), -6);
						$pass = md5(md5($password).$salt);
						$value = "`username`='$username',`password`='$pass',`email`='$email',`usertype`='2',`address`='$address',`status`='$status',`salt`='$salt',`moblie`='$moblie',`reg_date`='$time',`reg_ip`='$ip'";
					}
					$nid = $this->obj->DB_insert_once("member",$value);
					$new_info = $this->obj->DB_select_once("member","`username`='$username'");
					$uid = $new_info['uid'];
					if($uid>0)
					{
						$this->obj->DB_insert_once("company","`uid`='$uid',`name`='$name',`linktel`='$moblie',`linkmail`='$email',`address`='$address'");
						$rat_arr = @explode("+",$rating_name);
						$value = "`uid`='$uid',";
						$value.=$this->rating_info($rat_arr[0]);
						$this->obj->DB_insert_once("company_statis",$value);
						$this->obj->DB_insert_once("friend_info","`uid`='$uid',`nickname`='$username',`usertype`='2'");
						$data['msg']="会员(ID:".$uid.")添加成功";
						$data['type']='9';
					}
				}
			}
			if($_POST['type']){
				echo "<script type='text/javascript'>window.location.href='index.php?m=admin_company_job&c=show&uid=".$nid."'</script>";die;
			}else{
				$this->ACT_layer_msg($data['msg'],$data['type'],"index.php?m=admin_company",2,1);
			}

		}
		$this->yunset("get_info",$_GET);
		$this->yunset("rating_list",$rating_list);
		$this->yuntpl(array('admin/admin_member_comadd'));
	}
	function rating_info($id){
		if(!$id){
			$id =$this->config['com_rating'];
		}
		$row = $this->obj->DB_select_once("company_rating","`id`='".$id."'");
		$value="`rating`='$id',";
		$value.="`rating_name`='".$row['name']."',";
		$value.="`job_num`='".$row['job_num']."',";
		$value.="`down_resume`='".$row['resume']."',";
		$value.="`invite_resume`='".$row['interview']."',";
		$value.="`editjob_num`='".$row['editjob_num']."',";
		$value.="`breakjob_num`='".$row['breakjob_num']."',";
		$value.="`part_num`='".$row['part_num']."',";
		$value.="`editpart_num`='".$row['editpart_num']."',";
		$value.="`breakpart_num`='".$row['breakpart_num']."',";
		$value.="`zph_num`='".$row['zph_num']."',";
		$value.="`rating_type`='".$row['type']."',";
		if($row['service_time']>0)
		{
			$time=time()+86400*$row['service_time'];
		}else{
			$time=0;
		}
		$value.="`vip_etime`='".$time."',";
		$value.="`vip_stime`='".time()."'"; 
		return $value;
	}
	function getstatis_action(){
		if($_POST['uid']){
			$rating	= $this->obj->DB_select_once("company_statis","`uid`='".intval($_POST['uid'])."'");
			if($rating['vip_etime']>0){
				$rating['vipetime'] = date("Y-m-d",$rating['vip_etime']);
			}else{
				$rating['vipetime'] = '不限';
			}

			echo json_encode(yun_iconv('gbk','utf-8',$rating));
		}
	}
	function getrating_action(){
		if($_POST['id']){
			$rating	= $this->obj->DB_select_once("company_rating","`id`='".intval($_POST['id'])."' and `category`='1'");
			if($rating['service_time']>0){
				$rating['oldetime'] = time()+$rating['service_time']*86400;
				$rating['vipetime'] = date("Y-m-d",(time()+$rating['service_time']*86400));
			}else{
				$rating['oldetime'] = 0;
				$rating['vipetime'] = '不限';
			}
			echo json_encode(yun_iconv('gbk','utf-8',$rating));
		}
	}
	function uprating_action(){

		 if($_POST['ratuid']){

			$uid = intval($_POST['ratuid']);
			$statis = $this->obj->DB_select_once("company_statis","`uid`='".$uid."'");

			unset($_POST['ratuid']);unset($_POST['pytoken']);
			if((int)$_POST['addday']>0){
				if((int)$_POST['oldetime']>0){
					$_POST['vip_etime'] = intval($_POST['oldetime'])+intval($_POST['addday'])*86400;
				}else{
					$_POST['vip_etime'] = time()+intval($_POST['addday'])*86400;
				}
			}else{
				$_POST['vip_etime'] = intval($_POST['oldetime']);
			}
			unset($_POST['addday']);
			unset($_POST['oldetime']);

			foreach($_POST as $key=>$value){

				$statisValue[] = "`$key`='$value'";
			}
			$statisSqlValue = @implode(',',$statisValue);
			$ratinginfo = $this->obj->DB_select_once("company_rating","`id`='".$_POST['rating']."'","`type`");
			$statisSqlValue.=",`rating_type`='".$ratinginfo['type']."'";
			if($statis['rating'] != $_POST['rating']){
				$statisSqlValue.=",`vip_stime`='".time()."'";
				$this->obj->DB_update_all("company_job","`rating`='".$_POST['rating']."'","`uid`='".$uid."'");
			}
			$id = $this->obj->DB_update_all("company_statis",$statisSqlValue,"`uid`='".$uid."'");
			$id?$this->ACT_layer_msg("企业会员等级(ID:".$uid.")修改成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("修改失败！",8,$_SERVER['HTTP_REFERER']);
		}else{
			$this->ACT_layer_msg( "非法操作！",8,$_SERVER['HTTP_REFERER']);
		}

	}
	function recommend_action(){
		$nid=$this->obj->DB_update_all("company","`".$_GET['type']."`='".$_GET['rec']."'","`uid`='".$_GET['id']."'");
		$this->admin_log("知名企业(ID:".$_GET['id'].")设置成功");
		echo $nid?1:0;die;
	}
	function del_action(){
		$this->check_token();
	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($del){
				$del_array=array("member","company","company_job","company_cert","company_msg","company_news","company_order","company_product","company_show","banner","company_statis","friend_info","friend_state","question","attention","hotjob","invoice_record","special_com","zhaopinhui_com","partjob","answer","answer_review","evaluate_log");

	    		if(is_array($del)){
	    			$layer_type=1;
	    			$uids = @implode(",",$del);
	    			foreach($del as $k=>$v){
	    				delfiledir("../data/upload/tel/".intval($v));
	    			}
				    $company=$this->obj->DB_select_all("company","`uid` in (".$uids.") and `logo`!=''","logo,firmpic");
				    if(is_array($company)){
				    	foreach($company as $v){
				    		unlink_pic(".".$v['logo']);
				    		unlink_pic(".".$v['firmpic']);
				    	}
				    }
		    	    $cert=$this->obj->DB_select_all("company_cert","`uid` in (".$uids.") and `type`='3'","check");
		    	    if(is_array($cert)){
				    	foreach($cert as $v){
				    		unlink_pic("../".$v['check']);
				    	}
				    }
		    	    $product=$this->obj->DB_select_all("company_product","`uid` in (".$uids.")","pic");
		    	    if(is_array($product)){
		    	    	foreach($product as $val){
		    	    		unlink_pic("../".$val['pic']);
		    	    	}
		    	    }
		    	    $show=$this->obj->DB_select_all("company_show","`uid` in (".$uids.")","picurl");
		    	    if(is_array($show)){
		    	    	foreach($show as $val){
		    	    		unlink_pic("../".$val['picurl']);
		    	    	}
		    	    }
		    	    $uhotjob=$this->obj->DB_select_all("hotjob","`uid` in (".$uids.")","hot_pic");
		    	    if(is_array($uhotjob)){
		    	    	foreach($uhotjob as $val){
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
		    	  	$banner=$this->obj->DB_select_all("banner","`uid` in (".$uids.")","pic");
		    	    if(is_array($banner)){
		    	    	foreach($banner as $val)
		    	    	{
		    	    		unlink_pic($val['pic']);
		    	    	}
		    	    }
		    	    $friend_pic = $this->obj->DB_select_all("friend_info","`uid` in (".$uids.") and `pic`!=''","pic,pic_big");
		    		if(is_array($friend_pic))
		    		{
		    	    	foreach($friend_pic as $val)
		    	    	{
		    	    		unlink_pic($val['pic']);
		    	    		unlink_pic($val['pic_big']);
		    	    	}
		    		}

					foreach($del_array as $value)
					{
						$this->obj->DB_delete_all($value,"`uid` in (".$uids.")","");
					}
					$this->obj->DB_delete_all("email_msg","`uid` in (".$uids.") or `cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("company_msg","`cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("talent_pool","`cuid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("user_entrust_record","`comid` in (".$uids.")"," ");
					$this->obj->DB_delete_all("ad_order","`comid` in (".$uids.")"," ");
		    	    $this->obj->DB_delete_all("company_pay","`com_id` in (".$uids.")"," ");
					$this->obj->DB_delete_all("atn","`uid` in (".$uids.") or `sc_uid` in (".$uids.")","");
					$this->obj->DB_delete_all("look_resume","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("fav_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("userid_msg","`fid` in (".$uids.")","");
					$this->obj->DB_delete_all("userid_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("look_job","`com_id` in (".$uids.")","");
					$this->obj->DB_delete_all("message","`fa_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("friend_reply","`fid` in (".$uids.") or `uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("friend","`uid` in (".$uids.") or `nid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("friend_foot","`uid` in (".$uids.") or `fid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("friend_message","`uid`='".$del."' or `fid`='".$del."'","");
		    	    $this->obj->DB_delete_all("msg","`job_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("blacklist","`c_uid` in (".$uids.")","");
		    	    $this->obj->DB_delete_all("rebates","`job_uid` in (".$uids.") or `uid` in (".$uids.")"," ");
		    	    $this->obj->DB_delete_all("report","`p_uid` in ($uids) or `c_uid` in ($uids)","");
					$this->obj->DB_delete_all("part_apply","`comid` in (".$uids.")","");
					$this->obj->DB_delete_all("part_collect","`comid` in (".$uids.")","");
					$this->obj->DB_delete_all("member_log","`uid` in (".$uids.")","");
					$this->obj->DB_delete_all("down_resume","`comid` in (".$uids.")","");
		    	}else{
		    		$layer_type=0;
					$uids=$del = intval($del);
					$uids=$del;
		    		$friend_pic = $this->obj->DB_select_once("friend_info","`uid`='".$del."' and `pic`!=''","pic,pic_big");
		    		if(is_array($friend_pic)){
		    			unlink_pic($friend_pic['pic']);
		    			unlink_pic($friend_pic['pic_big']);
		    		}
		    		delfiledir("../data/upload/tel/".$del);
				    $company=$this->obj->DB_select_once("company","`uid`='".$del."' and `logo`!=''","logo,firmpic");
				    unlink_pic(".".$company['logo']);
				    unlink_pic(".".$company['firmpic']);
		    	    $cert=$this->obj->DB_select_once("company_cert","`uid`='".$del."' and `type`='3'","check");
		    	    unlink_pic("../".$cert['check']);
		    	    $product=$this->obj->DB_select_all("company_product","`uid`='".$del."'","pic");
		    	    if(is_array($product))
		    	    {
		    	    	foreach($product as $v)
		    	    	{
		    	    		unlink_pic("../".$v['pic']);
		    	    	}
		    	    }
		    	    $show=$this->obj->DB_select_all("company_show","`uid`='".$del."'","picurl");
		    	    if(is_array($show))
		    	    {
		    	    	foreach($show as $v)
		    	    	{
		    	    		unlink_pic("../".$v['picurl']);
		    	    	}
		    	    }
			    	$uhotjob=$this->obj->DB_select_all("hotjob","`uid`='".$del."'","hot_pic");
		    	    if(is_array($uhotjob))
		    	    {
		    	    	foreach($uhotjob as $val)
		    	    	{
		    	    		unlink_pic("../".$val['hot_pic']);
		    	    	}
		    	    }
		    	    $banner=$this->obj->DB_select_once("banner","`uid`='".$del."'","pic");
					unlink_pic($banner['pic']);
					foreach($del_array as $value){
						$this->obj->DB_delete_all($value,"`uid`='".$del."'","");
					}
					$this->obj->DB_delete_all("email_msg","`uid`='".$del."' or `cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("company_msg","`cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("talent_pool","`cuid`='".$del."'"," ");
					$this->obj->DB_delete_all("user_entrust_record","`comid`='".$del."'"," ");
					$this->obj->DB_delete_all("ad_order","`comid`='".$del."'"," ");
					$this->obj->DB_delete_all("company_pay","`com_id`='".$del."'"," ");
		    	    $this->obj->DB_delete_all("atn","`uid`='".$del."' or `sc_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("look_resume","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("look_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("fav_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("userid_msg","`fid`='".$del."'","");
		    	    $this->obj->DB_delete_all("userid_job","`com_id`='".$del."'","");
		    	    $this->obj->DB_delete_all("message","`fa_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("friend","`uid`='".$del."' or `nid`='".$del."'","");
		    	    $this->obj->DB_delete_all("friend_foot","`uid`='".$del."' or `fid`='".$del."'","");
		    	    $this->obj->DB_delete_all("friend_message","`uid`='".$del."' or `fid`='".$del."'","");
		    	    $this->obj->DB_delete_all("friend_reply","`fid`='".$del."' or `uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("msg","`job_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("blacklist","`c_uid`='".$del."'","");
		    	    $this->obj->DB_delete_all("rebates","`job_uid`='".$del."' or `uid` ='".$del."'"," ");
		    	    $this->obj->DB_delete_all("report","`p_uid`='".$del."' or `c_uid`='".$del."'","");
					$this->obj->DB_delete_all("part_apply","`comid` ='".$del."'","");
					$this->obj->DB_delete_all("part_collect","`comid` ='".$del."'","");
					$this->obj->DB_delete_all("member_log","`uid` ='".$del."'","");
					$this->obj->DB_delete_all("down_resume","`comid` ='".$del."'",""); 
		    	}
	    		$this->layer_msg( "公司(ID:".$uids.")删除成功！",9,$layer_type,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的公司！",8,1);
	    	}
	    }
	}
	function lockinfo_action(){
		$userinfo = $this->obj->DB_select_once("member","`uid`=".$_POST['uid'],"`lock_info`");
		echo $userinfo['lock_info'];die;
	}
	function lock_action(){
		$_POST['uid']=intval($_POST['uid']);
		if(($_POST['status']==2 || $_POST['status']==3)&&$_POST['lock_info']==''){
			$this->ACT_layer_msg("请填写锁定说明！",8);
		}
		if($_POST['status']==3 &&$_POST['statusip']){
			$ip=$this->config['sy_bannedip']?$this->config['sy_bannedip']."|".$_POST['statusip']:$_POST['statusip'];
			$this->obj->DB_update_all("admin_config","`config`='".$ip."'","`name`='sy_bannedip'");
			$this->web_config();
			$_POST['status']==2;
		}

		$email=$this->obj->DB_select_once("company","`uid`='".$_POST['uid']."'","`linkmail`,`name`");
		$this->obj->DB_update_all("company_job","`r_status`='".$_POST['status']."'","`uid`='".$_POST['uid']."'");
		$this->obj->DB_update_all("company","`r_status`='".$_POST['status']."'","`uid`='".$_POST['uid']."'");
		$id=$this->obj->DB_update_all("member","`status`='".$_POST['status']."',`lock_info`='".$_POST['lock_info']."'","`uid`='".$_POST['uid']."'");
		if($_POST['status']=='2'){
			$this->send_msg_email(array("email"=>$email['linkmail'],"uid"=>$_POST['uid'],"name"=>$email['name'],"lock_info"=>$_POST['lock_info'],"type"=>"lock"));
		} 
		
		$id?$this->ACT_layer_msg("企业会员(ID:".$_POST['uid'].")锁定设置成功！",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg( "设置失败！",8,$_SERVER['HTTP_REFERER']);
	}
	function status_action(){
		 extract($_POST);
		 $id = @explode(",",$uid);
		 $member=$this->obj->DB_select_all("member","`uid` in (".$uid.")","`email`,`uid`");
		 $smtp = $this->email_set();
		 if(is_array($member)&&$member){
			 $company=$this->obj->DB_select_all("company","`uid` in (".$uid.")","`name`,`uid`");
			 $info=array();
			foreach($company as $val){
				$info[$val['uid']]=$val['name'];
			}
			foreach($member as $v){
				$idlist[] =$v['uid'];
				if($this->config['sy_smtpserver']!="" && $this->config['sy_smtpemail']!="" && $this->config['sy_smtpuser']!=""){
					$this->send_msg_email(array("uid"=>$v['uid'],"name"=>$info[$v['uid']],"email"=>$v['email'],"status_info"=>$statusbody,"date"=>date("Y-m-d H:i:s"),"type"=>"userstatus"));
				}
			}
			if(trim($statusbody)){
				$lock_info=$statusbody;
			}
			$aid = @implode(",",$idlist);
			$id=$this->obj->DB_update_all("member","`status`='".$status."',`lock_info`='".$lock_info."'","`uid` IN (".$aid.")");
			if($id){
				if($status=="1"){
					$rstatus='1';
				}else{
					$rstatus='2';
				}
				$this->obj->DB_update_all("company","`r_status`='".$rstatus."'","`uid` IN (".$aid.")");
				$this->obj->DB_update_all("company_job","`r_status`='".$rstatus."'","`uid` IN (".$aid.")");
				$this->ACT_layer_msg("企业会员审核(ID:".$aid.")设置成功！",9,$_SERVER['HTTP_REFERER'],2,1);
			}else{
				$this->ACT_layer_msg("审核设置失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg( "非法操作！",8,$_SERVER['HTTP_REFERER']);
		}
	}

	function hotjobinfo_action(){
		if($_GET['id']){
			$hotjob=$this->obj->DB_select_once("hotjob","`uid`='".(int)$_GET['id']."'");
		}else if($_GET['uid']){
			$row = $this->obj->DB_select_alls("company","company_statis","a.`uid`='".(int)$_GET['uid']."' and b.`uid`='".(int)$_GET['uid']."'","a.`content`,a.`name` as username,b.`rating` as rating_id,b.`rating_name` as rating,a.`uid`,a.`logo` as hot_pic");
			$row=$row[0];
			$row['content']=@explode(' ',trim(strip_tags($row['content'])));
			if(is_array($row['content'])&&$row['content']){
				foreach($row['content'] as $val){
					$row['beizhu'].=trim($val);
				}
			}else{
				$row['beizhu']=$row['content'];
			}
			$hotjob=$row;
			$hotjob['time_start']=time();
		}
		$this->yunset("hotjob",$hotjob);
		$this->yuntpl(array('admin/admin_hotjob_info'));
	}

	function save_action(){
		extract($_POST);
		if(is_uploaded_file($_FILES['hot_pic']['tmp_name'])){
			$upload=$this->upload_pic("../data/upload/hotpic/");
			$pictures=$upload->picture($_FILES['hot_pic']);
			$pic = str_replace("../data/upload","/data/upload",$pictures);
		}else{
			if($_POST['hotad']){
				$defpic=".".$defpic;
				$url=@explode("/",$defpic);
				$url2=str_replace($url[4],date("Ymd"),$defpic);
				$name=@explode(".",$url[5]);
				$url2=str_replace($name[0],time(),$url2);
				if(!file_exists('../data/upload/company/'.date("Ymd")))
				{
					mkdir ('../data/upload/company/'.date("Ymd"));
				}
				copy($defpic,$url2);
				$pic = str_replace("../data/upload","data/upload",$url2);
			}
		}
		if($_POST['hotad']){ 
			$start = strtotime($time_start);
			$end = strtotime($time_end);
			$nid=$this->obj->DB_insert_once("hotjob","`uid`='$uid',`username`='$username',`sort`='$sort',`rating_id`='$rating_id',`rating`='$rating',`hot_pic`='$pic',`service_price`='$service_price',`beizhu`='$beizhu',`time_start`='$start',`time_end`='$end'");
			$this->obj->DB_update_all("company","`hottime`='".$end."',`rec`='1'","`uid`='".$uid."'");
			$this->ACT_layer_msg("名企招聘(ID:".$nid.")设定成功！",9,"index.php?m=admin_company",2,1);
		}elseif($_POST['hotup']){
			$start = strtotime($time_start);
			$end = strtotime($time_end);
			$value = "`service_price`='$service_price',`time_start`='$start',`time_end`='$end',`beizhu`='$beizhu',`sort`='$sort'";
			if($pic!=""){
				$hot=$this->obj->DB_select_once("hotjob","`id`='$id'");
				unlink_pic("../".$hot['hot_pic']);
				$value.=",`hot_pic`='$pic'";
			}
			$this->obj->DB_update_all("hotjob",$value,"`id`='$id'");
			$this->obj->DB_update_all("company","`hottime`='".$end."'","`uid`='".$uid."'");
			$this->ACT_layer_msg("名企招聘(ID:".$id.")修改成功！",9,"index.php?m=admin_company",2,1);
		}
		$this->yuntpl(array('admin/admin_hotjob_add'));
	}
	function delhot_action(){
		$this->check_token();
	    if(isset($_GET['id'])){
	    	$hot=$this->obj->DB_select_once("hotjob","`uid`='".$_GET['id']."'");
			unlink_pic("../".$hot['hot_pic']);
			$result=$this->obj->DB_delete_all("hotjob","`uid`='".$_GET['id']."'" );
			if($result){
				$this->obj->DB_update_all("company","`hottime`='',`rec`='0'","`uid`='".$hot['uid']."'");
				$this->layer_msg('名企招聘(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
			}
		}
	}
	function changeorder_action(){
		if($_POST['uid']){
			if(!$_POST['order']){
				$_POST['order']='0';
			}
			$this->obj->DB_update_all("company","`order`='".$_POST['order']."'","`uid`='".$_POST['uid']."'");
			$this->admin_log("公司(ID:".$_POST['uid'].")排序设置成功");
		}
		die;
	}
	function Imitate_action(){
		extract($_GET);
		$user_info = $this->obj->DB_select_once("member","`uid`='".$uid."'");
		$this->unset_cookie();
		$this->add_cookie($user_info['uid'],$user_info['username'],$user_info['salt'],$user_info['email'],$user_info['password'],$user_info['usertype'],1,$user_info['did']);

		header('Location: '.$this->config['sy_weburl'].'/member');
	}
	function xls_action()
	{
		if($_POST['where'])
		{
			$gettype=$_POST['type'];
			$_POST['where']=str_replace(array("[","]","an d","\&acute;","\\"),array("(",")","and","'",""),$_POST['where']);
			if(in_array("lastdate",$_POST['type']) || in_array("rating",$_POST['type']) || in_array("vip_stime",$_POST['type']))
			{
				foreach($_POST['type'] as $v)
				{
					if($v=="lastdate"){
						$type[]="lastupdate";
					}elseif($v!="rating" && $v!="vip_stime"){
						$type[]=$v;
					}
				}
				$_POST['type']=$type;
			}
			$select=@implode(",",$_POST['type']);
			if(strstr($select,"rating") && strstr($select,"uid")==false)
			{
				$select=$select.",uid";
			}
			$list=$this->obj->DB_select_all("company","uid in (".$_POST['uid'].") and ".$_POST['where'],$select);
			if(!empty($list))
			{
				if(in_array("rating",$gettype))
				{
					foreach($list as $v)
					{
						$uid[]=$v['uid'];
					}
					$rating=$this->obj->DB_select_all("company_statis","uid in (".@implode(",",$uid).")","uid,rating_name,vip_stime");
					foreach($list as $k=>$v)
					{
						foreach($rating as $val)
						{
							if($v['uid']==$val['uid'])
							{
								$list[$k]['rating']=$val['rating_name'];
								$list[$k]['vip_stime']=$val['vip_stime'];
							}
						}
					}
				}
				$this->yunset("list",$list);
				$this->yunset($this->MODEL('cache')->GetCache(array('city','hy','com')));
				$this->yunset("type",$gettype);
				$this->yuntpl(array('admin/admin_company_xls'));
				$this->admin_log("导出公司信息");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=company.xls");
			}
		}
	}
	function check_username_action(){
		$username=iconv("utf-8", "gbk", $_POST['username']);
		$member=$this->obj->DB_select_once("member","`username`='".$username."'","`uid`");
		echo $member['uid'];die;
	}

	function checksitedid_action(){
		if($_POST['uid']){
			$uids=@explode(',',$_POST['uid']);
			$uid = pylode(',',$uids);
			if($uid){
				$siteDomain = $this->MODEL('site');
				$Table = array('member','company','company_statis','company_job','company_cert','company_news','company_order','company_product','friend_info','invoice_record','partjob');
				$siteDomain->UpDid(array('report'),$_POST['did'],"`p_uid` IN (".$uid.")");
				$siteDomain->UpDid(array('userid_msg'),$_POST['did'],"`fid` IN (".$uid.")");
				$siteDomain->UpDid(array('down_resume','company_pay'),$_POST['did'],"`com_id` IN (".$uid.")");
				$siteDomain->UpDid(array('look_resume','ad_order'),$_POST['did'],"`comid` IN (".$uid.")");
				$siteDomain->UpDid($Table,$_POST['did'],"`uid` IN (".$uid.")");
				$this->ACT_layer_msg( "会员(ID:".$_POST['uid'].")分配站点成功！",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("请正确选择需分配用户！",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg( "参数不全请重试！",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function saveusername_action()
	{
		if($_POST['username']){
			$username=yun_iconv("utf-8","gbk",$_POST['username']);
			$M=$this->MODEL("userinfo");
			$num=$M->GetMemberNum(array("username"=>$username));
			if($num>0){
				echo 1;die;
			}else{
				$M->UpdateMember(array("username"=>$username),array("uid"=>$_POST['uid']));
				$Friend=$this->MODEL("friend");				
			    $Friend->SaveFriendInfo(array("nickname"=>$username),array("uid"=>$_POST['uid']));
				echo 0;die;
			}
		}
	}
}
?>