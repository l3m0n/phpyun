
function applyjobuid(url){
	$.layer({
		type : 2,
		fix: false,
		maxmin: false,
		shadeClose: true,
		title :'��������ְλ', 
		offset: [($(window).height() - 600)/2 + 'px', ''],
		closeBtn : [0 , true], 
		area : ['900px','600px'],
		zIndex: 999,
		iframe: {src:url} 
	})
}
function OnLogin(){ 
	showlogin('1');
}
function checkaddresume(){   
	var name=$.trim($("#name").val());
	var uname=$.trim($("#uname").val());
	var sex=$("input[name=sex]:checked").val();
	var birthday=$.trim($("#birthday").val());
	var edu=$.trim($("#educid").val());
	var exp=$.trim($("#expid").val());
	var telphone=$.trim($("#telphone").val());
	var email=$.trim($("#email").val());
	var living=$.trim($("#living").val());
	var hy=$.trim($("#hyid").val());
	var job_classid=$.trim($("#job_class").val());
	var salary=$.trim($("#salaryid").val());
	var provinceid=$.trim($("#provinceid").val());
	var cityid=$.trim($("#citysid").val());
	var three_cityid=$.trim($("#three_cityid").val());
	var type=$.trim($("#typeid").val());
	var report=$.trim($("#reportid").val());
	var jobstatus=$.trim($("#statusid").val());
	
	if(name==""){
		parent.layer.msg("����д�������ƣ�",2,8);return false;
	}
	if(uname==""){
		parent.layer.msg("����д��ʵ������",2,8);return false;
	}
	if(edu==""){
		parent.layer.msg("��ѡ�����ѧ����",2,8);return false;
	}
	if(exp==""){
		parent.layer.msg("��ѡ�������飡",2,8);return false;
	}
	if(telphone==''){
		parent.layer.msg("����д�ֻ����룡",2,8);return false;
	}else{
	  var reg= /^[1][34578]\d{9}$/; //��֤�ֻ�����  
		 if(!reg.test(telphone)){
			parent.layer.msg("�ֻ������ʽ����",2,8);return false;
		 }
	}
	if(email==''){
		parent.layer.msg("����д��ϵ���䣡",2,8);return false;
	}else{
		var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	   if(!myreg.test(email)){
			parent.layer.msg("�����ʽ����",2,8);return false; 
	   }
	}
	if(living==""){
		parent.layer.msg("�������־�ס�أ�",2,8);return false;
	}
	if(hy==""){
		parent.layer.msg("��ѡ�������ҵ��",2,8);return false;
	}
	if(job_classid==''){
		parent.layer.msg("��ѡ������ְλ��",2,8);return false;
	}
	if(salary==""){
		parent.layer.msg("��ѡ������н�ʣ�",2,8);return false;
	}
	if(cityid==""){
		parent.layer.msg("��ѡ���������У�",2,8);return false;
	}
	if(report==""){
		parent.layer.msg("��ѡ�񵽸�ʱ�䣡",2,8);return false;
	}
	if(type==""){
		parent.layer.msg("��ѡ�������ʣ�",2,8);return false;
	}
	if(jobstatus==""){
		parent.layer.msg("��ѡ����ְ״̬��",2,8);return false;
	}
	var jobload=parent.layer.load('�����У����Ժ�...',0);
	$.post(weburl+"/index.php?m=ajax&c=temporaryresume",{name:name,uname:uname,sex:sex,birthday:birthday,edu:edu,exp:exp,telphone:telphone,email:email,living:living,hy:hy,job_classid:job_classid,salary:salary,provinceid:provinceid,cityid:cityid,three_cityid:three_cityid,type:type,report:report,jobstatus:jobstatus},function(data){ 
		parent.layer.close(jobload); 
		if(data>0){ 
			$("#resumeid").val(data);
			$.layer({
				type : 1,
				title :'����ע��', 
				offset: ['60px', ''],
				closeBtn : [0 , true],
				border : [10 , 0.3 , '#000', true],
				area : ['500px','390px'],
				page : {dom :"#userregdiv"}
			}); 
		}else{ 
			parent.layer.msg(data,2,8);return false;
		}
	})
}
function checkreg(){
	var username=$("#reg_username").val();
	var password=$("#reg_password").val();
	var authcode=$("#reg_authcode").val();
	var resumeid=$("#resumeid").val();
	var jobid=$("#jobid").val();
	if(username==""){
		parent.layer.msg("�������û�����",2,8);return false;
	}else if(username.length<2||username.length>16){
		parent.layer.msg("������2��16λ�����������ַ����û�����",2,8);return false;
	}
	if(password==""){
		parent.layer.msg("���������룡",2,8);return false;
	}else if(password.length<6 || password.length>20 ){
		parent.layer.msg("������6��20λ���룡",2,8);return false;
	}
	if(authcode==""){
		parent.layer.msg("��������֤�룡",2,8);return false;
	}
	var loadi=layer.load('�����У����Ժ�...',0);
	$.post(weburl+"/index.php?m=ajax&c=userreg",{username:username,password:password,authcode:authcode,resumeid:resumeid,jobid:jobid},function(data){
		layer.close(loadi);  
		if(data==1){
			parent.layer.msg('����ɹ���', 2, 9,function(){parent.location.reload();}); 
		}else if(data==2){
			parent.layer.msg("�û����Ѵ���!",2,8,function(){checkcodes();}); 
		}else if(data==3){
			parent.layer.msg("��֤�����!",2,8,function(){checkcodes();}); 
		}else if(data==4){
			parent.layer.msg("��Ч�û���!",2,8,function(){checkcodes();}); 
		}else{
			parent.layer.msg("����ʧ��!", 2, 8,function(){parent.location.reload();}); 
		}
	})
}
