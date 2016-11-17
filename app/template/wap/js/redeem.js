function marquee(time,id){
	$(function(){
		var _wrap=$(id);
		var _interval=time;
		var _moving;
		_wrap.hover(function(){
			clearInterval(_moving);
		},function(){
			_moving=setInterval(function(){
			var _field=_wrap.find('li:first');
			var _h=_field.height();
			_field.animate({marginTop:-_h+'px'},800,function(){
			_field.css('marginTop',0).appendTo(_wrap);
			})
		},_interval)
		}).trigger('mouseleave');
	});
}

//���ֶһ���
function checkform_redeem_show(){
	var num=$("#num").val();
	var stock=$("#stock").val();
	var uid=$("#uid").val();
	var restriction=$("#restriction").val();
	if(!uid){
		layermsg('����û�е�¼�����ȵ�¼��');
		return false;
	}else if(num==0){
		layermsg('����ȷ��д�һ�������');
		return false;
	}else if(Number(num)>Number(restriction) && restriction!="0"){
		layermsg('�����޹�����,����ȷ��д��');
		return false;
	}else if(Number(num)>Number(stock)){
		layermsg('�����������,����ȷ��д��');
		return false;
	}
}
//��Ʒ���ࡢ����
$(document).ready(function(){
	$('.nav_ft').hover(function(){ 
		$(this).find('.nav_ft_list').show(); 
	},function(){ 
		$(this).find('.nav_ft_list').hide(); 
	});
	$('.nav_rt').hover(function(){ 
		$(this).find('.nav_rt_list').show(); 
	},function(){ 
		$(this).find('.nav_rt_list').hide(); 
	});
})

function placeshow(){
	$("#redeemdh").hide();
	$("#dhplaces").show();
}
function dhplace(){
	var linkman=$("#linkman").val();
	var linktel=$("#linktel").val();
	var postcodes=$("#postcodes").val();
	var body='';
	if($("#provinceid").val()!=''){
		var provinceid=$("#provinceid option:selected").text();
		body=provinceid+'ʡ';
	}
	if($("#cityid").val()!=''){
		var cityid=$("#cityid option:selected").text();
		body=body+cityid+'��';
	}
	if($("#three_cityid").val()!=''){
		var threecityid=$("#three_cityid option:selected").text();
		body=body+threecityid;
	}
	var address=$("#address").val();
		body=body+address;
	if(!linkman){
		layermsg('�ռ��˲���Ϊ�գ�',2,function(data){
			$("#redeemdh").hide();
			$("#dhplaces").show();
		});
	}else if(!linktel){
		layermsg('�ֻ����벻��Ϊ�գ�',2,function(data){
			$("#redeemdh").hide();
			$("#dhplaces").show();
		});
	}else if(linktel&&!isjsMobile(linktel)){
		layermsg('�ֻ������ʽ����',2,function(data){
			$("#redeemdh").hide();
			$("#dhplaces").show();
		});
	}
	$("#dhplaces").hide();
	$("#redeemdh").show();
	$("#dhlinkman").html(linkman);
	$("#dhlinktel").html(linktel);
	$("#dhpostcodes").html(postcodes);
	$("#dhbody").html(body);
	$("#dhplaced").show();
}
function redeem_dh(){
	var id=$("#id").val();
	var num=$("#num").val();
	var linkman=$("#linkman").val();
	var linktel=$("#linktel").val();
	var body='';
	if($("#provinceid").val()!=''){
		var provinceid=$("#provinceid option:selected").text();
		body=provinceid+'ʡ';
	}
	if($("#cityid").val()!=''){
		var cityid=$("#cityid option:selected").text();
		body=body+cityid+'��';
	}
	if($("#three_cityid").val()!=''){
		var threecityid=$("#three_cityid option:selected").text();
		body=body+threecityid;
	}
	var address=$("#address").val();
	var other=$("#other").val();
	var postcodes=$("#postcodes").val();
	if(provinceid||address){
		body='��ַ��'+body+address+'��'
	}
	if(postcodes){
		body=body+'�ʱࣺ'+postcodes+'��'
	}
	if(other){
		body=body+'��ע��'+other;
	}
	if(!linkman||!linktel){
		layermsg('������ջ���ַ��',2,function(data){
			$("#redeemdh").hide();
			$("#dhplaces").show();
		});;
	}else{
		var passshow=$("#passshow").html();
		$("#passshow").html('');
		layer.open({ 
			title:'����������',
			content: passshow,
		    btn: ['ȷ��', 'ȡ��'],
		    yes: function(){
		    	var password=$("#password").val();
		    	$.post(weburl+"/wap/index.php?c=redeem&a=savedh",{linkman:linkman,linktel:linktel,id:id,num:num,body:body,password:password},function(data){
					var data=eval('('+data+')');
					if(data.type==9){
						layermsg(data.msg,2,function(){window.location.href=data.url});
					}else{
						layermsg(data.msg);
					}
					$("#passshow").html(passshow);
				});
				return true;
		    },
			cancel:function (){
				$("#passshow").html(passshow);
			},
			no:function (){
				$("#passshow").html(passshow);
			},
			shadeClose:false
			
		});
	}
}