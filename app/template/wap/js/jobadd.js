function checkinfo(){
	var linkphone='';
	var name=$.trim($("#name").val());
	var hy=$.trim($("#hy").val());
	var pr=$.trim($("#pr").val());
	var cityid=$.trim($("#cityid").val());
	var address=$.trim($("#address").val());
	var mun=$.trim($("#mun").val());
	var phone=$.trim($("#phone").val());
	var phonetwo=$.trim($("#phonetwo").val());
	var phonethree=$.trim($("#phonethree").val());
	var linktel=$.trim($("#linktel").val());
	var linkmail=$.trim($("#linkmail").val()); 
	var linkqq=$.trim($("#linkqq").val()); 
	var content=UE.getEditor('content').hasContents();
	if(phonetwo){
		if(phone==''){
			layermsg("����д���ţ�");return false;
		}
		linkphone=phone+'-'+phonetwo; 
		if(phonethree){
			linkphone=linkphone+'-'+phonethree;
		}
	}
	 
	
	if(name==''){layermsg("��������ҵ���ƣ�");return false;}
	if(hy==''){layermsg("��ѡ����ҵ��ҵ��");return false;}
	if(pr==''){layermsg("��ѡ����ҵ���ʣ�");return false;}
	if(cityid==''){layermsg("��ѡ�����ڵأ�");return false;}
	if(mun==''){layermsg("��ѡ����ҵ��ģ��");return false;}
	if(address==''){layermsg("����д��˾��ַ��");return false;}
	if(linkphone==''&&linktel==''){layermsg("��ϵ�绰����ϵ�ֻ�����һ�");return false;}
	if(linkmail==''){layermsg("����д���䣡");return false;}
	if(content==''||content==false){layermsg("����д��ҵ��飡");return false;}
	if(linkqq&&(linkqq.length<6||linkqq.length>12)){
		layermsg("ֻ������6-12λQQ�ţ�");return false;
	}
}
function checkjob(id,type){
	if(id>0){
		$.post(wapurl+"?c=ajax&a=wap_job",{id:id,type:type},function(data){
			if(type==1){
				$("#job1_son").html(data);
			}else{
				$("#job_post").html(data);
			}
		})
	}else{
		if(type==1){
			$("#job1_son").html('<option value="">��ѡ��</option>');
		}
	}
	$("#job_post").html('<option value="">��ѡ��</option>');
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
function checkfrom() {
	if($.trim($("#name").val())==""){
		layermsg("��Ƹ���Ʋ���Ϊ�գ�");return false;
	}else if($.trim($("#job_post").val())==""){
		layermsg("��ѡ��ְλ���");return false;
	}else if($.trim($("#cityid").val())==""){
		layermsg("��ѡ�����ص㣡");return false;
	}else if($.trim($("#salaryid").val())==""){
		layermsg("��ѡ��нˮ������");return false;
	}else if($.trim($("#days").val())<1){
		layermsg("����ȷ��д��Ƹ������");return false;
	}
	var description=UE.getEditor('description').hasContents();  
	if(description==""||description==false){
		layermsg("ְλ��������Ϊ�գ�");return false;
	} 
}