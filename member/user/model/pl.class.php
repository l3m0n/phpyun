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
class pl_controller extends user{
	function index_action(){
		$this->public_action();
		$urlarr=array("c"=>"pl","page"=>"{{page}}");
		$pageurl=Url('member',$urlarr);
		$rows=$this->get_page("company_msg","`uid`='".$this->uid."'order by `ctime`",$pageurl,"10");
		foreach($rows as $v){
			$uid[]=$v['cuid'];
		}
		$uids=@implode(',',$uid);
		$name=$this->obj->DB_select_all("company","`uid`in(".$uids.")","`name`,`uid`");
	
		foreach($rows as $key=>$val){
			foreach($name as $k=>$v){
				if($val['cuid']==$v['uid']){
					$rows[$key]['com_name']=$v['name'];
				}
			}
		}
		$this->yunset("rows",$rows);
		$this->user_tpl('pl');
	}
    function del_action(){
        if($_GET['id']){
            $nid=$this->obj->DB_delete_all("company_msg","`id`='".(int)$_GET['id']."' AND `uid`='".$this->uid."'"," ");
            $this->obj->member_log("ɾ����ҵ����");
            if($nid){
	            $this->layer_msg('ɾ���ɹ���',9);
            }else{
	            $this->layer_msg('ɾ��ʧ�ܣ�',8);
            }
        }
    }
}
?>