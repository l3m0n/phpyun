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
class user extends common{
	function public_action(){
		$user=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","`photo`,`resume_photo`,`idcard_pic`,`idcard_status`");
		$this->yunset("user_photo",$user['photo']);
		$this->yunset("resume_photo",$user['resume_photo']);
		$this->yunset("idcard_pic",$user['idcard_pic']);
		$this->yunset("idcard_status",$user['idcard_status']);
		$now_url=@explode("/",$_SERVER['REQUEST_URI']);
		$now_url=$now_url[count($now_url)-1];
		$this->yunset("now_url",$now_url);
		$myresumenum=$this->obj->DB_select_num("resume_expect","`uid`='".$this->uid."'");
		$this->yunset("myresumenum",$myresumenum);
		if($_GET['c']==''){
			$this->yunset("left",1);
		}elseif($_GET['c']=='uppic'||$_GET['c']=='info'||$_GET['c']=='expect'||$_GET['c']=='resume'||$_GET['c']=='import'||$_GET['c']=='look'||$_GET['c']=='privacy'||$_GET['c']=='resumeout'||$_GET['c']=='show'||$_GET['c']=='likejob'){
			$this->yunset("left",2);
		}elseif($_GET['c']=='finder'||$_GET['c']=='favorite'||$_GET['c']=='look_job'||$_GET['c']=='job'||$_GET['c']=='msg'||$_GET['c']=='atn'||$_GET['c']=='partapply'||$_GET['c']=='partcollect'||$_GET['c']=='rebates'||$_GET['c']=='atnlt'){
			$this->yunset("left",3);
		}elseif($_GET['c']=='setname'){
			$this->yunset("left",4);
		}elseif($_GET['c']=='paylist'||$_GET['c']=='paylog'|| $_GET['c']=='integral' || $_GET['c']=='integral_reduce' || $_GET['c']=='reward_list' || $_GET['c']=='pay' || $_GET['c']=='payment'||$_GET['m']=='invitereg'){
			$this->yunset("left",5);
		}elseif($_GET['c']=='commsg'||$_GET['c']=='fav_subject'||$_GET['c']=='baoming_subject'||$_GET['c']=='passwd'||$_GET['c']=='binding'||$_GET['c']=='message'||$_GET['c']=='seemessage'||$_GET['c']=='sysnews'||$_GET['c']=='xin'||$_GET['c']=='pl'||$_GET['m']=='ask'){ 
			$this->yunset("left",6);
		}
	}
	function member_satic(){
		$statis=$this->obj->DB_select_once("member_statis","`uid`='".$this->uid."'");
		$this->yunset("statis",$statis);
		return $statis;
	}
	function get_user(){
		$statis=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'");
		if(!$statis['name'] || !$statis['edu']){
			$this->ACT_msg($_SERVER['HTTP_REFERER'],"请先完善个人资料！");
		}
	}
	function user_tpl($tpl){
		$this->yuntpl(array('member/user/'.$tpl));
	}
	function logout_action(){
		$this->logout();
	}
	function resume($table,$url,$nexturl,$name=""){
		if($_GET['del']){
			$eid=(int)$_GET['e'];
			$del=(int)$_GET['del'];
			$nid=$this->obj->DB_delete_all($table,"`id`='".$del."' and `uid`='".$this->uid."'");
			$data[num]=$this->obj->DB_select_num($table,"`eid`='".$eid."' and `uid`='".$this->uid."'");
			$this->obj->DB_update_all("user_resume","`".$url."`='".$data[num]."'","`eid`='".$eid."' and `uid`='".$this->uid."'");
			$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
			$numresume=$this->complete($resume_row); 
			$data[integrity]=$numresume;
			echo json_encode($data);die; 
		}
       if($_POST['submit']){
			$eid=(int)$_POST['eid'];
			$id=(int)$_POST['id'];
			$dom_sort=$_POST['dom_sort'];
			$_POST['uid']=$this->uid;
			unset($_POST['submit']);
			unset($_POST['id']);
			unset($_POST['table']);
			unset($_POST['dom_sort']);
			if($_POST['name'])$_POST['name'] = $this->stringfilter($_POST['name']);
			if($_POST['content'])$_POST['content'] =$this->stringfilter($_POST['content']);
			if($_POST['title'])$_POST['title'] =$this->stringfilter($_POST['title']);
			if($_POST['salary'])$_POST['salary'] =$this->stringfilter($_POST['salary']);
			if($_POST['department'])$_POST['department'] =$this->stringfilter($_POST['department']);
			if($_POST['sys'])$_POST['sys'] = $this->stringfilter($_POST['sys']);
			if($_POST['specialty'])$_POST['specialty'] = $this->stringfilter($_POST['specialty']);
			if($_POST['sdate']){
				$_POST['sdate']=strtotime($_POST['sdate']);
			}
			if($_POST['edate']){
				$_POST['edate']=strtotime($_POST['edate']);
			}
			$this->obj->DB_update_all("resume_expect","`dom_sort`='$dom_sort'","`id`='$eid' and `uid`='".$this->uid."'");
			if(!$id){
				$nid=$this->obj->insert_into($table,$_POST);
				$this->obj->DB_update_all("user_resume","`$url`=`$url`+1","`eid`='$eid' and `uid`='".$this->uid."'");
				if($nid){
					$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
					$numresume=$this->complete($resume_row);
					$this->select_resume($table,$nid,$numresume);
				}else{
					echo 0;die;
				}
			}else{
				$where['id']=$id;
				$nid=$this->obj->update_once($table,$_POST,$where);
				if($nid){
					$this->select_resume($table,$id);
				}else{
					echo 0;die;
				}
			}
		}
		$rows=$this->obj->DB_select_all($table,"`eid`='".$eid."'");
		$this->yunset("rows",$rows);
	}
	function select_resume($table,$id,$numresume=""){
		include(PLUS_PATH."user.cache.php");
		$tables=array("resume_expect","resume_skill","resume_work","resume_project","resume_edu","resume_training","resume_cert","resume_other");
		if(!in_array($table,$tables)){
			echo $table;
			exit();
		}
		$info=$this->obj->DB_select_once($table,"`id`='".$id."'");
		$info['skillval']=$userclass_name[$info['skill']];	
		$info['educationval']=$userclass_name[$info['education']];	
		$info['ingval']=$userclass_name[$info['ing']];
		$info['syear']=date("Y",$info['sdate']);
		$info['smonth']=date("m",$info['sdate']);
		$info['sday']=date("d",$info['sdate']);
		if($table=='resume_cert'){
			$info['sdate']=date("Y-m-d",$info['sdate']);
		}else{
			$info['sdate']=date("Y-m",$info['sdate']);
		}
		if($info['edate']>0){
			$info['eyear']=date("Y",$info['edate']);
			$info['emonth']=date("m",$info['edate']);
			$info['eday']=date("d",$info['edate']);
			$info['edate']=date("Y-m",$info['edate']);
		}else{
			$info['edate']='至今';
			$info['totoday']='1';
		}
		$info['numresume']=$numresume;
		if(is_array($info)){
			foreach($info as $k=>$v){
				$arr[$k]=iconv("gbk","utf-8",$v);
			}
		}
		echo json_encode($arr);die;
	}
	function user_left(){
		if($_GET['e']){
			$eid=(int)$_GET['e'];
			$resume_row=$this->obj->DB_select_once("user_resume","`eid`='".$eid."'");
			$this->yunset("resume_row",$resume_row);
			$numresume=$this->complete($resume_row);
		}else{
			$numresume=20;
		}
		$this->yunset("numresume",$numresume);
		return $numresume;
	}
	function finder(){
		$finder=$this->obj->DB_select_all("finder","`uid`='".$this->uid."' order  by `id` desc");
		$sdate=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','30'=>'最近一个月','90'=>'最近三个月');
		if($finder&&is_array($finder)){
			include PLUS_PATH."/com.cache.php";
			include PLUS_PATH."/job.cache.php";
			include PLUS_PATH."/industry.cache.php";
			include PLUS_PATH."/city.cache.php";
			foreach($finder as $key=>$val){
				$jobname=$findername=$arr=array();
				$para=@explode('##',$val['para']);
				$arr['m']='job'; 
				foreach($para as $val){
					$parav=@explode('=',$val);
					$arr[$parav[0]]=$parav[1];
				}
				if($arr['jobids']){
					$jobids=@explode(',',$arr['jobids']);
					foreach($jobids as $val){
						$jobname[]=$job_name[$val];
					}
					$findername[]=@implode('、',$jobname);
				}
				if($arr['hy']){$findername[]=$industry_name[$arr['hy']];}
				if($arr['job1']){$findername[]=$job_name[$arr['job1']];}
				if($arr['job1_son']){$findername[]=$job_name[$arr['job1_son']];}
				if($arr['job_post']){$findername[]=$job_name[$arr['job_post']];}
				if($arr['sdate']){$findername[]=$sdate[$arr['sdate']];}
				if($arr['type']){$findername[]=$comclass_name[$arr['type']];}
				if($arr['cityid']){$findername[]=$city_name[$arr['cityid']];}
				if($arr['exp']){$findername[]=$comclass_name[$arr['exp']];}
				if($arr['salary']){$findername[]=$comclass_name[$arr['salary']];}
				if($arr['edu']){$findername[]=$comclass_name[$arr['edu']];}
				if($arr['sex']){$findername[]=$comclass_name[$arr['sex']];}
				if($arr['keyword']){$findername[]=$arr['keyword'];}
				$finder[$key]['findername']=@implode('+',array_filter($findername));
				$_GET=array_merge($_GET,$arr);
				$finder[$key]['url']=searchListRewrite($_GET,$this->config);
			}
		}
		return $finder;
	}
}
?>