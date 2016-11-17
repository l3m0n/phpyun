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
class zph_space_controller extends common
{
	function index_action(){
		$position=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='0' order by sort asc");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/zph_space'));
	}
	function classadd_action()
	{
		if($_GET['id'])
		{
			$info=$this->obj->DB_select_once("zhaopinhui_space","`id`='".$_GET['id']."'");
			$this->yunset("info",$info);
		}else{
			$position=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='0' order by sort asc");
			$this->yunset("position",$position);
		}
		$this->yuntpl(array('admin/zph_space_classadd'));
	}
	function save_action()
	{
		if($_POST['submit'])
		{
			if($_POST['keyid']!="")
			{
				$value.="`keyid`='".$_POST['keyid']."',";
				$value.="`price`='".$_POST['price']."',";
			}elseif($_POST['nid']!=""){
				$value.="`keyid`='".$_POST['nid']."',";
			}
			$value.="`name`='".$_POST['position']."',";
			$value.="`sort`='".$_POST['sort']."',";
			$content=str_replace("&amp;","&",html_entity_decode($_POST['content'],ENT_QUOTES,"GB2312"));
			$value.="`content`='".$content."'";
			if($_FILES['pic']['tmp_name']!=""){
				$upload=$this->upload_pic("../data/upload/zhaopinhui/","22");
				$pictures=$upload->picture($_FILES['pic']);
				$value.=",`pic`='".str_replace("../","",$pictures)."'";
				if($_POST['id']){
					$row=$this->obj->DB_select_once("zhaopinhui_space","`id`='".$_POST['id']."'");
					if($row['pic']!=""){
						@unlink("../".$row['pic']);
					}
				}
			}
			if($_POST['id'])
			{
				$nid=$this->obj->DB_update_all("zhaopinhui_space",$value,"`id`='".$_POST['id']."'");
				$msg="更新";
			}else{
				$nid=$this->obj->DB_insert_once("zhaopinhui_space",$value);
				$msg="添加";
			}
			$nid?$this->ACT_layer_msg($msg."成功！",9,"index.php?m=zph_space",2,1):$this->ACT_layer_msg($msg."失败！",8,"index.php?m=zph_space");
		}
	}
	function ajaxjob_action(){
		extract($_GET);
		if($id!=""){
		    $jobs=$this->obj->DB_select_all("zhaopinhui_space","`keyid`=$id");
			if(is_array($jobs)){
				$html .= "";
				if($ajax=="1"){
					$html .= "<option value=\"\">==请选择==</option>";
				}
				foreach($jobs as $key=>$v){
					$html .= "<option value='".$v['id']."'>".$v['name']."</option>";
				}
				
				echo $html;
			 	die;
			}die;
		}
	}
	function up_action(){
		if((int)$_GET['id']){
			$id=(int)$_GET['id'];
			$onejob=$this->obj->DB_select_once("zhaopinhui_space","`id`='".$_GET['id']."'");
			$twojob=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$_GET['id']."' order by sort asc","id,sort");
			if(is_array($twojob)){
				foreach($twojob as $key=>$v){
					$val[]=$v['id'];
					$root_arr = @implode(",",$val);
				}
			}
			$jobs=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='".$_GET['id']."' or `keyid` in ($root_arr) order by sort asc");
			$a=0;
			if(is_array($jobs)){
				foreach($jobs as $key=>$v){
					if($v['keyid']==$id){
						$twojob[$a]['id']=$v['id'];
						$twojob[$a]['sort']=$v['sort'];
						$twojob[$a]['name']=$v['name'];
						$a++;
					}else{
						$threejob[$v['keyid']][]=$v;
					}
				}
			}
			$this->yunset("id",$id);
			$this->yunset("onejob",$onejob);
			$this->yunset("twojob",$twojob);
			$this->yunset("threejob",$threejob);
		}
		$position=$this->obj->DB_select_all("zhaopinhui_space","`keyid`='0'");
		$this->yunset("position",$position);
		$this->yuntpl(array('admin/zph_space'));
	}
	function del_action(){
		if((int)$_GET['delid']){
			$this->check_token();
			$ids=array(intval($_GET['delid']));
			$layer_type='0';
		}else if($_POST['del']){
			foreach($_POST['del'] as $v){
				if(intval($v)){
					$ids[]=intval($v);
				}
			}
			$layer_type='1';
		}
		$id=$this->obj->DB_delete_all("zhaopinhui_space","`id` in (".@implode(',',$ids).")","");
		isset($id)?$this->layer_msg('删除成功！',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,$layer_type,$_SERVER['HTTP_REFERER']);
	}
	function ajax_action()
	{
		if($_POST['sort'])
		{
			$this->obj->DB_update_all("zhaopinhui_space","`sort`='".$_POST['sort']."'","`id`='".$_POST['id']."'");
			$this->admin_log("修改职位类别(ID:".$_POST['id'].")的排序");
		}
		if($_POST['name'])
		{
			$_POST['name']=iconv("UTF-8","gbk",$_POST['name']);
			$this->obj->DB_update_all("zhaopinhui_space","`name`='".$_POST['name']."'","`id`='".$_POST['id']."'");
			$this->admin_log("修改职位类别(ID:".$_POST['id'].")的名称");
		}
		echo '1';die;
	}
}

?>