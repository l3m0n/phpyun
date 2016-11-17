function addworktime(){  
	$("#shour").val('请选择');
	$("#sminute").val('请选择');
	$("#ehour").val('请选择');
	$("#eminute").val('请选择');
	$("#timeid").val('');
	$.layer({
		type : 1,
		title : '添加工作时间', 
		closeBtn : [0 , true],
		fix : false,
		border : [10 , 0.3 , '#000', true], 
		area : ['400px','220px'],
		page : {dom :'#partworktime'}
	}); 
}
function showtime(type){
	$(".parttime_sel_m").hide();
	$("#"+type+"list").show();
}
function check_time(id,type){
	$("#"+type).val(id);
	$("#"+type+"list").hide();
}
function saveworktime(){
	var shour=$("#shour").val();
	var sminute=$("#sminute").val();
	var ehour=$("#ehour").val();
	var eminute=$("#eminute").val();
	if(shour=="请选择"||sminute=="请选择"||ehour=="请选择"||eminute=="请选择"){
		layer.msg("请将信息填写完整！",2,8);return false;
	}
	if((Number(ehour)<Number(shour))||(Number(ehour)==Number(shour) && Number(eminute)<Number(sminute))){
		layer.msg("工作结束时间不能小于开始时间！",2,8);return false;
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

	layer.closeAll();
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
	$.layer({
		type : 1,
		title : '添加工作时间', 
		closeBtn : [0 , true],
		fix : false,
		border : [10 , 0.3 , '#000', true],
		move : false,
		area : ['400px','220px'],
		page : {dom :'#partworktime'}
	}); 
}

function CheckPost_part(){
	if($.trim($("#name").val())==""){
		layer.msg("请输入职位名称！",2,8);return false;
	}
	if($.trim($("#typeid").val())==""){
		layer.msg("请选择工作类型！",2,8);return false;
	}
	var sdate=$("#sdate").val();
	var edate=$("#edate").val();
	var today=$("#today").val();
	var timetype=$("input[name='timetype']:checked").val();
	if(timetype){
		if(sdate==""){
			layer.msg("请选择开始日期！",2,8);return false;
		}
	}else{
		if(sdate=="" || edate==""){
			layer.msg("请选择工作日期！",2,8);return false;
		} 
		if(toDate(edate).getTime()<toDate(sdate).getTime() || toDate(edate).getTime()<toDate(today).getTime()){
			layer.msg("请正确选择工作日期！",2,8);return false;
		}
	}
	var worktime=$("#worktimelist").html();
	if(worktime==""){
		layer.msg("请选择工作时间！",2,8);return false;
	}
	if($.trim($("#number").val())==""){
		layer.msg("请输入招聘人数！",2,8);return false;
	}
	if($.trim($("#salary_typeid").val())==""){
		layer.msg("请选择薪水类型！",2,8);return false;
	}
	if($.trim($("#salary").val())==""||$.trim($("#salary").val())<1){
		layer.msg("请输入薪水！",2,8);return false;
	}
	if($.trim($("#billing_cycleid").val())==""){
		layer.msg("请选择结算周期！",2,8);return false;
	}
	if($.trim($("#three_cityid").val())==""){
		layer.msg("请选择工作地点！",2,8);return false;
	}	
	if($.trim($("#address").val())==""){
		layer.msg("请输入详细地址！",2,8);return false;
	}
	if($.trim($("#map_x").val())==""||$.trim($("#map_y").val())==""){
		layer.msg("请选择地图！",2,8);return false;
	}
	var html = editor.text();
	if($.trim(html)==""){
		layer.msg("请输入兼职内容！",2,8);return false;
	}

	var end=$("#deadline").val();
  var st=toDate(today).getTime();
	var ed=toDate(end).getTime();
	if(end==''){
		layer.msg("请选择报名截止时间！",2,8);return false;
	}else if(ed<=st){ 
		layer.msg("报名截止时间不能小于当前时间！",2,8);return false;
	}
	if($.trim($("#linkman").val())==""){
		layer.msg("请输入联系人！",2,8);return false;
	}
	if($.trim($("#linktel").val())==""){
		layer.msg("请输入联系手机！",2,8);return false;
	}
	var iftelphone = isjsMobile($.trim($("#linktel").val()));
	if(iftelphone==false){layer.msg('请正确填写联系手机！',2,8);return false;}
}