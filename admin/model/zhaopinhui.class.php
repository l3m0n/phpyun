<?php
/* *
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
*/
class zhaopinhui_controller extends common{ 
	function set_search(){
		$search_list[]=array("param"=>"status","name"=>'���״̬',"value"=>array("0"=>"δ��ʼ","1"=>"�ѿ�ʼ","2"=>"�ѽ���"));
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		extract($_GET);
		$where="1";
		if($status==='0'){
			$where.=" and UNIX_TIMESTAMP(`starttime`) > '".time()."'";
			$urlarr['status']=$status;
		}elseif($status=='1'){
			$where.=" and UNIX_TIMESTAMP(`starttime`)<'".time()."' and UNIX_TIMESTAMP(`endtime`)>'".time()."'";
			$urlarr['status']=$status;
		}elseif($status=='2'){
			$where.=" and UNIX_TIMESTAMP(`endtime`)<'".time()."'";
			$urlarr['status']=$status;
		}
		
		if($_GET['news_search']){
			if ($_GET['type']=='2'){
				$where.=" and `address` like '%".$_GET['keyword']."%'";
			}else{
				$where.=" and `title` like '%".$_GET['keyword']."%'";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
			$urlarr['news_search']=$_GET['news_search'];
		}
		$where.=" order by id desc";
		$urlarr['page']="{{page}}";
		$pageurl=Url($m,$urlarr,'admin');
		$rows=$this->get_page("zhaopinhui",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($rows)){
			$zid=array();
			foreach($rows as $key=>$val){
				$rows[$key]['comnum']=$rows[$key]['booking']='0';
				$zid[]=$val['id'];
			}
			$all=$this->obj->DB_select_all("zhaopinhui_com","`zid` in(".pylode(",",$zid).") group by `zid` ","`zid`,count(id) as num");
			$status=$this->obj->DB_select_all("zhaopinhui_com","`zid` in(".pylode(",",$zid).") and `status`='0' group by `zid` ","`zid`,count(id) as num");
			foreach($rows as $key=>$v){
				foreach($all as $val){
					if($v['id']==$val['zid']){
						$rows[$key]['comnum']=$val['num'];
					}
				}
				foreach($status as $val){
					if($v['id']==$val['zid']){
						$rows[$key]['booking']=$val['num'];
					}
				} 
			}
		} 
		$Domain = $this->obj->DB_select_all("domain","`type`='1'");
		$Dname[0] = '��վ';
		if(is_array($Domain)){
			foreach($Domain as $key=>$value){
				$Dname[$value['id']]  =  $value['title'];
			}
		}
		$this->yunset("Dname", $Dname); 
		$this->yunset("get_type", $_GET);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_zhaopinhui_list'));
	}

	function add_action(){
		include PLUS_PATH."/domain_cache.php";  
		$domainnum=ceil((count($site_domain)+1)/4); 
		$this->yunset("domain",$site_domain); 
		$this->yunset("domainnum",$domainnum);
		if($domainnum<2){
			$this->yunset("ieheight",40);
		}else{
			$this->yunset("ieheight",($domainnum*35)+10);
		} 
		
		if((int)$_GET['id']){
			$num=$this->obj->DB_select_num("zhaopinhui_com","`zid`='".$_GET['id']."'");
			if($num>0){
				$this->get_admin_msg("index.php?m=zhaopinhui","����Ƹ��������ҵ�����������޸ģ�");
			}
			$linkarr=$this->obj->DB_select_once("zhaopinhui","id='".$_GET['id']."'");
			$this->yunset("lasturl",$_SERVER['HTTP_REFERER']);
			foreach($site_domain as $v){
				if($v['id']==$linkarr['did']&&$linkarr['did']>0){
					$this->yunset("domainname",$v['webtitle']);
				}
			}   
			$this->yunset("linkrow",$linkarr);
		}
		$this->yunset("domainnum",count($domain));
		$this->yunset("domain",$domain);
		$space = $this->obj->DB_select_all("zhaopinhui_space","`keyid`='0'");
		$this->yunset("space",$space);
		$this->yuntpl(array('admin/admin_zhaopinhui_add'));
	} 
	function del_action(){
		if(is_array($_POST['del'])){
			$linkid=@implode(',',$_POST['del']);
			$layer_msg=1;
		}else{
			$this->check_token();
			$linkid=(int)$_GET['id'];
			$layer_msg=0;
		}
		$delid=$this->obj->DB_delete_all("zhaopinhui","`id` in (".$linkid.")","");
		if($delid){
			$row=$this->obj->DB_select_all("zhaopinhui_pic","`zid` in ($linkid)");
			if(is_array($row)){
				foreach($row as $v){
					unlink_pic("..".$v['pic']);
				}
			}
			$this->obj->DB_delete_all("zhaopinhui_pic","`zid` in (".$linkid.")","");
			$this->obj->DB_delete_all("zhaopinhui_com","`zid` in (".$linkid.")","");
			$this->layer_msg('��Ƹ��(ID:'.$linkid.')ɾ���ɹ���',9,$layer_msg,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_msg,$_SERVER['HTTP_REFERER']);
		}
	} 
	function save_action(){
		extract($_POST);
		$id=$_POST['id'];		
		unset($_POST['update']);
		unset($_POST['add']);
		unset($_POST['id']);
		$_POST['body']=str_replace("&amp;","&",html_entity_decode($_POST['body'],ENT_QUOTES,"GB2312"));
		$_POST['media']=str_replace("&amp;","&",html_entity_decode($_POST['media'],ENT_QUOTES,"GB2312"));
		$_POST['packages']=str_replace("&amp;","&",html_entity_decode($_POST['packages'],ENT_QUOTES,"GB2312"));
		$_POST['booth']=str_replace("&amp;","&",html_entity_decode($_POST['booth'],ENT_QUOTES,"GB2312"));
		$_POST['participate']=str_replace("&amp;","&",html_entity_decode($_POST['participate'],ENT_QUOTES,"GB2312"));
		if($add){			
			$_POST['ctime']=mktime();			
			$_POST['status']="0";
			$nbid=$this->obj->insert_into("zhaopinhui",$_POST);
			isset($nbid)?$this->ACT_layer_msg("��Ƹ��(ID:$nbid)��ӳɹ���",9,"index.php?m=zhaopinhui",2,1):$this->ACT_layer_msg("��Ƹ��(ID:$nbid)���ʧ�ܣ�",8,"index.php?m=zhaopinhui");
		}
		
		if($update){						
			$where['id']=$id;
			unset($_POST['lasturl']);
			$info=$this->obj->DB_select_once("zhaopinhui","`id`='".$id."'");
			if($info['did']!=$_POST['did']){
				$this->obj->DB_update_all("zhaopinhui_pic","`did`='".$_POST['did']."'","`zid`='".$id."'");
				$this->obj->DB_update_all("zhaopinhui_com","`did`='".$_POST['did']."'","`zid`='".$id."'");
			}
			$nbid=$this->obj->update_once("zhaopinhui",$_POST,$where);
 			isset($nbid)?$this->ACT_layer_msg("��Ƹ��(ID:$id)�޸ĳɹ���",9,"index.php?m=zhaopinhui",2,1):$this->ACT_layer_msg("��Ƹ��(ID:$id)�޸�ʧ�ܣ�",8,"index.php?m=zhaopinhui");
		}
	}
	function upload_action(){
		extract($_GET);
		if($editid){
			$editrow=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$editid."'");
			$this->yunset("pic",substr($editrow['pic'],(strrpos($editrow['pic'],'/')+1)));
			$this->yunset("editrow",$editrow);
			$id=$editrow['zid'];
		}
		$row=$this->obj->DB_select_once("zhaopinhui","`id`='".$id."'");
		$this->yunset("row",$row);
		$urlarr['c']=$c;
		$urlarr['id']=$id;
		$urlarr['page']="{{page}}";
		$pageurl=Url($m,$urlarr,'admin');
		$rows=$this->get_page("zhaopinhui_pic","zid='".$id."'",$pageurl,"13");
		if(is_array($rows)){
			
			foreach($rows as $key=>$value){
			
				if(strpos($value['pic'],'http')===false){
					$rows[$key]['pic'] = $this->config['sy_weburl'].'/'.$value['pic'];
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_zhaopinhui_upload'));
	}
	function uploadsave_action(){
	    $_POST=$this->post_trim($_POST);
		extract($_POST);
		$id=$_POST['id'];
		unset($_POST['update']);
		unset($_POST['add']);
		unset($_POST['id']);
		if($add){
			if($_POST['pic']){
				$_POST['pic']=str_replace($this->config['sy_weburl'],"",$_POST['pic']);
			}else{
				$this->ACT_layer_msg("����ͼ����Ϊ�գ�",8,$_SERVER['HTTP_REFERER']);
			}
			$_POST['is_themb']=0;
			$row=$this->obj->DB_select_once("zhaopinhui","`id`='".$zid."'","`did`");
			$_POST['did']=$row['did'];
			$nbid=$this->obj->insert_into("zhaopinhui_pic",$_POST);
			isset($nbid)?$this->ACT_layer_msg("��Ƹ��ͼƬ(ID:".$nbid.")��ӳɹ���",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("���ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
		}
		if($update){
			if($_POST['pic']){
				$row=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$id."'");
				$_POST['pic']=str_replace($this->config['sy_weburl'],"",$_POST['pic']);
				if($_POST['pic']==''){
				    $_POST['pic']=$row['pic'];
				}
				if($_POST['pic']!=$row['pic']){
					unlink_pic("..".$row['pic']);
				}
			}
			$where['id']=$id;
			$nbid=$this->obj->update_once("zhaopinhui_pic",$_POST,$where);
			isset($nbid)?$this->ACT_layer_msg("��Ƹ��ͼƬ(ID:".$id.")�޸ĳɹ���",9,"index.php?m=zhaopinhui&c=upload&id=".intval($_POST['zid'])."",2,1):$this->ACT_layer_msg("�޸�ʧ�ܣ�",8,"index.php?m=zhaopinhui&c=upload&id=".intval($_POST['zid'])."");
		}
	}
	function pic_action()
	{
		if($_GET['sid'])
		{
			$row=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$_GET['sid']."'");
			$nbidone=$this->obj->DB_update_all("zhaopinhui_pic","`is_themb`='0'","`zid`='".$row['zid']."'");
			$nbid=$this->obj->DB_update_all("zhaopinhui_pic","`is_themb`='1'","`id`='".$_GET['sid']."'");
			$nbid2=$this->obj->DB_update_all("zhaopinhui","`pic`='".$row['pic']."'","`id`='".$row['zid']."'");
 			isset($nbidone) && isset($nbid) && isset($nbid2)?$this->layer_msg("��Ƹ��ͼƬ(ID:".$_GET['sid'].")��Ϊ����ͼ�ɹ���",9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg("�޸�ʧ�ܣ�",8,0,$_SERVER['HTTP_REFERER']);
		}
		if($_GET['delid']){
			$this->check_token();
			$row=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$_GET['delid']."'");
			if($row['is_themb']==1){
				$nbid2=$this->obj->DB_update_all("zhaopinhui","`pic`=''","`id`='".$row['zid']."'");
			}
			$row=$this->obj->DB_select_once("zhaopinhui_pic","`id`='".$_GET['delid']."'");
			unlink_pic("..".$row['pic']);
			$delid=$this->obj->DB_delete_all("zhaopinhui_pic","`id`='".$_GET['delid']."'");
 			$delid?$this->layer_msg("��Ƹ��ͼƬ(ID:".$_GET['delid'].")ɾ���ɹ���",9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,0,$_SERVER['HTTP_REFERER']);
		}
	}
	function set_searchs(){
		$search_list[]=array("param"=>"status","name"=>'���״̬',"value"=>array("1"=>"�����","2"=>"δͨ��","3"=>"δ���"));
		$this->yunset("search_list",$search_list);
	}
	function com_action(){
		$this->set_searchs();
		extract($_GET);

		$where="1";
		if($id){
			$where.=" and `zid`='".$id."'";
		}
		$urlarr['id']=$id;
		if($status){
			if($status=="3")
			{
				$status="0";
			}
			$where.=" and `status`='$status'";
			$urlarr['status']=$status;
		}

		$where.=" order by id desc";
		$urlarr['c']=$c;
		$urlarr['page']="{{page}}";
		$pageurl=Url($m,$urlarr,'admin');
		$rows=$this->get_page("zhaopinhui_com",$where,$pageurl,"13");
		if(is_array($rows)){
			$space=$this->obj->DB_select_all("zhaopinhui_space");
			$spacearr=array();
			foreach($space as $val){
				$spacearr[$val['id']]=$val['name'];
			}
			foreach($rows as $v){
				$uid[]=$v['uid'];
				$jobid[]=$v['jobid'];
				$zid[]=$v['zid'];
			}
			$company=$this->obj->DB_select_all("company","`uid` in (".@implode(",",$uid).")","`uid`,`name`");
			$company_job=$this->obj->DB_select_all("company_job","`id` in (".@implode(",",$jobid).")","`name`,`id`");
			$zhp=$this->obj->DB_select_all("zhaopinhui","`id` in (".@implode(",",$zid).")","`id`,`title`");
			foreach($rows as $k=>$v)
			{
				foreach($company as $val)
				{
					if($v['uid']==$val['uid'])
					{
						$rows[$k]['comname']=$val['name'];
					}
				}
				foreach($zhp as $val)
				{
					if($v['zid']==$val['id'])
					{
						$rows[$k]['zphname']=$val['title'];
					}
				}
				$jobids=array();
				$jobname=array();
				$jobids=@explode(",",$v['jobid']);
				foreach($company_job as $val)
				{
					foreach($jobids as $value)
					{
						if($value==$val['id'])
						{
							$url=Url("job",array("c"=>"comapply","id"=>$val['id']));
							$jobname[]='<a target="_blank" href="'.$url.'" class="admin_cz_sc">'.$val['name'].'</a>';
						}
					}
					$rows[$k]['jobname']=@implode(",",$jobname);
				}
			}
		}
		$this->yunset("spacearr",$spacearr);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_zhaopinhui_com'));
	}
	function sbody_action(){
		$zhaopinhui_com=$this->obj->DB_select_once("zhaopinhui_com","`id`='".$_POST['id']."'",'statusbody');
		echo $zhaopinhui_com['statusbody'];die;
	}
	function delcom_action(){

		if(is_array($_POST['del'])){
			$linkid=@implode(',',$_POST['del']);
			$layer_type=1;
		}else{
			$this->check_token();
			$linkid=(int)$_GET['delid'];
			$layer_type=0;
		}
		$list=$this->obj->DB_select_all("zhaopinhui_com","`status`='0' and `price`>'0' and `id` in (".$linkid.")","`price`,`status`,`uid`");
		if(is_array($list)){
			foreach($list as $v){
				$this->company_invtal($v['uid'],$v['price'],true,"ɾ����Ƹ��",true,2,'integral');
			}
		}
		$delid=$this->obj->DB_delete_all("zhaopinhui_com","`id` in (".$linkid.")"," ");
		$delid?$this->layer_msg('��Ƹ��λ���ҵ(ID:'.$linkid.')ɾ���ɹ���',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
	function status_action(){
		extract($_POST);
		$id = @explode(",",$pid);
		if(is_array($id)){
			foreach($id as $value){
				$idlist[] = $value;
				$data[] = $this->shjobmsg($value,$status,$statusbody);
			}
			if($data!=""){
			    $smtp = $this->email_set();
			    foreach($data as $key=>$value){
			        $this->send_msg_email($value,$smtp);
			    }
			}
			$aid = @implode(",",$idlist);
			if($status==2){
			    $list=$this->obj->DB_select_all("zhaopinhui_com","`status`='0' and `price`>'0' and `id` in (".$aid.")","`price`,`status`,`uid`");
			    if(is_array($list)){
			        foreach($list as $v){
			        	$this->company_invtal($v['uid'],$v['price'],true,"��Ƹ�����δͨ��",true,2,'integral'); 
			        }
			    }
			}
			$id=$this->obj->DB_update_all("zhaopinhui_com","`status`='$status',`statusbody`='".$statusbody."'","`id` IN ($aid)");
 			$id?$this->ACT_layer_msg("��Ƹ��(ID:".$aid.")���óɹ���",9,$_SERVER['HTTP_REFERER'],2,1):$this->ACT_layer_msg("����ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);
 			if($this->config['sy_email_lock']=='1'){
 			    $userinfo = $this->obj->DB_select_once("member","`uid` IN ($aid)","`email`,`uid`,`name`,`usertype`");
 			    $data=$this->forsend($userinfo);
 			
 			    $this->send_msg_email(array("email"=>$userinfo['email'],'uid'=>$data['uid'],'name'=>$data['name'],"certinfo"=>$statusbody,"username"=>$userinfo['name'],"type"=>"lock"));
 			}
		}else{
			$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
		}
	}
	
	function shjobmsg($pid,$yesid,$statusbody){
	    $data=array();
	    $zphcom=$this->obj->DB_select_once("zhaopinhui_com","`id`='".$pid."'","uid,zid");
	    $zph=$this->obj->DB_select_once("zhaopinhui","`id`='".$zphcom['zid']."'","title");
	    if($yesid==1){
	        $data['type']="zphshtg";
	    }elseif($yesid==2){
	        $data['type']="zphshwtg";
	    }
	    if($data['type']!=""){
	        $uid=$this->obj->DB_select_alls("member","company","a.`uid`='".$zphcom['uid']."' and a.`uid`=b.`uid`","a.email,a.moblie,a.uid,b.name");
	        $data['uid']=$uid[0]['uid'];
	        $data['name']=$uid[0]['name'];
	        $data['email']=$uid[0]['email'];
	        $data['moblie']=$uid[0]['moblie'];
	        $data['zphname']=$zph['title'];
	        $data['date']=date("Y-m-d H:i:s");
	        $data['status_info']=$statusbody;
	        return $data;
	    }
	}
	
	function checksitedid_action(){
		if($_POST['uid']){
			$uids=@explode(',',$_POST['uid']);
			$uid = pylode(',',$uids);
			if($uid){
				$siteDomain = $this->MODEL('site');
				$siteDomain->UpDid(array("zhaopinhui"),$_POST['did'],"`id` IN (".$uid.")");
				$siteDomain->UpDid(array("zhaopinhui_com",'zhaopinhui_pic'),$_POST['did'],"`zid` IN (".$uid.")");
				$this->ACT_layer_msg( "��Ƹ��(ID:".$_POST['uid'].")����վ��ɹ���",9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->ACT_layer_msg("����ȷѡ����������ݣ�",8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->ACT_layer_msg( "������ȫ�����ԣ�",8,$_SERVER['HTTP_REFERER']);
		}
	}
	function getzhanwei_action()
	{
		if($_POST['sid']){
			$linkarr=$this->obj->DB_select_once("zhaopinhui","id='".$_POST['zid']."'","`reserved`");
			$reserved=@explode(',',$linkarr['reserved']);
			$list=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$_POST['sid']."'");
			if(is_array($list)){
				$html.='<div class="zph_zw_box"><table cellspacing="2" cellpadding="3" class="zp_zw_table">';
				foreach($list as $v){
					$html.='<tr class="zp_zw_title"><td colspan="6">'.$v[name].'</td></tr>';
					$html.='<tr>';
					$rows=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$v['id']."' order by sort asc");
					foreach($rows as $key=>$val){
						$ck='';
						if(in_array($val[id],$reserved)){
							$ck=' checked="checked"';
						}
						$html.='<td>&nbsp;<input type="checkbox" name="zhanwei" value="'.$val[id].'" '.$ck.'>&nbsp;'.$val[name].'</td>';
						if(($key+1)%6==0){
							$html.='</tr><tr>';
						}
					}
					$html.='</tr>';
				}
				$html.='</table></div>';
				$html.='<div class="zph_zw_box_b">
            <input type="button" onClick="check_zhanwei();"  value="ȷ��" class="submit_btn">
          &nbsp;&nbsp;<input type="button" onClick="layer.closeAll();" class="submit_btn_hjj" value="ȡ��"></div>';
				echo $html;die;
			}
		}
	}

}

?>