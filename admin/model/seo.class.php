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
class seo_controller extends common
{

	function index_action(){
		include (CONFIG_PATH."/db.data.php");
		$this->yunset("arr_data",$arr_data);
		$action=$_GET[action]?$_GET[action]:"index";
		$where="`seomodel`='".$action."'";
		$seolist = $this->obj->DB_select_all("seo",$where);
		$this->yunset("get_type",$_GET);
		$this->yunset("seolist",$seolist);
		$this->yuntpl(array('admin/admin_list_seo'));
	}
	function SeoAdd_action()
	{
		include(CONFIG_PATH."db.data.php"); 
		$this->yunset("arr_data",$arr_data);
		include PLUS_PATH."/domain_cache.php";  
		$domainnum=ceil((count($site_domain)+1)/4); 
		$this->yunset("domain",$site_domain); 
		$this->yunset("domainnum",$domainnum);
		if($domainnum<2){
			$this->yunset("ieheight",40);
		}else{
			$this->yunset("ieheight",($domainnum*35)+10);
		} 
		$this->yuntpl(array('admin/admin_add_seo'));
	}
	function Modify_action()
	{
		include PLUS_PATH."/domain_cache.php";  
		$domainnum=ceil((count($site_domain)+1)/4); 
		$this->yunset("domain",$site_domain); 
		$this->yunset("domainnum",$domainnum);
		if($domainnum<2){
			$this->yunset("ieheight",40);
		}else{
			$this->yunset("ieheight",($domainnum*35)+10);
		} 
		
		if($_GET['id']){
			$seo= $this->obj->DB_select_once("seo","`id`='".$_GET['id']."'");
			foreach($site_domain as $v){
				if($v['id']==$seo['did']&&$seo['did']>0){
					$this->yunset("domainname",$v['webtitle']);
				}
			}  
			$this->yunset("seo",$seo);
		}
		include(CONFIG_PATH."db.data.php"); 
		$this->yunset("arr_data",$arr_data);
		$this->yuntpl(array('admin/admin_add_seo'));
	}
	function Save_action(){
		extract($_POST);
		$value = "`seoname`='$seoname',";
		$value.= "`ident`='$ident',";
		$value.= "`seomodel`='$seomodel',";
		$value.= "`title`='$title',";
		$value.= "`keywords`='$keywords',";
		$value.= "`php_url`='$php_url',";
		$value.= "`rewrite_url`='$rewrite_url',";
		$value.= "`description`='$description',";
		$value.= "`did`='$did',";
		$value.= "`time`='".time()."'";

		if($_POST['update'])
		{
			$this->obj->DB_update_all("seo",$value,"`id`='$id'");
			$this->cache_action();
			$msg = "SEO �޸ĳɹ���";
		}elseif($_POST['add']){
			$this->obj->DB_insert_once("seo",$value);
			$this->cache_action();
			$msg = "SEO ��ӳɹ���";
		}
		$this->ACT_layer_msg( $msg,9,"index.php?m=seo&action=".$seomodel,2,1);
		$this->yuntpl(array('admin/admin_add_seo'));
	}

	function getseo_action()
	{
		include(PLUS_PATH."seo.cache.php"); 
	}
	function del_action()
	{
		if($_GET['del'])
		{
			$this->check_token();
	    	if(is_array($_GET['del']))
	    	{
	    		$del=@implode(",",$_GET['del']);
	    		$layer_status=1;
	    	}else{
	    		$del=$_GET['del'];
	    		$layer_status=0;
	    	}
			$id=$this->obj->DB_delete_all("seo","`id` in (".$del.")","");
			if($id)
			{
				$this->cache_action();
				$this->layer_msg('SEO(ID:'.$del.')ɾ���ɹ���',9,$layer_status,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('SEO(ID:'.$del.')ɾ��ʧ�ܣ�',8,$layer_status,$_SERVER['HTTP_REFERER']);
			}
		}

	}
	function cache_action(){
		include(LIB_PATH."cache.class.php");
		$cacheclass= new cache(PLUS_PATH,$this->obj);
		$makecache=$cacheclass->seo_cache("seo.cache.php");
	}

}