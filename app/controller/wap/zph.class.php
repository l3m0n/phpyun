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
class zph_controller extends common{
	function index_action(){
		$this->yunset("headertitle","��Ƹ��");
		$this->seo("zph");
		$this->yuntpl(array('wap/zph'));
	}
	function show_action(){
		$id=(int)$_GET['id'];
		$M=$this->MODEL('zph');
	    if($id){
	      $row=$M->GetZphOnce(array("id"=>$id));
	      $row["stime"]=strtotime($row['starttime'])-mktime();
	      $row["etime"]=strtotime($row['endtime'])-mktime();
	      $data['zph_title']=$row['title'];
	      $data['zph_desc']=$this->GET_content_desc($row['body']);
	    }
		$this->data=$data;
		$this->yunset("Info",$row);
		$this->yunset("headertitle","��Ƹ������");
		$this->seo("zph_show");
		$this->yuntpl(array('wap/zph_show'));
	}
	function com_action(){
		$id=(int)$_GET['id'];
		$M=$this->MODEL('zph');
		$Job=$this->MODEL('job');
		$UserinfoM=$this->MODEL('userinfo');
	    if($id){
	        $row=$M->GetZphOnce(array("id"=>$id),array('field'=>'starttime,endtime,title,body'));
	        $row["stime"]=strtotime($row['starttime'])-mktime();
	        $row["etime"]=strtotime($row['endtime'])-mktime();
	        $data['zph_title']=$row['title'];
	        $data['zph_desc']=$this->GET_content_desc($row['body']);
	        $urlarr["c"]=$_GET['c'];
	        $urlarr["a"]=$_GET['a'];
	        $urlarr["id"]=(int)$_GET['id'];
	        $urlarr["page"]="{{page}}";
	        $pageurl=Url('wap',$urlarr,"1");
	        $rows=$M->get_page("zhaopinhui_com","`zid`='".(int)$_GET['id']."' and status='1'  order by id desc",$pageurl,"13");
	        if(is_array($rows['rows'])&&$rows['rows']){
				$uid=$bid=$jobid=array();
				foreach($rows['rows'] as $v){
					$uid[]=$v['uid'];
					$bid[]=$v['bid'];
					$jobid[]=$v['jobid'];
				}
				$com=$M->GetZphComInfo($UserinfoM,array("uid in(".@implode(",",$uid).")"),array("field"=>"`uid`,`name`"));
				$bidspace=$M->GetspaceList(array("id in(".@implode(",",$bid).")"),array('field'=>'id,name'));
				$jobs=$Job->GetComjobList(array("`id` in (".@implode(",",$jobid).")  and `status`<>'1' and `r_status`<>'2'"),array('field'=>"name,uid,id")); 
				foreach($rows['rows'] as $key=>$v){
					foreach($com as $val){
						if($v['uid']==$val['uid']){
							$rows['rows'][$key]['comname']=$val['name'];
						}
					}
					foreach($bidspace as $val){
						if($v['bid']==$val['id']){
							$rows['rows'][$key]['bidname']=$val['name'];
						}
					}
					foreach($jobs as $val){
						if($v['uid']==$val['uid']){
							$rows['rows'][$key]['job'][]=array('id'=>$val['id'],'uid'=>$val['uid'],'name'=>$val['name']);
						}
					}
				}
	        }
	    } 
		$this->yunset($rows);
		$this->yunset("row",$row);
		$this->data=$data;
		$this->yunset("headertitle","�λ���ҵ");
		$this->seo("zph_com");
		$this->yuntpl(array('wap/zph_com'));
	}
}
?>