function ShowPartDiv(id){
	$("#"+id).show();
}
$(function(){
	$('body').click(function (evt) {
		if($(evt.target).parents("#BillingCycle").length==0 && evt.target.id != "BillingCycleButton") {
		   $('#BillingCycle').hide();
		}
		if($(evt.target).parents("#PartType").length==0 && evt.target.id != "PartTypeButton") {
		   $('#PartType').hide();
		}
	})
	//������ʾ����
	$('.share_con').hover(
		function(){
			$('.newJsbg').show();							   
		},function(){
			$('.newJsbg').hide();	
		}
	);	
})
function CheckPartType(id,name){
	$("#PartTypeButton").val(name);
	$("#type").val(id);
	$('#PartType').hide();
}
//�ղؼ�ְ
function PartCollect(jobid,comid){
	$.post(weburl+"/index.php?m=ajax&c=partcollect",{jobid:jobid,comid:comid},function(data){
		if(data==1){
			layer.msg("ֻ�и����û������ղأ�",2,8);
		}else if(data==2){
			layer.msg("���Ѿ��ղع��ü�ְ��",2,8);
		}else if(data==0){
			$("#Collect").html("���ղ�");
			layer.msg("�ղسɹ���",2,9);
		}
	})
}

//��ְ����
function PartApply(jobid,jobname,comid){
	layer.load('ִ���У����Ժ�...',0);
	$.post(weburl+"/index.php?m=ajax&c=partapply",{jobid:jobid,jobname:jobname,comid:comid},function(data){
		layer.closeAll();
		var data=eval('('+data+')');
		layer.msg(data.msg, 2, Number(data.status),function(){location.reload();});return false;
	})
}

//����qq�ռ䡢���ˡ���Ѷ΢������������type: qq,sina,qqwb,renren
function shareTO(type,title,webname){
	var tip =  '�Ͻ�������������Ѱɡ�';
	var info = webname+' -- ' + '"'+ title + '"'+ '������'+ weburl + ')��  ';
	switch(type){
		case 'qq':
			 var href = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=' + encodeURIComponent(info + tip) + '&summary=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href);
			break;
		case 'sina':
			var href = 'http://service.weibo.com/share/share.php?title=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href) + '&source=bookmark';
			break;
		case 'qqwb':
			 var href = 'http://v.t.qq.com/share/share.php?title=' + encodeURIComponent(info + tip) + '&url=' + encodeURIComponent(window.location.href);
			break;
		case 'renren':
			 var href = 'http://share.renren.com/share/buttonshare.do?link=' + encodeURIComponent(window.location.href) + '&title==' + encodeURIComponent(info + tip);
			break;
	}
	window.open(href);    
} 