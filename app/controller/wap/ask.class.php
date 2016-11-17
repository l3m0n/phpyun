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
class ask_controller extends common{
	function index_action(){
		$this->rightinfo();
		$this->get_moblie();
		$M=$this->MODEL('ask');
        if($this->uid){ 
			$my_attention=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>1));
			$my_atten=@explode(',',rtrim($my_attention['ids'],",")); 
			$this->yunset("my_atten",$my_atten);			
		} 		
		$this->yunset("headertitle","�ʴ���ҳ");
		$this->seo('ask_index');
		$this->yuntpl(array('wap/ask'));
	}
	
	function list_action(){
		$this->rightinfo();
		$this->get_moblie();
		$M=$this->MODEL('ask');
        if($this->uid){ 
			$my_attention=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>1));
			$my_atten=@explode(',',rtrim($my_attention['ids'],",")); 
			$this->yunset("my_atten",$my_atten);			
		} 
	    $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('ask'));
		$this->yunset($CacheList);
		if(trim($_GET['keyword'])){$this->addkeywords(12,trim($_GET['keyword']));}
		$this->yunset("getinfo",$_GET);    
		$this->yunset("headertitle","�ʴ��б�"); 
		$this->seo('ask_search');
		$this->yuntpl(array('wap/asklist'));
	}
	function content_action(){
		$this->rightinfo();
		$this->get_moblie();
	    $M=$this->MODEL('ask');
        $UserInfoM=$this->MODEL('userinfo');
		$FriendM=$this->MODEL('friend');		
        $ID=(int)$_GET['id'];
		$show=$M->GetQuestionOne(array("id"=>$ID));		
		if($_GET['orderby']){
			$order=" order by support desc";
		}
		$pageurl=Url('wap',array("c"=>$_GET['c'],"a"=>$_GET['a'],'id'=>$ID,'orderby'=>$_GET['orderby'],"page"=>"{{page}}"));
		$rows=$M->get_page("answer","`qid`='".$ID."'".$order,$pageurl,"10");
		$this->yunset($rows);
		$answer=$M->GetAnswerList($rows['rows']);
		
        $Userinfo=$FriendM->GetFriendInfo(array('uid'=>$show['uid']));
		$AnswerNum=$M->GetAnswerNum(array('qid'=>$show['id'])); 
		$M->UpdateQuestion(array('answer_num'=>$AnswerNum),array('id'=>$show['id']));
		if($this->uid){
			$atten_ask=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>1));
			$atn=$M->GetAtnList(array('uid'=>$this->uid),array('field'=>'sc_uid'));
			$myinfo=$FriendM->GetFriendInfo(array('uid'=>$this->uid),array('field'=>'`nickname`,`description`,`pic`,`uid`'));
			if($myinfo['pic']==''){
				$myinfo['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
			}
		}else{
			$myinfo['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
		}
		if(!empty($answer)){
			foreach($answer as $key=>$val){
				if($val['uid']==$this->uid){
					$answer[$key]['is_atn']='2';
				}else{
					foreach($atn as $a_v){
						if($a_v['sc_uid']==$val['uid']){
							$answer[$key]['is_atn']='1';
						}
					}
				}
				if($val['pic']){
					$answer[$key]['pic']=str_replace("..",$this->config['sy_weburl'],$val['pic']);
				}else{
					$answer[$key]['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
				}
			}
		}
		if($Userinfo['uid']==$this->uid){
			$Userinfo['useratn']='2';
			$show['qatn']='2';
		}else if(!empty($atn)){
			foreach($atn as $val){
				if($Userinfo['uid']==$val['sc_uid']){
					$Userinfo['useratn']='1';
				}
			}
		}
		if($atten_ask&&is_array($atten_ask)&&$show['qatn']==''){
			$ids=explode(',',rtrim($atten_ask['ids'],','));
			if(in_array($show['id'],$ids)){
				$show['qatn']='1';
			}
		}
		$data['ask_title']=$show['title'];
		$this->data=$data;
        $CacheM=$this->MODEL('cache');
        $reason=$CacheM->GetCache('reason');
        $M->AddQuestionHits(array("id"=>(int)$_GET['id']));
		$show['pic']?$show['pic']=str_replace("..",$this->config['sy_weburl'],$show['pic']):$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
 		$this->yunset("userinfo",$Userinfo);
 		$this->yunset("myinfo",$myinfo); 
        $this->yunset(array("reason"=>$reason,"show"=>$show,"uid"=>$this->uid,"answer"=>$answer,"ask_title"=>$show['title'].' - '.$this->config['sy_webname'],"c"=>"index"));							
		$this->yunset("headertitle",$show['title']);
		$this->seo("ask_content");
		$this->yuntpl(array('wap/askcontent'));
	}
	
	function answer_action(){
		$this->rightinfo();
		$this->get_moblie();
	    $M=$this->MODEL('ask');
        $UserInfoM=$this->MODEL('userinfo');
		$FriendM=$this->MODEL('friend');	    
		$id=(int)$_POST['id'];
		if($_POST['content']&&$id){	
		    session_cache_limiter('private, must-revalidate');
			session_start();
			$authcode=md5($_POST['authcode']);	
			if($authcode!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){ 
				unset($_SESSION['authcode']);
				echo '0';die;		
			}else{
			    if($this->config['integral_answer_type']=="1"){
			        $auto=true;
			    }else{
			        $statis=$MemberM->GetUserstatisOne(array("uid"=>$this->uid),array("field"=>"`integral`","usertype"=>$this->usertype));
			        if($statis['integral']<$this->config['integral_answer']){
			           echo '4';die;	
			        }
			        $auto=false;
			    }
			    if($data['msg']==''){
			        $info=$M->GetQuestionOne(array('id'=>$id),array('field'=>'`id`,`uid`,`title`,`content`'));
			        $content = str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),html_entity_decode($_POST["content"],ENT_QUOTES,"GB2312"));
			        $data=array();
			        $data['qid']=$id;
			        $data['content']=iconv('utf-8','gbk',trim(strip_tags($content)));
			        $data['uid']=$this->uid;
			        $data['comment']=0;
			        $data['support']=0;
			        $data['oppose']=0;
			        $data['add_time']=time();
			        $id=$M->insert_into("answer",$data);
			        if($id){
			            $result=$this->max_time('�ش�����');
			            if($result==true){
			                $this->company_invtal($this->uid,$this->config['integral_answer'],$auto,"�ش�����",true,2,'integral');
			            }
			            $FriendM->SaveFriendInfo(array("`answer`=`answer`+'1'"),array("uid"=>$this->uid));
			            $M->UpdateQuestion(array("`answer_num`=`answer_num`+1","lastupdate"=>time()),array('id'=>$info['id']));
			           echo '1';die;	
			        }else{
			           echo '2';die;	
			        }
			    }
			}
		}
	}		
	function topic_action(){
		$this->rightinfo();
		$this->get_moblie();
	    $M=$this->MODEL('ask');
        if($this->uid){ 
			$my_attention=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>1));
			$my_atten=@explode(',',rtrim($my_attention['ids'],",")); 
			$this->yunset("my_atten",$my_atten);			
		} 
		$this->yunset("headertitle","����");
		$this->seo("ask_topic");
		$this->yuntpl(array('wap/asktopic'));
	}	
	function myquestion_action(){
		$this->rightinfo();
		$this->get_moblie();
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}		
	    $M=$this->MODEL('ask'); 
		$FriendM=$this->MODEL('friend');     
		$CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('ask'));
		$this->yunset($CacheList);				
		$urlarr['c']='ask';
		$urlarr['a']='myquestion';
		$urlarr['page']='{{page}}';
		$pageurl=Url('wap',$urlarr);
		$rows=$M->get_page('question',"`uid`='".$uid."'",$pageurl,'10');		
		$this->yunset($rows);	
		$this->myinfo($FriendM,$uid);		
		$this->yunset("headertitle","��������");
		$this->seo("myquestio");
		$this->yuntpl(array('wap/question'));
	}
	function delask_action(){
		$id=(int)$_GET['id'];
		if($id){
			$AskM=$this->MODEL('ask');
			
			$result=$AskM->DeleteQuestion(array("id"=>$id,"uid"=>$this->uid));
			$result?$this->layer_msg('�����ɹ���',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('����ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
	function attention_action(){			
	    $M=$this->MODEL('ask');				
		$id = (int)$_POST['id'];
		$type = (int)$_POST['type'];
		if($id==''&&(int)$_GET['id']){
			$id=(int)$_GET['id'];
			$type=1;
		}
		$isset=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>$type));
		$ids=@explode(',',$isset['ids']);
		if($type=='1'){
			$info=$M->GetQuestionOne(array('id'=>$id),array('field'=>"`id`,`title`,`uid`,`atnnum`"));
			$gourl=url('ask',array("c"=>"content","id"=>$info['id']));
			$content="��ע��<a href=\"".$gourl."\" target=\"_blank\">��".$info['title']."��</a>��";
			$n_contemt="ȡ���˶�<a href=\"".$gourl."\" target=\"_blank\">��".$info['title']."��</a>�Ĺ�ע��";
			$log="��ע�ˡ�".$info['title']."��";
			$n_log="ȡ���˶ԡ�".$info['title']."��";
		}else{
			$info=$M->GetQclassOnce(array('id'=>$id),array('field'=>"`id`,`name`,`atnnum`"));
			$content="��ע�ˡ�".$info['name']."����";
			$n_contemt="ȡ���ˡ�".$info['name']."����";
			$log="��ע��".$info['name'];
			$n_log="ȡ���˶�".$info['name']."</a>�Ĺ�ע��";
		}
		if($info['uid']==$this->uid){
			$this->layer_msg('���ܹ�ע�Լ����������⣡',8,0,$_SERVER['HTTP_REFERER']);			
		}else{
			$data['uid']=$this->uid;
			$data['type']=$type;
			$arr['isadd']=1;
			if($ids[0]==''){
				$data['ids']=$id;
				$nid=$M->AddAttention($data);
			} else if(in_array($id,$ids)&&$ids[0]){
				$nids=array();
				foreach($ids as $val){
					if($val!=$id&&$val&&in_array($val,$nids)==false){
						$nids[]=$val;
					}
				}
				$arr['isadd']=0;
				$data['ids']=pylode(',',$nids);
				$nid=$M->UpdateAttention($data,array("id"=>$isset['id']));
			}else if(in_array($id,$ids)==false&&$ids[0]>0){
				$ids[]=$id;
				$data['ids']=pylode(',',$ids);
				$nid=$M->UpdateAttention($data,array("id"=>$isset['id']));
			}else if(in_array($_POST['id'],$ids)==false&&$ids[0]<1){
				$nid=$M->UpdateAttention(array("ids"=>$id),array("id"=>$isset['id']));
			}
			if($nid){
				if($data['type']=='1'){
					if($arr['isadd']){
						$atnnum=$info['atnnum']+1;
						$M->UpdateQuestion(array("atnnum"=>$atnnum),array("id"=>$info['id']));
					}else{
						$atnnum=$info['atnnum']-1;
						$M->UpdateQuestion(array("atnnum"=>$atnnum),array("id"=>$info['id']));
					}
				}
				if($data['type']=='2'){
					include(LIB_PATH."cache.class.php");
					$cacheclass= new cache(PLUS_PATH,$this->obj);
					$makecache=$cacheclass->ask_cache("ask.cache.php");
				}
				$sql['uid']=$this->uid;
				$sql['content']=$content;
				$sql['ctime']=time();
				$sql['type']=2;
				$Friend=$this->MODEL("friend");
				$Friend->InsertFriendState($sql);
				$M->member_log($log);
				if($atnnum<0){$atnnum=0;}
				if($_GET['id']){
					$this->layer_msg('�����ɹ���',9,0,$_SERVER['HTTP_REFERER']);					
				}else{					
					$this->layer_msg('�����ɹ���',9,0,$atnnum,$arr['isadd']);
				}
			}else{				
				$this->layer_msg('����ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
			}
		}				
	}
	function myanswer_action(){
		$this->rightinfo();
		$this->get_moblie();		
	    $M=$this->MODEL('ask');
		$FriendM=$this->MODEL('friend');
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}
		$pageurl=Url('ask',array("c"=>$_GET['c'],'a'=>$_GET['a'],'uid'=>$uid,"page"=>"{{page}}"));
		$rows=$M->get_page("answer","`uid`='".$uid."' order by `add_time` desc",$pageurl,"10");
		if($rows['total']){
			foreach($rows['rows'] as $v){
				$qid[]=$v['qid'];
			}
			$question=$M->GetQuestionList(array("`id` in (".pylode(',',$qid).")"),array('field'=>'`id` as `qid`,`title`'));
			foreach($rows['rows'] as $key=>$val){
				foreach($question as $k=>$v){
					if($val['qid']==$v['qid']){
						$rows['rows'][$key]['title']=$v['title'];
						$rows['rows'][$key]['qid']=$v['qid'];
					}
				}
			}
		}		
		$this->myinfo($FriendM,$uid);		
 		$this->yunset($rows);		
		$this->yunset("headertitle","�����ʴ�");
		$this->seo("myanswer");
		$this->yuntpl(array('wap/answer'));
	}
	function atnuser_action(){
		$id=(int)$_POST['id'];
		if($id>0){
			if($this->uid){
				if($_POST['id']==$this->uid){
					echo 4;die;
				}
                $M=$this->MODEL('ask');
				$FriendM=$this->MODEL('friend');
				$atninfo = $M->GetAtnOne(array('uid'=>$this->uid,'sc_uid'=>$id));
                 $UserInfoM=$this->MODEL('userinfo');
                $user=$UserInfoM->GetMemberOne(array('uid'=>$id),array('field'=>'`usertype`'));
				$comurl = url("ask",array("c"=>"friend","uid"=>$id));
				$row=$FriendM->GetFriendInfo(array('uid'=>$id));
				$name = $row['nickname'];
				if(is_array($atninfo)&&!empty($atninfo)){
					$M->DeleteAtn(array('id'=>$atninfo['id']));
                    $FriendM->SaveFriendInfo(array('`fans`=`fans`-1'),array('uid'=>$id));
                    $FriendM->SaveFriendInfo(array('`atnnum`=`atnnum`-1'),array('uid'=>$this->uid));
					$this->addstate("ȡ���˶�<a href=\"".$comurl."\">".$name."</a>�Ĺ�ע��",2);
					$this->automsg("�û� ".$this->username." ȡ���˶���Ĺ�ע��",$id);
					$M->member_log("ȡ���˶�".$name."�Ĺ�ע��");
					echo "2";die;
				}else{
					$M->insert_into("atn",array('uid'=>$this->uid,'sc_uid'=>$id,'usertype'=>(int)$this->usertype,'sc_usertype'=>$user['usertype'],'time'=>time()));
					$FriendM->SaveFriendInfo(array('`fans`=`fans`+1'),array('uid'=>$id));
					$FriendM->SaveFriendInfo(array('`atnnum`=`atnnum`+1'),array('uid'=>$this->uid));
					$this->addstate("��ע��<a href=\"".$comurl."\">".$name."</a>",2);
					$this->automsg("�û� ".$this->username." ��ע���㣡",$id);
					$M->member_log("��ע��".$name);
					echo "1";die;
				}
			}else{
				echo "3";die;
			}
		}
	}
	function attenquestion_action(){
		$this->rightinfo();
		$this->get_moblie();	
	    $M=$this->MODEL('ask');
		$FriendM=$this->MODEL('friend');
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}
        $atnlist=$M->GetAttentionList(array('uid'=>$uid,'type'=>1),array('field'=>'ids'));
		$ids=array_filter(@explode(',',$atnlist['ids']));
		if(count($ids)){
			$pageurl=Url('ask',array("c"=>$_GET['c'],'a'=>$_GET['a'],'uid'=>$uid,"page"=>"{{page}}"));
			$rows=$M->get_page("question","`id` in (".pylode(',',$ids).") order by `add_time` desc",$pageurl,"10");
		}
		$this->myinfo($FriendM,$uid);		
		$this->yunset($rows);	 
		$this->yunset("headertitle","��ע������");
		$this->seo("attenquestion");
		$this->yuntpl(array('wap/attenquestion'));
	}
	function myatn_action(){
		$this->rightinfo();
		$this->get_moblie(); 
	    $M=$this->MODEL('ask');
		$FriendM=$this->MODEL('friend');
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}
		$pageurl=Url('ask',array("c"=>$_GET['c'],'a'=>"myfans",'uid'=>$uid,"page"=>"{{page}}"));
		$rows=$M->get_page("atn","`uid`='".$uid."' order by `time` desc",$pageurl,"10");
		if($rows['rows']&&is_array($rows['rows'])){
			$uids=array();
			foreach($rows['rows'] as $key=>$val){
				$uids[]=$val['sc_uid'];
			}
			if($this->uid!=""){
				$fanlist=$M->GetAtnList(array("`sc_uid` in(".pylode(',',$uids).")","uid"=>$uid),array("field"=>"sc_uid"));
			}
			$friend=$FriendM->GetFriendAll(array("`uid` in(".pylode(',',$uids).")"),array("field"=>"`uid`,`nickname`,`pic`,`ask`,`answer`,`fans`,`description`,`atnnum`"));
			foreach($rows['rows'] as $key=>$val){
				foreach($friend as $v){
					if($val['sc_uid']==$v['uid']){
						if($v['pic']==''){
							$v['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
						}
						$v['url']=Url("ask",array("c"=>"friend","uid"=>$v['uid']));
						$rows['rows'][$key]=array_merge($rows['rows'][$key],$v);
					}
				}
				$rows['rows'][$key]['atn']=0;
				foreach($fanlist as $v){
					if($val['sc_uid']==$v['sc_uid']){
						$rows['rows'][$key]['atn']=1;
					}
				}
			}
		}		
		$this->myinfo($FriendM,$uid);		
 		$this->yunset($rows);		
		$this->yunset("headertitle","��ע����");
		$this->seo("myatn");
		$this->yuntpl(array('wap/atn'));
	}
	function myfans_action(){
		$this->rightinfo();
		$this->get_moblie();	
	    $M=$this->MODEL('ask');
		$FriendM=$this->MODEL('friend');
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}
		$pageurl=Url('wap',array("c"=>'ask','a'=>"myfans",'uid'=>$uid,"page"=>"{{page}}"));
		$rows=$M->get_page("atn","`sc_uid`='".$uid."' order by `time` desc",$pageurl,"10");
		if($rows['rows']&&is_array($rows['rows'])){
			$uids=array();
			foreach($rows['rows'] as $key=>$val){
				$uids[]=$val['uid'];
			}
			if($this->uid!="")
			{
				$fanlist=$M->GetAtnList(array("`sc_uid` in(".pylode(',',$uids).")","uid"=>$uid),array("field"=>"sc_uid"));
			}
			$friend=$FriendM->GetFriendAll(array("`uid` in(".pylode(',',$uids).")"),array("field"=>"`uid`,`nickname`,`pic`,`ask`,`answer`,`fans`,`description`,`atnnum`"));
			foreach($rows['rows'] as $key=>$val){
				foreach($friend as $v){
					if($val['uid']==$v['uid']){
						if($v['pic']==''){
							$v['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
						}
						$v['url']=Url("wap",array("c"=>"ask","a"=>"friend","uid"=>$v['uid']));
						$rows['rows'][$key]=array_merge($rows['rows'][$key],$v);
					}
				}
				$rows['rows'][$key]['atn']=0;
				foreach($fanlist as $v)
				{
					if($val['uid']==$v['sc_uid']){
						$rows['rows'][$key]['atn']=1;
					}
				}
			}
		}		
		$this->myinfo($FriendM,$uid);		
 		$this->yunset($rows);	
		$this->yunset("headertitle","��˿");
		$this->seo("myfans");
		$this->yuntpl(array('wap/fans'));
	}
	function friend_action(){
		if(!$this->uid && !$this->username){
		    $data['msg']='���ȵ�¼��';
			$data['url']=$this->config['sy_weburl'].'/wap/index.php?c=login';
			$this->yunset("layer",$data);
		}
		$this->rightinfo();
		$this->get_moblie();
		$uid=(int)$_GET['uid'];
		if($uid==''){
			$uid=$this->uid;
		}
		$AskM=$this->MODEL('ask');
		$FriendM=$this->MODEL('friend');
		$page_url['c'] = $_GET['c'];
		$page_url['uid'] = (int)$_GET['uid'];
		$page_url['page'] = "{{page}}";
		$pageurl=Url('ask',$page_url);
		$rows = $this->get_page("friend_state","`uid`='".$uid."' and `type`='1' order by ctime desc",$pageurl,'10');
		if(is_array($rows)&&$rows){
			$fsids=$fuid=array();
			foreach($rows as $val){
				$fsids[]=$val['id'];
			} 
			$replylist=$FriendM->GetFriendReplyAll(array("`nid` in (".pylode(',',$fsids).")"),array("orderby"=>"id asc","field"=>"`nid`,`uid`,`ctime`,`reply`"));
			foreach($rows as $key=>$val){
				$rows[$key]['commentnum'] = "0";
				$rows[$key]['ctime'] = date("Y-m-d H:i:s",$val['ctime']);
				foreach($replylist as $v){
					if($val['id']==$v['nid']){
						$rows[$key]['commentnum']+=1;
						$rows[$key]['reply'][$rows[$key]['commentnum']]= $v;
					}
				}
			}
		} 
		$this->yunset("rows",$rows);
		$this->myinfo($FriendM,$uid);
		$this->yunset("headertitle","�ҵ���ҳ");
		$this->seo("message");
		$this->yuntpl(array('wap/askfriend'));
	}
	function delm_action(){
		if((int)$_GET['id']){
			$M=$this->MODEL('friend');
			$did=$M->DeleteFriendInfo(array("id"=>(int)$_GET['id'],"uid"=>$this->uid),array("table"=>"state"));
			if($did){
				$M->DeleteFriendInfo(array("nid"=>(int)$_GET['id']),array("table"=>"reply"));
				$M->member_log("ɾ������Ȧ��̬");
				$this->layer_msg('ɾ���ɹ���',9,0);
			}else{
				$this->layer_msg('ɾ��ʧ�ܣ�',8,0);
			}
		}
	}
	function myinfo($FriendM,$uid){
		$info=$FriendM->GetFriendInfo(array('uid'=>$uid),array("field"=>"`pic`,`nickname`,`uid`,`description`,`fans`,`atnnum`"));
		if($info['pic']==''){
			$info['pic']=$this->config['sy_weburl'].'/'.$this->config['sy_friend_icon'];
		}		
		$fansNum = 0;
		$atnNum = 0;
		if($this->uid != ""){
			$fansNum = $this->obj->DB_select_num('atn','`sc_uid`='.$uid);
			$atnNum = $this->obj->DB_select_num('atn','`uid`='.$uid);
		}
		$info["fans"] = $fansNum;
		$info["atnnum"] = $atnNum;
		$data['nickname']=$info['nickname'];		
		$this->data=$data;
		$this->yunset("info",$info);
	}
	function hotweek_action(){
		$this->rightinfo();
		$this->get_moblie();
	    $M=$this->MODEL('ask'); 
		$FriendM=$this->MODEL('friend');     
		$CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('ask'));
		$this->yunset($CacheList);
		 if($this->uid){ 
			$my_attention=$M->GetAttentionList(array('uid'=>$this->uid,'type'=>1));
			$my_atten=@explode(',',rtrim($my_attention['ids'],",")); 
			$this->yunset("my_atten",$my_atten);			
		} 		
		$this->yunset("headertitle","һ���ȵ�");
		$this->seo("ask_hot_week");
		$this->yuntpl(array('wap/askhotweek'));
	}
	function addquestion_action(){
		if($this->uid==''){				
			$data['msg']='���ȵ�¼��';
			$data['url']=$this->config['sy_weburl'].'/wap/index.php?c=login';
			$this->yunset("layer",$data);
		 }
	    $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache(array('ask'));
		$this->yunset($CacheList);
		$this->yunset("headertitle","�����ʴ�");
		$this->seo("ask_add_question");
		$this->yuntpl(array('wap/addquestion'));
	}
	function addquestions_action(){
		$M=$this->MODEL('ask');
		$MemberM=$this->MODEL("userinfo");	
		$cid=(int)$_POST['cid'];
		if($cid){
			if(strpos($this->config['code_web'],'ְ������')!==false){
			session_start();
			if(md5($_POST['authcode'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){ 
				echo 0;die;									       
			}
			}
			if($this->config['integral_question_type']=="1"){
				$auto=true;
			}else{ 
				$auto=false;
			} 
			if($auto==false&&$this->config['integral_question']>0){
				$info=$MemberM->GetUserstatisOne(array('uid'=>$this->uid),array('usertype'=>$this->usertype,'field'=>'integral'));
				if($this->config['integral_question']>$info['integral']){
					echo 3;die;	
				}
			}
			$rows=array();
			$rows['title']=iconv("UTF-8","gbk",$_POST['title']);
			$rows['cid']=$cid;
			$rows['content'] = str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'','',''),html_entity_decode($_POST["content"],ENT_QUOTES,"GB2312"));
			$rows['content']=iconv("UTF-8","gbk",strip_tags(trim($rows['content']),"<p> <br> <b>"));
			$rows['uid']=$this->uid;
			$rows['add_time']=time();
			$nid=$M->SaveQuestion($rows);
		}
		if($nid){
			$result=$this->max_time('��������');		
			if($result==true){
				$this->company_invtal($this->uid,$this->config['integral_question'],$auto,"��������",true,2,'integral');
			}
			$FriendM=$this->MODEL('friend');
			$sql['uid']=$this->uid;
			$sql['content']="�������ʴ�<a href=\"".url("wap",array("c"=>"ask","a"=>"content","id"=>$nid))."\" target=\"_blank\">".iconv("UTF-8","gbk",$_POST['title'])."</a>����";
			$sql['ctime']=time();
			$sql['type']='2';
			$FriendM->SaveFriendInfo(array("`ask`=`ask`+'1'"),array("uid"=>$this->uid));
			$FriendM->InsertFriendState($sql);
			$M->member_log("�������ʴ�".iconv("UTF-8","gbk",$_POST['title'])."��");
			unset($_SESSION['authcode']);		
			echo 1;die;
		}else{			
			echo 2;die;			
		}
	}
	function qclass_action(){
		$CacheM=$this->MODEL('cache');
        $info=$CacheM->GetCache(array('ask'));
		$rows=array();
		$id=(int)$_POST['id'];
		foreach($info['ask_type'][$id] as $v){
			$rows[$v]=urlencode($info['ask_name'][$v]);
		}
		$rows = json_encode($rows);
		echo urldecode($rows);die;
	}

	function qrepost_action(){
		$M=$this->MODEL('ask');
		if($this->uid==""||$this->username==''){
			echo 'no_login';die;
		}
		$eid=(int)$_POST['eid'];
		$reason=$_POST['reason'];
		$is_set=$M->GetReportOne(array('type'=>1,'r_type'=>1,'eid'=>$eid),array('field'=>'`p_uid`'));
		if(empty($is_set)){
            $question=$M->GetQuestionOne(array('id'=>$eid),array('field'=>'`uid`')); 
            $UserInfoM=$this->MODEL('userinfo');
            $FriendM=$this->MODEL('friend'); 
            $Userinfo=$FriendM->GetFriendInfo(array('uid'=>$question['uid']),array('field'=>'`nickname`,`uid`'));
			$my_nickname=$FriendM->GetFriendInfo(array('uid'=>$this->uid),array('field'=>'`nickname`'));
			$data['did']=$this->userdid;
			$data['p_uid']=$this->uid;
			$data['c_uid']=$question['uid'];
			$data['eid']=(int)$_POST['eid'];
			$data['usertype']=$this->usertype;
			$data['inputtime']=time();
			$data['username']=$my_nickname['nickname'];
			$data['r_name']=$Userinfo['nickname'];
			$data['r_reason']=$_POST['reason'];
			$data['type']=1;
			$data['r_type']=1;
			$new_id=$M->AddReport($data);
			if($new_id){
				$M->member_log('�ٱ��ʴ�����');
				echo '1';
			}else{
				echo '0';
			}
		}else{
			if($is_set['p_uid']==$this->uid){
				echo '2';
			}else{
				echo '3';
			}
		}
	}
	function getcomment_action(){
		$M=$this->MODEL('ask');
		$aid=(int)$_POST['aid'];
		$comment=$M->GetCommentList(array('aid'=>$aid));
		if(is_array($comment)){
			foreach($comment as $k=>$v){
				if($v['pic']!=""){
					$comment[$k]['pic']=str_replace("..",$this->config['sy_weburl'],$v['pic']);
				}
				$comment[$k]['errorpic']=$this->config['sy_weburl']."/".$this->config['sy_friend_icon'];
				$comment[$k]['url']=Url('ask',array("c"=>"friend","uid"=>$v['uid']));
				$comment[$k]['nickname']=urlencode($v['nickname']);
				$comment[$k]['content']=urlencode($v['content']);
				$comment[$k]['date']=date("Y-m-d H:i",$v['add_time']);
				if($v['uid']==$this->uid){
					$comment[$k]['myself']='1';
				}
			}
			$comment_json = json_encode($comment);
			echo urldecode($comment_json);die;
		}
	}
	function forcomment_action(){
		$M=$this->MODEL('ask');
		if($this->uid==""||$this->username==''){
			echo 'no_login';die;
		}
		$MemberM=$this->MODEL("userinfo");
		if($this->config['integral_answerpl_type']=="1"){
			$auto=true;
		}else{
			$statis=$MemberM->GetUserstatisOne(array("uid"=>$this->uid),array("field"=>"`integral`","usertype"=>$this->usertype));
			if($statis['integral']<$this->config['integral_answerpl']){
				echo "���ģ�".$this->config['integral_pricename']."���㣬���ȳ�ֵ���������ʴ�";die;
			}
			$auto=false;
		}
		$data['aid']=(int)$_POST['aid'];
		$data['qid']=(int)$_POST['qid'];
		$data['content']=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'background-color:','background-color:','white-space:'),html_entity_decode($_POST['content'],ENT_QUOTES,"GB2312"));
		$data['content']=iconv("utf-8","gbk",$data['content']);
		$data['uid']=$this->uid;
		$data['add_time']=time();
		$new_id=$M->AddAnswerReview($data);
		if($new_id){ 
			$result=$this->max_time('�����ʴ�');		
			if($result==true){  	 			 
				$this->company_invtal($this->uid,$this->config['integral_answerpl'],$auto,"�����ʴ�",true,2,'integral');
			}
			$M->member_log("�����ʴ�");
			$num=$M->GetCommentNum(array('aid'=>(int)$_POST['aid']));
			$M->UpdateAnswer(array('comment'=>$num),array('id'=>(int)$_POST['aid']));
			echo '1';
		}else{
			echo '0';
		}
	}
	function forsupport_action(){
		$M=$this->MODEL('ask');
		if($_COOKIE['support_'.$_POST['aid']]=='1'){
			echo '2';
		}else{
			$id=$M->UpdateAnswer(array("`support`=`support`+1"),array('id'=>$_POST['aid']));
			if($id){
				$M->member_log("������ش����");
				SetCookie('support_'.$_POST['aid'],"1",time()+3600,"/");
				echo '1';
			}else{
				echo '0';
			}
		}
	}	
}
?>