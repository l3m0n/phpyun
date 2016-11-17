<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class index_controller extends part_controller{

	function index_action(){
		$cache=$this->MODEL('cache')->GetCache(array('city','part'));
		$this->yunset($cache);
		if($_GET['type']){
			$search[]=$cache['partclass_name'][$_GET['type']];
		}
		if($_GET['cycle']){
			$search[]=$cache['partclass_name'][$_GET['cycle']];
		}
		if($_GET['cityid']){
			$search[]=$cache['city_name'][$_GET['cityid']];
		}
		if($_GET['keyword']){
			$search[]=$_GET['keyword'];
		}
		if(!empty($search)){
			$data['seacrh_class']=@implode("-",$search);
			$this->data=$data;
		}
 
    include PLUS_PATH."keyword.cache.php";
    if(is_array($keyword)){
      foreach($keyword as $k=>$v){
        if($v['type']=='2'&&$v['tuijian']=='1'){
          $partkeyword[]=$v;
        }
      }
    }
    $this->yunset("partkeyword",$partkeyword);
    
		$this->seo("part_index");
		$this->yun_tpl(array('index'));
	}
	function show_action(){
		if($_GET['id']){
			$id=(int)$_GET['id'];
			$M=$this->MODEL("part");
			$job=$M->GetPartJobOne(array("id"=>$id));
			if($job['uid']!=$this->uid&&($job['id']==''||$job['state']==0||$job['state']==3)){  
				$this->ACT_msg($this->config['sy_weburl'],"没有找到该兼职！"); 
			} 
			$this->yunset("job",$job);
			$M->AddPartJobHits($id);
			if($this->usertype==1){
				$apply=$M->GetPartApplyOne(array("uid"=>$this->uid,"jobid"=>$id));
				$this->yunset("apply",$apply);
				$collect=$M->GetPartCollectOne(array("uid"=>$this->uid,"jobid"=>$id));
				$this->yunset("collect",$collect);
			}
			$this->yunset($this->MODEL('cache')->GetCache(array('city','part')));
		}
		$data['part_name']=$job['name'];
		$this->data=$data;
		$this->seo("part_show");
		$this->yun_tpl(array('show'));
	}
}
?>