$(document).ready(function(){
	$(".job1").change(function(){
		var job=$(this).val();
		var lid=$(this).attr("lid");
		if(job==""){
			$("#"+lid+" option").remove()
			$("<option value=''>��ѡ��</option>").appendTo("#"+lid);
			lid2=$("#"+lid).attr("lid");
			if(lid2){
				$("#"+lid2+" option").remove();
				$("<option value=''>��ѡ��</option>").appendTo("#"+lid2);
			}
		}
		$.post(weburl+"/index.php?m=ajax&c=ajax_job", {"str":job},function(data) {
			if(lid!="" && data!=""){
				$('#'+lid+' option').remove();
				$(data).appendTo("#"+lid);
				job_type(lid);
			}
		})
	})
 
	$(".province").change(function(){
		var province=$(this).val();
		var lid=$(this).attr("lid");
		if(lid){ 
			if(province==""){
				$("#"+lid+" option").remove()
				$("<option value='0'>��ѡ�����</option>").appendTo("#"+lid);
				lid2=$("#"+lid).attr("lid");
				if(lid2){
					$("#"+lid2+" option").remove();
					$("<option value='0'>��ѡ�����</option>").appendTo("#"+lid2);
					$("#"+lid2).hide();
				}
			}
			$.post(weburl+"/index.php?m=ajax&c=ajax", {"str":province},function(data) {
				if(lid!="" && data!=""){
					$('#'+lid+' option').remove();
					$(data).appendTo("#"+lid);
					city_type(lid);
				}
			})
		}
		getcircle(province);
	})
})
function getcircle(id){
	if($('#circleurl').length>0){ 
		var circleurl=$('#circleurl').val();
		if(circleurl){
			$.post(circleurl,{cid:id},function(arrdata){
				if(arrdata){
					var html="<option value=''>����</option>";
					var arrdata = eval("("+arrdata+")"); 
					$.each(arrdata,function(index,item){ 
						html+="<option value='"+item.id+"'>"+item.name+"</option>"; 
					}); 
					$("select[name='circle']").html(html);
				}else{
					$("select[name='circle']").html("<option value=''>������Ȧ</option>");
				}
			})
		}
	}
}
function job_type(id){
	var id;
	var job=$("#"+id).val();
	var lid=$("#"+id).attr("lid");
	$.post(weburl+"/index.php?m=ajax&c=ajax_job", {"str":job},function(data) {
		if(lid!="" && data!=""){
			$('#'+lid+' option').remove();
			$(data).appendTo("#"+lid);
		}
	})
}
function city_type(id){
	var id;
	var province=$("#"+id).val();
	var lid=$("#"+id).attr("lid");
	$.post(weburl+"/index.php?m=ajax&c=ajax", {"str[]":[province]},function(data) {
		if(lid!=""){
			if(lid!="three_cityid" && lid!="three_city" && data!=""){
				$('#'+lid+' option').remove();
				$(data).appendTo("#"+lid);
			}else{
				if(data!=""){
					$('#'+lid+' option').remove();
					$(data).appendTo("#"+lid);
					$('#'+lid).show();
				}else{
					$('#'+lid+' option').remove();
					$("<option value='0'>��ѡ�����</option").appendTo("#"+lid);
					$('#'+lid).hide();
				}
			}
		}
	})
}
function toDate(str){
	var sd=str.split("-");
	return new Date(sd[0],sd[1],sd[2]);
}
function check_form_job(){
	var end = $("#edate").val();
	var name = $.trim($("#name").val());
	if(name==''){
		parent.layer.msg('ְλ���Ʋ���Ϊ�գ�',2,8);return false;
	}
	if($("#job1_son").val()==''){
		parent.layer.msg('��ѡ��ְλ���',2,8);return false;
	}

	if($("#provinceid").val()==''){
		parent.layer.msg('��ѡ�����ص㣡',2,8);return false;
	}
	var content = editor.text(); 
	if(content==""){
		parent.layer.msg('ְλ��������Ϊ�գ�',2,8);return false;
	}else{
		$("#content").val(content);
	}
	if(end==""){
		parent.layer.msg('����ʱ�䲻��Ϊ�գ�', 2, 8);return false;
	}else{
        var now = new Date();
        var year = now.getFullYear();       //��
        var month = now.getMonth() + 1;     //��
        var day = now.getDate();    
		var st=toDate(year+"-"+month+"-"+day); 
		var edate=end.split("-");
		var ed=new Date(edate[0],parseInt(edate[1].replace(/\b(0+)/gi,"")),edate[2]);
		if(st>ed){
			parent.layer.msg('�������ڱ�����ڽ������ڣ�', 2,8);return false;
			
		}
	}
}
function check_form_jobs(){
	var end = $("#edate").val();
	var name = $.trim($("#name").val());
	if(name==''){
		parent.layer.msg('ְλ���Ʋ���Ϊ�գ�',2,2);return false;
	}
	
	if($("#provinceid").val()==''){
		parent.layer.msg('��ѡ�����ص㣡',2,2);return false;
	}
	if($("#number").val()==''){
		parent.layer.msg('��ѡ����Ƹ������',2,2);return false;
	}
	if($("#sex").val()==''){
		parent.layer.msg('��ѡ���Ա�',2,2);return false;
	}
	if($("#salary").val()==''){
		parent.layer.msg('������нˮ',2,2);return false;
	}
	if($("#salary_type").val()==''){
		parent.layer.msg('��ѡ��нˮ����',2,2);return false;
	}
	if($("#billing_cycle").val()==''){
		parent.layer.msg('��ѡ���������',2,2);return false;
	}
	var content = editor.text(); 
	if(content==""){
		parent.layer.msg('ְλ��������Ϊ�գ�',2,2);return false;
	}else{
		$("#content").val(content);
	}
	if(end==""){
		parent.layer.msg('����ʱ�䲻��Ϊ�գ�', 2, 2);return false;
	}else{
        var now = new Date();
        var year = now.getFullYear();       //��
        var month = now.getMonth() + 1;     //��
        var day = now.getDate();    
		var st=toDate(year+"-"+month+"-"+day);
		var ed=toDate(end);
		if(st>ed){
			parent.layer.msg('�������ڱ�����ڽ������ڣ�', 2, 3);return false;
		}
	}
	if($("#linkman").val()==''){
		parent.layer.msg('��ϵ�˲���Ϊ��',2,2);return false;
	}
	if($("#linktel").val()==''){
		parent.layer.msg('��ϵ�绰����Ϊ��',2,2);return false;
	}
}