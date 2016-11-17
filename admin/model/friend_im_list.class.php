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
class friend_im_list_controller extends common
{
	function set_search(){
		$ad_time=array('1'=>'今天','3'=>'最近三天','7'=>'最近七天','15'=>'最近半月','30'=>'最近一个月');
		$search_list[]=array("param"=>"end","name"=>'聊天时间',"value"=>$ad_time);
		$this->yunset("search_list",$search_list);
	}
	function index_action()
	{
		include(PLUS_PATH."user.cache.php");
		include(PLUS_PATH."com.cache.php");
		$this->set_search();
		$where = "1";
		if(trim($_GET['keyword'])){
			if($_GET['type']==3){
				$where.=" and `username` like '%".$_GET['keyword']."%'";
			}elseif ($_GET['type']=='2'){
				$where.=" and `content` like '%".$_GET['keyword']."%'";
			}elseif ($_GET['type']=='1'){
				$friendinfo=$this->obj->DB_select_all("member","`username` like '%".$_GET['keyword']."%'","`uid`");
				if (is_array($friendinfo)){
					foreach ($friendinfo as $key=>$val){
						$friuids[]=$val['uid'];
					}
					$listuids=@implode(",",$friuids);
				}
				$where.=" and `touid` in (".$listuids.")";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['end']){
			if($_GET['end']=='1'){
				$where.=" and `ctime` >= '".strtotime(date("Y-m-d 00:00:00"))."'";
			}else{
				$where.=" and `ctime` >= '".strtotime('-'.(int)$_GET['end'].'day')."'";
			}
			$urlarr['end']=$_GET['end'];
		}
 		 
		if($_GET['order'])
		{
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by id desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$mes_list=$this->get_page("friend_im_list",$where,$pageurl,$this->config['sy_listnum']);
		if(is_array($mes_list)&&$mes_list){
			$tuid=array();
			foreach($mes_list as $val){
				$tuid[]=$val['touid'];
			}
			$statis =$this->obj->DB_select_all("member","`uid` in(".@implode(',',$tuid).")","username,uid");
			foreach($mes_list as $key=>$value){
				foreach($statis as $k=>$v){
					if($value['touid']==$v['uid']){
						$mes_list[$key]['name'] = $v['username'];
					}
				}
			}
		}
		$this->yunset("get_type", $_GET);
		$this->yunset("mes_list",$mes_list);
		$this->yuntpl(array('admin/friend_im_list'));
	}

	function del_action(){
		$this->check_token();
 	    if($_GET['del']){
	    	$del=$_GET['del'];
	    	if($_GET['del']){
	    		if(is_array($_GET['del'])){
					$this->obj->DB_delete_all("friend_im_list","`id` in(".@implode(',',$_GET['del']).")","");
					$del=@implode(',',$_GET['del']);
		    	}else{
		    		$this->obj->DB_delete_all("friend_im_list","`id`='$del'");
		    	}
	    		$this->layer_msg( "聊天记录(ID:".$del.")删除成功！",9,1,$_SERVER['HTTP_REFERER']);
	    	}else{
				$this->layer_msg( "请选择您要删除的信息！",8,1,$_SERVER['HTTP_REFERER']);
	    	}
	    }
 	    if(isset($_GET['id'])){
			$result=$this->obj->DB_delete_all("friend_im_list","`id`='".$_GET['id']."'" );
			isset($result)?$this->layer_msg('聊天记录(ID:'.$_GET['id'].')删除成功！',9,0,$_SERVER['HTTP_REFERER']):$this->layer_msg('删除失败！',8,0,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('非法操作！',3,0,$_SERVER['HTTP_REFERER']);
		}
	}
}
?>