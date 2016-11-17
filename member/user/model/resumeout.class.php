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
class resumeout_controller extends user{
    function index_action(){
        $rows=$this->obj->DB_select_all("resume_expect","`uid`='".$this->uid."'","id,name");
        $urlarr=array("c"=>"resumeout","page"=>"{{page}}");
        $pageurl=Url('member',$urlarr);
        $out=$this->get_page("resumeout","`uid`='".$this->uid."' order by id desc",$pageurl,"10");
        $this->public_action();
        $this->yunset('rows',$rows);
        $this->yunset('out',$out);
        $this->user_tpl('resumeout');
    }
    
    function send_action() {
        $_POST=$this->post_trim($_POST);
        if($_POST['submit']){
            if($_POST['resume']==''){
                $this->ACT_layer_msg('请选择简历',8);
            }
            $email=$_POST['email'];
            if($email==''){
                $this->ACT_layer_msg('请输入邮箱',8);
            }elseif ($this->CheckRegEmail($email)==false){
                $this->ACT_layer_msg('邮箱格式错误',8);
            }
            if($_POST['comname']==''){
                $this->ACT_layer_msg('请输入企业名称',8);
            }
            if ($_POST['jobname']==''){
                $this->ACT_layer_msg('请输入职位名称',8);
            }
            if($this->config["sy_smtpserver"]=="" || $this->config["sy_smtpemail"]=="" ||	$this->config["sy_smtpuser"]==""){
                $this->ACT_layer_msg('网站邮件服务器不可用!',8);
            }
            if($_COOKIE["sendresume"]==$_POST['resume']){
                $this->ACT_layer_msg('请不要频繁发送邮件！同一简历发送间隔为两分钟！',8);
            }
            $resume=$this->obj->DB_select_once("resume","`uid`='".$this->uid."'","name");
            $data=array('uid'=>$this->uid,'comname'=>$_POST['comname'],'jobname'=>$_POST['jobname'],'recipient'=>$_POST['comname'],'email'=>$email,'datetime'=>time(),'resume'=>$_POST['resumename']);
            
            $contents=file_get_contents($this->config['sy_weburl']."/resume/index.php?c=sendresume&id=".$_POST['resume']);
            $smtp = $this->email_set();
            $smtpusermail =$this->config['sy_smtpemail'];
            $sendid = $smtp->sendmail($this->config['sy_smtpnickname'],$email,$smtpusermail,"我看到贵公司在招收".$_POST['jobname']."，向您自荐一份简历，请查看合适后联系我！",$contents,"HTML","","","",'');
            if($sendid){
                $this->obj->insert_into('resumeout',$data);
                $this->MODEL()->insert_into("email_msg",array('uid'=>'','name'=>$data['recipient'],'cuid'=>$this->uid,'cname'=>$resume['name'],'email'=>$data['email'],'title'=>'简历外发','content'=>'','state'=>'1','ctime'=>time()));
                $this->ACT_layer_msg('发送成功',9,'index.php?c=resumeout');
            }else{
                $this->MODEL()->insert_into("email_msg",array('uid'=>'','name'=>$data['recipient'],'cuid'=>$this->uid,'cname'=>$resume['name'],'email'=>$data['email'],'title'=>'简历外发','content'=>'','state'=>'0','ctime'=>time()));
                $this->ACT_layer_msg('邮件发送错误 原因：1邮箱不可用 2网站关闭邮件服务',8);
            }
        }
    }
    function del_action(){
            if($_GET['id']){
            $nid=$this->obj->DB_delete_all("resumeout","`id`='".(int)$_GET['id']."' AND `uid`='".$this->uid."'"," ");
            $this->obj->member_log("删除简历外发记录");
            if($nid){
	            $this->layer_msg('删除成功！',9);
            }else{
	            $this->layer_msg('删除失败！',8);
            }
        }
    }
}
?>