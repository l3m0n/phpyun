function addworktime(){  
	$("#shour").val('��ѡ��');
	$("#sminute").val('��ѡ��');
	$("#ehour").val('��ѡ��');
	$("#eminute").val('��ѡ��');
	$("#timeid").val('');
	$.layer({
		type : 1,
		title : '��ӹ���ʱ��', 
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
	if(shour=="��ѡ��"||sminute=="��ѡ��"||ehour=="��ѡ��"||eminute=="��ѡ��"){
		layer.msg("�뽫��Ϣ��д������",2,8);return false;
	}
	if((Number(ehour)<Number(shour))||(Number(ehour)==Number(shour) && Number(eminute)<Number(sminute))){
		layer.msg("��������ʱ�䲻��С�ڿ�ʼʱ�䣡",2,8);return false;
	}
	var timeval=shour+':'+sminute+'-'+ehour+':'+eminute;
	var timeid=$("#timeid").val();
	if(timeid>0){//�޸�
		var html='<input type="hidden" name="worktime[]" value="'+timeval+'"><span>ʱ��� '+timeval+'</span><em><a href="javascript:Save_time(\''+timeid+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">�޸�</a><a href="javascript:Delete_time(\''+timeid+'\');">ɾ��</a></em>';
		$("#handletime_"+timeid).html(html);
	}else{
		var timestamp=new Date().getTime();
		var html='<div class="part_hour" id="handletime_'+timestamp+'"><input type="hidden" name="worktime[]" value="'+timeval+'"><span>ʱ��� '+timeval+'</span><em><a href="javascript:Save_time(\''+timestamp+'\',\''+shour+'\',\''+sminute+'\',\''+ehour+'\',\''+eminute+'\');">�޸�</a><a href="javascript:Delete_time(\''+timestamp+'\');">ɾ��</a></em></div>';
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
		title : '��ӹ���ʱ��', 
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
		layer.msg("������ְλ���ƣ�",2,8);return false;
	}
	if($.trim($("#typeid").val())==""){
		layer.msg("��ѡ�������ͣ�",2,8);return false;
	}
	var sdate=$("#sdate").val();
	var edate=$("#edate").val();
	var today=$("#today").val();
	var timetype=$("input[name='timetype']:checked").val();
	if(timetype){
		if(sdate==""){
			layer.msg("��ѡ��ʼ���ڣ�",2,8);return false;
		}
	}else{
		if(sdate=="" || edate==""){
			layer.msg("��ѡ�������ڣ�",2,8);return false;
		} 
		if(toDate(edate).getTime()<toDate(sdate).getTime() || toDate(edate).getTime()<toDate(today).getTime()){
			layer.msg("����ȷѡ�������ڣ�",2,8);return false;
		}
	}
	var worktime=$("#worktimelist").html();
	if(worktime==""){
		layer.msg("��ѡ����ʱ�䣡",2,8);return false;
	}
	if($.trim($("#number").val())==""){
		layer.msg("��������Ƹ������",2,8);return false;
	}
	if($.trim($("#salary_typeid").val())==""){
		layer.msg("��ѡ��нˮ���ͣ�",2,8);return false;
	}
	if($.trim($("#salary").val())==""||$.trim($("#salary").val())<1){
		layer.msg("������нˮ��",2,8);return false;
	}
	if($.trim($("#billing_cycleid").val())==""){
		layer.msg("��ѡ��������ڣ�",2,8);return false;
	}
	if($.trim($("#three_cityid").val())==""){
		layer.msg("��ѡ�����ص㣡",2,8);return false;
	}	
	if($.trim($("#address").val())==""){
		layer.msg("��������ϸ��ַ��",2,8);return false;
	}
	if($.trim($("#map_x").val())==""||$.trim($("#map_y").val())==""){
		layer.msg("��ѡ���ͼ��",2,8);return false;
	}
	var html = editor.text();
	if($.trim(html)==""){
		layer.msg("�������ְ���ݣ�",2,8);return false;
	}

	var end=$("#deadline").val();
  var st=toDate(today).getTime();
	var ed=toDate(end).getTime();
	if(end==''){
		layer.msg("��ѡ������ֹʱ�䣡",2,8);return false;
	}else if(ed<=st){ 
		layer.msg("������ֹʱ�䲻��С�ڵ�ǰʱ�䣡",2,8);return false;
	}
	if($.trim($("#linkman").val())==""){
		layer.msg("��������ϵ�ˣ�",2,8);return false;
	}
	if($.trim($("#linktel").val())==""){
		layer.msg("��������ϵ�ֻ���",2,8);return false;
	}
	var iftelphone = isjsMobile($.trim($("#linktel").val()));
	if(iftelphone==false){layer.msg('����ȷ��д��ϵ�ֻ���',2,8);return false;}
}