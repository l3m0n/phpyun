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
class admin_company_partjob_controller extends common{
	function set_search(){
		$rating=$this->obj->DB_select_all("company_rating","`category`='1' order by `sort` asc","`id`,`name`");
		if(!empty($rating)){
			foreach($rating as $k=>$v){
                 $ratingarr[$v['id']]=$v['name'];
			}
		}
		$nrating=array();
		foreach($rating as $val){
			$nrating[$val['id']]=$val['name'];
		}
		$this->yunset("rating", $nrating);
		$edtime=array('1'=>'7����','2'=>'һ������','3'=>'������','4'=>'һ����');
		$this->yunset("edtime",$edtime);
		$search_list[]=array("param"=>"rating","name"=>'��Ա�ȼ�',"value"=>$ratingarr);
		$search_list[]=array("param"=>"time","name"=>'����ʱ��',"value"=>$edtime);
		$this->yunset("ratingarr",$ratingarr);
		$this->yunset("search_list",$search_list);
	}
	function index_action(){
		$this->set_search();
		$where=$mwhere="1";
		$uids=array();
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
			$where.=" and `time_end`>'".time()."' and `time_end`<'".strtotime($num)."'";
			$urlarr['time']=$_GET['time'];
		}
		if($_GET['rating']){
			$where.=" and  `rating_id`='".$_GET['rating']."'";
			$urlarr['rating']=$_GET['rating'];
		}
	    if($_GET['keyword']){
			$where .= " and `username` like '%".$_GET['keyword']."%' ";
			$urlarr['keyword']=$_GET['keyword'];
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
		$PageInfo=$M->get_page("hotjob",$where,$pageurl,$this->config['sy_listnum']);
        $this->yunset($PageInfo);
        $rows=$PageInfo['rows'];
 		if(is_array($rows)&&$rows){
			if(empty($list)){
				$list=$this->obj->DB_select_all("company_statis","`uid` in (".@implode(",",$uids).")","`uid`,`pay`,`integral`,`rating`,`rating_name`,`vip_etime`,`msg_num`");
			}
 			foreach($rows as $k=>$v){
				$rows[$k]['hot_pics']="<a href=\"javascript:void(0)\" class=\"previews\" url=\"".$v['hot_pic']."\">ͼƬԤ��</a>";
 				if(strlen($v['username'])>14){
					$rows[$k]['username']=mb_substr($v['username'],"0","12","gbk")."...";
 				}
				if(strlen($v['beizhu'])>24){
					$rows[$k]['beizhu']=mb_substr($v['beizhu'],"0","12","gbk")."...";
 				}
				if($v['did']<1)
				{
					$rows[$k]['did'] = 0;
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
		$this->yunset("where", $where);
		$this->yunset("rows",$rows);
		$this->yuntpl(array('admin/admin_company_partjob'));
	}
	
}
?>