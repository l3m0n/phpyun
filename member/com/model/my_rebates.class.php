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
class my_rebates_controller extends company
{
	function index_action()
	{
		$this->public_action();
		$this->company_satic();
		$this->yunset("js_def",5);
		$urlarr=array("c"=>$_GET['c'],"page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("rebates","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
		if(is_array($rows))
		{
			foreach($rows as $k=>$v)
			{
				$uid[]=$v['job_uid'];
			}
			$uid=pylode(",",$uid);
			$user=$this->obj->DB_select_all("member","`uid` in (".$uid.")","`uid`,`username`");
			foreach($rows as $k=>$v)
			{
				foreach($user as $val)
				{
					if($v['job_uid']==$val['uid'])
					{
						$rows[$k]['username']=$val['username'];
					}
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->com_tpl('my_rebates');
	}
}
?>