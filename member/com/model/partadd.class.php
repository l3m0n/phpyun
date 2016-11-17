<?php
/* *
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
*/
class partadd_controller extends company{
	function index_action(){
		if($_GET['id']){
			$row=$this->obj->DB_select_once("partjob","`uid`='".$this->uid."' and `id`='".(int)$_GET['id']."'");
			if($row['worktime']!=""){
				$worktime=@explode(",",$row['worktime']);
				foreach($worktime as $k=>$v){
					$arr=@explode(":",$v);
					$arrs=@explode("-",$arr[1]);
					$data.='<div class="part_hour" id="handletime_'.$k.'"><input type="hidden" name="worktime[]" value="'.$v.'"><span>时间段'.$v.'</span><em><a href="javascript:Save_time(\''.$k.'\',\''.$arr[0].'\',\''.$arrs[0].'\',\''.$arrs[1].'\',\''.$arr[2].'\');">修改</a><a href="javascript:Delete_time(\''.$k.'\');">删除</a></em></div>';
				}
				$this->yunset("worktime",$data);
			}
			$this->yunset("row",$row);
		}else{
			$statics = $this->company_satic();
			if( $statics['addpartjobnum'] == 2){
				if(intval($statics['integral']) < intval($this->config['integral_partjob'])){
					$this->ACT_msg($_SERVER['HTTP_REFERER'],"你的".$this->config['integral_pricename']."不够发布兼职！",8);
				}
			}
		
		}
		$save=$this->obj->DB_select_once("lssave","`uid`='".$this->uid."'and `savetype`='5'");
		$save=unserialize($save['save']);
		if($save['lastupdate']){
			$save['time']=date('H:i',ceil(($save['lastupdate'])));
		}
		$this->yunset("save",$save);
		$this->yunset($this->MODEL('cache')->GetCache(array('city','part')));
		$this->company_satic();
		$this->yunset("today",date("Y-m-d"));
		$this->yunset("js_def",6);
		$this->com_tpl('partadd');
	}
	function save_action(){
		if($_POST['submit']){
			if($_POST['worktime']){
				$_POST['worktime']=@implode(",",$_POST['worktime']);
			}
			$_POST['content']=str_replace(array("&amp;","background-color:#ffffff","background-color:#fff","white-space:nowrap;"),array("&",'background-color:','background-color:','white-space:'),html_entity_decode($_POST['content'],ENT_QUOTES,"GB2312"));
			$_POST['sdate']=strtotime($_POST['sdate']);
			$_POST['deadline']=strtotime($_POST['deadline']);
			if($_POST['timetype']){
				$_POST['edate']="";
			}else{
				$_POST['edate']=strtotime($_POST['edate']);
				if($_POST['deadline']>$_POST['edate']){
					$this->ACT_layer_msg("截至日期不能大于结束日期！",8);
				}
			}
			
			$_POST['addtime'] = time();
			$_POST['lastupdate'] = time();
			$_POST['state'] = $this->config['com_partjob_status'];
			$_POST['uid'] = $this->uid;
			$id=(int)$_POST['id'];
			unset($_POST['submit']);
			unset($_POST['id']);
			$company=$this->obj->DB_select_once("company","`uid`='".$this->uid."'","`name`,`did`");
			$_POST['com_name']=$company['name'];
			$_POST['did']=$company['did'];
			if(!$id){
				$this->get_com(7);
				$nid=$this->obj->insert_into("partjob",$_POST);
				$name="添加兼职职位";
				$type='1';
				if($nid){
					$this->obj->DB_delete_all("lssave","`uid`='".$this->uid."'and `savetype`='5'");
					$state_content = "新发布了兼职职位 <a href=\"".url("part",array("c"=>"show","id"=>$nid))."\" target=\"_blank\">".$_POST['name']."</a>。";
					$this->addstate($state_content,2);
				}
			}else{
				$job=$this->obj->DB_select_once("partjob","`id`='".$id."'","state");
				if($job['state']=="1" || $job['state']=="2"){
					$this->get_com(8);
				}
				$where['id']=$id;
				$where['uid']=$this->uid;
				$nid=$this->obj->update_once("partjob",$_POST,$where);
				$name="更新兼职职位";
				$type='2';
			}
			if($nid){
				$this->obj->member_log($name."《".$_POST['name']."》",1,$type);
				$this->ACT_layer_msg($name."成功！",9,"index.php?c=part");
			}else{
				$this->ACT_layer_msg($name."失败！",8,$_SERVER['HTTP_REFERER']);
			}
		}
	}
}
?>