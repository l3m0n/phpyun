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
class once_controller extends common{
	function index_action(){
		$this->rightinfo();
		if($this->config['sy_wzp_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->get_moblie();
		$this->seo("once");
		$this->yunset("topplaceholder","��������Ƹ�ؼ���,�磺����Ա...");
		$M=$this->MODEL('once');
		$ip=fun_ip_get();
		$start_time=strtotime(date('Y-m-d 00:00:00')); 
		$mess=$M->GetOncejobNum(array('login_ip'=>$ip,'`ctime`>\''.$start_time.'\''));
		$num=$this->config['sy_once']-$mess;
		$this->yunset("num",$num);
		$this->yunset("headertitle","������Ƹ");
		$this->yuntpl(array('wap/once'));
	}
	function add_action(){ 
		$this->rightinfo();
		if($this->config['sy_wzp_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->get_moblie();
		$TinyM=$this->MODEL('once');

		if((int)$_GET['id']){
            $row=$TinyM->GetOncejobOne(array('id'=>(int)$_GET[id]));
			$row['edate']=ceil(($row['edate']-$row['ctime'])/86400) ;
			$this->yunset("row",$row);
		}
		if($_POST['submit']){
			$_POST=$this->post_trim($_POST);
			$_POST['mans'] = (int)$_POST['mans'];
			$_POST = yun_iconv('utf-8','gbk',$_POST);
			$_POST['status']=$this->config['com_fast_status'];
			$_POST['ctime']=time();
			$_POST['edate']=strtotime("+".(int)$_POST['edate']." days");
			$password=md5(trim($_POST['password']));
			if(is_uploaded_file($_FILES['pic']['tmp_name'])){
				$upload=$this->upload_pic("../data/upload/once/",false);
				$pictures=$upload->picture($_FILES['pic']);
				$pic=str_replace("../data/upload/once/","data/upload/once/",$pictures);
				$_POST['pic']=$pic;
			}
			unset($_POST['submit']);
			$id=intval($_POST['id']);
			if($id<1){
				$ip=fun_ip_get();
				$start_time=strtotime(date('Y-m-d 00:00:00')); 
				$mess=$TinyM->GetOncejobNum(array('login_ip'=>$ip,'`ctime`>\''.$start_time.'\'')); 
				if($this->config['sy_once']<=$mess&&$this->config['sy_once']){
					$data['msg']="һ����ֻ�ܷ���".$this->config['sy_once']."�Σ�";
					$data['url']='index.php?c=once';
				}else{
					unset($_POST['id']);
					$_POST['ip']=$ip;
					$_POST['password']=$password;
					$_POST['did']=$this->config['did'];
					$nid=$TinyM->AddOncejob($_POST);
					if($this->config['com_fast_status']=="0"){$msg="�����ɹ����ȴ���ˣ�";}else{$msg="�����ɹ�!";}
					$nid?$data['msg']=$msg:$data['msg']=$msg;
					$data['url']='index.php?c=once';
				}
			}else{
				$arr=$TinyM->GetOncejobOne(array('id'=>$id,'password'=>$password),array('field'=>'pic,id'));
				if($arr['id']){
				    $data['mans']=$_POST['mans'];
				    $data['title']=$_POST['title'];
				    $data['require']=$_POST['require'];
				    $data['companyname']=$_POST['companyname'];
				    $data['phone']=$_POST['phone'];
				    $data['linkman']=$_POST['linkman'];
				    $data['address']=$_POST['address'];
				    $data['status']=$this->config['com_fast_status'];
				    $data['password']=$password;
				    $data['edate']=$_POST['edate'];
				    if ($_POST['pic']!=''){
				        $data['pic']=$_POST['pic'];
				    }else{
				        $data['pic']=$arr['pic'];
				    }
					$nid=$TinyM->UpdateOncejob($data,array("id"=>$id));
					if($this->config['com_fast_status']=="0"){
					    $msg="�����ɹ����ȴ���ˣ�";
					}else{
					    $msg="�����ɹ�!";
					}
					$nid?$data['msg']=$msg:$data['msg']=$msg;
					$data['url']='index.php?c=once';
				}else{ 
					$data['msg']='�������';
					$data['url']=url('wap',array('c'=>'once','a'=>'show','id'=>$id));
				}
			}
			
            $data=yun_iconv('gbk','utf-8',$data);
            echo json_encode($data);die;
		}
		
		$CacheList=$this->MODEL('cache')->GetCache('user');
        $this->yunset($CacheList);
		$this->yunset("layer",$data);
		$this->yunset("headertitle","������Ƹ");
		$this->yunset("title","��ӵ�����Ƹ");
		$this->yuntpl(array('wap/once_add'));
	}
	function show_action(){
		if($this->config['sy_wzp_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->rightinfo();
		$this->get_moblie();
		$this->yunset("headertitle","������Ƹ");
        $TinyM=$this->MODEL('once');
		$TinyM->UpdateOncejob(array("`hits`=`hits`+1"),array('id'=>(int)$_GET[id]));
		$row=$TinyM->GetOncejobOne(array('id'=>(int)$_GET[id]));
		$row['ctime']=date("Y-m-d",$row[ctime]);
		$row['require'] = str_replace("\r\n","<br>",$row['require']);
		$row['require'] = str_replace("\n","<br>",$row['require']);
		$this->yunset("row",$row);
		$data['once_job']=$row['title'];
		$data['once_name']=$row['companyname'];
		$description=$row['require'];
		$data['once_desc']=$this->GET_content_desc($description);
		$this->data=$data;
		$this->seo('once_show');
		$this->yuntpl(array('wap/once_show'));
	}
}
?>