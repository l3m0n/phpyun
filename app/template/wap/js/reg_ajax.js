function exitsid(id){
	if(document.getElementById(id))
	{
		return true;
	}else{
		return false;
	}
}
function checkRegUser(target_form) {
	var oneid=$("#regway1").val();
	var twoid=$("#regway2").val();
	var threeid=$("#regway3").val();
	var authcode;
	if(oneid==1){
		var username=$("#username").val(); 
     	if(username==''){
		   layermsg("�û�������Ϊ�գ�");return false;  
	    }else if(username.length<2||username.length>20){
	    	layermsg("�û���Ӧ��2-10λ�ַ�֮�䣡");return false;   
	    }
		var password=$("#password").val(); 
		if(password==""){
			layermsg("���벻��Ϊ�գ�");return false;  
		}else if(password.length<6||password.length>20){
			layermsg("���볤��Ӧ��6-20λ��");return false;   
		}
		if(exitsid("passconfirm")){
			var passconfirm=$("#passconfirm").val(); 
			if(passconfirm==""){
				layermsg("ȷ�����벻��Ϊ�գ�");return false;  
			}else if(password!=passconfirm){
				layermsg("�������벻һ�£�");return false;  
			}
		}
		if(exitsid("email")){
			var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			var email=$("#email").val(); 
			if(email==""){
				layermsg("���䲻��Ϊ�գ�");return false;  
			}else if(!myreg.test(email)){
				layermsg("�����ʽ����ȷ��");return false;  
			}
		}
		if(exitsid("moblie")){
			var reg= /^[1][34578]\d{9}$/; //��֤�ֻ�����  
			var moblie=$("#moblie").val(); 
			if(moblie==""){
				layermsg("����д�ֻ��ţ�");return false;  
			}else if(!reg.test(moblie)){
				layermsg("�ֻ���ʽ����ȷ��");return false;  
			}
		}
		var usertype=$("#usertype").val();
		if(usertype==1){
			if(exitsid("name")){
				var name=$("#name").val(); 
				if(name==""){
					layermsg("��ʵ���Ʋ���Ϊ�գ�");return false;  
				}
			}
		}else{
			if(exitsid("comname")){
				var comname=$("#comname").val(); 
				if(comname==""){
					layermsg("��ҵ���Ʋ���Ϊ�գ�");return false;  
				}
			}
			if(exitsid("linkman")){
				var linkman=$("#linkman").val(); 
				if(linkman==""){
					layermsg("��ϵ�˲���Ϊ�գ�");return false;  
				}
			}
			if(exitsid("address")){
				var address=$("#address").val(); 
				if(address==""){
					layermsg("��ҵ��ַ����Ϊ�գ�");return false;  
				}
			}
		}
		if($("#code").val()=="1"){
			authcode=$("#checkcode"+oneid).val();
		}
		if(authcode==""){
			layermsg('��֤�벻��Ϊ�գ�');return false; 
		}
		if($("#xieyi"+oneid).attr("checked")!='checked'){  
			layermsg('������ͬ��ע��Э����ܳ�Ϊ��վ��Ա��');return false;  
		}
	}else if(twoid==2){
		var reg= /^[1][34578]\d{9}$/; 
	    var moblie=$("#moblie").val(); 
	    if(moblie==""){
		   layermsg("����д�ֻ��ţ�");return false;  
	    }else if(!reg.test(moblie)){
		   layermsg("�ֻ���ʽ����ȷ��");return false;  
	    }
	    
		if($("#moblie_code").val()==""){
		    layermsg('������֤�벻��Ϊ�գ�');return false; 
	    }
		var password=$("#password2").val(); 
	    if(password==""){
		    layermsg("���벻��Ϊ�գ�");return false;  
	    }else if(password.length<6||password.length>20){
		    layermsg("���볤��Ӧ��6-20λ��");return false;   
	    }
	    if(exitsid("passconfirm2")){
		    var passconfirm=$("#passconfirm2").val(); 
		    if(passconfirm==""){
			    layermsg("ȷ�����벻��Ϊ�գ�");return false;  
		    }else if(password!=passconfirm){
			    layermsg("�������벻һ�£�");return false;  
		    }
	    }
		if($("#code").val()=="1"){
			authcode=$("#checkcode"+twoid).val();
		}
		if(authcode==""){
			layermsg('��֤�벻��Ϊ�գ�');return false; 
		}
		if($("#xieyi"+twoid).attr("checked")!='checked'){  
			layermsg('������ͬ��ע��Э����ܳ�Ϊ��վ��Ա��');return false;  
		}
	}else if(threeid==3){
		var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		var email=$("#email").val(); 
		if(email==""){
			layermsg("���䲻��Ϊ�գ�");return false;  
		}else if(!myreg.test(email)){
			layermsg("�����ʽ����ȷ��");return false;  
		}
		
		var password=$("#password3").val(); 
	    if(password==""){
		    layermsg("���벻��Ϊ�գ�");return false;  
	    }else if(password.length<6||password.length>20){
		    layermsg("���볤��Ӧ��6-20λ��");return false;   
	    }
	    if(exitsid("passconfirm3")){
		    var passconfirm=$("#passconfirm3").val(); 
		    if(passconfirm==""){
			    layermsg("ȷ�����벻��Ϊ�գ�");return false;  
		    }else if(password!=passconfirm){
			    layermsg("�������벻һ�£�");return false;  
		    }
	    }
		if($("#code").val()=="1"){
			var authcode=$("#checkcode"+threeid).val();
		}
		if(authcode==""){
			layermsg('��֤�벻��Ϊ�գ�');return false; 
		}
		if($("#xieyi"+threeid).attr("checked")!='checked'){  
			layermsg('������ͬ��ע��Э����ܳ�Ϊ��վ��Ա��');return false;  
		}
	}
	post2ajax(target_form);
	return false;	
}
function sendmsg(){
	var send=$("#send").val();
	var reg= /^[1][34578]\d{9}$/; //��֤�ֻ�����  
	var moblie=$("#moblie").val();
	if(moblie==""){
		layermsg("����д�ֻ��ţ�");return false;  
	}
	var date=$("#moblie").attr("date");
	var code='';
	if($("#checkcode2").length>0){
		code=$.trim($("#checkcode2").val());  
		if(!code){
			layermsg('����дͼƬ��֤�룡');return false;
		}	
	} 
	if(send>0){ 
		layermsg('�벻ҪƵ���ظ����ͣ�');return false;  
	}
	if(date==1 && send==0){
		layer_load('ִ���У����Ժ�...');
		$.post(weburl+"/wap/index.php?c=ajax&a=regcode",{moblie:moblie,code:code},function(data){ 
		
			layer.closeAll();
			if(data==0){
				layermsg('�ֻ�����Ϊ�գ�', 2, 8,function(){check_code();});return false; 
			}else if(data==1){
				layermsg('ͬһ�ֻ���һ�췢�ʹ����ѳ���', 2, 8,function(){check_code();});
			}else if(data==2){
				layermsg('ͬһIPһ�췢�ʹ����ѳ���', 2, 8,function(){check_code();});
			}else if(data==3){
				layermsg('���Ż�û�����ã�����ϵ����Ա��', 2, 8,function(){check_code();});return false; 
			}else if(data==4){
				layermsg('�벻ҪƵ���ظ����ͣ�', 2, 8,function(){check_code();});return false; 
			}else if(data==5){
				layermsg('ͼƬ��֤�����', 2, 8,function(){check_code();});return false; 
			}else if(data=="���ͳɹ�!"){
				sendtime("121"); 
			}else{
				layermsg(data, 2, 8,function(){check_code();});return false; 
			}
		})
	}
}
function sendtime(i){
	i--;
	if(i==-1){
		$("#time").html("���»�ȡ");
		$("#send").val(0)
	}else{
		$("#send").val(1)
		$("#time").html(i+"��");
		setTimeout("sendtime("+i+");",1000);
	}
}
function checkreg(type){
	$(".reg_cur").removeClass("reg_cur");
	$("#reg"+type).addClass("reg_cur");
	$("#regtype"+type).show();
	if(type=="1"){
		$("#regway2").val(2);
		$("#regway3").val('');
		$("#regtype2").hide();
	}else if(type=="2"){
		$("#regway3").val(3);
		$("#regway2").val('');
		$("#regtype1").hide();
	}
}
function check_email(){
	var email=$("#email").val();
	var myreg = /^([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9\-]+@([a-zA-Z0-9\-]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(email==""){
		layermsg('����д���䣡');return false;  
	}else if(!myreg.test(email)){
		layermsg('�����ʽ����');return false;  
	}else{
		$.post("index.php?c=register&a=ajax_reg",{email:email},function(data){
			if(data!=0){				
				layermsg('�����Ѿ���ʹ�ã�');return false; 
			}
		});
	}
}
function check_moblie(){
	var moblie=$("#moblie").val();
	var reg= /^[1][34578]\d{9}$/;
	if(moblie==""){
		layermsg('����д�ֻ����룡');return false;  
	}else if(!reg.test(moblie)){
		layermsg('�ֻ������ʽ����');return false;  
	}else{
		$.post("index.php?c=register&a=regmoblie",{moblie:moblie},function(data){
			if(data==0){
				$("#moblie").attr('date','1');
			}else{
				$("#moblie").attr('date','0');
				layermsg('�ֻ��Ѿ���ʹ�ã�');return false; 
			}
		});
	}
}
function check_username(){
	var username=$("#username").val();
	if(username==""){
		layermsg('����д�û�����');return false;  
	}else if(username.length<2||username.length>20){
		   ("�û���Ӧ��2-20λ�ַ�֮�䣡");return false;   
    }else{
		$.post("index.php?c=register&a=ajax_reg",{username:username},function(data){
			if(data==1){
				layermsg('�û����Ѵ��ڣ�');return false;
			}else if(data==2){
				layermsg('�û������ð��������ַ���');return false;
			}else if(data==3){
				layermsg('���û����ѱ���ֹע�ᣡ');return false;
			} 
			if(data!=0){
				layermsg('�����Ѿ���ʹ�ã�');return false;
			}
		});
	}
}