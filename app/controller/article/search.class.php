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
class search_controller extends article_controller{
	function index_action(){ 
	
		$M=$this->MODEL('article');
        $class=$M->GetNewsGroupOnce(array('id'=>(int)$_GET['kid']),array('field'=>'`name`'));
        $news_group=$M->GetNewsGroupList();
        $a=0;$b=0;
        if(is_array($news_group)){
          foreach($news_group as $key=>$v){
            if($v['keyid']==0){
              $one_class[$a]['id']=$v['id'];
              $one_class[$a]['name']=$v['name'];
              $a++;
            }
            if($v['keyid']!=0){
              if(!is_array($two_class[$v['keyid']]))$b=0;
              $two_class[$v['keyid']][$b]['id']=$v['id'];
              $two_class[$v['keyid']][$b]['name']=$v['name'];
              $two_class[$v['keyid']][$b]['keyid']=$v['keyid'];
              $b++;
            }
          }
        }
        $this->yunset("one_class",$one_class);
        $this->yunset("two_class",$two_class);

        $this->yunset("classname",$class['name']);
		$data['news_class']=$class['name'];
		$this->data=$data;
		
		
		$urlarr["c"]="search";
		if($_GET['keyword']){
			$where="`title` like '%".trim($_GET['keyword'])."%'";
			$urlarr["keyword"]=$_GET['keyword'];
		}else{
			$where=1; 
		}
		$_GET['kid']=(int)$_GET['kid'];
		if($_GET['kid']){
			$where.=" and `kid`='".$_GET['kid']."'";
			$urlarr["kid"]=$_GET['kid'];
		}
		$_GET['nid']=(int)$_GET['nid'];
   		if($_GET['nid']){
			$where.=" and `nid`='".$_GET['nid']."'";
			$urlarr["nid"]=$_GET['nid'];
		}
		
		$urlarr["page"]="{{page}}";
		$pageurl=Url("article",$urlarr);
		$rows=$this->get_page("news_base",$where." order by `datetime` desc",$pageurl,"10");
		$this->yunset("rows",$rows);
		$row=$this->obj->DB_select_all("news_group");
		$this->yunset("row",$row);
		
		$this->seo("newslist");
		$this->news_tpl('search');
	}
}
?>