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
class index_controller extends special_controller{
	function index_action(){

		$this->seo("spe_index");
		$this->spetpl('index');
	}
	function show_action(){
		$SpecialM=$this->MODEL('special');
		$info=$SpecialM->GetSpecial(array("id"=>(int)$_GET['id'],"display"=>1));
		$tpl=@explode('.',$info['tpl']);
		if($info['limit']>$info['num']){
			$info['apply']='1';
		}
		if($this->uid && $this->usertype=='2'){
			$SpecialM=$this->MODEL('special');
			$isapply=$SpecialM->GetComNum(array("uid"=>$this->uid,"sid"=>(int)$_GET['id']));
			$this->yunset("isapply",$isapply);
		} 
		$this->data=array('spename'=>$info['title']);
		$this->yunset("info",$info);
		$this->seo("spe_show");
		$this->spetpl($tpl[0]);
	}
	function apply_action(){
		$id=(int)$_POST['id'];
		if($this->uid&&$this->usertype=='2'){
			$JobM=$this->MODEL('job');
			$UserInfoM=$this->MODEL('userinfo');
			$SpecialM=$this->MODEL('special');
			$info=$SpecialM->GetSpecial(array("id"=>$id,"display"=>1));
			if($info['com_bm']!='1'){
				$this->layer_msg('��ר���ֹ������',8,0);
			}
			$cominfo=$UserInfoM->GetUserinfoOne(array("uid"=>$this->uid),array('usertype'=>2,'field'=>'name'));
			$statis=$UserInfoM->GetUserstatisOne(array("uid"=>$this->uid),array("usertype"=>'2','field'=>'integral,`rating`'));
			$isapply=$SpecialM->GetComNum(array("uid"=>$this->uid,"id"=>$id,"`status`<'2'"));
			if($isapply){
				$this->layer_msg('���ѱ�����ר�⣬��ȴ�����Ա��ˣ�',8,0);
			}
			if($info['rating']){
				$rating=@explode(',',$info['rating']);
			}
			$time=time();
			$jobnum=$JobM->GetComjobNum(array("uid"=>$this->uid,"state"=>'1',"`edate`>'".$time."' and `sdate`<'".$time."'"));
			if($info['limit']<=$info['num']){
				$this->layer_msg('�������������´���ǰ������',8,0);
			}
			if($jobnum<1){
				$this->layer_msg('�����޹����Һ���ְλ��',8,0);
			}  
			if($rating&&is_array($rating)){ 
				
				if(!in_array($statis['rating'],$rating)){
					$ratings=$UserInfoM->GetRatinginfoAll(array("display"=>1,'category'=>1,"`id` in(".$info['rating'].")"),array("field"=>"`id`,`name`"));
					$rname=array();
					foreach($ratings as $val){
						$rname[]=$val['name'];
					}
					$this->layer_msg("ֻ��".@implode('��',$rname)."���ܱ�����ר�⣡",8,0);
				}
			}
			if($statis['integral']<$info['integral']){
				$this->layer_msg($this->config['integral_pricename'].'���㣬���ȳ�ֵ��',8,0);
			}
			$nid=$this->company_invtal($this->uid,$info['integral'],false,"����ר����Ƹ",true,2,'integral',9);
			if($nid){
				$SpecialM->AddSpecialCom(array("sid"=>$id,"uid"=>$this->uid,'integral'=>$info['integral'],'status'=>'0','time'=>time()));
				$SpecialM->UpdateSpecial(array("`limit`=`limit`+'1'"),array("id"=>$info['id']));
				$this->layer_msg('�����ɹ��������ĵ����ǹ�����Ա��ˣ�',9,$_SERVER['HTTP_REFERER']);
			}else{
				$this->layer_msg('����ʧ�ܣ����Ժ����ԣ�',8,$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->layer_msg('ֻ����ҵ�û����ܱ�����',8,0);
		}
	}
}
?>