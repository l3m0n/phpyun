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
class index_controller extends common{
	function index_action(){
		if($this->config['sy_wzp_web']=="2"){
			header("location:".Url('error'));
		}
		if($_GET['keyword']=='������Ҫ�����Ĺؼ���'){
			$_GET['keyword']='';
		}
		$M=$this->MODEL('once');
		$ip=fun_ip_get();
		$start_time=strtotime(date('Y-m-d 00:00:00')); 
		$mess=$M->GetOncejobNum(array('login_ip'=>$ip,'`ctime`>\''.$start_time.'\''));
		if($this->config['sy_once']>0){
			$num=$this->config['sy_once']-$mess;
		}else{
			$num=1;
		} 
		$this->yunset("num",$num);
		if($_POST['submit']){
            session_start();
			$authcode=md5($_POST['authcode']);
			$password=md5($_POST['password']);
			$id=(int)$_POST['id'];
			$submit=$_POST['submit'];
			unset($_POST['authcode']);
			unset($_POST['password']);
			unset($_POST['submit']);
			unset($_POST['id']);
			$_POST['status']=$this->config['com_fast_status'];
			$_POST['login_ip']=$ip;
			$_POST['ctime']=time();
			if(is_uploaded_file($_FILES['pic']['tmp_name'])){
				$upload=$this->upload_pic("../data/upload/once/",false);
				$pictures=$upload->picture($_FILES['pic']);
				$pic=str_replace("../data/upload/once/","data/upload/once/",$pictures);
				$_POST['pic']=$pic;
				if($_POST['id']){
					$row=$this->obj->DB_select_once("once_job","`id`='".$_POST['id']."'");
					unlink_pic("../".$row['pic']);
				}
			}
			if(strstr($this->config['code_web'],'������Ƹ')&&$authcode!=$_SESSION['authcode']){ 
				unset($_SESSION['authcode']);
				$this->ACT_layer_msg("��֤�����",8); 
			}
			$_POST['did']=$_COOKIE['did'];
			$_POST['edate']=strtotime("+".(int)$_POST['edate']." days");
			if($id){
				$arr=$M->GetOncejobOne(array('id'=>$id,'password'=>$password));
				if(empty($arr)){
					$this->ACT_layer_msg("���벻��ȷ",8,$_SERVER['HTTP_REFERER']);
				}
				$_POST['status']=0;
                $M->UpdateOncejob($_POST,array('id'=>$id));
				if($this->config['com_fast_status']=="0"){$msg="�޸ĳɹ����ȴ���ˣ�";}else{$msg="�޸ĳɹ�!";}
			}else{
				
				$_POST['password']=$password;
				
				if($num){
					$M->AddOncejob($_POST);
					if($this->config['com_fast_status']=="0"){$msg="�����ɹ����ȴ���ˣ�";}else{$msg="�����ɹ�!";}
				}else{
					$this->ACT_layer_msg("һ����ֻ�ܷ���".$this->config['sy_once']."�Σ�",8,$_SERVER['HTTP_REFERER']);
				} 
			}
			$this->ACT_layer_msg($msg,9,'index.php');
		}
		if((int)$_GET['id']){
			$onceinfo=$M->GetOncejobOne(array('id'=>(int)$_GET['id']));
			if(!empty($onceinfo)){

                $onceinfo=array_merge($onceinfo,array('title'=>yun_iconv("gbk","utf-8",$onceinfo["title"]),'companyname'=>yun_iconv("gbk","utf-8",$onceinfo["companyname"]),'linkman'=>yun_iconv("gbk","utf-8",$onceinfo["linkman"]),'address'=>yun_iconv("gbk","utf-8",$onceinfo["address"]),'require'=>yun_iconv("gbk","utf-8",$onceinfo["require"]),'edate'=>ceil(($onceinfo['edate']-mktime())/86400)));
				echo json_encode($onceinfo);die;
			}
			$this->yunset('once_id',$_GET['id']);
		}

		include PLUS_PATH."keyword.cache.php";
		if(is_array($keyword)){
		  foreach($keyword as $k=>$v){
			if($v['type']=='1'&&$v['tuijian']=='1'){
			  $oncekeyword[]=$v;
			}
		  }
		}
		$this->yunset("oncekeyword",$oncekeyword);

		$this->seo("once");
		$this->yunset("ip",$ip);
		$this->yun_tpl(array('index'));
	}
	function ajax_action(){
        session_start();
		if(md5($_POST['code'])!=$_SESSION['authcode'] || empty($_SESSION['authcode'])){
			unset($_SESSION['authcode']);
			echo 1;die;
		}
        
		$M=$this->MODEL('once');
        
		$jobinfo=$M->GetOncejobOne(array('id'=>(int)$_POST['tid'],'password'=>md5($_POST['pw'])));
		if(!is_array($jobinfo) || empty($jobinfo)){
			echo 2;die;
		}
		if($_POST['type']==1){
            
			if($this->config['com_xin']>$jobinfo['sxnumber'])
			{
				$M->UpdateOncejob(array('ctime'=>time(),'sxtime'=>time(),'sxnumber'=>$jobinfo['sxnumber']+1),array('id'=>(int)$jobinfo['id']));
				echo 3;die;
			}
			else
			{
				echo 5;die;
			}						
		}elseif($_POST['type']==3){
            
			$M->DeleteOncejob(array('id'=>(int)$jobinfo['id']));
			echo 4;die;
		}else{
			if($jobinfo['edate']>mktime()){
				$jobinfo['edate']=ceil(($jobinfo['edate']-mktime())/86400);
			}else{
				$jobinfo['edate']="�ѹ���";
			}
            

			$jobinfo = yun_iconv('gbk','utf-8',$jobinfo);
			echo json_encode($jobinfo);die;
		}
	}
	function show_action(){
		if(isset($_GET['id'])){
		   $id=(int)$_GET['id'];
            
		   $M=$this->MODEL('once');
           
		   $M->UpdateOncejob(array("`hits`=`hits`+1"),array('id'=>$id));
           
           $o_info=$M->GetOncejobOne(array('id'=>$id));
		}
		$ip=fun_ip_get();
		$this->yunset("ip",$ip);
		$o_info['require'] = str_replace("\r\n","<br>",$o_info['require']);
		$o_info['require'] = str_replace("\n","<br>",$o_info['require']);
		$this->yunset('o_info',$o_info);
		$data['once_job']=$o_info['title'];
		$data['once_name']=$o_info['companyname'];
		$description=$o_info['require'];
		$data['once_desc']=$this->GET_content_desc($description);
		$this->data=$data;
		$this->seo('once_show');
		$start_time=strtotime(date('Y-m-d 00:00:00')); 
		$mess=$M->GetOncejobNum(array('login_ip'=>$ip,'`ctime`>\''.$start_time.'\''));
		if($this->config['sy_once']){
			$num=$this->config['sy_once']-$mess;
		}else{
			$num=1;
		} 
		$this->yunset("num",$num);
		$this->yun_tpl(array('show'));
	} 
}
?>