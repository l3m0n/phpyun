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
class info_controller extends company{
	function index_action(){
		 
		$row=$this->obj->DB_select_once("company","`uid`='".$this->uid."'");
		$save=$this->obj->DB_select_once("lssave","`uid`='".$this->uid."'and `savetype`='3'");
		$save=unserialize($save['save']);
		if($save['lastupdate']){
			$save['time']=date('H:i',ceil(($save['lastupdate'])));
		} 
		if($row['linkphone']){
			$linkphone=@explode('-',$row['linkphone']);
			$row['phoneone']=$linkphone[0];
			$row['phonetwo']=$linkphone[1];
			$row['phonethree']=$linkphone[2]; 
		}
		$this->yunset("save",$save);
		$this->yunset("row",$row);
		$this->public_action();
		$this->city_cache();
		$this->job_cache();
		$this->com_cache();
		$this->industry_cache();
		$this->yunset("js_def",2);
		$this->com_tpl('info');
	}
	function save_action(){
		if($_POST['submitbtn']){
			$_POST=$this->post_trim($_POST);
			if(trim($_POST['linktel'])){
				$is_exist=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `moblie`='".$_POST['linktel']."'","`uid`");
			}else if($_POST['linkmail']){
				$is_exist=$this->obj->DB_select_once("member","`uid`<>'".$this->uid."' and `email`='".$_POST['linkmail']."'","`uid`"); 
			} 
			if($is_exist['uid']){
				$this->ACT_layer_msg("�ֻ��������Ѵ��ڣ�",8,$_SERVER['HTTP_REFERER']);
			}
			$comname=$this->obj->DB_select_all('company',"`uid`<>'".$this->uid."' and `name`='".$_POST['name']."'","`uid`");
			if($_POST['name']==""){
				$this->ACT_layer_msg("��ҵȫ�Ʋ���Ϊ�գ�",8,"index.php?c=info");
			}
			if($comname){
				$this->ACT_layer_msg("��ҵȫ���Ѵ��ڣ�",8,"index.php?c=info");
			}
			if($_POST['hy']==""){
				$this->ACT_layer_msg("������ҵ����Ϊ�գ�",8,"index.php?c=info");
			}
			if($_POST['pr']==""){
				$this->ACT_layer_msg("��ҵ���ʲ���Ϊ�գ�",8,"index.php?c=info");
			}
			if($_POST['provinceid']==""){
				$this->ACT_layer_msg("���ڵز���Ϊ�գ�",8,"index.php?c=info");
			}
			if($_POST['mun']==""){
				$this->ACT_layer_msg("��ҵ��ģ����Ϊ�գ�",8,"index.php?c=info");
			}
			if($_POST['address']==""){
				$this->ACT_layer_msg("��˾��ַ����Ϊ�գ�",8,"index.php?c=info");
			}
			$linkphone=array();
			if($_POST['phonetwo']){
				if($_POST['phoneone']){
					$linkphone[]=$_POST['phoneone'];
				}else{
					$this->ACT_layer_msg("����д���ţ�",8,"index.php?c=info");
				}
				if($_POST['phonetwo']){
					$linkphone[]=$_POST['phonetwo'];
				} 
				if($_POST['phonethree']){
					$linkphone[]=$_POST['phonethree'];
				} 
			}
			
			$_POST['linkphone']=pylode('-',$linkphone); 
			if($_POST['linktel']==""){
				
				if($_POST['linkphone']==""){
					$this->ACT_layer_msg("��ϵ�ֻ��͹̶��绰����һ�",8,"index.php?c=info");
				}else if($_POST['phoneone']==""){
					$this->ACT_layer_msg("����д���ţ�",8,"index.php?c=info");
				} 
			}
			if($_POST['content']==""){
				$this->ACT_layer_msg("��ҵ��鲻��Ϊ�գ�",8,"index.php?c=info");
			}
			$company=$this->obj->DB_select_once("company","`uid`='".$this->uid."'");
			if($_POST['linkmail']=="" && $company['email_status']!='1'){
				$this->ACT_layer_msg("��ϵ�ʼ�����Ϊ�գ�",8,"index.php?c=info");
			}
			delfiledir("../data/upload/tel/".$this->uid);
			unset($_POST['submitbtn']);
			if($_FILES['uplocadpic']['tmp_name']){
				$upload=$this->upload_pic("../data/upload/company/",false,$this->config['com_pickb']);
				$pictures=$upload->picture($_FILES['uplocadpic']);
				$this->picmsg($pictures,$_SERVER['HTTP_REFERER']);
				$s_thumb=$upload->makeThumb($pictures,185,75,'_S_');
				unlink_pic($pictures);
				$_POST['logo']=str_replace("../data/upload/company","./data/upload/company",$s_thumb);
				$row=$this->obj->DB_select_once("company","`uid`='".$this->uid."' and `logo`<>''");
				if(is_array($row)){
					unlink_pic(".".$row['logo']);
				}
			}
			if($_FILES['firmpic']['tmp_name']){
				$upload=$this->upload_pic("../data/upload/company/",false,$this->config['com_uppic']);
				$firmpic=$upload->picture($_FILES['firmpic']);
				$this->picmsg($firmpic,$_SERVER['HTTP_REFERER']);
				$_POST['firmpic'] = str_replace("../data/upload/company","./data/upload/company",$firmpic);
				$rows=$this->obj->DB_select_once("company","`uid`='".$this->uid."' and `firmpic`<>''");
				if(is_array($rows)){
					unlink_pic(".".$rows['firmpic']);
				}
			}
			if($company['moblie_status']=='1'){
				unset($_POST['linktel']);
			}else{
				$mvalue['moblie']=$_POST['linktel'];
			}
			if($company['email_status']=='1'){
				unset($_POST['linkmail']);
			}else{
				$mvalue['email']=$_POST['linkmail'];
			} 
			$where['uid']=$this->uid;
			$_POST['content']=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'background-color:','background-color:','white-space:'),html_entity_decode($_POST['content'],ENT_QUOTES,"GB2312"));
			$_POST['lastupdate']=mktime();
			if($company['yyzz_status']=='1'){
				unset($_POST['name']);
			}else{
				$data['com_name']=$_POST['name'];
			} 
			$nid=$this->obj->update_once("company",$_POST,$where);
			if($nid){
				$this->obj->DB_delete_all("lssave","`uid`='".$this->uid."'and `savetype`='3'");
				$this->obj->member_log("�޸���ҵ��Ϣ",7);
				if($company['lastupdate']==""){
					if($this->config['integral_userinfo_type']=="1"){
						$auto=true;
					}else{
						$auto=false;
					}
					

					$this->company_invtal($this->uid,$this->config['integral_userinfo'],$auto,"�״���д��������",true,2,'integral',25);
				}
				$data['pr']=$_POST['pr'];
				$data['mun']=$_POST['mun'];
				$data['com_provinceid']=$_POST['provinceid'];
				$this->obj->update_once("company_job",$data,array("uid"=>$this->uid));
				if(!empty($mvalue)){
					$this->obj->update_once('member',$mvalue,array("uid"=>$this->uid));
				}
				if($_POST['name']!=$company['name']&&$_POST['name']){
					$this->obj->update_once("hotjob",array("username"=>$_POST['name']),array("uid"=>$this->uid));
					$this->obj->update_once("partjob",array("com_name"=>$_POST['name']),array("uid"=>$this->uid));
					$this->obj->update_once("userid_job",array("com_name"=>$_POST['name']),array("com_id"=>$this->uid));
					$this->obj->update_once("fav_job",array("com_name"=>$_POST['name']),array("com_id"=>$this->uid));
					$this->obj->update_once("report",array("r_name"=>$_POST['name']),array("c_uid"=>$this->uid));
					$this->obj->update_once("blacklist",array("com_name"=>$_POST['name']),array("c_uid"=>$this->uid));
					$this->obj->update_once("msg",array("com_name"=>$_POST['name']),array("job_uid"=>$this->uid));
				}

				$this->ACT_layer_msg("���³ɹ���",9,"index.php");
			}else{
				$this->ACT_layer_msg("����ʧ�ܣ�",8,"index.php?c=info");
			}
		}
	}
}
?>