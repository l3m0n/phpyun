function CheckPost(){
    var name=$.trim($("#name").val());
    var sex=$.trim($("#sex").val());
	var telphone=$.trim($("#telphone").val());
	var email=$.trim($("#email").val());
	var birthday=$.trim($("#birthday").val());
	var edu=$.trim($("#educid").val()); 
	var exp=$.trim($("#expid").val()); 
	var living=$.trim($("#living").val());
	var telhome=$.trim($("#telhome").val());
	if(name==''){layer.msg('����д����', 2, 8);return false;}
	if(sex==''){layer.msg('��ѡ���Ա�', 2, 8);return false;}
	if(birthday==''){layer.msg("��ѡ���������", 2, 8);return false;}
	ifemail = check_email(email); 
	ifmoblie = isjsMobile(telphone);
	if(telphone==''){
		layer.msg('����д�ֻ���', 2, 8);return false;
	}else{
		if(ifmoblie==false){layer.msg("�ֻ���ʽ����ȷ", 2, 8);return false;}
	}
	if(email==''){
		layer.msg('����д����', 2, 8);return false;
	}else{
		if(ifemail==false){layer.msg("�����ʼ���ʽ����ȷ", 2, 8);return false;}
	}
	if(living==''){layer.msg('����д�־�ס��', 2, 8);return false;}
	if(edu==''){layer.msg('��ѡ��ѧ��', 2, 8);return false;} 
	if(exp==''){layer.msg('��ѡ��������', 2, 8);return false;}  
	if(telhome&&isjsTell(telhome)==false){
		layer.msg('����д��ȷ��������', 2, 8);return false;
	}
	layer.load('ִ���У����Ժ�...',0);
}
function checkmore(type){
	var getinfoid=$.trim($("#getinfoid").val());
	if(getinfoid!=1){
		layer.msg('�������ƻ������ϣ�', 2, 8);return false;
	}
	$("#save"+type).show();
	$("#get"+type).hide();
	ScrollTo(type+"_botton");
}
function checkClose(type){
	$("#save"+type).hide();
	$("#get"+type).show();
}
function ScrollTo(id){
	$("#"+id).ScrollTo(700);
}
function checkmore2(type){
	var getinfoid=$.trim($("#getinfoid").val());
	if(getinfoid!=1){
		layer.msg('�������ƻ������ϣ�', 2, 8);return false;
	}
	var eid=$.trim($("#eid").val());
	if(eid==""){
		layer.msg('����������ְ����', 2, 8);return false;
	}
	ScrollTo(type+"_add");
	$("#"+type).show();
	$("#"+type+"_botton").attr("class","jianli_list_noadd");
	$("#"+type+"_botton").html('<em>�ݲ���д</em>');
	$("#"+type+"_botton").attr("onclick","checkClose2('"+type+"');");
	$("#Add"+type).hide();
	if(type=="skill"){
		$("#skillcid").val('');
		$("#levelid").val('');
		$("#skill_name").val('');
		$("#skill_longtime").val('');
		$("#skillid").val('');
		$("#skillc").val('��ѡ�������');
		$("#level").val('��ѡ�������̶�');
	}
	if(type=="work"){
		$("#totoday").attr("checked",false);
		$("#work_name").val('');
		$("#work_sdate").val('');
		$("#work_edate").val('');
		$("#work_department").val('');
		$("#work_title").val('');
		$("#work_salary").val('');
		$("#work_content").val('');
		$("#workid").val('');
	}
	if(type=="project"){
		$("#project_name").val('');
		$("#project_sdate").val('');
		$("#project_edate").val('');
		$("#project_sys").val('');
		$("#project_title").val('');
		$("#project_content").val('');
		$("#projectid").val('');
	}
	if(type=="edu"){
		$("#edu_name").val('');
		$("#edu_sdate").val('');
		$("#edu_edate").val('');
		$("#educationcid").val('');
		$("#educationc").val('��ѡ�����ѧ��');
		$("#edu_title").val('');		
		$("#eduid").val('');
	}
	if(type=="training"){
		$("#training_name").val('');
		$("#training_sdate").val('');
		$("#training_edate").val('');
		$("#training_title").val('');
		$("#training_content").val('');
		$("#trainingid").val('');
	}
	if(type=="cert"){
		$("#cert_name").val('');
		$("#cert_sdate").val('');
		 
		$("#cert_title").val('');
		$("#cert_content").val('');
		$("#certid").val('');
	}
	if(type=="other"){
		$("#other_title").val('');
		$("#other_content").val('');
		$("#otherid").val('');
	}
}
function checkClose2(type){
	$("#"+type).hide();
	$("#"+type+"_botton").attr("class","jianli_list_add");
	$("#"+type+"_botton").html('<em>���</em>');
	$("#"+type+"_botton").attr("onclick","checkmore2('"+type+"');");
	$(".resume_"+type).addClass('state_done');
	$("#Add"+type).show();
}
function movelook(type){
	$("#"+type+"_upbox").addClass("yun_resume_handle_bg");
	$("#compile_"+type).show();
}
function outlook(type){
	//$("#"+type+"_upbox").bind("mouseleave", function(){
		$("#"+type+"_upbox").removeClass("yun_resume_handle_bg");
		$("#compile_"+type).hide();
	//});
}
function getresume(type,id){
	ScrollTo(type+"_add");
	$("#"+type).show();
	$("#Add"+type).hide();
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=resume&act=resume_ajax",{type:type,id:id},function(data){
		layer.closeAll();
		data=eval('('+data+')');
		if(type=="skill"){
			$("#skillcid").val(data.skill);
			$("#levelid").val(data.ing);
			$("#skill_name").val(data.name);
			$("#skill_longtime").val(data.longtime);
			$("#skillid").val(data.id);
			$("#skillc").val(data.skillval);
			$("#level").val(data.ingval);
		}
		if(type=="work"){ 
			$("#work_name").val(data.name);
			$("#work_syear").val(data.syear);
			$("#work_smonth").val(data.smonth);
			$("#work_syearid").val(data.syear);
			$("#work_smonthid").val(data.smonth);
			//$("#work_sdate").val(data.sdate);
			if(data.totoday=='1'){  
				$("#totoday").attr("checked",true);
				$("#work_eyearid").hide();
				$("#work_eyearid").val('');
				$("#work_emonthid").hide();
				$("#work_emonthid").val('');
			}else{
				$("#work_eyear").val(data.eyear);
				$("#work_emonth").val(data.emonth);
				$("#work_eyearid").val(data.eyear);
				$("#work_emonthid").val(data.emonth);
				//$("#work_edate").val(data.edate);
			} 
			$("#work_department").val(data.department);
			$("#work_title").val(data.title);
			$("#work_salary").val(data.salary);
			$("#work_content").val(data.content);
			$("#workid").val(data.id);
		}
		if(type=="project"){
			$("#project_name").val(data.name);
			$("#project_syear").val(data.syear);
			$("#project_smonth").val(data.smonth);
			$("#project_eyear").val(data.eyear);
			$("#project_emonth").val(data.emonth);
			$("#project_syearid").val(data.syear);
			$("#project_smonthid").val(data.smonth);
			$("#project_eyearid").val(data.eyear);
			$("#project_emonthid").val(data.emonth);
			//$("#project_sdate").val(data.sdate);
			//$("#project_edate").val(data.edate);
			$("#project_sys").val(data.sys);
			$("#project_title").val(data.title);
			$("#project_content").val(data.content);
			$("#projectid").val(data.id);
		}
		if(type=="edu"){
			$("#edu_name").val(data.name);
			$("#edu_syear").val(data.syear);
			$("#edu_smonth").val(data.smonth);
			$("#edu_eyear").val(data.eyear);
			$("#edu_emonth").val(data.emonth);
			$("#edu_syearid").val(data.syear);
			$("#edu_smonthid").val(data.smonth);
			$("#edu_eyearid").val(data.eyear);
			$("#edu_emonthid").val(data.emonth);
			//$("#edu_sdate").val(data.sdate);
			//$("#edu_edate").val(data.edate);
			$("#educationcid").val(data.education);	
			$("#educationc").val(data.educationval);						
			$("#edu_title").val(data.title);		
			$("#eduid").val(data.id);
		}
		if(type=="training"){
			$("#training_name").val(data.name);
			$("#training_syear").val(data.syear);
			$("#training_smonth").val(data.smonth);
			$("#training_eyear").val(data.eyear);
			$("#training_emonth").val(data.emonth);
			$("#training_syearid").val(data.syear);
			$("#training_smonthid").val(data.smonth);
			$("#training_eyearid").val(data.eyear);
			$("#training_emonthid").val(data.emonth);
			//$("#training_sdate").val(data.sdate);
			//$("#training_edate").val(data.edate);
			$("#training_title").val(data.title);
			$("#training_content").val(data.content);
			$("#trainingid").val(data.id);
		}
		if(type=="cert"){
			$("#cert_name").val(data.name);
			$("#cert_syear").val(data.syear);
			$("#cert_smonth").val(data.smonth);
			$("#cert_syearid").val(data.syear);
			$("#cert_smonthid").val(data.smonth);
			$("#cert_sday").val(data.sday);
			$("#cert_sdayid").val(data.sday);
			//$("#cert_sdate").val(data.sdate); 
			$("#cert_title").val(data.title);
			$("#cert_content").val(data.content);
			$("#certid").val(data.id);
		}
		if(type=="other"){
			$("#other_title").val(data.title);
			$("#other_content").val(data.content);
			$("#otherid").val(data.id);
		}
	})
}
function resume_del(table,id){
	var eid = $.trim($("#eid").val());
	layer.confirm('ȷ��Ҫɾ���������ݣ�', function(){
		layer.load('ִ���У����Ժ�...',0);
		$.post("index.php?c=resume&act=publicdel",{table:table,id:id,eid:eid},function(data){
			layer.closeAll();
			if(data!="0"){ 
				data=eval('('+data+')');
				$("#"+table+id).remove();
				if(parseInt(data.integrity)<60){
					 var showhtml="�����ڵļ���������̫�ͣ������ܹ�ʹ�ô˼���ӦƸ!"
				}else{
					var showhtml="���ļ����ѷ���Ҫ��"
				}
				//�����Ҳ�������
				if(data.num<1){
					changeRightIntegrityState("m_right_"+table,"remove");
				}
				$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
				$("#numresume").html(data.integrity+"%");
				$(".play").attr("style","width:"+data.integrity+"%");
				if(data.num=="0"){
					$(".resume_"+table).removeClass('state_done');
				} 
				layer.msg('ɾ���ɹ���', 2,9);				
			}else{ 
				layer.msg('���緱æ�����Ժ�', 2,8);	
			}
		});
	}); 
}
function saveskill(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#skillid").val());
	var skill = $.trim($("#skillcid").val());
	var ing = $.trim($("#levelid").val());
	var name = $.trim($("#skill_name").val());
	var longtime = $.trim($("#skill_longtime").val());
	if(eid==""){ 
		layer.msg('����������ְ����', 2, 8);
		return false;
	}
	if(name==""){ 
		layer.msg('����д�������ƣ�', 2, 8);
		return false;
	} 
	if(skill==""){ 
		layer.msg('��ѡ�������', 2, 8);
		return false;
	}
	if(ing==""){ 
		layer.msg('��ѡ�������̶ȣ�', 2, 8);
		return false;
	}
	if(longtime==""){ 
		layer.msg('����д����ʱ�䣡', 2, 8);
		return false;
	}
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=skill",{skill:skill,ing:ing,name:name,longtime:longtime,eid:eid,id:id,table:"resume_skill",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#skill").hide();
			$("#Addskill").show();
			if(id>0){ 
				var html='<li><span>�������ƣ�</span>'+data.name+'</li><li><span>����ʱ�䣺</span>'+data.longtime+'��</li><li><span>�������</span>'+data.skillval+'</li><li><span>�����̶ȣ�</span>'+data.ingval+'</li>';
				$("#skill_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('skill');ScrollTo("skill_botton");}); 
			}else{
				numresume(data.numresume,'skill');
				var html='<div class="expect_tj_list" id="skill'+data.id+'" onmousemove="movelook(\'skill\',\''+data.id+'\');" onmouseout="outlook(\'skill\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'skill\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'skill\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="skill_'+data.id+'"><li><span>�������ƣ�</span>'+data.name+'</li><li><span>����ʱ�䣺</span>'+data.longtime+'��</li><li><span>�������</span>'+data.skillval+'</li><li><span>�����̶ȣ�</span>'+data.ingval+'</li></ul></div>';
				$("#skillList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('skill');ScrollTo("skill_botton");}); 
			}
			changeRightIntegrityState("m_right2","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8);
		}
	});
}
function savework(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#workid").val());
	var sdate = $.trim($("#work_syear").val())+'-'+$.trim($("#work_smonth").val());
	var edate = $.trim($("#work_eyear").val())+'-'+$.trim($("#work_emonth").val());
	var name = $.trim($("#work_name").val());
	var department = $.trim($("#work_department").val());
	var title = $.trim($("#work_title").val());
	var salary = $.trim($("#work_salary").val());
	var content = $.trim($("#work_content").val());	
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('����д��λ���ƣ�', 2,8);
		return false;
	}
	if(sdate==""){
		layer.msg('��ʼʱ�䲻��Ϊ�գ�', 2,8); return false
	}else if(edate){
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 	
			layer.msg('��ʼʱ�䲻�ô��ڽ���ʱ��', 2,8);return false
		}
	}	
	if($("#totoday").attr("checked")!="checked" && edate==""){
		layer.msg('����ʱ�䲻��Ϊ�գ�', 2,8); return false
	}	
	if(title==""){ 
		layer.msg('����д����ְλ��', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('����д�������ݣ�', 2,8);
		return false;
	}
	layer.load('ִ���У����Ժ�...',0); 
	$.post("index.php?c=expect&act=work",{sdate:sdate,edate:edate,name:name,department:department,eid:eid,salary:salary,title:title,content:content,id:id,table:"resume_work",submit:"1",dom_sort:getDomSort()},function(data){	
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#work").hide();
			$("#Addwork").show();
			if(id>0){ 
				var html='<li><span>��λ���ƣ�</span>'+data.name+'</li><li><span>����ʱ�䣺</span>'+data.sdate+'��  '+data.edate+'</li><li><span>����ְλ��</span>'+data.title+'</li><li class="expect_amend_end"><span>�������ݣ�</span><em>'+data.content+'</em></li>';
				$("#work_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('work');ScrollTo("work_botton");}); 
			}else{
				numresume(data.numresume,'work');
				var html='<div class="expect_tj_list" id="work'+data.id+'" onmousemove="movelook(\'work\',\''+data.id+'\');" onmouseout="outlook(\'work\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'work\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'work\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="work_'+data.id+'"><li><span>��λ���ƣ�</span>'+data.name+'</li><li><span>����ʱ�䣺</span>'+data.sdate+'�� '+data.edate+'</li><li><span>����ְλ��</span>'+data.title+'</li><li class="expect_amend_end"><span>�������ݣ�</span><em>'+data.content+'</em></li></ul></div>';
				$("#workList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('work');ScrollTo("work_botton");}); 
			}
			changeRightIntegrityState("m_right3","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8);
		}
	});
}
function saveproject(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#projectid").val());
	var sdate = $.trim($("#project_syear").val())+'-'+$.trim($("#project_smonth").val());
	var edate = $.trim($("#project_eyear").val())+'-'+$.trim($("#project_emonth").val());
	var name = $.trim($("#project_name").val());
	var sys = $.trim($("#project_sys").val());
	var title = $.trim($("#project_title").val());
	var content = $.trim($("#project_content").val());
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('����д��Ŀ���ƣ�', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{ 
		layer.msg('��ʼʱ�䣬����ʱ�䲻��Ϊ�գ�', 2,8);
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 
			layer.msg('��ʼʱ�䲻�ô��ڽ���ʱ�䣡', 2,8);			
			return false
		}
	}		
	if(title==""){ 
		layer.msg('����д����ְλ��', 2,8);
		return false;
	}
	if(content==""){
		layer.msg('����д��Ŀ���ݣ�', 2,8); 
		return false;
	}
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=project",{sdate:sdate,edate:edate,name:name,sys:sys,eid:eid,title:title,content:content,id:id,table:"resume_project",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#project").hide();
			$("#Addproject").show();
			if(id>0){ 
				var html='<li><span>��Ŀ���ƣ�</span>'+data.name+'</li><li><span>��Ŀʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span> ����ְλ��</span>'+data.title+'</li><li class="expect_amend_end"><span>��Ŀ���ݣ�</span><em>'+data.content+'</em></li>';
				$("#project_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('project');ScrollTo("project_botton");});	 
			}else{
				numresume(data.numresume,'project');
				var html='<div class="expect_tj_list" id="project'+data.id+'" onmousemove="movelook(\'project\',\''+data.id+'\');" onmouseout="outlook(\'project\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'project\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'project\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="project_'+data.id+'"><li><span>��Ŀ���ƣ�</span>'+data.name+'</li><li><span>��Ŀʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span> ����ְλ��</span>'+data.title+'</li><li class="expect_amend_end"><span>��Ŀ���ݣ�</span><em>'+data.content+'</em></li></ul></div>';
				$("#projectList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('project');ScrollTo("project_botton");}); 
			}
			changeRightIntegrityState("m_right4","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8); 
		}
	});
}
function saveedu(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#eduid").val());
	var sdate = $.trim($("#edu_syear").val())+'-'+$.trim($("#edu_smonth").val());
	var edate = $.trim($("#edu_eyear").val())+'-'+$.trim($("#edu_emonth").val());
	var name = $.trim($("#edu_name").val());
	var education = $.trim($("#educationcid").val());
	var title = $.trim($("#edu_title").val());	
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('����дѧУ���ƣ�', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{
		layer.msg('��ʼʱ�䣬����ʱ�䲻��Ϊ�գ�', 2,8); 
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){
			layer.msg('��ʼʱ�䲻�ô��ڽ���ʱ��', 2,8); 
			return false
		}
	} 
	if(education==""){ 
		layer.msg('����д���ѧ����', 2,8); 
		return false;
	}
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=edu",{sdate:sdate,edate:edate,name:name,education:education,eid:eid,title:title,id:id,table:"resume_edu",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#edu").hide();
			$("#Addedu").show();
			if(id>0){ 
				var html='<li><span>ѧУ���ƣ�</span>'+data.name+'</li><li><span>��Уʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span>���ѧ����</span>'+data.educationval+'</li><li><span>����ְλ��</span>'+data.title+'</li>';
				$("#edu_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('edu');ScrollTo("edu_botton");}); 
			}else{
				numresume(data.numresume,'edu');
				var html='<div class="expect_tj_list" id="edu'+data.id+'" onmousemove="movelook(\'edu\',\''+data.id+'\');" onmouseout="outlook(\'edu\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'edu\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'edu\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="edu_'+data.id+'"><li><span>ѧУ���ƣ�</span>'+data.name+'</li><li><span>��Уʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span>���ѧ����</span>'+data.educationval+'</li><li><span>����ְλ��</span>'+data.title+'</li></ul></div>';
				$("#eduList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('edu');ScrollTo("edu_botton");}); 
			}
			changeRightIntegrityState("m_right0","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8);
		}
	});
}
function savetraining(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#trainingid").val());
	var sdate = $.trim($("#training_syear").val())+'-'+$.trim($("#training_smonth").val());
	var edate = $.trim($("#training_eyear").val())+'-'+$.trim($("#training_emonth").val());;
	var name = $.trim($("#training_name").val());
	var title = $.trim($("#training_title").val());
	var content = $.trim($("#training_content").val());
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('����д��ѵ���ģ�', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{ 
		layer.msg('��ʼʱ�䣬����ʱ�䲻��Ϊ�գ�', 2,8);		
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 
			layer.msg('��ʼʱ�䲻�ô��ڽ���ʱ��', 2,8);			
			return false
		}
	}	
	if(title==""){ 
		layer.msg('����д��ѵ����', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('����д��ѵ������', 2,8);
		return false;
	}	
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=training",{sdate:sdate,edate:edate,name:name,eid:eid,title:title,content:content,id:id,table:"resume_training",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#training").hide();
			$("#Addtraining").show();
			if(id>0){ 
				var html='<li><span>��ѵ���ģ�</span>'+data.name+'</li><li><span>��ѵʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span>��ѵ����</span>'+data.title+'</li><li class="expect_amend_end"><span>��ѵ������</span><em>'+data.content+'</em></li>';
				$("#training_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('training');ScrollTo("training_botton");}); 
			}else{
				numresume(data.numresume,'training');
				var html='<div class="expect_tj_list" id="training'+data.id+'" onmousemove="movelook(\'training\',\''+data.id+'\');" onmouseout="outlook(\'training\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'training\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'training\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="training_'+data.id+'"><li><span>��ѵ���ģ�</span>'+data.name+'</li><li><span>��ѵʱ�䣺</span>'+data.sdate+'��'+data.edate+'</li><li><span>��ѵ����</span>'+data.title+'</li><li class="expect_amend_end"><span>��ѵ������</span><em>'+data.content+'</em></li></ul></div>';
				$("#trainingList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('training');ScrollTo("training_botton");}); 
			}
			changeRightIntegrityState("m_right1","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8);
		}
	});
}
function savecert(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#certid").val());
	var sdate = $.trim($("#cert_syear").val())+'-'+$.trim($("#cert_smonth").val())+'-'+$.trim($("#cert_sday").val());
	var name = $.trim($("#cert_name").val());
	var title = $.trim($("#cert_title").val());
	var content = $.trim($("#cert_content").val());
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('����д֤�����ƣ�', 2,8);
		return false;
	}
	if(sdate==""){ 
		layer.msg('�䷢ʱ�䲻��Ϊ�գ�', 2,8);return false
	}
	if(title==""){ 
		layer.msg('����д�䷢��λ��', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('����д֤��������', 2,8);
		return false;
	}
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=cert",{sdate:sdate,name:name,eid:eid,title:title,content:content,id:id,table:"resume_cert",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#cert").hide();
			$("#Addcert").show();
			if(id>0){
				var html='<li><span>֤��ȫ�ƣ�</span>'+data.name+'</li><li><span>�䷢ʱ�䣺</span>'+data.sdate+'</li><li><span>�䷢��λ��</span>'+data.title+'</li><li class="expect_amend_end"><span>֤��������</span><em>'+data.content+'</em></li>';
				$("#cert_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('cert');ScrollTo("cert_botton");}); 
			}else{
				numresume(data.numresume,'cert');
				var html='<div class="expect_tj_list" id="cert'+data.id+'" onmousemove="movelook(\'cert\',\''+data.id+'\');" onmouseout="outlook(\'cert\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'cert\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'cert\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="cert_'+data.id+'"><li><span>֤��ȫ�ƣ�</span>'+data.name+'</li><li><span>�䷢ʱ�䣺</span>'+data.sdate+'</li><li><span>�䷢��λ��</span>'+data.title+'</li><li class="expect_amend_end"><span>֤��������</span><em>'+data.content+'</em></li></ul></div>';
				$("#certList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('cert');ScrollTo("cert_botton");}); 
			}
			changeRightIntegrityState("m_right5","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2,8);
		}
	});
}
function saveother(){
	shell();
	var eid = $.trim($("#eid").val());
	var id = $.trim($("#otherid").val());
	var title = $.trim($("#other_title").val());
	var content = $.trim($("#other_content").val());
	if(eid==""){ 
		layer.msg('����������ְ����', 2,8);
		return false;
	}
	if(title==""){ 
		layer.msg('����д�������⣡', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('����д����������', 2,8);
		return false;
	}	
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=other",{eid:eid,title:title,content:content,id:id,table:"resume_other",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#other").hide();
			$("#Addother").show();
			if(id>0){ 
				var html='<li><span>�������⣺</span>'+data.title+'</li><li class="expect_amend_end"><span>����������</span><em>'+data.content+'</em></li>';
				$("#other_"+id).html(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('other');ScrollTo("other_botton");}); 
			}else{
				numresume(data.numresume,'other');
				var html='<div class="expect_tj_list" id="other'+data.id+'" onmousemove="movelook(\'other\',\''+data.id+'\');" onmouseout="outlook(\'other\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'other\',\''+data.id+'\');">�޸�</a><a href="javascript:resume_del(\'other\',\''+data.id+'\');">ɾ��</a></div><ul class="expect_amend" id="other_'+data.id+'"><li><span>�������⣺</span>'+data.title+'</li><li class="expect_amend_end"><span>����������</span><em>'+data.content+'</em></li></ul></div>';
				$("#otherList").append(html);
				layer.msg('�����ɹ���', 2,9,function(){checkClose2('other');ScrollTo("other_botton");}); 				
			}
			changeRightIntegrityState("m_right6","add");
		}else{ 
			layer.msg('����ʧ�ܣ�', 2, 8);
		}	
	});
}
function toDate(str){
    var sd=str.split("-");
    return new Date(sd[0],sd[1],sd[2]);
}
function numresume(numresume,type){
	if(numresume<60){
		 var showhtml="�����ڵļ���������̫�ͣ������ܹ�ʹ�ô˼���ӦƸ!"
	}else{
		var showhtml="���ļ����ѷ���Ҫ��"
	}
	$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
	$("#numresume").html(numresume+"%");
	//$(".resume_"+type).show();
	$(".play").attr("style","width:"+numresume+"%");
}
function changeRightIntegrityState(id,state){
	if(state=="add"){
		$("#"+id).find(".dom_m_right_state").removeClass("state");
		$("#"+id).find(".dom_m_right_state").addClass("state_done");
		$("."+id).removeClass("state");
		$("."+id).addClass("state_done");		
	}else{
		$("#"+id).find(".dom_m_right_state").removeClass("state_done");
		$("#"+id).find(".dom_m_right_state").addClass("state");	
		$("."+id).removeClass("state_done");
		$("."+id).addClass("state");		
	}
}
function shell(){
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect",{shell:1},function(data){
 		if(data==1){
			layer.msg('�������ƻ������ϣ�', 2, 8);return false;
		}
	});
}
function saveexpect(){	
	shell(); 
	var hy = $.trim($("#hyid").val());  
	var job_classid = $.trim($("#job_class").val());
	var provinceid = $.trim($("#provinceid").val());
	var cityid = $.trim($("#citysid").val());
	var three_cityid = $.trim($("#three_cityid").val());
	var salary = $.trim($("#salaryid").val()); 
	var type = $.trim($("#typeid").val()); 
	var report = $.trim($("#reportid").val());
	var eid = $.trim($("#eid").val());
	var jobstatus = $.trim($("#statusid").val());
	if(hy==""){layer.msg('��ѡ�������ҵ��', 2, 8);return false;}
	if(three_cityid==""&&cityid==''){layer.msg('��ѡ�����ص㣡', 2, 8);return false;}
	if(job_classid==""){layer.msg('��ѡ�����ְλ��', 2, 8);return false;}
	if(salary==""){layer.msg('��ѡ������н�ʣ�', 2, 8);return false;}
	if(type==""){layer.msg('��ѡ�������ʣ�', 2, 8);return false;}
	if (report == "") { layer.msg('��ѡ�񵽸�ʱ�䣡', 2, 8); return false; }
	if (jobstatus == "") { layer.msg('��ѡ����ְ״̬��', 2, 8); return false; }
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=saveexpect", { hy: hy, job_classid: job_classid, provinceid: provinceid, cityid: cityid, three_cityid: three_cityid, salary: salary, jobstatus: jobstatus, type: type, report: report, eid: eid, submit: "1", dom_sort: getDomSort() }, function (data) {
		layer.closeAll();
		if(data==0){
			layer.msg('����ʧ�ܣ�', 2, 8);
		}else if(data==1){
			layer.msg('��ļ������Ѿ�����ϵͳ���õļ������ˣ�', 2, 8);
		}else{
			data=eval('('+data+')');
			if(eid==""){
				 window.location.href="index.php?c=expect&e="+data.id;
			}else{
				$("#saveexpect").hide();
				//numresume(data.numresume,'expect');
				$("#eid").val(data.id);
				var html='<li>����������ҵ��'+data.hy+'</li><li>���������ص㣺'+data.city+'</li><li>����н�ʣ�'+data.salary+'</li><li>����ʱ�䣺'+data.report+'</li><li>��ְ״̬��'+data.jobstatus+'</li><li>�����������ʣ�'+data.type+'</li><li  class="yun_resume_job_intention_list_end">��������ְλ��'+data.job_classname+'</li>';
				$("#expect").html(html);
				layer.msg('�����ɹ���', 2,9,function(){ScrollTo("expect_botton");$(".resume_expect").addClass('state_done');}); 
			}
		}
	});
}
function savedescription(){	
    shell();
	var eid = $.trim($("#eid").val());
	var description = $.trim($("#description").val()); 
	if(description==""){layer.msg('����д�������ۣ�', 2, 8);return false; }
	layer.load('ִ���У����Ժ�...',0);
	$.post("index.php?c=expect&act=savedescription",{description:description,submit:"1",eid:eid},function(data){
		layer.closeAll();
		if(data==0){
			layer.msg('����ʧ�ܣ�', 2, 8,function(){location.reload();});
		}else{
			layer.msg('�����ɹ���', 2, 9,function(){location.reload();});
		}
	});
}
function totoday(){
	if($("#totoday").attr("checked")=='checked'){
		$('#work_emonthid').val('');
		$('#work_emonth').val('');
		$('#work_eyearid').val('');
		$('#work_eyear').val('');
		$('#yearwork').hide();
		$('#monthwork').hide();
	}else{
		$('#yearwork').show();
		$('#monthwork').show();
	}
}
function checkbox_more(id){
	var codewebarr="";
	$("#"+id+" input[type=checkbox][checked]").each(function(){
		if(codewebarr==""){codewebarr=$(this).val();}else{codewebarr=codewebarr+","+$(this).val();}
	}); 
	return codewebarr;
}
function location_url(url){
	if($.trim($("#eid").val())==""){
		layer.msg('�������Ƽ�����', 2,8);
	}else{
		 window.location.href=url;
	}
}
function getDomSort(){
	var domsort="";
	var elements=$("#dom0 .dom_m");
	for(var i=0;i<elements.length;i++){
		domsort=domsort+","+$(elements[i]).attr("id");
	}
	return domsort=domsort.substring(1,domsort.length);
}


function addexpect(){	
	var name = $.trim($("#expect_name").val()); 
	var hy = $.trim($("#hyid").val());  
	var job_classid = $.trim($("#job_class").val());
	var provinceid = $.trim($("#provinceid").val());
	var cityid = $.trim($("#citysid").val());
	var three_cityid = $.trim($("#three_cityid").val());
	var salary = $.trim($("#salaryid").val()); 
	var type = $.trim($("#typeid").val()); 
	var report = $.trim($("#reportid").val());
	var eid = $.trim($("#eid").val());
	var jobstatus = $.trim($("#statusid").val());
	if(name==""){layer.msg('����д�������ƣ�', 2, 8);return false; }
	if(hy==""){layer.msg('��ѡ�������ҵ��', 2, 8);return false;}
	if(three_cityid=="" && cityid==''){layer.msg('��ѡ�����ص㣡', 2, 8);return false;}
	if(job_classid==""){layer.msg('��ѡ�����ְλ��', 2, 8);return false;}
	if(salary==""){layer.msg('��ѡ������н�ʣ�', 2, 8);return false;}
	if(type==""){layer.msg('��ѡ�������ʣ�', 2, 8);return false;}
	if (report == "") { layer.msg('��ѡ�񵽸�ʱ�䣡', 2, 8); return false; }
	if (jobstatus == "") { layer.msg('��ѡ����ְ״̬��', 2, 8); return false; }

	layer.load('���������У����Ժ�...',0);
}
function showMore(type,width,height,name){
	$("#add"+type+" li").show();
	$(".newresumebox").removeClass("newresumebox");  //�򿪵�����ʱ�Ƴ��¼����ݵ�class
	$.layer({
		type : 1,
		title : name,
		shift : 'top',
		offset : [($(window).height() - height)/2 + 'px', ''],
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : [width+'px',height+'px'],
		page : {dom :"#save"+type},
		close: function(index){
			$(".newresumebox").remove();  //�رյ������Ƴ�δ������ݵ��µ�����class
			$("#save"+type).hide();
			var num=$(".show"+type+"num");
			if(num.length==1){
				$(".hidepic").hide();
			}
		} 
	});
}
function hideMore(type){
	$(".newresumebox").remove();//�رյ������Ƴ�δ������ݵ��µ�����class
	$("#save"+type).hide();
	var num=$(".show"+type+"num");
	if(num.length==1){
		$(".hidepic").hide();
	}
	layer.closeAll();
}
function changeIntegrityState(id,state){
	if(state=="add"){
		$("#"+id).find(".integrity_degree").addClass("state_done");		
	}else{
		$("#"+id).find(".integrity_degree").removeClass("state_done");	
	}
}
function checkModel(id){
	if(id==1){
		$("#module").addClass("resume_right_box_tit_cur");
		$("#template").removeClass("resume_right_box_tit_cur");
		$("#resume_module").show();
		$("#resume_template").hide();
	}else{
		$("#module").removeClass("resume_right_box_tit_cur");
		$("#template").addClass("resume_right_box_tit_cur");
		$("#resume_module").hide();
		$("#resume_template").show();
	}
}
function untiltoday(id,edate,num,toid){
	if($("#"+id).attr("checked")=='checked'){
		$("#"+edate).attr('readonly','readonly');
		$("#"+toid).val('2');
		$("#"+edate).attr('value','����');
		$("#"+num).hide();
	}else{
		$("#"+edate).removeAttr('readonly');
		$("#"+edate).val('');
		$("#"+toid).val('1');
		$("#"+edate).show();
		$("#"+num).hide();
	}
}
function deleteupbox(delid,boxid,showid,table){
	var id=$("#add"+table).find("."+delid).val();
	var eid=$("#eid").val();
	$("#"+delid).remove();
	$("#"+boxid).removeClass(showid);	
	var num=$("."+showid);
	if(num.length==1){
		$("."+showid).hide();
		$(".newresumebox").removeClass("newresumebox");  //ֻʣ�����һ��������Ƴ��¼����ݵ�class
	}
	if(id&&eid&&table){
		$.post("index.php?c=resume&act=publicdel",{id:id,eid:eid,table:table},function(data){
			if(data!='0'){
				
				data=eval('('+data+')');
				if(parseInt(data.integrity)<60){
					 var showhtml="�����ڵļ���������̫�ͣ������ܹ�ʹ�ô˼���ӦƸ!"
				}else{
					var showhtml="���ļ����ѷ���Ҫ��"
				}
				//�����Ҳ�������
				if(data.num<1){
					changeIntegrityState("m_right_"+table,"remove");
				}
				$("#"+table+""+id).remove();
				$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
				$("#numresume").html(data.integrity+"%");
				$(".play").attr("style","width:"+data.integrity+"%");
				
				if(data.num=="0"){
					$(".resume_"+table).removeClass('state_done');
					$("#"+table+"_empty").show();             //û��������ʾ��ʾ
				} 
				//layer.msg('ɾ���ɹ���', 2,9);			
			}else{
				layer.msg('����ʧ�ܣ�', 2, 9,function(){location.reload();});
			}
		});
	}
}

function addWork(){
	$("#addwork").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='w'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showworknum' id='iw"+randnum+"' onclick=\"deleteupbox('"+randnum+"','iw"+randnum+"','showworknum','work')\">-</i><input type='hidden' name='id[]' value='' class='"+randnum+"'/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='iw"+randnum+"'><input type='hidden' class='usedwork' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��˾���ƣ�</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mworkn"+randnum+"')\" class='yun_resume_popup_text work_name'><i class='yun_resume_popup_qyname_tip' id='mworkn"+randnum+"' style='display:none'>����д��˾����</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>����ְλ��</span><input type='text' name='title[]' value='' class='yun_resume_popup_text work_title'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>����ʱ�䣺</span><div class='yun_resume_popup_list_box'><input type='text' id='work_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_sdate'><script>$('#work_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mworks"+randnum+"' style='display:none'>��ѡ��ʼ����</i></div><span class='yun_resume_popup_time'>-</span><div class='yun_resume_popup_list_box'><input type='text' id='work_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_edate'><script>$('#work_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mworkiw"+randnum+"' style='display:none'>��ȷ�������Ⱥ�˳��</i></div><input type='hidden' id='to"+randnum+"' name='totoday[]' value='1'><input class='yun_resume_popup_checkbox' type='checkbox' value='1' onclick=\"untiltoday('totoday"+randnum+"','work_edate"+randnum+"','mworkiw"+randnum+"','to"+randnum+"')\" id='totoday"+randnum+"'><span class='yun_resume_popup_checkbox_s'><label for='totoday'>����</label></span> </div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>�������ݣ�</span><textarea rows='5' cols='50' name='content[]' id='work_content' class='infor_textarea work_content'></textarea></div></li>";
        return html;
    });
	$(".showworknum").show();
	var div = document.getElementById('work_div');
	$("#work_div").animate({scrollTop:div.scrollHeight},1000);

}
 
function addTraining(){
	$("#addtraining").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='t'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showtrainingnum'  id='it"+randnum+"' onclick=\"deleteupbox('"+randnum+"','it"+randnum+"','showtrainingnum','training')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='it"+randnum+"'><input type='hidden' class='usedtraining' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��ѵ���ģ�</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mtrainingn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mtrainingn"+randnum+"' style='display:none'>����д��ѵ��������</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��ѵ����</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��ѵʱ�䣺</span><div class='yun_resume_popup_list_box'><input type='text' id='edu_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#edu_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mtrainings"+randnum+"' style='display:none'>��ѡ��ʼ����</i></div><span class='yun_resume_popup_time'>��</span><div class='yun_resume_popup_list_box'><input type='text' id='edu_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#edu_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mtrainingit"+randnum+"' style='display:none'>��ȷ�������Ⱥ�˳��</i></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��ѵ���ݣ�</span><textarea rows='5' cols='50' name='content[]' id='training_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showtrainingnum").show();
	var div = document.getElementById('training_div');
	$("#training_div").animate({scrollTop:div.scrollHeight},1000);
}
function addProject(){
	$("#addproject").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='p'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showprojectnum'  id='ip"+randnum+"' onclick=\"deleteupbox('"+randnum+"','ip"+randnum+"','showprojectnum','project')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='ip"+randnum+"'><input type='hidden' class='usedproject' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��Ŀ���ƣ�</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mprojectn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mprojectn"+randnum+"' style='display:none'>����д����Ŀ����</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>����ְλ��</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��Ŀʱ�䣺</span>            <div class='yun_resume_popup_list_box'><input type='text' id='project_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#project_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  </script><i class='yun_resume_popup_list_box_tip' id='mprojects"+randnum+"' style='display:none'>��ѡ��ʼ����</i></div><span class='yun_resume_popup_time'>��</span><div class='yun_resume_popup_list_box'><input type='text' id='project_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#project_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  </script><i class='yun_resume_popup_list_box_tip' id='mprojectip"+randnum+"' style='display:none'>��ȷ�������Ⱥ�˳��</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>��Ŀ���ݣ�</span><textarea rows='5' cols='50' name='content[]' id='project_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showprojectnum").show();
	var div = document.getElementById('project_div');
	$("#project_div").animate({scrollTop:div.scrollHeight},1000);
}
function addSkill(){
	$("#addskill").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='s'+parseInt(Math.random()*1000);
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showskillnum' id='is"+randnum+"' onclick=\"deleteupbox('"+randnum+"','is"+randnum+"','showskillnum','skill')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='is"+randnum+"'><input type='hidden' class='usedskill' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>�������ƣ�</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_name' value='' onblur=\"hidemsg('mskilln"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mskilln"+randnum+"' style='display:none'>����д��������</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>����ʱ�䣺</span><input type='text' name='longtime[]' id='skill_longtime' value='' onkeyup='this.value=this.value.replace(/[^0-9.]/g,'')' class='yun_resume_popup_text'>��</div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>�ϴ�֤�飺</span><div class='yun_resume_popup_upp'><input type='file' title='����ϴ�֤��' name='cert[]'style='width:200px;'></div></div></li>";
        return html;
    });
	$(".showskillnum").show();
	var div = document.getElementById('skill_div');
	$("#skill_div").animate({scrollTop:div.scrollHeight},1000);
}
function addOther(){
	$("#addother").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='o'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showothernum'  id='io"+randnum+"' onclick=\"deleteupbox('"+randnum+"','io"+randnum+"','showothernum','other')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='io"+randnum+"'><input type='hidden' class='usedother' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>���⣺</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_title' value='' onblur=\"hidemsg('mothern"+randnum+"')\"class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mothern"+randnum+"' style='display:none'>����д����</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>���ݣ�</span><textarea rows='5' cols='50' name='content[]' id='skill_content' class='infor_textarea '></textarea></div></li>";
        return html;
    });
	$(".showothernum").show();
	var div = document.getElementById('other_div');
	$("#other_div").animate({scrollTop:div.scrollHeight},1000);
}

function hidemsg(eid,sid){
	$("#"+eid).hide();
	$("#"+sid).hide();
}
function resumeFanhui(frame_id){ 
	if(frame_id==''||frame_id==undefined){
		frame_id='supportiframe';
	}
	var msg = $(window.frames[frame_id].document).find("#layer_msg").val(); 
	if(msg != null){
		var url=$(window.frames[frame_id].document).find("#layer_url").val();
		var layer_time=$(window.frames[frame_id].document).find("#layer_time").val();
		var layer_st=$(window.frames[frame_id].document).find("#layer_st").val();
		layer.msg(msg, layer_time, Number(layer_st),function(){window.location.href = url;window.event.returnValue = false;return false;});
	}
	var timenum = $(window.frames[frame_id].document).find("#timenum").val(); //��ʾʱ��˳������
	var wrong = $(window.frames[frame_id].document).find("#wrong").val(); 
	if(timenum !=null && wrong !=null){
		var tnums=timenum.split('-');
		for(var i=0;i<tnums.length;i++){
			$("#m"+wrong+tnums[i]).show();
		}
	}
	var namenum = $(window.frames[frame_id].document).find("#namenum").val(); //��ʾnameΪ�յ�
	if(namenum !=null && wrong !=null){
		var namenums=namenum.split('-');
		for(var i=0;i<namenums.length;i++){
			$("#m"+wrong+namenums[i]).show();
		}
	}
	var sdatenum = $(window.frames[frame_id].document).find("#sdatenum").val(); //��ʾsdateΪ�յ�
	if(sdatenum !=null && wrong !=null){
		var sdatenums=sdatenum.split('-');
		for(var i=0;i<sdatenums.length;i++){
			$("#m"+wrong+sdatenums[i]).show();
		}
	}
	var emptynum = $(window.frames[frame_id].document).find("#emptynum").val(); //������棬�ٴε㿪ʱ����ֵ��<li>����������û��ֵ��<li>�Ƴ�
	var littlenum = $(window.frames[frame_id].document).find("#littlenum").val(); //����ύʱ��ֵ��<li>�е�С��ǩ����
    if(emptynum !=null){
		var enums=emptynum.split('-');
		if(enums.length==1){            //�յ�<li>ֻ��һ����
			if(littlenum !=null){
				$("#"+enums[0]).remove();
		    }
		}else{
			if(littlenum == null){
				for(var i=i;i<enums.length;i++){
				    $("#"+enums[i]).remove();
				}
			}else{
				for(var i=0;i<enums.length;i++){
				    $("#"+enums[i]).remove();
			  }
			}	
		}
	}
	var message = $(window.frames[frame_id].document).find("#resumeAll").val();
	if(message != null){
		if(parseInt(message)==2){
			$(".newresumebox").remove();
			$(".hidepic").hide();
		}else if(parseInt(message)==3){
			var resume="work";
		}else if(parseInt(message)==4){
			var resume="edu";
		}else if(parseInt(message)==5){
			var resume="training";
		}else if(parseInt(message)==6){
			var resume="skill";
		}else if(parseInt(message)==7){
			var resume="project";
		}else if(parseInt(message)==8){
			var resume="cert";
		}else if(parseInt(message)==9){
			var resume="other";
		}else if(parseInt(message)==10){
			var resume="description";
		}
		if(resume!=''){
			var upnum = $(window.frames[frame_id].document).find("#upnum").val();
			if(upnum!=0){
				$("#"+resume+"_empty").hide();
			}
		}
		
		var eid=$.trim($("#eid").val());	
		layer.load('ִ���У����Ժ�...',0); 
		$.post(weburl+"/member/index.php?c=expect&act=showresume",{eid:eid,resume:resume},function(data){		
			layer.closeAll();

			if(data!=0){

				if(parseInt(message)!=10||parseInt(message)!=9){
					var integrity = $(window.frames[frame_id].document).find("#integrity").val();   //�Ҳ�����ٷֱ�
					numresume(integrity,resume);
					changeIntegrityState("m_right"+message,"add");
				}
				
				if(littlenum !=null){
					var num=$(".show"+resume+"num");
					if(num.length==1){                                                   //С��ǩֻ��һ��ʱ����
					$(".show"+resume+"num").hide();
					$(".newresumebox").removeClass("newresumebox");  //ֻʣ�����һ��������Ƴ��¼����ݵ�class
				    }

				}
                var newids = $(window.frames[frame_id].document).find("#newids").val();  //append��ӵľ��������id
				var dels = $(window.frames[frame_id].document).find("#dels").val();      //��ӽ������id
				if(newids !=null && dels != null){
					var newnums=newids.split('-');
					var delnums=dels.split('-');
					for(var i=0;i<newnums.length;i++){
						$("#add"+resume).find("."+delnums[i]).val(newnums[i]);  //�����append��ӵĺͿ�ʼ�Ŀյľ���id
					}
				}
				$("#save"+resume).hide();

				$(".used"+resume).val('1');
				$("#"+resume).html(data);
				layer.msg('�����ɹ���', 2,9,function(){ScrollTo(resume+"_upbox");})
			}else{ 
				layer.msg('����ʧ�ܣ�', 2,8);
			}
	   });
    }
}
function changeModel(type,name,height){
	$.layer({
		type: 2,
		shadeClose: true,
		title: name,
		closeBtn: [0, true],
		shade: [0.8, '#000'],
		border: [0],
		offset: ['20px',''],
		area: ['1000px', ($(window).height() - 50) +'px'],
		iframe: {src: type}
    }); 
}
function changename(){
	$.layer({
		type : 1,
		title : '���ļ�����',
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['350px','150px'],
		page : {dom :"#changename"},
	});
}
$(function(){
		//�����ͣʱ����ʾ֪����ҵ��ע��Ϣ
	$('.yun_resume_info').delegate('.user_item','mouseover',function(){
		$(this).find('.photochange').show();
	});
	//����뿪ʱ������֪����ҵ��ע��Ϣ
	$('.yun_resume_info').delegate('.user_item','mouseout',function(){
		$(this).find('.photochange').hide();
	});
});
function checkdes(){
	var description=$.trim($("#check_des").val());
 	if(description==''){
		$("#des_show").show();
		return false;
	} 
}