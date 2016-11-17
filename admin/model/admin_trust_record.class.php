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
class admin_trust_record_controller extends common
{
	function index_action()
	{
		$where = "1";
		if($_GET['keyword']!="")
		{
			if($_GET['type']=="1"||$_GET['type']=="")
			{
				$resume=$this->obj->DB_select_all("resume_expect","`name` like '%".$_GET['keyword']."%'","`id`,`name`");
				if(is_array($resume))
				{
					foreach($resume as $v)
					{
						$eid[]=$v['id'];
					}
				}
				$where.=" and `eid` in (".@implode(",",$eid).")";
			}else{
				if($_GET['type']=="2")
				{
					$jobwhere="`com_name` like '%".$_GET['keyword']."%'";
				}else{
					$jobwhere="`name` like '%".$_GET['keyword']."%'";
				}
				$job=$this->obj->DB_select_all("company_job",$jobwhere,"`id`,`name`,`com_name`");
				if(is_array($job))
				{
					foreach($job as $v)
					{
						$jobid[]=$v['id'];
					}
				}
				$where.=" and `jobid` in (".@implode(",",$jobid).")";
			}
			$urlarr['type']=$_GET['type'];
			$urlarr['keyword']=$_GET['keyword'];
		}
		$urlarr["page"]="{{page}}";
		$pageurl=Url($_GET['m'],$urlarr,'admin');
		$list=$this->get_page("user_entrust_record",$where." order by `id` desc",$pageurl,$this->config['sy_listnum']);
		if(is_array($list))
		{
			foreach($list as $v)
			{
				$eid[]=$v['eid'];
				$jobid[]=$v['jobid'];
			}
			if($_GET['keyword']!="")
			{
				if($_GET['type']=="1" || $_GET['type']=="")
				{
					$job=$this->obj->DB_select_all("company_job","`id` in (".@implode(",",$jobid).")","`id`,`name`,`com_name`");
				}else{
					$resume=$this->obj->DB_select_all("resume_expect","`id` in (".@implode(",",$eid).")","`id`,`name`");
				}
			}else{
				$resume=$this->obj->DB_select_all("resume_expect","`id` in (".@implode(",",$eid).")","`id`,`name`");
				$job=$this->obj->DB_select_all("company_job","`id` in (".@implode(",",$jobid).")","`id`,`name`,`com_name`");
			}
			foreach($list as $k=>$v)
			{
				foreach($resume as $val)
				{
					if($v['eid']==$val['id'])
					{
						$list[$k]['resume_name']=$val['name'];
					}
				}
				foreach($job as $val)
				{
					if($v['jobid']==$val['id'])
					{
						$list[$k]['job_name']=$val['name'];
						$list[$k]['com_name']=$val['com_name'];
					}
				}
			}
		}
		$this->yunset("list",$list);
		$this->yuntpl(array('admin/admin_trust_record'));
	}

	function del_action()
	{
		$this->check_token();
	    if($_GET['del'])
	    {
	    	if(is_array($_GET['del']))
	    	{
	    		$del=@implode(",",$_GET['del']);
	    		$layer_status=1;
	    	}else{
	    		$del=$_GET['del'];
	    		$layer_status=0;
	    	}
	    	$this->obj->DB_delete_all("user_entrust_record","`id` in (".$del.")","");
	    	$this->layer_msg( "��������(ID:".@implode(',',$del).")ɾ���ɹ���",9,$layer_status,$_SERVER['HTTP_REFERER']);
	    }else{
	    	$this->ACT_layer_msg("�Ƿ�������",8,$_SERVER['HTTP_REFERER']);
	    }
	}

}
?>