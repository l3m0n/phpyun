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
class index_controller extends zph_controller{
	function index_action(){
		$this->Zphpublic_action();
		$this->seo("zph");
		$this->zph_tpl('index');
	}
    function review_action(){
		$this->Zphpublic_action();
		$this->seo("zph");
		$this->zph_tpl('review');
	}
	function reserve_action()
	{
		$this->Zphpublic_action();
		$id=(int)$_GET['id'];
		$M=$this->MODEL('zph');
		$row=$M->GetZphOnce(array("id"=>$id));
		$row["stime"]=strtotime($row['starttime'])-mktime();
		$row["etime"]=strtotime($row['endtime'])-mktime();
		$rows=$M->GetZphPic(array("zid"=>$id));
		$data['zph_title']=$row['title'];
		$data['zph_desc']=$this->GET_content_desc($row['body']);
		$this->data=$data;
		$this->yunset("Info",$row);
		if($row['reserved']){
			$reserved=@explode(",",$row['reserved']);
			$this->yunset("reserved",$reserved);
		}
		$this->yunset("Image_info",$rows);
		$space=$M->GetZphspaceOnce(array("id"=>$row['sid']));
		$this->yunset("space",$space);
		$spacelist=$M->GetspaceList(array("keyid"=>$row['sid']),array("orderby"=>"sort","desc"=>"asc"));
		if(is_array($spacelist)){
			foreach($spacelist as $v){
				$keyid[]=$v['id'];
			}
			$keyid=@implode(",",$keyid);
			$spacelistall=$M->GetspaceList(array("keyid in (".$keyid.")"),array("orderby"=>"sort","desc"=>"asc"));
			$comlist=$M->GetZphComList(array("zid"=>$id));
			if(is_array($comlist)){
				foreach($comlist as $val){
					$uids[]=$val['uid'];
					$jobids[]=$val['jobid'];
				}
				$Company=$this->MODEL("company");
				$companylist=$Company->GetComList(array("uid in (".@implode(",",$uids).")"),array("field"=>"uid,name"));
				$Job=$this->MODEL("job");
				$joblist=$Job->GetComjobList(array("id in (".@implode(",",$jobids).")"),array("field"=>"id,name,lastupdate"));
				foreach($comlist as $k=>$v){
					foreach($companylist as $val){
						if($v['uid']==$val['uid'] ){
							$comlist[$k]['name']=$val['name'];
						}
					}
					$jobid=@explode(",",$v['jobid']);
					foreach($joblist as $val){
						if(in_array($val['id'],$jobid)){
							$comlist[$k]['joblist'][]=$val;
						}
					}
				}
				foreach($spacelistall as $k=>$v){
					$spacelistall[$k]['comstatus']="-1";
					foreach($comlist as $val){
						if($v['id']==$val['bid']){
							$spacelistall[$k]['comstatus']=$val['status'];
							$spacelistall[$k]['uid']=$val['uid'];
							$spacelistall[$k]['comname']=$val['name'];
							$spacelistall[$k]['joblist']=$val['joblist'];
						}
					}
				}
			}
			foreach($spacelist as $k=>$v){
				foreach($spacelistall as $val){
					if($v['id']==$val['keyid']){
						$spacelist[$k]['list'][]=$val;
					}
				}
			}
		} 
		$this->yunset("spacelist",$spacelist);
		$this->seo("zph_reserve");
		$this->zph_tpl('reserve');
	}
}
?>