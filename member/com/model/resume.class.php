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
class resume_controller extends company{
    function index_action(){
        $uptime=array(1=>'����',3=>'���3��',7=>'���7��',30=>'���һ����',90=>'���������');
        $this->yunset('uptime',$uptime);
        $CacheM=$this->MODEL('cache');
        $CacheList=$CacheM->GetCache (array('city','user','job','hy'));
        $this->yunset($CacheList);
        $this->yunset("type",$_GET['type']);
        $this->public_action();
        $this->yunset("js_def",5);
        $this->com_tpl('resume');
    }
}