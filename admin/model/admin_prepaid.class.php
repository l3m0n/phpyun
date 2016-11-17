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
class admin_prepaid_controller extends common{
	function set_search(){
		$search_list[]=array("param"=>"time","name"=>'��Ч��',"value"=>array("1"=>"δ����","2"=>"�ѹ���"));
		$search_list[]=array("param"=>"status","name"=>'ʹ��״̬',"value"=>array("1"=>"��ʹ��","2"=>"δʹ��"));
		$search_list[]=array("param"=>"type","name"=>'״̬',"value"=>array("1"=>"����","2"=>"������"));
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where=1;
		if($_GET['keyword']){
			$where.=" and `card` like '%".$_GET['keyword']."%'";
			$urlarr['keyword']=$_GET['keyword'];
		}
		if($_GET['type']){
			if($_GET['type']=='1'){
				$where.=" and `type`='".$_GET['type']."' and `username` IS NULL";
			}else{
				$where.=" and `type`='".$_GET['type']."' or `username`<>''";
			}
			$urlarr['type']=$_GET['type'];
		}
		if($_GET['status']){
			if($_GET['status']==1){
				$where.=" and `uid`>'0'";
			}else{
				$where.=" and `uid` is null";
			}
			$urlarr['status']=$_GET['status'];
		}
		if($_GET['time']){
			if($_GET['time']==1){
				$where.=" and `etime`>'".time()."'";
			}else{
				$where.=" and `etime`<'".time()."' and `uid` is null";
			}
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['order']){
			$where.=" order by ".$_GET['t']." ".$_GET['order'];
			$urlarr['order']=$_GET['order'];
			$urlarr['t']=$_GET['t'];
		}else{
			$where.=" order by `id` desc";
		}
		$urlarr['page']="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
        $M=$this->MODEL();
		$PageInfo=$M->get_page("prepaid_card",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
		$this->yuntpl(array('admin/admin_prepaid'));
	}
	function upcard_action(){
		if($_POST['submit']){
			$stime=strtotime($_POST['stime'].' 00:00:00');
			$etime=strtotime($_POST['etime'].' 23:59:59');
			$nid=$this->obj->DB_update_all("prepaid_card","`stime`='".$stime."',`etime`='".$etime."',`password`='".trim($_POST['password'])."',`quota`='".trim($_POST['quota'])."',`type`='".$_POST['type']."'","`id`='".intval($_POST['id'])."' and `utime` is null");
			$nid?$this->ACT_layer_msg("��ֵ��(ID:".intval($_POST['id']).")���³ɹ���",9,"index.php?m=admin_prepaid",2,1):$this->ACT_layer_msg("����ʧ�ܣ�",8,$_SERVER['HTTP_REFERER']);;
		}
		if($_GET['id']){ 
            $info=$this->obj->DB_select_once("prepaid_card","`id`='".intval($_GET['id'])."'");
			if($info['id']){
				$this->yunset("info",$info);
				$this->yuntpl(array('admin/admin_prepaid_upcard'));
			}else{
				$this->ACT_msg("index.php?m=admin_prepaid","�Ƿ�����");
			}
		}
	}
	function add_action(){
		if($_POST['submit']){
			$quota=trim($_POST['quota']);
			$num=intval($_POST['num']);
			$cid=intval($_POST['cid']);
			$stime=strtotime($_POST['stime']);
			$etime=strtotime($_POST['etime']); 
			$type=trim($_POST['type']);
			$value=array();
			for($i=1;$i<=$num;$i++){
				$time = @explode(" ", microtime () );
				$time = $time[1].($time[0]*1000000);
				if(strlen($time)<16){
					$time=substr($time.'0000',0,16);
				}
				$card = substr($time.rand(100,999),0,16);
				$password=substr(base_convert($card,10,8),-5).rand(100,999);
				$value[]="('".$card."','".$password."','".$quota."','".$type."','".$stime."','".$etime."','".time()."')";
			}
			$this->obj->DB_query_all("INSERT INTO ".$this->def."prepaid_card(`card`,`password`,`quota`,`type`,`stime`,`etime`,`atime`) VALUES ".@implode(',',$value));
			$this->ACT_layer_msg("��ֵ����ӳɹ���",9,"index.php?m=admin_prepaid");
		}
		$this->yuntpl(array('admin/admin_prepaid_add'));
	}
	function rec_action(){
		intval($_GET['rec'])=='1'?$type='1':$type='2';
		$id=$this->obj->DB_update_all("prepaid_card","`type`='".$type."'","`id`='".$_GET['id']."'");
		$this->admin_log("��ֵ��(ID:".$_GET['id'].")״̬���óɹ���");
		echo $id?1:0;die;
	}

	function del_action(){
		if($_GET['del']){
			$this->check_token();
			$del=$_GET['del'];
			if(is_array($del)){
				$del=@implode(',',$del);
				$layer_type=1;
			}else{
				$layer_type=0;
			}
			$id=$this->obj->DB_delete_all("prepaid_card","`id` in (".$del.")"," ");
			$del?$this->layer_msg('��ֵ��(ID:'.$del.')ɾ���ɹ���',9,$layer_type,$_SERVER['HTTP_REFERER']):$this->layer_msg('ɾ��ʧ�ܣ�',8,$layer_type,$_SERVER['HTTP_REFERER']);
		}else{
			$this->layer_msg('��ѡ��Ҫɾ�������ݣ�',8);
		}
	}
}
?>