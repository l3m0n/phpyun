function show_div(id){
	$("#"+id).show();
}
function checkcity(id,type){
	if(id>0){
		$.post(wapurl+"/index.php?c=ajax&a=wap_city",{id:id,type:type},function(data){ 
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
function checkinfo() {
	var name=$.trim($("input[name='name']").val());
	var birthday=$.trim($("input[name='birthday']").val());
	var living=$.trim($("input[name='living']").val());
	var email=$.trim($("#email").val());
	var telphone=$.trim($("#telphone").val());
	var description=$.trim($("#description").val());

	ifemail = check_email(email);  
	iftelphone = isjsMobile(telphone);
	if(name==""){layermsg('����д������');return false;}
	if($("#user_idcard").val()==1){
		 ifidcard = isIdCardNo(idcard);
		 if(ifidcard==false){layermsg('����ȷ��д���֤���룡');return false;}
	}
	if(birthday==""){layermsg('����д�������£�');return false;}	
	if(living==""){layermsg('����д�־�ס�أ�');return false;}
	if(iftelphone==false){layermsg('����ȷ��д�ֻ����룡');return false;}
	if(ifemail==false){layermsg('����д�����ʼ���');return false;}
	var returntype=false;
	$.ajax({ 
		async: false, 
		type : "POST", 
		url : "index.php?c=get_email_moblie", 
		dataType : 'json', 
		data:{'moblie':telphone,'email':email},
		success : function(data) {
			if(data.msg==1){
				returntype=true;
			}else{
				layermsg(data.msg);return false;
			}
		} 
	});
	return returntype;
/*	var returntype=false;
	$.post("index.php?c=get_email_moblie",{moblie:telphone,email:email},function(data){alert(data);
		if(data==1){
			return true;
		}else{
			layermsg(data);
		}
	})
	return false;*/
}
function addresume(){
	if($.trim($("input[name='name']").val())==""){
		layermsg('����д�������ƣ�');return false;
	}
	if($.trim($("input[name='job_classid']").val())==""){
		layermsg('��ѡ����������ְλ��');return false;
	}
	if($.trim($("#provinceid").val())==""){
		layermsg('��ѡ�����������ص㣡');return false;
	}	
	//--------
	var name=$.trim($("input[name='uname']").val());
	var birthday=$.trim($("input[name='birthday']").val());
	var living=$.trim($("input[name='living']").val());
	var email=$.trim($("input[name='email']").val());
	var telphone=$.trim($("input[name='telphone']").val());
	var description=$.trim($("#description").val());
	ifemail = check_email(email);
	iftelphone = isjsMobile(telphone);
	
	if(iftelphone==false){layermsg('����ȷ��д�ֻ����룡');return false;}
	if(ifemail==false){layermsg('����д�����ʼ���');return false;}
		
	if(name==""){layermsg('����д������');return false;}
	if(living==""){layermsg('����д�־�ס�أ�');return false;}
	if(birthday==""){layermsg('����д�������£�');return false;}

	var returntype=false;
	$.ajax({ 
		async: false, 
		type : "POST", 
		url : "index.php?c=get_email_moblie", 
		dataType : 'json', 
		data:{'moblie':telphone,'email':email},
		success : function(data) {
			if(data.msg==1){
				returntype=true;
			}else{
				layermsg(data.msg);return false;
			}
		} 
	});


	return returntype;
	//-----------
	
}
function convertFormToJson(formid){
	var elements=$("#"+formid).find("*");	
	var str = '';
	for(var i=0;i<elements.length;i++){
		if($(elements).eq(i).attr("name")){ 
			str=str+","+$(elements).eq(i)[0].name+':"'+$(elements).eq(i)[0].value+'"';
		}
	}
	if(str.length>0){
		str=str.substring(1);
	}
	var cToObj=eval("({"+str+"})");
	return cToObj;
}
function check_email(strEmail) {
	 var emailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	 if (emailReg.test(strEmail))
	 return true;
	 else
	 return false;
 }
function isjsMobile(obj){
	if(obj.length!=11) return false;
	else if (obj.substring(0, 2) != "13" && obj.substring(0, 2) != "15" && obj.substring(0, 2) != "18" && obj.substring(0, 3) != "177") return false;
	else if(isNaN(obj)) return false;
	else  return true;
}
function checkshow(id){
	if(id=="expect"){
		$("#infobutton").show();
		$("#info").hide();
	}else if(id=="info"){
		$("#expectbutton").show();
		$("#expect").hide();
	}
	$("#"+id+"button").hide();
	$("#"+id).show();
} 
function saveexpect(){
	var hy=$.trim($("#hy").val());
	var job_classid=$.trim($("#job_classid").val());
	var provinceid=$.trim($("#provinceid").val());
	var cityid=$.trim($("#cityid").val());
	var three_cityid=$.trim($("#three_cityid").val());
	var salary=$.trim($("#salary").val());
	var report=$.trim($("#report").val());
	var type=$.trim($("#type").val());
	var jobstatus=$.trim($("#jobstatus").val());
	var eid=$.trim($("#eid").val());
	if(job_classid==""){
		layermsg('��ѡ����������ְλ��');return false;
	}
	if(provinceid==""){
		layermsg('��ѡ�����������ص㣡');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + "/member/index.php?c=expect", {hy: hy, job_classid: job_classid, provinceid: provinceid, cityid: cityid, three_cityid: three_cityid, salary: salary, report: report,type:type,jobstatus:jobstatus,eid: eid }, function (data) {
		layer.close(layerIndex);
		if(data>0){
			layermsg('����ɹ���',2,function(){window.location.href='index.php?c=modify&eid='+eid;}); 
		}else{
			layermsg('����ʧ�ܣ�');
		}
	})
}

function checkskill(){
	var name=$.trim($("input[name='name']").val());
	var longtime=$.trim($("input[name='longtime']").val());
	var longtime=$.trim($("input[name='longtime']").val());
	if(name==""){
		layermsg('����д�������ƣ�');return false;
	}
}
function checkdesc(){
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#descInfo").attr("action"), convertFormToJson("descInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checkwork(){
	var name=$.trim($("input[name='name']").val());
	var sdate=$.trim($("input[name='sdate']").val()); 
	var edate=$.trim($("input[name='edate']").val()); 
	var title=$.trim($("input[name='title']").val());
	var content=$.trim($("textarea[name='content']").val());
	if(name==""){
		layermsg('����д��λ���ƣ�');return false;
	}
	if(sdate==""||edate==""){
		layermsg('����ȷ��д����ʱ�䣡');return false;
	}	
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#workInfo").attr("action"), convertFormToJson("workInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checkproject(){
	var name=$.trim($("input[name='name']").val());
	var sdate=$.trim($("input[name='sdate']").val()); 
	var edate=$.trim($("input[name='edate']").val()); 
	var title=$.trim($("input[name='title']").val());
	var content=$.trim($("textarea[name='content']").val());
	if(name==""){
		layermsg('����д��Ŀ���ƣ�');return false;
	}
	if(sdate==""||edate==""){
		layermsg('����ȷ��д��Ŀʱ�䣡');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#projectInfo").attr("action"), convertFormToJson("projectInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checkedu(){
	var name=$.trim($("input[name='name']").val());
	var sdate=$.trim($("input[name='sdate']").val()); 
	var edate=$.trim($("input[name='edate']").val()); 
	var education=$.trim($("#education").val());
	var title=$.trim($("input[name='title']").val());
	var specialty=$.trim($("input[name='specialty']").val());
	if(name==""){
		layermsg('����дѧУ���ƣ�');return false;
	}
	if(sdate==""||edate==""){
		layermsg('����ȷ��д��Уʱ�䣡');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl+'/member/'+$("#eduInfo").attr("action"),convertFormToJson("eduInfo"),function(data){
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checktraining(){
	var name=$.trim($("input[name='name']").val());
	var sdate=$.trim($("input[name='sdate']").val()); 
	var edate=$.trim($("input[name='edate']").val()); 
	var title=$.trim($("input[name='title']").val());
	var content=$.trim($("textarea[name='content']").val());
	if(name==""){
		layermsg('����д��ѵ���ģ�');return false;
	}
	if(sdate==""||edate==""){
		layermsg('����ȷ��д��ѵʱ�䣡');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#trainingInfo").attr("action"), convertFormToJson("trainingInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checkcert(){
	var name=$.trim($("input[name='name']").val());
	var sdate=$.trim($("input[name='sdate']").val()); 
	var edate=$.trim($("input[name='edate']").val()); 
	var title=$.trim($("input[name='title']").val());
	var content=$.trim($("textarea[name='content']").val());
	if(name==""){
		layermsg('����д֤��ȫ�ƣ�');return false;
	}
	if(sdate==""){
		layermsg('����д֤��䷢ʱ�䣡');return false;
	}
	if(title==""){
		layermsg('����д�䷢��λ��');return false;
	}
	if(content==""){
		layermsg('����д֤��������');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#certInfo").attr("action"), convertFormToJson("certInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function checkother(){
	var name=$.trim($("input[name='name']").val());
	var content=$.trim($("textarea[name='content']").val());
	if(name==""){
		layermsg('����д�������⣡');return false;
	}
	var layerIndex=layer.open({
		type: 2,
		content: 'Ŭ��������'
	});
	$.post(wapurl + '/member/' + $("#otherInfo").attr("action"), convertFormToJson("otherInfo"), function (data) {
		layer.close(layerIndex);
		var jsonData=eval("("+data+")"); 
		if(jsonData.url){
			layermsg(jsonData.msg,2,function(){window.location.href=jsonData.url;}); 
		}else{
			layermsg(jsonData.msg);
		}
	});
	return false;
}
function ckresume(type){
	var val=$("#"+type).find("option:selected").text(); 
	$('.'+type).html(val); 
}
function check_show(id){
	$('#'+id).toggle();
}