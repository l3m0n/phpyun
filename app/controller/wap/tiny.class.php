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
class tiny_controller extends common{
	function index_action(){
		$this->rightinfo();
		if($this->config['sy_wjl_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$M=$this->MODEL('tiny');
		$ip = fun_ip_get();
		$s_time=strtotime(date('Y-m-d 00:00:00')); 
		$m_tiny=$M->GetTinyresumeNum(array('login_ip'=>$ip,'`time`>\''.$s_time.'\''));
		$num=$this->config['sy_tiny']-$m_tiny;
		$this->yunset("num",$num);
        $this->yunset($this->MODEL('cache')->GetCache('user'));
		$this->get_moblie();
		$this->seo("tiny");
		$this->yunset("topplaceholder","������ؼ����磺�չ�");
		$this->yunset("headertitle","�չ�ר��");
		$this->yuntpl(array('wap/tiny'));
	}
	function add_action(){
		$this->rightinfo();
		if($this->config['sy_wjl_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->get_moblie();
        $TinyM=$this->MODEL('tiny');

		if((int)$_GET['id']){
			$row=$TinyM->GetTinyresumeOne(array('id'=>(int)$_GET[id]));
			$this->yunset("row",$row);
		}
		if($_POST['submit']){
			$_POST['status']=$this->config['user_wjl'];
			$_POST['time']=time();
			$_POST['username']=yun_iconv('utf-8','gbk',trim($_POST['username']));
			$_POST['production']=yun_iconv('utf-8','gbk',trim($_POST['production']));
			$_POST['job']=yun_iconv('utf-8','gbk',trim($_POST['job']));
			$password=md5(trim($_POST['password']));
			$type=trim($_POST['type']);
			$id=intval($_POST['id']);
			unset($_POST['submit']);
			unset($_POST['type']);
			if($id==''){
				$ip = fun_ip_get();
				$s_time=strtotime(date('Y-m-d 00:00:00')); 
				$m_tiny=$TinyM->GetTinyresumeNum(array('login_ip'=>$ip,'`time`>\''.$s_time.'\''));
				if($this->config['sy_tiny']>$m_tiny||$this->config['sy_tiny']<1){
					$_POST['password']=$password;
					$_POST['ip']=$ip;
					$_POST['did']=$this->config['did'];
					$nid=$this->obj->insert_into("resume_tiny",$_POST);
					$nid?$data['msg']='�����ɹ���':$data['msg']='����ʧ�ܣ�';
					$data['url']='index.php?c=tiny';
				}else{
					$data['msg']="һ����ֻ�ܷ���".$this->config['sy_tiny']."�Σ�";
					$data['url']='index.php?c=tiny';
				}
			}else{ 
				$row=$TinyM->GetTinyresumeOne(array("id"=>$id,'password'=>$password));
				if($row['id']){
					unset($_POST['id']);
					unset($_POST['password']); 
					$nid=$TinyM->UpdateTinyresume($_POST,array("id"=>$id,'password'=>$password));
					
					$nid?$data['msg']='�����ɹ���':$data['msg']='����ʧ�ܣ�';
					$data['url']='index.php?c=tiny';
				}else{
					$data['msg']='�������';
					$data['url']='index.php?c=tiny';
				} 
			}
            $data=yun_iconv('gbk','utf-8',$data);
            echo json_encode($data);die;
		} 
		$this->user_cache();
		$this->yunset(array("layer"=>$data,"headertitle"=>"�չ�ר��"));
		$this->yunset("title","���΢����");
		$this->yuntpl(array('wap/tiny_add'));
	}
	function show_action(){
		$this->rightinfo();
		if($this->config['sy_wjl_web']=="2"){
			$data['msg']='�ܱ�Ǹ����ģ���ѹرգ�';
			$data['url']='index.php';
			$this->yunset("layer",$data);
		}
		$this->get_moblie();
		$this->yunset("headertitle","�չ�ר��");
		$CacheList=$this->MODEL('cache')->GetCache('user');
        $this->yunset($CacheList);
        $TinyM=$this->MODEL('tiny');
        $TinyM->UpdateTinyresume(array("`hits`=`hits`+1"),array('id'=>(int)$_GET[id]));
		$row=$TinyM->GetTinyresumeOne(array('id'=>(int)$_GET[id]));

		$this->data=array('tiny_username'=>$row['username'],'tiny_job'=>$row['job'],'tiny_desc'=>$row['production']);
		$this->seo('tiny_cont');
		$this->yunset("row",$row);
		$this->yuntpl(array('wap/tiny_show'));
	}
}
?>