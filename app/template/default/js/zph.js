function showyuding(id){
	$(".order_two").hide();
	$("#showstatus"+id).show();
}
function hideyuding(id){
	$("#showstatus"+id).hide();
}
$(document).ready(function(){
	$(".fairs_disp_position").hover(function(){
		var aid=$(this).attr("aid");
		$(this).addClass("zph_popup");
		$(".order_two").hide();
		$("#showstatus"+aid).show();
	},function(){
		
		var aid=$(this).attr("aid");
		$(this).removeClass("zph_popup");
		$("#showstatus"+aid).hide();
	})   
})

function jobaddurl(num,integral_job,integral_pricename){ 
	if(num==0){
		var msg='�ײ������꣬���ȹ����Ա��';
		layer.confirm(msg, function(){ 
			window.open(weburl+'/index.php?c=right');  
		});
	}else if(num==2){
		var msg='�ײ������꣬������������۳�'+integral_job+' '+integral_pricename+'���Ƿ������';
		layer.confirm(msg, function(){
			window.open(weburl+'/member/index.php?c=jobadd');   
		});
	}
}

function submitzph(){
	var bid=$("#bid").val();
	var zid=$("#zid").val();
	var jobid=get_comindes_jobid();
	layer.load('ִ���У����Ժ�...',0);
	$.get(weburl+"/index.php?m=ajax&c=zphcom&bid="+bid+"&zid="+zid+"&jobid="+jobid, function(data){
		var data=eval('('+data+')');
		var status=data.status;
		var content=data.content;
		layer.closeAll();
		if(status==0){
			layer.msg(content, 2,8);
		}else{
			layer.msg(content, 2,9,function(){location.reload();});
		} return false;
	})
}