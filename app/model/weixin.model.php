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
class weixin_model extends model{
   
  
	function myMsg($wxid='')
	{
		$userBind = $this->isBind($wxid);
		
		if($userBind['bindtype']=='1')
		{
			$Return['centerStr'] = "<Content><![CDATA[".iconv('gbk','utf-8',"������û���µ���Ϣ��")."]]></Content>";
			
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}
	

	function Audition($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Aud = $this->DB_select_all("userid_msg","`uid`='".$userBind['uid']."' ORDER BY `datetime` DESC limit 5");
			
			if(is_array($Aud) && !empty($Aud))
			{
				foreach($Aud as $key=>$value)
				{
					$Info['title'] = iconv('gbk','utf-8',"��".$value['fname']."����������\n����ʱ�䣺".date('Y-m-d H:i:s'));
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_weburl']."/wap/member/index.php?c=invite";
					$List[]        = $Info;
				}
				$Msg['title'] = iconv('gbk','utf-8','��������');
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_weburl']."/wap/member/index.php?c=invite";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA['.iconv('gbk','utf-8','���������������').']]></Content>';
				$Return['MsgType']   = 'text';
			}
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}

	function ApplyJob($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{
			$Apply = $this->DB_select_all("userid_job","`com_id`='".$userBind['uid']."' AND `is_browse`='1' ORDER BY `datetime` DESC limit 5");
			
			if(is_array($Apply) && !empty($Apply))
			{
				foreach($Apply as $key=>$value)
				{
					$uid[] = $value['uid'];
				}

				$userList = $this->DB_select_all("resume","`uid` IN (".@implode(',',$uid).")","`uid`,`name`,`edu`,`exp`");
				if(is_array($userList)){
					
					foreach($userList as $key=>$value)
					{
						$resumeList[$value['uid']] = $value;
					}
				}
				include(PLUS_PATH."/user.cache.php");
				foreach($Apply as $key=>$value)
				{
					$Info['title'] = iconv('gbk','utf-8',"��".$resumeList[$value['uid']]['name']."��".$userclass_name[$resumeList[$value['uid']]['edu']]."/".$userclass_name[$resumeList[$value['uid']]['exp']]."��������\n����������ְλ��".$value['job_name']."\nͶ��һ�ݼ���\nͶ��ʱ�䣺".date('Y-m-d H:i',$value['datetime']));
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_weburl']."/wap/member/index.php?c=invite";
					$List[]        = $Info;
				}
				$Msg['title'] = iconv('gbk','utf-8','����Ͷ��');
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_weburl']."/wap/member/index.php?c=invite";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA['.iconv('gbk','utf-8','������޼���Ͷ��').']]></Content>';
				$Return['MsgType']   = 'text';
			}
			
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}

	function PartApply($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{
			$Apply = $this->DB_select_all("part_apply","`comid`='".$userBind['uid']."' AND `status`='1' ORDER BY `ctime` DESC limit 5");
			
			if(is_array($Apply) && !empty($Apply))
			{
				foreach($Apply as $key=>$value)
				{
					$uid[] = $value['uid'];
					$jobid[] = $value['jobid'];
				}

				$partJob = $this->DB_select_all("partjob","`uid`='".$userBind['uid']."' AND `id` IN (".@implode(',',$jobid).")","`id`,`name`");
				if(is_array($partJob)){
					
					foreach($partJob as $key=>$value)
					{
						$jobname[$value['id']] = $value['name'];
					}
				}

				$userList = $this->DB_select_all("resume","`uid` IN (".@implode(',',$uid).")","`uid`,`name`,`edu`,`exp`");
				if(is_array($userList)){
					
					foreach($userList as $key=>$value)
					{
						$resumeList[$value['uid']] = $value;
					}
				}
				include(PLUS_PATH."/user.cache.php");
				foreach($Apply as $key=>$value)
				{
					$Info['title'] = iconv('gbk','utf-8',"��".$resumeList[$value['uid']]['name']."��".$userclass_name[$resumeList[$value['uid']]['edu']]."/".$userclass_name[$resumeList[$value['uid']]['exp']]."��������\n������ְ��".$jobname[$value['jobid']]."\n����ʱ�䣺".date('Y-m-d H:i',$value['ctime']));
					$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
					$Info['url']   = $this->config['sy_weburl']."/wap/member/index.php?c=partapply";
					$List[]        = $Info;
				}
				$Msg['title'] = iconv('gbk','utf-8','��ְ����');
				$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
				$Msg['url'] = $this->config['sy_weburl']."/wap/member/index.php?c=partapply";
				$Return['centerStr'] = $this->Handle($List,$Msg);
				$Return['MsgType']   = 'news';

			}else{

				$Return['centerStr'] ='<Content><![CDATA['.iconv('gbk','utf-8','������ޱ���').']]></Content>';
				$Return['MsgType']   = 'text';
			}
			
			return $Return;
		}else{
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}

	function lookResume($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Aud = $this->DB_select_all("look_resume","`uid`='".$userBind['uid']."'  ORDER BY `datetime`  DESC limit 5");
			if(is_array($Aud) && !empty($Aud))
			{
				
				foreach($Aud as $key=>$value)
				{
					$comid[] = $value['com_id'];
				}
				$comids =pylode(',',$comid);
		
				if($comids){
					$comList = $this->DB_select_all('company','`uid` IN ('.$comids.')','`uid`,`name`');
					if(is_array($comList)){
						foreach($comList as $key=>$value)
						{
							$comname[$value['uid']] = $value['name'];
						}
					}
					foreach($Aud as $key=>$value)
					{
						$Info['title'] = iconv('gbk','utf-8', "�鿴��ҵ����".$comname[$value['com_id']]."��\n�鿴ʱ�䣺".date('Y-m-d H:i:s',$value['datetime']));
						$Info['pic']   = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
						$Info['url']   = $this->config['sy_weburl']."/wap/member/index.php?c=look";
						$List[]        = $Info;
					}
					$Msg['title'] = iconv('gbk','utf-8','����鿴�ҵļ���');
					$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
					$Msg['url'] = $this->config['sy_weburl']."/wap/member/index.php?c=look";
					$Return['centerStr'] = $this->Handle($List,$Msg);
					$Return['MsgType']   = 'news';
				}else{
					$Return['centerStr']='<Content><![CDATA['.iconv('gbk','utf-8','�Ѿ��ܾ�û��˾�鿴���ļ����ˣ�').']]></Content>';
					$Return['MsgType']   = 'text';
				}
			}else{

				$Return['centerStr']='<Content><![CDATA['.iconv('gbk','utf-8','�Ѿ��ܾ�û��˾�鿴���ļ����ˣ�').']]></Content>';
				$Return['MsgType']   = 'text';
			}
			return $Return;

		}else{

			
			$Return['MsgType']   = 'text';
			$Return['centerStr'] = $userBind['cenetrTpl'];
			return $Return;
		}
	}

	function refResume($wxid='')
	{
		$userBind = $this->isBind($wxid);
		if($userBind['bindtype']=='1')
		{
			$Resume = $this->DB_select_num("resume_expect","`uid`='".$userBind['uid']."'");
			
			if($Resume>0)
			{
				$this->DB_update_all("resume_expect","`lastupdate`='".time()."'","`uid` = '".$userBind['uid']."'");
				$Return['centerStr']="<Content><![CDATA[".iconv('gbk','utf-8','����ˢ�³ɹ�\nˢ��ʱ��').":".date('Y-m-d H:i:s')."]]></Content>";

			}else{

				$Return['centerStr']='<Content><![CDATA['.iconv('gbk','utf-8','�����������ļ�����').']]></Content>';
				
			}
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
			
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}


	function refJob($wxid='')
	{
		$userBind = $this->isBind($wxid,2);
		if($userBind['bindtype']=='1')
		{

			$jobNum = $this->DB_select_num('company_job',"`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
			if($jobNum>0){
				
				$membeStatis = $this->DB_select_once('company_statis',"`uid`='".$userBind['uid']."'");
				$refIntegral = $this->config['integral_jobefresh']*$jobNum;
				
				if($membeStatis['rating_type']=='2'){
					if($membeStatis['vip_etime']>=time()){
						
						$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
						$msg = 'ְλˢ����ɣ����ι�ˢ��'.$jobNum."��ְλ��";
					}else{

						$useIntegral = 1;
					}
				}else{
					
					if(($membeStatis['vip_etime']<1 || $membeStatis['vip_etime']>=time()) && $membeStatis['breakjob_num']>=$jobNum){

						$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");

						
						$this->DB_update_all('company_statis',"`breakjob_num`='".($membeStatis['breakjob_num']-$jobNum)."'","`uid`='".$userBind['uid']."'");

						$msg = 'ְλˢ����ɣ����ι�ˢ��'.$jobNum."��ְλ��";
					}else{
						$useIntegral = 1;
					}
				}
				if($useIntegral=='1'){
					
					if($this->config['com_integral_online']=='1'){
							
						if($this->config['integral_jobefresh_type']=='2'){
							
							
							if($membeStatis['integral']>=$refIntegral){
								
								$this->DB_update_all('company_job',"`lastupdate`='".time()."'","`uid`='".$userBind['uid']."' AND `state`='1' AND `status`<>1");
								
								$this->DB_update_all('company_statis',"`integral`='".($membeStatis['integral']-$refIntegral)."'","`uid`='".$userBind['uid']."'");
							}else{

								$msg = "����ˢ�¹���".$refIntegral."���֣����ȳ�ֵ���֣�";
							}
						}else{
							$msg = "Ȩ�޲��㣬������Ա�����ܸ������";
						}
						
					}else{
						$msg = "Ȩ�޲��㣬������Ա�����ܸ������";
					}
				}
			}else{
				$msg = '��û��������Ƹ��ְλ��';
			}
			$Return['centerStr']='<Content><![CDATA['.iconv('gbk','utf-8',$msg).']]></Content>';
		}else{

			$Return['centerStr'] = $userBind['cenetrTpl'];
			
		}
		$Return['MsgType']   = 'text';
		return $Return;
	}


	function searchJob($keyword)
	{

		$keyword = trim($keyword);
		
		include(PLUS_PATH."/city.cache.php");
		if($keyword)
		{
			$keywords = @explode(' ',$keyword);
		
			if(is_array($keywords))
			{
				foreach($keywords as $key=>$value)
				{
					$iscity = 0;
					if($value!='')
					{
						foreach($city_name as $k=>$v)
						{
							if(strpos($v,iconv('utf-8','gbk',trim($value)))!==false)
							{
								$CityId[] = $k;
								$iscity = 1;
							}
						}
						if($iscity==0)
						{
							$searchJob[] = "(`name` LIKE '%".iconv('utf-8','gbk',trim($value))."%') OR (`com_name` LIKE '%".iconv('utf-8','gbk',trim($value))."%')";
						}
					}
				}
				
				$searchWhere = "`state`='1' AND `sdate`<='".time()."' AND `edate`>= '".time()."' AND `status`<>'1' AND `r_status`<>'2'";
				if(!empty($searchJob))
				{
					$searchWhere .=  " AND (".implode(' OR ',$searchJob).")";
				}
				if(!empty($CityId))
				{
					$City_id = pylode(',',$CityId);
					$searchWhere .= " AND (`provinceid` IN (".$City_id.") OR `cityid` IN (".$City_id.") OR `three_cityid` IN (".$City_id."))";
				}
				$jobList = $this->DB_select_all("company_job",$searchWhere." order by `lastupdate` desc limit 5","`id`,`name`,`com_name`");
			}
		}	
	
		if(is_array($jobList) && !empty($jobList))
		{

			foreach($jobList as $key=>$value)
			{
				$Info['title'] = iconv('gbk','utf-8',"��".$value['name']."��\n".$value['com_name']);
				$Info['pic'] = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
				$Info['url'] = Url("wap",array('c'=>'job','a'=>'view','id'=>$value['id']));
				$List[]     = $Info;
			}
			$Msg['title'] = iconv('gbk','utf-8','�롾').$keyword.iconv('gbk','utf-8','����ص�ְλ');
			$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
			$Msg['url'] = Url('wap',array('c'=>'job','keyword'=>urlencode(iconv('utf-8','gbk',$keyword))));
			
			$Return['centerStr'] = $this->Handle($List,$Msg);
			$Return['MsgType']   = 'news';
		}else{

			$Return['centerStr'] = '<Content><![CDATA['.iconv('gbk','utf-8','δ�ҵ����ʵ�ְλ��').']]></Content>';
			$Return['MsgType']   = 'text';
		}
		
		return $Return;
		
	}
	
	function bindUser($wxid='')
	{
	
		$bindType = $this->isBind($wxid);
		$Return['MsgType']   = 'text';
		$Return['centerStr'] = $bindType['cenetrTpl'];
		return $Return;
		
	}
	function getWxUser($wxid){
		 global $config;
		
		
		$Token = getToken($config);
	
		$Url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$Token.'&openid='.$wxid.'&lang=zh_CN';
		$CurlReturn  = CurlPost($Url);
		$UserInfo    = json_decode($CurlReturn,true);

		return $UserInfo;
	
	}

	function isBind($wxid='',$usertype=1)
	{	
	
		if($wxid)
		{
			
			$UserInfo    = $this->getWxUser($wxid);
			$wxid        = $wxid;
			$unionid	 = $UserInfo['unionid'];
			$User = $this->DB_select_once("member","`wxid`='".$wxid."' OR (`unionid`<>'' AND `unionid`='".$unionid."' )","`uid`,`username`,`usertype`,`wxid`,`unionid`");
			if($User['unionid']!='' && $User['wxid']!=$wxid)
			{
				$User = $this->DB_update_all("member","`wxid`='".$wxid."'","`uid`='".$User['uid']."'");
			}
		}
		if($User['uid']>0)
		{
			
			$urlLogin = $this->config['sy_wapdomain']."/index.php?c=login&bind=1&wxid=".$wxid."&unionid=".$unionid;
			if($User['usertype']!=$usertype)
			{
				switch($usertype){
					case '1':
						$User['cenetrTpl'] = "<Content><![CDATA[".iconv('gbk','utf-8',"����".$this->config['sy_webname']."�ʺţ�".$User['username']."Ϊ��ҵ�ʺţ����¼���ĸ����ʺŽ��а󶨣� \n\n\n ��Ҳ����<a href=\"".$urlLogin."\">�������</a>���а������ʺ�")."]]></Content>";;
					break;
					case '2':
						$User['cenetrTpl'] = "<Content><![CDATA[".iconv('gbk','utf-8',"����".$this->config['sy_webname']."�ʺţ�".$User['username']."Ϊ�����ʺţ����¼������ҵ�ʺŽ��а󶨣� \n\n\n ������<a href=\"".$urlLogin."\">�������</a>���н�������ʺ�")."]]></Content>";
					break;

				}
				
			}else{
				$User['bindtype'] = '1';
				$User['cenetrTpl'] = "<Content><![CDATA[".iconv('gbk','utf-8',"����".$this->config['sy_webname']."�ʺţ�".$User['username']."�ѳɹ��󶨣� \n\n\n ��Ҳ����<a href=\"".$urlLogin."\">�������</a>���н���������ʺ�")."]]></Content>";
			}
			
		}else{

			
			$urlLogin = $this->config['sy_wapdomain']."/index.php?c=login&wxid=".$wxid."&unionid=".$unionid;
			$User['cenetrTpl'] = '<Content><![CDATA['.iconv('gbk','utf-8','����û�а��ʺţ�<a href="'.$urlLogin.'">�������</a>���а�!').']]></Content>';
		}

		return $User;
	}
	
	function recJob()
	{

		$JobList = $this->DB_select_all("company_job","`sdate`<='".time()."' AND `edate`>= '".time()."' AND `status`<>1 AND `r_status`<>1 AND `rec_time`>'".time()."' order by `lastupdate` desc limit 5","`id`,`name`,`com_name`,`lastupdate`");
		
		if(is_array($JobList) && !empty($JobList))
		{
			foreach($JobList as $key=>$value)
			{
				$Info['title'] = iconv('gbk','utf-8',"��".$value['name']."��\n".$value['com_name']);
				$Info['pic'] = $this->config['sy_weburl'].'/data/upload/wx/jt.jpg';
				$Info['url'] = Url("wap",array('c'=>'job','a'=>'view','id'=>$value['id']));
				$List[]        = $Info;
			}
			$Msg['title'] = iconv('gbk','utf-8','�Ƽ�ְλ');
			$Msg['pic'] = $this->config['sy_weburl'].'/'.$this->config['sy_wx_logo'];
			$Msg['url'] = Url("wap",array('c'=>'job'));
			$Return['centerStr'] = $this->Handle($List,$Msg);
			$Return['MsgType']   = 'news';
			
		}else{
			$Return['centerStr'] ='<Content><![CDATA['.iconv('gbk','utf-8','û�к��ʵ�ְλ��').']]></Content>';
			$Return['MsgType']   = 'text';
		}
		
		return $Return;
	}
	
	
	function Handle($List,$Msg)
	{

		$articleTpl = '<Content><![CDATA['.$Msg['title'].']]></Content>';

		$articleTpl .= '<ArticleCount>'.(count($List)+1).'</ArticleCount><Articles>';

		$centerTpl = "<item>
		<Title><![CDATA[%s]]></Title>  
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>";

		$articleTpl.=sprintf($centerTpl,$Msg['title'],'',$Msg['pic'],$Msg['url']); 

		foreach($List as $value)
		{	
			$articleTpl.=sprintf($centerTpl,$value['title'],'',$value['pic'],$value['url']);
		}
		$articleTpl .= '</Articles>';
		return $articleTpl;
	}
	
	function valid($echoStr,$signature,$timestamp,$nonce)
    {
        if($this->checkSignature($signature,$timestamp,$nonce)){
        	echo $echoStr;	
        	exit;
        }
    }
	
	
	function checkSignature($signature, $timestamp,$nonce)
	{   		
		$token = $this->config['wx_token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature  && $token!=''){
			return true;
		}else{
			return false;
		}
	}
	
	function ArrayToString($obj,$withKey=true,$two=false)
	{
		if(empty($obj))	return array();
		$objType=gettype($obj);
		if ($objType=='array') {
			$objstring = "array(";
			foreach ($obj as $objkey=>$objv) {
				if($withKey)$objstring .="\"$objkey\"=>";
				$vtype =gettype($objv) ;
				if ($vtype=='integer') {
				  $objstring .="$objv,";
				}else if ($vtype=='double'){
				  $objstring .="$objv,";
				}else if ($vtype=='string'){
				  $objv= str_replace('"',"\\\"",$objv);
				  $objstring .="\"".$objv."\",";
				}else if ($vtype=='array'){
				  $objstring .="".$this->ArrayToString($objv,false).",";
				}else if ($vtype=='object'){
				  $objstring .="".$this->ArrayToString($objv,false).",";
				}else {
				  $objstring .="\"".$objv."\",";
				}
			}
			$objstring = substr($objstring,0,-1)."";
			return $objstring.")\n";
		}
	}
	function markLog($wxid,$wxuser,$content,$reply){

		$this->DB_insert_once("wxlog","`wxid`='".$wxid."',`wxuser`='".$wxuser."',`content`='".$content."',`reply`='".$reply."',`time`='".time()."'");
	}



}
?>