function sendmoblie(){
	if($("#send").val()=="1"){
		return false;
	}
	var moblie=$("input[name=moblie]").val();
	var reg= /^[1][34578]\d{9}$/; //��֤�ֻ�����
	if(moblie==''){
		layermsg('�ֻ��Ų���Ϊ�գ�',2);return false;
	}else if(!reg.test(moblie)){
		layermsg('�ֻ������ʽ����',2);return false;
	}
	layer_load('ִ���У����Ժ�...',0);
	$.post(weburl+"/wap/index.php?c=ajax&a=mobliecert", {str:moblie},function(data) {
		layer.closeAll();
		if(data=="���ͳɹ�!"){ 
			layermsg('���ͳɹ���',2,function(){send(121);}); 
		}else if(data==1){
			layermsg('ͬһ�ֻ���һ�췢�ʹ����ѳ���', 2);
		}else if(data==2){
			layermsg('ͬһIPһ�췢�ʹ����ѳ���', 2);
		}else if(data==3){
			layermsg('����֪ͨ�ѹرգ�����ϵ����Ա��',2);
		}else if(data==4){
			layermsg('��û�����ö��ţ�����ϵ����Ա��',2);
		}else if(data==5){
			layermsg('�벻Ҫ�ظ����ͣ�',2);
		}else{
			layermsg(data,2);
		}
	})
}
function send(i){
	i--;
	if(i==-1){
		$("#time").html("���»�ȡ");
		$("#send").val(0)
	}else{
		$("#send").val(1)
		$("#time").html(i+"��");
		setTimeout("send("+i+");",1000);
	}
}
function check_moblie(){
	var moblie=$("input[name=moblie]").val();
	var authcode=$("input[name=authcode]").val();
	var code=$("#moblie_code").val();
	if(moblie==""){ 
		layermsg('�������ֻ����룡',2);return false;
	}else if(code==""){ 
		layermsg('�����������֤�룡',2);return false;
	}else if(!authcode){
		layermsg('��������֤�룡',2);return false;
	}
	layer_load('ִ���У����Ժ�...',0);
	$.post("index.php?c=binding",{moblie:moblie,code:code,authcode:authcode},function(data){
		layer.closeAll();
		if(data==1){
			layermsg('�ֻ��󶨳ɹ���',2,function(){window.location.href = 'index.php?c=binding'}); 
		}else if(data==3){
			layermsg('������֤�벻��ȷ��',2);
		}else if(data==4){
			layermsg('��֤�벻��Ϊ�գ�',2);
		}else if(data==5){
			layermsg('��֤�벻��ȷ��',2,function(){check_code()});
		}else{
			layermsg('��������',2); 
		}
	})
}
function check_email(){
	var email=$("input[name=email]").val();
	var authcode=$("input[name=authcode]").val();
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(email==''){
		layermsg('���䲻��Ϊ�գ�',2);return false;
	}else if(!myreg.test(email)){
		layermsg('�����ʽ����',2);return false;
	}else if(!authcode){
		layermsg('��֤�벻��Ϊ�գ�',2);return false;
	}
	layer_load('ִ���У����Ժ�...',0);
	$.post(weburl+"/wap/index.php?c=ajax&a=emailcert",{email:email,authcode:authcode},function(data){
		layer.closeAll();
		if(data){
			if(data=="3"){
				layermsg('�ʼ�û�����ã�����ϵ����Ա��',2);
			}else if(data=="2"){
				layermsg('�ʼ�֪ͨ�ѹرգ�����ϵ����Ա��',2);
			}else if(data=="1"){
				layermsg('�ʼ��ѷ��͵������䣬��ע�������֤��',2,function(){window.location.href = 'index.php?c=binding'});
			}else if(data=="5"){
				layermsg('��֤�벻��Ϊ�գ�',2);
			}else if(data=="4"){
				layermsg('��֤�벻��ȷ��',2,function(){check_code()});
			}
		}else{
			layermsg('�����µ�¼��',2);
		} 
	})
}
