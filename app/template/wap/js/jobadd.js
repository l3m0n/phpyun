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
			layermsg("请填写区号！");return false;
		}
		linkphone=phone+'-'+phonetwo; 
		if(phonethree){
			linkphone=linkphone+'-'+phonethree;
		}
	}
	 
	
	if(name==''){layermsg("请输入企业名称！");return false;}
	if(hy==''){layermsg("请选择企业行业！");return false;}
	if(pr==''){layermsg("请选择企业性质！");return false;}
	if(cityid==''){layermsg("请选择所在地！");return false;}
	if(mun==''){layermsg("请选择企业规模！");return false;}
	if(address==''){layermsg("请填写公司地址！");return false;}
	if(linkphone==''&&linktel==''){layermsg("联系电话和联系手机必填一项！");return false;}
	if(linkmail==''){layermsg("请填写邮箱！");return false;}
	if(content==''||content==false){layermsg("请填写企业简介！");return false;}
	if(linkqq&&(linkqq.length<6||linkqq.length>12)){
		layermsg("只能输入6-12位QQ号！");return false;
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
			$("#job1_son").html('<option value="">请选择</option>');
		}
	}
	$("#job_post").html('<option value="">请选择</option>');
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
			$("#cityid").html('<option value="">请选择</option>');
		}
	}
	$("#three_cityid").html('<option value="">请选择</option>');
}
function checkfrom() {
	if($.trim($("#name").val())==""){
		layermsg("招聘名称不能为空！");return false;
	}else if($.trim($("#job_post").val())==""){
		layermsg("请选择职位类别！");return false;
	}else if($.trim($("#cityid").val())==""){
		layermsg("请选择工作地点！");return false;
	}else if($.trim($("#salaryid").val())==""){
		layermsg("请选择薪水待遇！");return false;
	}else if($.trim($("#days").val())<1){
		layermsg("请正确填写招聘天数！");return false;
	}
	var description=UE.getEditor('description').hasContents();  
	if(description==""||description==false){
		layermsg("职位描述不能为空！");return false;
	} 
}