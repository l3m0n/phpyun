
//收藏兼职
function PartCollect(jobid,comid){
	$.post(wapurl+"?c=part&a=collect",{jobid:jobid,comid:comid},function(data){
		if(data==1){
			layermsg("只有个人用户才能收藏！",2);
		}else if(data==2){
			layermsg("您已经收藏过该兼职！",2);
		}else if(data==0){
			layermsg("收藏成功！",2,function(){location.reload();});
			
		}
	})
}

//兼职报名
function PartApply(jobid,jobname,comid){
	$.post(wapurl+"/index.php?c=part&a=apply",{jobid:jobid,jobname:jobname,comid:comid},function(data){
		if(data==1){
			layermsg("只有个人用户才能申请报名！",2);
		}else if(data==2){
			layermsg("您已经报名过该兼职！",2);
		}else if(data==3){
			layermsg("拥有简历才可以报名兼职！",2);
		}else if(data==4){
			layermsg("报名已截止！",2);
		}else if(data==0){
			layermsg("报名成功！",2,function(){ 
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
			$("#cityid").html('<option value="">请选择</option>');
		}
	}
	$("#three_cityid").html('<option value="">请选择</option>');
}
function addworktime(){
	$("#shour").val('请选择');
	$("#sminute").val('请选择');
	$("#ehour").val('请选择');
	$("#eminute").val('请选择');
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
	if(shour=="请选择"||sminute=="请选择"||ehour=="请选择"||eminute=="请选择"){
		layermsg("请将信息填写完整！",2);return false;
	}
	if((Number(ehour)<Number(shour))||(Number(ehour)==Number(shour) && Number(eminute)<Number(sminute))){
		layermsg("工作结束时间不能小于开始时间！",2);return false;
	}
	var timeval=shour+':'+sminute+'-'+ehour+':'+eminute;
	var timeid=$("#timeid").val();
	if(timeid>0){//修改
		var html='<input type="hidden" name="worktime[]" value="'+timeval+'"><span>时间段 '+timeval+'</span><em><a href="javascript:Save_time(\''+timeid+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">修改</a><a href="javascript:Delete_time(\''+timeid+'\');">删除</a></em>';
		$("#handletime_"+timeid).html(html);
	}else{
		var timestamp=new Date().getTime();
		var html='<div class="part_hour" id="handletime_'+timestamp+'"><input type="hidden" name="worktime[]" value="'+timeval+'"><span>时间段 '+timeval+'</span><em><a href="javascript:Save_time(\''+timestamp+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">修改</a><a href="javascript:Delete_time(\''+timestamp+'\');">删除</a></em></div>';
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
		layermsg("请输入职位名称！",2);return false;
	}
	if($.trim($("#typeid").val())==""){
		layermsg("请选择工作类型！",2);return false;
	}
	var today=$("#today").val();
	var sdate=$("#sdate").val().split(' ');
	var edate=$("#edate").val().split(' ');
	var timetype=$("input[name='timetype']:checked").val();
	if(sdate==""){
		layermsg("请选择开始日期！",2);return false;
	}
	if(toDate(sdate[0])<toDate(today)){
		layermsg("开始日期不能小于当前日期！",2);return false;
	}  
	if(timetype!='1'){
		if(edate==""){
			layermsg("请选择结束日期！",2);return false;
		}
		if(toDate(edate[0])<toDate(sdate[0])){
			layermsg("开始日期不能大于结束日期！",2);return false;
		}
	}
	
	var worktime=$("#worktimelist").html();
	if(worktime==""){
		layermsg("请输入工作时间！",2);return false;
	}
	if($.trim($("#number").val())==""||$.trim($("#number").val())=="0"){
		layermsg("请输入招聘人数！",2);return false;
	}
	if($.trim($("#salary_typeid").val())==""){
		layermsg("请选择薪水类型！",2);return false;
	}
	if($.trim($("#salary").val())==""||$.trim($("#salary").val())=="0"){
		layermsg("请输入薪水！",2);return false;
	}
	if($.trim($("#billing_cycleid").val())==""){
		layermsg("请选择结算周期！",2);return false;
	}
	if($.trim($("#three_cityid").val())==""){
		layermsg("请选择工作地点！",2);return false;
	}	
	if($.trim($("#address").val())==""){
		layermsg("请输入详细地址！",2);return false;
	}
	if($.trim($("#map_x").val())==""||$.trim($("#map_y").val())==""){
		layermsg("请选择地图！",2);return false;
	}
	
	var content=UE.getEditor('description').hasContents();  
	
	if(content==""||content==false){
		layermsg("请输入兼职内容！",2);return false;
	}else{
		var description =UE.getEditor('description').getContent();  
		document.getElementById("description").value=description;
	} 
	var end=$("#deadline").val().split(' ');
	 
	if(toDate(end[0])<toDate(today)){
		layermsg("报名截止时间不能小于当前时间！",2);return false;
	}
	 
	if(timetype!='1'&&toDate(end[0])>toDate(edate[0])){
		layermsg("报名截止时间不能大于时间！",2);return false;
	}
	if($.trim($("#linkman").val())==""){
		layermsg("请输入联系人！",2);return false;
	}
  var linktel=isjsMobile($.trim($("#linktel").val()));
	if($.trim($("#linktel").val())==""){
		layermsg("请输入联系手机！",2);return false;
	}else if(linktel==false){
     layermsg("请输入正确的联系手机！",2);return false;
  }
}
function ckpartjob(type){
	var val=$("#"+type+"id").find("option:selected").text();
	$('.'+type).html(val);
}