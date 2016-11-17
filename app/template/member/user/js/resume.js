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
	if(name==''){layer.msg('请填写姓名', 2, 8);return false;}
	if(sex==''){layer.msg('请选择性别', 2, 8);return false;}
	if(birthday==''){layer.msg("请选择出生年月", 2, 8);return false;}
	ifemail = check_email(email); 
	ifmoblie = isjsMobile(telphone);
	if(telphone==''){
		layer.msg('请填写手机号', 2, 8);return false;
	}else{
		if(ifmoblie==false){layer.msg("手机格式不正确", 2, 8);return false;}
	}
	if(email==''){
		layer.msg('请填写邮箱', 2, 8);return false;
	}else{
		if(ifemail==false){layer.msg("电子邮件格式不正确", 2, 8);return false;}
	}
	if(living==''){layer.msg('请填写现居住地', 2, 8);return false;}
	if(edu==''){layer.msg('请选择学历', 2, 8);return false;} 
	if(exp==''){layer.msg('请选择工作经验', 2, 8);return false;}  
	if(telhome&&isjsTell(telhome)==false){
		layer.msg('请填写正确的座机号', 2, 8);return false;
	}
	layer.load('执行中，请稍候...',0);
}
function checkmore(type){
	var getinfoid=$.trim($("#getinfoid").val());
	if(getinfoid!=1){
		layer.msg('请先完善基本资料！', 2, 8);return false;
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
		layer.msg('请先完善基本资料！', 2, 8);return false;
	}
	var eid=$.trim($("#eid").val());
	if(eid==""){
		layer.msg('请先完善求职意向！', 2, 8);return false;
	}
	ScrollTo(type+"_add");
	$("#"+type).show();
	$("#"+type+"_botton").attr("class","jianli_list_noadd");
	$("#"+type+"_botton").html('<em>暂不填写</em>');
	$("#"+type+"_botton").attr("onclick","checkClose2('"+type+"');");
	$("#Add"+type).hide();
	if(type=="skill"){
		$("#skillcid").val('');
		$("#levelid").val('');
		$("#skill_name").val('');
		$("#skill_longtime").val('');
		$("#skillid").val('');
		$("#skillc").val('请选择技能类别');
		$("#level").val('请选择熟练程度');
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
		$("#educationc").val('请选择最高学历');
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
	$("#"+type+"_botton").html('<em>添加</em>');
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
	layer.load('执行中，请稍候...',0);
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
	layer.confirm('确定要删除该项内容？', function(){
		layer.load('执行中，请稍候...',0);
		$.post("index.php?c=resume&act=publicdel",{table:table,id:id,eid:eid},function(data){
			layer.closeAll();
			if(data!="0"){ 
				data=eval('('+data+')');
				$("#"+table+id).remove();
				if(parseInt(data.integrity)<60){
					 var showhtml="您现在的简历完整度太低，还不能够使用此简历应聘!"
				}else{
					var showhtml="您的简历已符合要求！"
				}
				//更新右侧完整度
				if(data.num<1){
					changeRightIntegrityState("m_right_"+table,"remove");
				}
				$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
				$("#numresume").html(data.integrity+"%");
				$(".play").attr("style","width:"+data.integrity+"%");
				if(data.num=="0"){
					$(".resume_"+table).removeClass('state_done');
				} 
				layer.msg('删除成功！', 2,9);				
			}else{ 
				layer.msg('网络繁忙，请稍后！', 2,8);	
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
		layer.msg('请先完善求职意向！', 2, 8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写技能名称！', 2, 8);
		return false;
	} 
	if(skill==""){ 
		layer.msg('请选择技能类别！', 2, 8);
		return false;
	}
	if(ing==""){ 
		layer.msg('请选择熟练程度！', 2, 8);
		return false;
	}
	if(longtime==""){ 
		layer.msg('请填写掌握时间！', 2, 8);
		return false;
	}
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=skill",{skill:skill,ing:ing,name:name,longtime:longtime,eid:eid,id:id,table:"resume_skill",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#skill").hide();
			$("#Addskill").show();
			if(id>0){ 
				var html='<li><span>技能名称：</span>'+data.name+'</li><li><span>掌握时间：</span>'+data.longtime+'年</li><li><span>技能类别：</span>'+data.skillval+'</li><li><span>熟练程度：</span>'+data.ingval+'</li>';
				$("#skill_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('skill');ScrollTo("skill_botton");}); 
			}else{
				numresume(data.numresume,'skill');
				var html='<div class="expect_tj_list" id="skill'+data.id+'" onmousemove="movelook(\'skill\',\''+data.id+'\');" onmouseout="outlook(\'skill\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'skill\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'skill\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="skill_'+data.id+'"><li><span>技能名称：</span>'+data.name+'</li><li><span>掌握时间：</span>'+data.longtime+'年</li><li><span>技能类别：</span>'+data.skillval+'</li><li><span>熟练程度：</span>'+data.ingval+'</li></ul></div>';
				$("#skillList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('skill');ScrollTo("skill_botton");}); 
			}
			changeRightIntegrityState("m_right2","add");
		}else{ 
			layer.msg('操作失败！', 2,8);
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写单位名称！', 2,8);
		return false;
	}
	if(sdate==""){
		layer.msg('开始时间不能为空！', 2,8); return false
	}else if(edate){
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 	
			layer.msg('开始时间不得大于结束时间', 2,8);return false
		}
	}	
	if($("#totoday").attr("checked")!="checked" && edate==""){
		layer.msg('结束时间不能为空！', 2,8); return false
	}	
	if(title==""){ 
		layer.msg('请填写担任职位！', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('请填写工作内容！', 2,8);
		return false;
	}
	layer.load('执行中，请稍候...',0); 
	$.post("index.php?c=expect&act=work",{sdate:sdate,edate:edate,name:name,department:department,eid:eid,salary:salary,title:title,content:content,id:id,table:"resume_work",submit:"1",dom_sort:getDomSort()},function(data){	
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#work").hide();
			$("#Addwork").show();
			if(id>0){ 
				var html='<li><span>单位名称：</span>'+data.name+'</li><li><span>工作时间：</span>'+data.sdate+'至  '+data.edate+'</li><li><span>担任职位：</span>'+data.title+'</li><li class="expect_amend_end"><span>工作内容：</span><em>'+data.content+'</em></li>';
				$("#work_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('work');ScrollTo("work_botton");}); 
			}else{
				numresume(data.numresume,'work');
				var html='<div class="expect_tj_list" id="work'+data.id+'" onmousemove="movelook(\'work\',\''+data.id+'\');" onmouseout="outlook(\'work\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'work\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'work\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="work_'+data.id+'"><li><span>单位名称：</span>'+data.name+'</li><li><span>工作时间：</span>'+data.sdate+'至 '+data.edate+'</li><li><span>担任职位：</span>'+data.title+'</li><li class="expect_amend_end"><span>工作内容：</span><em>'+data.content+'</em></li></ul></div>';
				$("#workList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('work');ScrollTo("work_botton");}); 
			}
			changeRightIntegrityState("m_right3","add");
		}else{ 
			layer.msg('操作失败！', 2,8);
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写项目名称！', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{ 
		layer.msg('开始时间，结束时间不能为空！', 2,8);
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 
			layer.msg('开始时间不得大于结束时间！', 2,8);			
			return false
		}
	}		
	if(title==""){ 
		layer.msg('请填写担任职位！', 2,8);
		return false;
	}
	if(content==""){
		layer.msg('请填写项目内容！', 2,8); 
		return false;
	}
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=project",{sdate:sdate,edate:edate,name:name,sys:sys,eid:eid,title:title,content:content,id:id,table:"resume_project",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#project").hide();
			$("#Addproject").show();
			if(id>0){ 
				var html='<li><span>项目名称：</span>'+data.name+'</li><li><span>项目时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span> 担任职位：</span>'+data.title+'</li><li class="expect_amend_end"><span>项目内容：</span><em>'+data.content+'</em></li>';
				$("#project_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('project');ScrollTo("project_botton");});	 
			}else{
				numresume(data.numresume,'project');
				var html='<div class="expect_tj_list" id="project'+data.id+'" onmousemove="movelook(\'project\',\''+data.id+'\');" onmouseout="outlook(\'project\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'project\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'project\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="project_'+data.id+'"><li><span>项目名称：</span>'+data.name+'</li><li><span>项目时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span> 担任职位：</span>'+data.title+'</li><li class="expect_amend_end"><span>项目内容：</span><em>'+data.content+'</em></li></ul></div>';
				$("#projectList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('project');ScrollTo("project_botton");}); 
			}
			changeRightIntegrityState("m_right4","add");
		}else{ 
			layer.msg('操作失败！', 2,8); 
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写学校名称！', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{
		layer.msg('开始时间，结束时间不能为空！', 2,8); 
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){
			layer.msg('开始时间不得大于结束时间', 2,8); 
			return false
		}
	} 
	if(education==""){ 
		layer.msg('请填写最高学历！', 2,8); 
		return false;
	}
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=edu",{sdate:sdate,edate:edate,name:name,education:education,eid:eid,title:title,id:id,table:"resume_edu",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#edu").hide();
			$("#Addedu").show();
			if(id>0){ 
				var html='<li><span>学校名称：</span>'+data.name+'</li><li><span>在校时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span>最高学历：</span>'+data.educationval+'</li><li><span>担任职位：</span>'+data.title+'</li>';
				$("#edu_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('edu');ScrollTo("edu_botton");}); 
			}else{
				numresume(data.numresume,'edu');
				var html='<div class="expect_tj_list" id="edu'+data.id+'" onmousemove="movelook(\'edu\',\''+data.id+'\');" onmouseout="outlook(\'edu\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'edu\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'edu\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="edu_'+data.id+'"><li><span>学校名称：</span>'+data.name+'</li><li><span>在校时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span>最高学历：</span>'+data.educationval+'</li><li><span>担任职位：</span>'+data.title+'</li></ul></div>';
				$("#eduList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('edu');ScrollTo("edu_botton");}); 
			}
			changeRightIntegrityState("m_right0","add");
		}else{ 
			layer.msg('操作失败！', 2,8);
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写培训中心！', 2,8);
		return false;
	}
	if(sdate==""||edate=="")
	{ 
		layer.msg('开始时间，结束时间不能为空！', 2,8);		
		return false
	}else{
		var st=toDate(sdate);
		var ed=toDate(edate);
		if(st>ed){ 
			layer.msg('开始时间不得大于结束时间', 2,8);			
			return false
		}
	}	
	if(title==""){ 
		layer.msg('请填写培训方向！', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('请填写培训描述！', 2,8);
		return false;
	}	
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=training",{sdate:sdate,edate:edate,name:name,eid:eid,title:title,content:content,id:id,table:"resume_training",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#training").hide();
			$("#Addtraining").show();
			if(id>0){ 
				var html='<li><span>培训中心：</span>'+data.name+'</li><li><span>培训时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span>培训方向：</span>'+data.title+'</li><li class="expect_amend_end"><span>培训描述：</span><em>'+data.content+'</em></li>';
				$("#training_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('training');ScrollTo("training_botton");}); 
			}else{
				numresume(data.numresume,'training');
				var html='<div class="expect_tj_list" id="training'+data.id+'" onmousemove="movelook(\'training\',\''+data.id+'\');" onmouseout="outlook(\'training\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'training\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'training\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="training_'+data.id+'"><li><span>培训中心：</span>'+data.name+'</li><li><span>培训时间：</span>'+data.sdate+'至'+data.edate+'</li><li><span>培训方向：</span>'+data.title+'</li><li class="expect_amend_end"><span>培训描述：</span><em>'+data.content+'</em></li></ul></div>';
				$("#trainingList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('training');ScrollTo("training_botton");}); 
			}
			changeRightIntegrityState("m_right1","add");
		}else{ 
			layer.msg('操作失败！', 2,8);
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(name==""){ 
		layer.msg('请填写证书名称！', 2,8);
		return false;
	}
	if(sdate==""){ 
		layer.msg('颁发时间不能为空！', 2,8);return false
	}
	if(title==""){ 
		layer.msg('请填写颁发单位！', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('请填写证书描述！', 2,8);
		return false;
	}
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=cert",{sdate:sdate,name:name,eid:eid,title:title,content:content,id:id,table:"resume_cert",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#cert").hide();
			$("#Addcert").show();
			if(id>0){
				var html='<li><span>证书全称：</span>'+data.name+'</li><li><span>颁发时间：</span>'+data.sdate+'</li><li><span>颁发单位：</span>'+data.title+'</li><li class="expect_amend_end"><span>证书描述：</span><em>'+data.content+'</em></li>';
				$("#cert_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('cert');ScrollTo("cert_botton");}); 
			}else{
				numresume(data.numresume,'cert');
				var html='<div class="expect_tj_list" id="cert'+data.id+'" onmousemove="movelook(\'cert\',\''+data.id+'\');" onmouseout="outlook(\'cert\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'cert\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'cert\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="cert_'+data.id+'"><li><span>证书全称：</span>'+data.name+'</li><li><span>颁发时间：</span>'+data.sdate+'</li><li><span>颁发单位：</span>'+data.title+'</li><li class="expect_amend_end"><span>证书描述：</span><em>'+data.content+'</em></li></ul></div>';
				$("#certList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('cert');ScrollTo("cert_botton");}); 
			}
			changeRightIntegrityState("m_right5","add");
		}else{ 
			layer.msg('操作失败！', 2,8);
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
		layer.msg('请先完善求职意向！', 2,8);
		return false;
	}
	if(title==""){ 
		layer.msg('请填写其他标题！', 2,8);
		return false;
	}
	if(content==""){ 
		layer.msg('请填写其他描述！', 2,8);
		return false;
	}	
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=other",{eid:eid,title:title,content:content,id:id,table:"resume_other",submit:"1",dom_sort:getDomSort()},function(data){
		layer.closeAll();
		if(data!=0){
			data=eval('('+data+')');
			$("#other").hide();
			$("#Addother").show();
			if(id>0){ 
				var html='<li><span>其他标题：</span>'+data.title+'</li><li class="expect_amend_end"><span>其他描述：</span><em>'+data.content+'</em></li>';
				$("#other_"+id).html(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('other');ScrollTo("other_botton");}); 
			}else{
				numresume(data.numresume,'other');
				var html='<div class="expect_tj_list" id="other'+data.id+'" onmousemove="movelook(\'other\',\''+data.id+'\');" onmouseout="outlook(\'other\',\''+data.id+'\');"><div class="expect_modify"><a href="javascript:getresume(\'other\',\''+data.id+'\');">修改</a><a href="javascript:resume_del(\'other\',\''+data.id+'\');">删除</a></div><ul class="expect_amend" id="other_'+data.id+'"><li><span>其他标题：</span>'+data.title+'</li><li class="expect_amend_end"><span>其他描述：</span><em>'+data.content+'</em></li></ul></div>';
				$("#otherList").append(html);
				layer.msg('操作成功！', 2,9,function(){checkClose2('other');ScrollTo("other_botton");}); 				
			}
			changeRightIntegrityState("m_right6","add");
		}else{ 
			layer.msg('操作失败！', 2, 8);
		}	
	});
}
function toDate(str){
    var sd=str.split("-");
    return new Date(sd[0],sd[1],sd[2]);
}
function numresume(numresume,type){
	if(numresume<60){
		 var showhtml="您现在的简历完整度太低，还不能够使用此简历应聘!"
	}else{
		var showhtml="您的简历已符合要求！"
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
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect",{shell:1},function(data){
 		if(data==1){
			layer.msg('请先完善基本资料！', 2, 8);return false;
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
	if(hy==""){layer.msg('请选择从事行业！', 2, 8);return false;}
	if(three_cityid==""&&cityid==''){layer.msg('请选择工作地点！', 2, 8);return false;}
	if(job_classid==""){layer.msg('请选择从事职位！', 2, 8);return false;}
	if(salary==""){layer.msg('请选择期望薪资！', 2, 8);return false;}
	if(type==""){layer.msg('请选择工作性质！', 2, 8);return false;}
	if (report == "") { layer.msg('请选择到岗时间！', 2, 8); return false; }
	if (jobstatus == "") { layer.msg('请选择求职状态！', 2, 8); return false; }
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=saveexpect", { hy: hy, job_classid: job_classid, provinceid: provinceid, cityid: cityid, three_cityid: three_cityid, salary: salary, jobstatus: jobstatus, type: type, report: report, eid: eid, submit: "1", dom_sort: getDomSort() }, function (data) {
		layer.closeAll();
		if(data==0){
			layer.msg('操作失败！', 2, 8);
		}else if(data==1){
			layer.msg('你的简历数已经超过系统设置的简历数了！', 2, 8);
		}else{
			data=eval('('+data+')');
			if(eid==""){
				 window.location.href="index.php?c=expect&e="+data.id;
			}else{
				$("#saveexpect").hide();
				//numresume(data.numresume,'expect');
				$("#eid").val(data.id);
				var html='<li>期望从事行业：'+data.hy+'</li><li>期望工作地点：'+data.city+'</li><li>期望薪资：'+data.salary+'</li><li>到岗时间：'+data.report+'</li><li>求职状态：'+data.jobstatus+'</li><li>期望工作性质：'+data.type+'</li><li  class="yun_resume_job_intention_list_end">期望从事职位：'+data.job_classname+'</li>';
				$("#expect").html(html);
				layer.msg('操作成功！', 2,9,function(){ScrollTo("expect_botton");$(".resume_expect").addClass('state_done');}); 
			}
		}
	});
}
function savedescription(){	
    shell();
	var eid = $.trim($("#eid").val());
	var description = $.trim($("#description").val()); 
	if(description==""){layer.msg('请填写自我评价！', 2, 8);return false; }
	layer.load('执行中，请稍候...',0);
	$.post("index.php?c=expect&act=savedescription",{description:description,submit:"1",eid:eid},function(data){
		layer.closeAll();
		if(data==0){
			layer.msg('操作失败！', 2, 8,function(){location.reload();});
		}else{
			layer.msg('操作成功！', 2, 9,function(){location.reload();});
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
		layer.msg('请先完善简历！', 2,8);
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
	if(name==""){layer.msg('请填写简历名称！', 2, 8);return false; }
	if(hy==""){layer.msg('请选择从事行业！', 2, 8);return false;}
	if(three_cityid=="" && cityid==''){layer.msg('请选择工作地点！', 2, 8);return false;}
	if(job_classid==""){layer.msg('请选择从事职位！', 2, 8);return false;}
	if(salary==""){layer.msg('请选择期望薪资！', 2, 8);return false;}
	if(type==""){layer.msg('请选择工作性质！', 2, 8);return false;}
	if (report == "") { layer.msg('请选择到岗时间！', 2, 8); return false; }
	if (jobstatus == "") { layer.msg('请选择求职状态！', 2, 8); return false; }

	layer.load('简历创建中，请稍候...',0);
}
function showMore(type,width,height,name){
	$("#add"+type+" li").show();
	$(".newresumebox").removeClass("newresumebox");  //打开弹出框时移除新加内容的class
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
			$(".newresumebox").remove();  //关闭弹出框移除未添加内容的新弹出的class
			$("#save"+type).hide();
			var num=$(".show"+type+"num");
			if(num.length==1){
				$(".hidepic").hide();
			}
		} 
	});
}
function hideMore(type){
	$(".newresumebox").remove();//关闭弹出框移除未添加内容的新弹出的class
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
		$("#"+edate).attr('value','至今');
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
		$(".newresumebox").removeClass("newresumebox");  //只剩下最后一个新添加移除新加内容的class
	}
	if(id&&eid&&table){
		$.post("index.php?c=resume&act=publicdel",{id:id,eid:eid,table:table},function(data){
			if(data!='0'){
				
				data=eval('('+data+')');
				if(parseInt(data.integrity)<60){
					 var showhtml="您现在的简历完整度太低，还不能够使用此简历应聘!"
				}else{
					var showhtml="您的简历已符合要求！"
				}
				//更新右侧完整度
				if(data.num<1){
					changeIntegrityState("m_right_"+table,"remove");
				}
				$("#"+table+""+id).remove();
				$("#_ctl0_UserManage_LeftTree1_msnInfo").html(showhtml);
				$("#numresume").html(data.integrity+"%");
				$(".play").attr("style","width:"+data.integrity+"%");
				
				if(data.num=="0"){
					$(".resume_"+table).removeClass('state_done');
					$("#"+table+"_empty").show();             //没有内容显示提示
				} 
				//layer.msg('删除成功！', 2,9);			
			}else{
				layer.msg('操作失败！', 2, 9,function(){location.reload();});
			}
		});
	}
}

function addWork(){
	$("#addwork").append(function(){
		$(".lastupbox").removeClass("lastupbox");
		var randnum='w'+parseInt(Math.random()*1000); 
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showworknum' id='iw"+randnum+"' onclick=\"deleteupbox('"+randnum+"','iw"+randnum+"','showworknum','work')\">-</i><input type='hidden' name='id[]' value='' class='"+randnum+"'/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='iw"+randnum+"'><input type='hidden' class='usedwork' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>公司名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mworkn"+randnum+"')\" class='yun_resume_popup_text work_name'><i class='yun_resume_popup_qyname_tip' id='mworkn"+randnum+"' style='display:none'>请填写公司名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>担任职位：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text work_title'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>工作时间：</span><div class='yun_resume_popup_list_box'><input type='text' id='work_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_sdate'><script>$('#work_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mworks"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>-</span><div class='yun_resume_popup_list_box'><input type='text' id='work_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mworkiw"+randnum+"','mworks"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90 work_edate'><script>$('#work_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mworkiw"+randnum+"' style='display:none'>请确认日期先后顺序</i></div><input type='hidden' id='to"+randnum+"' name='totoday[]' value='1'><input class='yun_resume_popup_checkbox' type='checkbox' value='1' onclick=\"untiltoday('totoday"+randnum+"','work_edate"+randnum+"','mworkiw"+randnum+"','to"+randnum+"')\" id='totoday"+randnum+"'><span class='yun_resume_popup_checkbox_s'><label for='totoday'>至今</label></span> </div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>工作内容：</span><textarea rows='5' cols='50' name='content[]' id='work_content' class='infor_textarea work_content'></textarea></div></li>";
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
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showtrainingnum'  id='it"+randnum+"' onclick=\"deleteupbox('"+randnum+"','it"+randnum+"','showtrainingnum','training')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='it"+randnum+"'><input type='hidden' class='usedtraining' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训中心：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mtrainingn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mtrainingn"+randnum+"' style='display:none'>请填写培训中心名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训方向：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训时间：</span><div class='yun_resume_popup_list_box'><input type='text' id='edu_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#edu_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mtrainings"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>至</span><div class='yun_resume_popup_list_box'><input type='text' id='edu_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mtrainingit"+randnum+"','mtrainings"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#edu_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  <\/script><i class='yun_resume_popup_list_box_tip' id='mtrainingit"+randnum+"' style='display:none'>请确认日期先后顺序</i></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>培训内容：</span><textarea rows='5' cols='50' name='content[]' id='training_content' class='infor_textarea '></textarea></div></li>";
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
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showprojectnum'  id='ip"+randnum+"' onclick=\"deleteupbox('"+randnum+"','ip"+randnum+"','showprojectnum','project')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='sdateid[]' value='s"+randnum+"'><input type='hidden' name='timeid[]' value='ip"+randnum+"'><input type='hidden' class='usedproject' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' value=''  onblur=\"hidemsg('mprojectn"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mprojectn"+randnum+"' style='display:none'>请填写培项目名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>担任职位：</span><input type='text' name='title[]' value='' class='yun_resume_popup_text'></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目时间：</span>            <div class='yun_resume_popup_list_box'><input type='text' id='project_sdate"+randnum+"' name='sdate[]' value='' onblur=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#project_sdate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  </script><i class='yun_resume_popup_list_box_tip' id='mprojects"+randnum+"' style='display:none'>请选择开始日期</i></div><span class='yun_resume_popup_time'>至</span><div class='yun_resume_popup_list_box'><input type='text' id='project_edate"+randnum+"' name='edate[]' value='' onblur=\"hidemsg('mprojectip"+randnum+"','mprojects"+randnum+"')\" class='yun_resume_popup_text yun_resume_popup_textw90'><script>$('#project_edate"+randnum+"').fdatepicker({format: 'yyyy-mm',startView:4,minView:3});  </script><i class='yun_resume_popup_list_box_tip' id='mprojectip"+randnum+"' style='display:none'>请确认日期先后顺序</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>项目内容：</span><textarea rows='5' cols='50' name='content[]' id='project_content' class='infor_textarea '></textarea></div></li>";
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
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showskillnum' id='is"+randnum+"' onclick=\"deleteupbox('"+randnum+"','is"+randnum+"','showskillnum','skill')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='is"+randnum+"'><input type='hidden' class='usedskill' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>技能名称：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_name' value='' onblur=\"hidemsg('mskilln"+randnum+"')\" class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mskilln"+randnum+"' style='display:none'>请填写技能名称</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>掌握时间：</span><input type='text' name='longtime[]' id='skill_longtime' value='' onkeyup='this.value=this.value.replace(/[^0-9.]/g,'')' class='yun_resume_popup_text'>年</div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>上传证书：</span><div class='yun_resume_popup_upp'><input type='file' title='点击上传证书' name='cert[]'style='width:200px;'></div></div></li>";
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
		var html="<li class='yun_resume_popup newresumebox lastupbox' id='"+randnum+"'><i class='yun_resume_popup_del showothernum'  id='io"+randnum+"' onclick=\"deleteupbox('"+randnum+"','io"+randnum+"','showothernum','other')\">-</i><input type='hidden' name='id[]' class='"+randnum+"' value=''/><input type='hidden' name='nameid[]' value='n"+randnum+"'><input type='hidden' name='timeid[]' value='io"+randnum+"'><input type='hidden' class='usedother' name='usedid[]' value=''><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>标题：</span><div class='yun_resume_popup_qyname'><input type='text' name='name[]' id='skill_title' value='' onblur=\"hidemsg('mothern"+randnum+"')\"class='yun_resume_popup_text'><i class='yun_resume_popup_qyname_tip' id='mothern"+randnum+"' style='display:none'>请填写标题</i></div></div><div class='yun_resume_popup_list'><span class='yun_resume_popup_name'>内容：</span><textarea rows='5' cols='50' name='content[]' id='skill_content' class='infor_textarea '></textarea></div></li>";
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
	var timenum = $(window.frames[frame_id].document).find("#timenum").val(); //显示时间顺序出错的
	var wrong = $(window.frames[frame_id].document).find("#wrong").val(); 
	if(timenum !=null && wrong !=null){
		var tnums=timenum.split('-');
		for(var i=0;i<tnums.length;i++){
			$("#m"+wrong+tnums[i]).show();
		}
	}
	var namenum = $(window.frames[frame_id].document).find("#namenum").val(); //显示name为空的
	if(namenum !=null && wrong !=null){
		var namenums=namenum.split('-');
		for(var i=0;i<namenums.length;i++){
			$("#m"+wrong+namenums[i]).show();
		}
	}
	var sdatenum = $(window.frames[frame_id].document).find("#sdatenum").val(); //显示sdate为空的
	if(sdatenum !=null && wrong !=null){
		var sdatenums=sdatenum.split('-');
		for(var i=0;i<sdatenums.length;i++){
			$("#m"+wrong+sdatenums[i]).show();
		}
	}
	var emptynum = $(window.frames[frame_id].document).find("#emptynum").val(); //点过保存，再次点开时把有值的<li>留下来，而没有值的<li>移除
	var littlenum = $(window.frames[frame_id].document).find("#littlenum").val(); //获得提交时有值的<li>中的小标签数量
    if(emptynum !=null){
		var enums=emptynum.split('-');
		if(enums.length==1){            //空的<li>只有一个，
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
		layer.load('执行中，请稍候...',0); 
		$.post(weburl+"/member/index.php?c=expect&act=showresume",{eid:eid,resume:resume},function(data){		
			layer.closeAll();

			if(data!=0){

				if(parseInt(message)!=10||parseInt(message)!=9){
					var integrity = $(window.frames[frame_id].document).find("#integrity").val();   //右侧简历百分比
					numresume(integrity,resume);
					changeIntegrityState("m_right"+message,"add");
				}
				
				if(littlenum !=null){
					var num=$(".show"+resume+"num");
					if(num.length==1){                                                   //小标签只有一个时隐藏
					$(".show"+resume+"num").hide();
					$(".newresumebox").removeClass("newresumebox");  //只剩下最后一个新添加移除新加内容的class
				    }

				}
                var newids = $(window.frames[frame_id].document).find("#newids").val();  //append添加的经历的随机id
				var dels = $(window.frames[frame_id].document).find("#dels").val();      //添加进表格后的id
				if(newids !=null && dels != null){
					var newnums=newids.split('-');
					var delnums=dels.split('-');
					for(var i=0;i<newnums.length;i++){
						$("#add"+resume).find("."+delnums[i]).val(newnums[i]);  //添加用append添加的和开始的空的经历id
					}
				}
				$("#save"+resume).hide();

				$(".used"+resume).val('1');
				$("#"+resume).html(data);
				layer.msg('操作成功！', 2,9,function(){ScrollTo(resume+"_upbox");})
			}else{ 
				layer.msg('操作失败！', 2,8);
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
		title : '更改简历名',
		closeBtn : [0 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['350px','150px'],
		page : {dom :"#changename"},
	});
}
$(function(){
		//光标悬停时，显示知名企业关注信息
	$('.yun_resume_info').delegate('.user_item','mouseover',function(){
		$(this).find('.photochange').show();
	});
	//光标离开时，隐藏知名企业关注信息
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