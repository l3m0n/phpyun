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