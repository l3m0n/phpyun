
//�ղؼ�ְ
function PartCollect(jobid,comid){
	$.post(wapurl+"?c=part&a=collect",{jobid:jobid,comid:comid},function(data){
		if(data==1){
			layermsg("ֻ�и����û������ղأ�",2);
		}else if(data==2){
			layermsg("���Ѿ��ղع��ü�ְ��",2);
		}else if(data==0){
			layermsg("�ղسɹ���",2,function(){location.reload();});
			
		}
	})
}

//��ְ����
function PartApply(jobid,jobname,comid){
	$.post(wapurl+"/index.php?c=part&a=apply",{jobid:jobid,jobname:jobname,comid:comid},function(data){
		if(data==1){
			layermsg("ֻ�и����û��������뱨����",2);
		}else if(data==2){
			layermsg("���Ѿ��������ü�ְ��",2);
		}else if(data==3){
			layermsg("ӵ�м����ſ��Ա�����ְ��",2);
		}else if(data==4){
			layermsg("�����ѽ�ֹ��",2);
		}else if(data==0){
			layermsg("�����ɹ���",2,function(){ 
				location.href=wapurl+"/index.php?c=part&a=show&id="+jobid+"#con_link_per";
			});
			
		}
	})
}
function checkcity(id,type){
	if(id>0){
		$.post(wapurl+"?c=ajax&a=wap_city",{id:id,type:type},function(data){
			if(type==1){
				$("#cityid").html(data);
			}else{
				$("#three_cityid").html(data);
			}
		})
	}else{
		if(type==1){
			$("#cityid").html('<option value="">��ѡ��</option>');
		}
	}
	$("#three_cityid").html('<option value="">��ѡ��</option>');
}
function addworktime(){
	$("#shour").val('��ѡ��');
	$("#sminute").val('��ѡ��');
	$("#ehour").val('��ѡ��');
	$("#eminute").val('��ѡ��');
	$("#timeid").val('');
	$("#partworktime").show();
	$(".partwork_bth").hide();
}
function hideworktime(){
	$(".partwork_bth").show();
	$("#partworktime").hide();
}
function saveworktime(){
	var shour=$("#shour").val();
	var sminute=$("#sminute").val();
	var ehour=$("#ehour").val();
	var eminute=$("#eminute").val();
	if(shour=="��ѡ��"||sminute=="��ѡ��"||ehour=="��ѡ��"||eminute=="��ѡ��"){
		layermsg("�뽫��Ϣ��д������",2);return false;
	}
	if((Number(ehour)<Number(shour))||(Number(ehour)==Number(shour) && Number(eminute)<Number(sminute))){
		layermsg("��������ʱ�䲻��С�ڿ�ʼʱ�䣡",2);return false;
	}
	var timeval=shour+':'+sminute+'-'+ehour+':'+eminute;
	var timeid=$("#timeid").val();
	if(timeid>0){//�޸�
		var html='<input type="hidden" name="worktime[]" value="'+timeval+'"><span>ʱ��� '+timeval+'</span><em><a href="javascript:Save_time(\''+timeid+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">�޸�</a><a href="javascript:Delete_time(\''+timeid+'\');">ɾ��</a></em>';
		$("#handletime_"+timeid).html(html);
	}else{
		var timestamp=new Date().getTime();
		var html='<div class="part_hour" id="handletime_'+timestamp+'"><input type="hidden" name="worktime[]" value="'+timeval+'"><span>ʱ��� '+timeval+'</span><em><a href="javascript:Save_time(\''+timestamp+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">�޸�</a><a href="javascript:Delete_time(\''+timestamp+'\');">ɾ��</a></em></div>';
		$("#worktimelist").append(html);
	}
	$("#partworktime").hide();
	$(".partwork_bth").show();
}
function Delete_time(id){
	$("#handletime_"+id).remove();
}
function Save_time(id,shour,sminute,ehour,eminute){
	$("#shour").val(shour);
	$("#sminute").val(sminute);
	$("#ehour").val(ehour);
	$("#eminute").val(eminute);
	$("#timeid").val(id);
	$("#partworktime").show();
}
function toDate(str){
	var sd=str.split("-");
	return new Date(sd[0],sd[1],sd[2]);
}
function CheckPost_part(){
	if($.trim($("#name").val())==""){
		layermsg("������ְλ���ƣ�",2);return false;
	}
	if($.trim($("#typeid").val())==""){
		layermsg("��ѡ�������ͣ�",2);return false;
	}
	var today=$("#today").val();
	var sdate=$("#sdate").val().split(' ');
	var edate=$("#edate").val().split(' ');
	var timetype=$("input[name='timetype']:checked").val();
	if(sdate==""){
		layermsg("��ѡ��ʼ���ڣ�",2);return false;
	}
	if(toDate(sdate[0])<toDate(today)){
		layermsg("��ʼ���ڲ���С�ڵ�ǰ���ڣ�",2);return false;
	}  
	if(timetype!='1'){
		if(edate==""){
			layermsg("��ѡ��������ڣ�",2);return false;
		}
		if(toDate(edate[0])<toDate(sdate[0])){
			layermsg("��ʼ���ڲ��ܴ��ڽ������ڣ�",2);return false;
		}
	}
	
	var worktime=$("#worktimelist").html();
	if(worktime==""){
		layermsg("�����빤��ʱ�䣡",2);return false;
	}
	if($.trim($("#number").val())==""||$.trim($("#number").val())=="0"){
		layermsg("��������Ƹ������",2);return false;
	}
	if($.trim($("#salary_typeid").val())==""){
		layermsg("��ѡ��нˮ���ͣ�",2);return false;
	}
	if($.trim($("#salary").val())==""||$.trim($("#salary").val())=="0"){
		layermsg("������нˮ��",2);return false;
	}
	if($.trim($("#billing_cycleid").val())==""){
		layermsg("��ѡ��������ڣ�",2);return false;
	}
	if($.trim($("#three_cityid").val())==""){
		layermsg("��ѡ�����ص㣡",2);return false;
	}	
	if($.trim($("#address").val())==""){
		layermsg("��������ϸ��ַ��",2);return false;
	}
	if($.trim($("#map_x").val())==""||$.trim($("#map_y").val())==""){
		layermsg("��ѡ���ͼ��",2);return false;
	}
	
	var content=UE.getEditor('description').hasContents();  
	
	if(content==""||content==false){
		layermsg("�������ְ���ݣ�",2);return false;
	}else{
		var description =UE.getEditor('description').getContent();  
		document.getElementById("description").value=description;
	} 
	var end=$("#deadline").val().split(' ');
	 
	if(toDate(end[0])<toDate(today)){
		layermsg("������ֹʱ�䲻��С�ڵ�ǰʱ�䣡",2);return false;
	}
	 
	if(timetype!='1'&&toDate(end[0])>toDate(edate[0])){
		layermsg("������ֹʱ�䲻�ܴ���ʱ�䣡",2);return false;
	}
	if($.trim($("#linkman").val())==""){
		layermsg("��������ϵ�ˣ�",2);return false;
	}
  var linktel=isjsMobile($.trim($("#linktel").val()));
	if($.trim($("#linktel").val())==""){
		layermsg("��������ϵ�ֻ���",2);return false;
	}else if(linktel==false){
     layermsg("��������ȷ����ϵ�ֻ���",2);return false;
  }
}
function ckpartjob(type){
	var val=$("#"+type+"id").find("option:selected").text();
	$('.'+type).html(val);
}