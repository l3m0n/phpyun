function setsite(id,name){
	$.post(wapurl+"/index.php?c=site&a=domain",{id:id,name:name},function(data){
		window.location.href=wapurl;
	});
}
function showMoreNav(){
	$(".subnav").toggle();
}
function show(obj){
	var obj=document.getElementById(obj);  
	if(obj.style.display=="block"){
		obj.style.display="none";
	}else{
		obj.style.display="block";
	}
}
function showImgDelay(imgObj,imgSrc,maxErrorNum){  
    if(maxErrorNum>0){ 
        imgObj.onerror=function(){
            showImgDelay(imgObj,imgSrc,maxErrorNum-1);
        };
        setTimeout(function(){
            imgObj.src=imgSrc;
        },500);
		maxErrorNum=parseInt(maxErrorNum)-parseInt(1);
    }
}
function layer_load(msg){
	layer.open({
		type: 2,
		content: msg
	});
};
function layer_del(msg,url){ 
	if(msg==''){ 
		layer_load('ִ���У����Ժ�...');
		$.get(url,function(data){
			layer.closeAll();
			var data=eval('('+data+')');
			if(data.url=='1'){ 
				layermsg(data.msg,Number(data.tm),function(){location.reload();});return false;
			}else{
				layermsg(data.msg,Number(data.tm),function(){location.href=data.url;});return false;
			}
		});
	}else{
		layer.open({
			content: msg,
			btn: ['ȷ��', 'ȡ��'],
			shadeClose: false,
			yes: function(){
				layer.closeAll();
				layer_load('ִ���У����Ժ�...');
				$.get(url,function(data){
					layer.closeAll();
					var data=eval('('+data+')');
					if(data.url=='1'){ 
						layermsg(data.msg,Number(data.tm),function(){location.reload();});return false;
					}else{
						layermsg(data.msg,Number(data.tm),function(){location.href=data.url;});return false;
					}
				});
			} 
		}); 
	}
}
function checkshowjob(type) {
    window.show_scrolltop = document.body.scrollTop;
    document.body.scrollTop = 0;
	if(type=='once'||type=='tiny'){
		layer.open({
			type:1,
			content: $("#"+type+"list").html(),
			shadeClose: false
		});return;
	}else{
		$("#"+type+"list").show();
		checkhide('info'); 
	}
}
function checkhide(id){ 
	$("#"+id+"button").show();
	$("#"+id).hide();
}
function checkjob1(id,type){
	var style=$("#"+type+"list"+id).attr("style");
	$(".onelist").addClass("lookshow");
	$(".lookhide").attr("style","display: none;");
	if(style=="display: none;"){
		$("#"+type+"list"+id).show();
		$("#"+type+id).removeClass("lookshow");
	}
}
function checkjob2(id,type){
	if($("#citylevel").length>0){
		if(parseInt($("#citylevel").val())==2){
			$("#cityclassbutton").val($(event.target).html());
			$("#cityclassbutton").html($(event.target).html());
			$("#three_cityid").val(id);
			$("#cityid").val(id);
			Close('city');
			return;
		}
	}
	var style=$("#"+type+"post"+id).attr("style");
	$(".post_show_three").attr("style","display: none;");
	if(style=="display: none;"){
		$("#"+type+"post"+id).show();
	}
} 
function checkedcity(id,name){
	$("#cityclassbutton").val(name);
	$("#cityclassbutton").html(name);
	$("#three_cityid").val(id);
	Close('city');
}
function checked_input(id){
	var one=$("input[name='jobclassone']:checked").val();
	var name=$("#r"+id).attr('name');  
	 
	var one_length=$("input[name='jobclassone']:checked").length;
	var check_length = $("input[name='jobclass']:checked").length;
	if((one_length+check_length)>5){ 
	    layermsg('�����ֻ��ѡ�������',2,function(){
			if(name=='jobclassone'){
				$(".one"+id).attr("checked",false);
			}
			if(one!=id){
				$("#r"+id).attr("checked",false);
			}	
		}); 	
	}else if(one!=''){
		$(".one"+one).attr('checked','checked'); 
	}
}
function realy() {
	var info="";
	var value=""; 
	$("input[name='jobclass']:checked").each(function(){
		var obj = $(this).val();
		var name = $(this).attr("data");
		if(info==""){
			info=obj;
			value=name;
		}else{
			info=info+","+obj;
			value=value+","+name;
		}
	})
	$("input[name='jobclassone']:checked").each(function(){
		obj = $(this).val();
		name = $(this).attr("data");
		if(info==""){
			info=obj;
			value=name;
		}else{
			info=info+","+obj;
			value=value+","+name;
		}
	})
	
	if(info==""){
		layermsg("��ѡ��ְλ���");return false;
	}else{
		var waptype=$("#waptype").val();
		if(waptype==1){
			var url=$("#searchurl").val();
			$.post(wapurl+"/?c=job&a=ajax_url",{url:url,type:"jobin",id:info},function(data){
				location.href=wapurl+data;
			})
		}else{
			$("#job_classid").val(info);
			$("#wapexpect").html(value);
			$("#jobclassbutton").val(value);
			Close("job");
		}
	}
}
function removes(){
	var waptype=$("#waptype").val();
	if(waptype==1){
		var url=$("#searchurl").val();
		$.post(wapurl+"/?c=job&a=ajax_url",{url:url,type:"jobin",id:''},function(data){
			location.href=wapurl+data;
		})
	}else{
		$("#jobclassbutton").val("��ѡ��ְλ���");
		$("#job_classid").val(""); 
		$(".onelist").attr("class","onelist lookshow");
		$(".onelist>.lookhide").hide();
		$(".post_show_three").hide();
		$("input[name='jobclass']").removeAttr("checked");
	}
}
function Close(type) {
    document.body.scrollTop = window.show_scrolltop;
	$("#"+type+"list>.onelist").attr("class","onelist lookshow");
	$("#"+type+"list>.onelist>.lookhide").hide();
	$("#"+type+"list>.post_show_three").hide();
	$("#"+type+"list").hide(); 
}
function checkfrom(target_form) {
	var username=$.trim($("#username").val());
	if(username==""){ 
		layermsg("�û�������Ϊ�գ�");return false;
	}else if(username.length<2||username.length>16){
		layermsg("�û�������Ӧ��2-16λ��");return false;
	} 
	var email=$.trim($("#email").val()); 
    var myreg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    if(!myreg.test(email)){
		layermsg("�����ʽ����");return false;
	} 
	var password=$.trim($("#password").val());
	var password2=$.trim($("#password2").val());
	if(password==""){
		layermsg("���벻��Ϊ�գ�");return false;
	}else if(password.length<6||password.length>20){
		layermsg("���볤��Ӧ��6-20λ��");return false;
	}
	if(password!=password2){
		layermsg("�������벻һ�£�");return false;
	}
} 
function ckpwd(target_form) {
	var oldpassword=$.trim($("input[name='oldpassword']").val());
	var password1=$.trim($("input[name='password1']").val());
	var password2=$.trim($("input[name='password2']").val());
	if(oldpassword==''||password1==''||password2==''){
		layermsg("�����롢�����롢ȷ�����������Ϊ�գ�");return false;
	}
	if(oldpassword==password1){
		layermsg("�������������һ�£�����Ҫ�޸ģ�");return false;
	}
	if(password1!=password2){
		layermsg("�������벻һ�£�");return false;
	}
	post2ajax(target_form);
	return false;
}
function isdel(url){
	layer.open({
		content: '�Ƿ�ɾ�������ݣ�',
		btn: ['ȷ��', 'ȡ��'],
		shadeClose: false,
		yes: function(){
			location.href =url;
		} 
	});
}
function islogout(url,msg) {
    layer.open({
        content: msg ? msg : 'ȷ���˳���',
        btn: ['ȷ��', 'ȡ��'],
        shadeClose: false,
        yes: function () {
            location.href = url;
        }
    });
}
function comjob(id){
	if(id>0){ 
		$.post(wapurl+"/index.php?c=ajax&a=wap_job",{id:id,type:1},function(data){  
			$("select[name='job1_son']").html(data);
		})
	}
}
function comcity(id,name){
	if(id>0){
		$.post(wapurl+"/index.php?c=ajax&a=wap_city",{id:id,type:1},function(data){  
			$("select[name='"+name+"']").html(data); 
		})
	} 
	if(name=='cityid'){$("select[name='three_cityid']").html("<option value=\"\">--��ѡ��--</option>");} 
}
function mlogin(target_form) {
	var username=$.trim($("#username").val());
	var password=$.trim($("#password").val()); 
	if(username==''||password==''){
		layermsg('�û��������������Ϊ�գ�');return false; 
	}
	post2ajax(target_form);
	return false;
}  

function cktiny(target_form) {
	var name=$.trim($("input[name='username']").val()); 
	var job=$.trim($("input[name='job']").val());
	var mobile=$.trim($("input[name='mobile']").val());
	var production=$.trim($("#production").val());
	var password=$.trim($("input[name='password']").val());
	var id=$.trim($("input[name='id']").val()); 
	if(name==''){layermsg('��������Ϊ�գ�');return false; }	
	if(mobile==''){
		layermsg('��ϵ�ֻ�����Ϊ�գ�');
		return false; 
	}else{
		var reg= /^[1][34578]\d{9}$/;   
		if(!reg.test(mobile)){ 
			layermsg('��ϵ�ֻ���ʽ����');
			return false;
		}
	}
	if(job==''){layermsg('����д��Ҫ�ҵĹ�����');return false; }
	if(production==''){layermsg('���ҽ��ܲ���Ϊ�գ�');return false; }
	if (password == '') {
		if(id==''){
			layermsg('���벻��Ϊ�գ�'); return false;
		}else{			
			layermsg('���������ʱ�����룡'); return false;
		}
	}
	post2ajax(target_form);
	return false;
}
function ckonce(target_form) {
	var title=$.trim($("input[name='title']").val()); 	
	var companyname=$.trim($("input[name='companyname']").val()); 
	var linkman=$.trim($("input[name='linkman']").val()); 
	var phone=$.trim($("input[name='phone']").val()); 
	var password=$.trim($("input[name='password']").val()); 
	var require=$.trim($("textarea[name='require']").val());
	var id=$.trim($("input[name='id']").val()); 
	if(title==''){layermsg('��Ƹ���Ʋ���Ϊ�գ�');return false; } 
	if(companyname==''){layermsg('�������Ʋ���Ϊ�գ�');return false; } 
	if(linkman==''){layermsg('��ϵ�˲���Ϊ�գ�');return false; } 
	if(phone==''){layermsg('��ϵ�绰����Ϊ�գ�');return false; } 
	var reg_phone= (/^[1][34578]\d{9}$|^([0-9]{3,4})[-]?[0-9]{7,8}$/); 
	if(!reg_phone.test(phone)){
		layermsg('����ȷ��д��ϵ�绰��');return false; 
	}  
	if(require==''){layermsg('Ҫ����Ϊ�գ�');return false; } 
	if (password == '') {
		if(id==''){
			layermsg('���벻��Ϊ�գ�'); return false;
		}else{			
			layermsg('���������ʱ�����룡'); return false;
		}
	}
	post2ajax(target_form);
	return false;
}

function islayer(){
	if($.trim($("#layermsg").val())){
		var msg=$.trim($("#layermsg").val());
		var url=$.trim($("#layerurl").val());
        if(msg){
		    if(url){
			    layermsg(msg,2,function(){location.href=url;});
		    }else{
			    layermsg(msg);
		    } 
	    }
	} 
}
function layermsg(content,time,end){ 
	layer.open({
		content: content, 
		time: time === undefined ? 2 : time,
		end: end
	});
	return false;
}
function layeralert(title,content,time,end){ 
	layer.open({
		title: [title,'background-color:#0099CC; color:#fff;'],
		content: content, 
		time: time === undefined ? 2 : time,
		end:end===undefined?'':function(){location.href = end;}
	});
}
function really(name){
	var chk_value =[];    
	$('input[name="'+name+'"]:checked').each(function(){    
		chk_value.push($(this).val());   
	});   
	if(chk_value.length==0){
		layermsg("��ѡ��Ҫɾ�������ݣ�",2);return false;
	}else{
		layer.open({
			content: 'ȷ��ɾ����',
			btn: ['ȷ��', 'ȡ��'],
			shadeClose: false,
			yes: function(){
				setTimeout(function(){$('#myform').submit()},0); 
			} 
		});
	} 
}
//ȫѡ
function m_checkAll(form){
	var elements=$("input[name='"+"delid[]"+"']");
	for (var i=0;i<elements.length;i++){
		var e = elements.eq(i)[0];
		e.checked = $("#checkAll")[0].checked; 
	}
} 
function checkAll(name){
	$("input[name="+name+"]").attr("checked",true);
} 
function getDaysHtml(year,month){
	var days=30;
	if((month==1)||(month==3)||(month==4)||(month==7)||(month==8)||(month==10)||(month==12)){
		days=31;
	}else if((month==4)||(month==6)||(month==9)||(month==11)){
		days=30;
	}else{
		if((year%4)==0){
			days=29;
		}else{
			days=28;
		}
	}
	var daysHtml='';
	for(var i=1;i<=days;i++){
		daysHtml+="<option value='"+i+"'>"+i+"</option>"
	}
	return daysHtml;
}
function selectMonth(yearid,monthid,dayid){
	$("#"+dayid).html(getDaysHtml(parseInt($("#"+yearid).val()),parseInt($("#"+monthid).val())));
}
function setSelectDay(dayid,day){
	$("#"+dayid).val(day);
}
function isjsMobile(obj) {
    if (obj.length != 11) return false;
    else if (obj.substring(0, 2) != "13" && obj.substring(0, 2) != "14" && obj.substring(0, 2) != "15" && obj.substring(0, 2) != "18" && obj.substring(0, 3) != "177") return false;
    else if (isNaN(obj)) return false;
    else return true;
}
function isjsTell(str) {
    var result = str.match(/\d{3}-\d{8}|\d{4}-\d{7}/);
    if (result == null) return false;
    return true;
}
$(document).ready(function () {
    $(document).delegate('.tiny_show_tckbox_h1_icon', 'click', function () {
        layer.closeAll();
    });
	$("#price_int").blur(function(){
		var value=$(this).val();
		var proportion=$(this).attr("int");
		$("#com_vip_price").val(value/proportion);
		$("#span_com_vip_price").html(value/proportion);
	});
	$("#click_invite").click(function(){
		var uid=$("#uid").val();
		var content=$("#content").val();
		var username=$("#username").val();
		var job=$("#jobname").val();
		var intertime=$("#intertime").val();
		var linkman=$("#linkman").val();
		var linktel=$("#linktel").val();
		var address=$("#address").val();
		job=job.split("+");
		var jobname=job[0];
		var jobid=job[1];
		if($("#update_yq").attr("checked")=='checked'){
			var update_yq=1;
		}else{
			var update_yq=0;
		}
		if($.trim(linktel)== ''){
			layermsg('��ϵ�绰����Ϊ�գ�', 2); return false;
		}else if(isjsTell(linktel)==false&&isjsMobile(linktel)==false){
		    layermsg('��ϵ�绰��ʽ����', 2); return false;
		}
		if($.trim(intertime)==""){
			layermsg('����ʱ�䲻��Ϊ�գ�', 2, 8);return false;
		}
		layer_load('ִ���У����Ժ�...');
		$.post(wapurl+"/index.php?c=ajax&a=sava_ajaxresume",{uid:uid,content:content,username:username,jobname:jobname,update_yq:update_yq,address:address,linkman:linkman,linktel:linktel,intertime:intertime,jobid:jobid},function(data){
			layer.closeAll();
			var data=eval('('+data+')');
			var status=data.status;
			var integral=data.integral;
			if(status==8){
				layermsg('���ѱ����û������������');return false;
			}else if(status==9){
				layermsg('���û��ѱ��������������');return false;
			}else if(!status || status==0){ 
				layermsg('���ȵ�¼��',2);
			}else if(status==5){
				layermsg('������'+integral+integral_pricename+'���޷��������ԣ�',2,function(){history.back();}); 
			}else if(status==3){
				layermsg('���ѳɹ����룡',2,function(){location.href=document.referrer;});
				
			}else if(status==7){
				layermsg('���Ѿ���������˲ţ��벻Ҫ�ظ����룡',2,function(){history.back();});
			}
		});
	});
});
function checkOncePassword(id){
	if($(".layermmain #once_password").val()==''){
		layermsg('����������');
		return;
	}
	var operation_type=$("#operation_type").val();
	$.post(wapurl + "/index.php?c=ajax&a=checkOncePassword", { id: id, password: $(".layermmain #once_password").val(), operation_type: operation_type }, function (data) {
	    if (data == '1') {						
	        var url = '',msg='';
	        if (operation_type == 'refresh') {
	            url = wapurl + 'index.php?c=once&a=show&id=' + id;
	            msg = 'ˢ�³ɹ���';
	        } else if (operation_type == 'edit') {
	            url = wapurl + 'index.php?c=once&a=add&id=' + id;
	            msg = '��֤ͨ����';
	        } else if (operation_type == 'remove') {
	            url = wapurl + 'index.php?c=once';
	            msg = 'ɾ���ɹ���';
	        }
	        layermsg(msg, 2, function () { location.href = url; });									
		}else if (data == '3'){
			layermsg('�Բ������Ѵﵽһ�����ˢ�´�����',2,function(){});	
		}else{
			layermsg('��֤ʧ�ܣ�',2,function(){});			
		}
	});
}
function checkTinyPassword(id){
	if($(".layermmain #tiny_password").val()==''){
		layermsg('����������');
		return;
	}
	var operation_type = $("#operation_type").val();
	$.post(wapurl + "/index.php?c=ajax&a=checkTinyPassword", { id: id, password: $(".layermmain #tiny_password").val(), operation_type: operation_type }, function (data) {
	    if (data == '1') {
	        var url = '', msg = '';
	        if (operation_type == 'refresh') {
	            url = wapurl + 'index.php?c=tiny&a=show&id=' + id;
	            msg = 'ˢ�³ɹ���';
	        } else if (operation_type == 'edit') {
	            url = wapurl + 'index.php?c=tiny&a=add&id=' + id;
	            msg = '��֤ͨ����';
	        } else if (operation_type == 'remove') {
	            url = wapurl + 'index.php?c=tiny';
	            msg = 'ɾ���ɹ���';
	        }
	        layermsg(msg, 2, function () { location.href = url; });
	    } else {
	        layermsg('��֤ʧ�ܣ�', 2);
	    }
	});
}
function form2json(target_form) {
    var json_form = '';
    $(target_form).find('input,select,textarea').each(function () {
        if ($(this).attr('name')) {
            json_form += ',' + $(this).attr('name') + ':"' + $(this).val().replace(/[\r\n]+/g, '\\n')+'"';
        }
    });
    return eval('({' + json_form.substring(1) + '})');
}
function formfile2json(target_form) {
    var json_form = '';
    var formData = new FormData(target_form);
    $(target_form).find('input,select').each(function () {
        if ($(this).attr('name')) {
            //alert($(this)[0].type);
            if ($(this)[0].type == 'file') {
                //alert('adsfad');
                formData.append('file', $('input[type=file]', target_form).get(0).files[0]);
            } else {
                formData.append($(this).attr('name'), $(this).val());
            }
        }
    });
    
    //alert(formData.length);
    //formData.append('file', $('input[type=file]', target_form).get(0).files[0]);
    //alert(formData);
    return formData;
}
function form2string(target_form) {
    var json_form = '';
    $(target_form).find('input,select').each(function () {
        if ($(this).attr('name')) {
            json_form += '&' + $(this).attr('name') + '=' + $(this).val();
        }
    });
    return json_form;
}
function post2ajax(target_form) {
	layer_load('ִ���У����Ժ�...');
    if ($('input[type=file]', target_form).length > 0) {
        $.ajax({
            url: $(target_form).attr('action'),
            data: formfile2json(target_form),
            processData: false,
            type: 'POST',
			async: false,  
			cache: false,
			contentType: false,
            success: function (data) {
				layer.closeAll();
                var json_data = eval('(' + data + ')');
                if (json_data.msg) {
                    layermsg(json_data.msg, json_data.tm, function () { if (json_data.url) { location.href = json_data.url; } });
                } else if (json_data.url) {
                    location.href = json_data.url;
                }
				
            }
        });
    } else {
        if ($(target_form).attr('action') == 'get') {
            $.get($(target_form).attr('action') + form2string(target_form), function (data) {
				layer.closeAll();
                var json_data = eval('(' + data + ')');
                if (json_data.msg) {
                    layermsg(json_data.msg, json_data.tm, function () { if (json_data.url) { location.href = json_data.url; } });
                } else if (json_data.url) {
                    location.href = json_data.url;
                }
            });
        } else {		
            $.post($(target_form).attr('action'), form2json(target_form), function (data) {
				layer.closeAll();
                var json_data = eval('(' + data + ')');
                if (json_data.msg) {
                    layermsg(json_data.msg, json_data.tm, function () { if (json_data.url) { location.href = json_data.url; } });
                } else if (json_data.url) {
                    location.href = json_data.url;
                }
            });
        }
    }
    return false;
} 
//�޸��û���
function Savenamepost(){
	var username = $.trim($("#username").val());
	var pass = $.trim($("#password").val());
	var repass = $.trim($("#repassword").val());
	if(username.length<2 || username.length>16){
		layermsg("�û�������Ӧ��Ϊ2-16λ��",2);return false;
	}
	if(pass.length<6 || pass.length>20){
		layermsg("���볤��Ӧ��Ϊ6-20λ��",2);return false;
	}
	if(pass!=repass){
		layermsg("�������벻һ�£�",2);return false;
	}
	$.post(weburl+"/wap/member/index.php?c=setname",{username:username,password:pass},function(data){
		if(data==1){
			layermsg("�޸ĳɹ��������µ�¼��", 2,function(){location.href=weburl+"/wap/index.php?m=login"});return false;
		}else{
			layermsg(data,2);return false;
		}
	})
}
function jobadd_url(num,integral_job,integral_pricename,type,have,id){
	var gourl='';
	if(type=="part"){
		if(id){
			gourl='index.php?c=partadd&id='+id;
		}else{
			gourl='index.php?c=partadd';
		}
	}else if(type=="job"){
		if(id){
			gourl='index.php?c=jobadd&id='+id;
		}else{
			gourl='index.php?c=jobadd';
		}
	}
	if(num==0){
		var msg='�ײ������꣬���ȹ����Ա��';
		layer.open({
        content: msg,
        btn: ['ȷ��', 'ȡ��'],
        shadeClose: false
    });
	}else if(num==1){
		location.href=gourl;
	}else if(num==2){
		if((parseInt(have*100)-parseInt(integral_job*100))<0){
			layermsg('��Ҫ'+integral_job+' '+integral_pricename+'�����㣬���ֵ��');
		}else{
			if(integral_job>0){
				var msg='�ײ������꣬������������۳�'+integral_job+' '+integral_pricename+'���Ƿ������';
				layer.open({
					content: msg,
					btn: ['����', 'ȡ��'],
					shadeClose: false,
					yes: function () {
						location.href = gourl;
					}
				});
			}else{
				location.href = gourl;
			} 
		}
	}
}
function check_code(){
	document.getElementById("vcode_img").src=weburl+"/app/include/authcode.inc.php?"+Math.random();
}
function checkcode(){
	document.getElementById("vcodeimg").src=weburl+"/app/include/authcode.inc.php?"+Math.random();
}
//�ʴ��ע����
function attention(id,type,url){
	$.post(url,{id:id,type:type},function(data){
   		var data=eval('('+data+')');  
		if(type==1){var msg='��ע';}else{var msg='+  ��ע';} 
		if(data.st==8){
			layermsg(data.msg, 2);return false;	
		}else{		
			$(".num"+id).html(data.url+"�˹�ע");
			$(".index_num"+id).html(data.url);
			if(data.tm==1){				
				$(".q"+id+">a").attr("class","watch_qxgz");
				$(".q"+id+">a").html("ȡ����ע");
				layermsg("��ע�ɹ���", 2,function(){location.reload();});return false; 
			}else{
				$(".q"+id+">a").attr("class","watch_gz");
				$(".q"+id+">a").html(msg);
				layermsg("ȡ���ɹ���", 2,function(){location.reload();});return false; 
			}				
		} 
	});
}
function showlogins(data){
	if(data==1){
		layermsg('���ȵ�¼��',2,function(){location.href='index.php?c=login';}); 
	}
}
function get_show(eid){
	$("#eid").val(eid); 
	layer.open({
		type:1,
		content: $("#TB_window").html(),
		shadeClose: false
	});return; 
} 

function get_comment(aid,show,url){ 
	$(".pl_menu").hide();
	var style=$(".review"+aid).css("display");
	var info=$(".review"+aid+" ul").html();
	if(style=="none"||show>0){ 
		if((info==''||info==null)||show>0){
			$.post(url,{aid:aid},function(data){
				var html='';  
				var datas = Array();			
				datas = eval("("+data+")");
				$.each(datas,function(key,val){
					html+="<li>"+
							"<div class=\"menu_p1_tx\"><img src=\""+val.pic+"\" onerror=\"showImgDelay(this,'"+val.errorpic+"',2);\"/></div>"+
							"<div class=\"menu_right\">"+
								"<div class=\"menu_rig_h2\">"+
									"<span class=\"menu_user\"><a href=\""+val.url+"\" target=\"_blank\">"+val.nickname+"</a>��</span>"+
									"<span class=\"menu_mes\">"+val.content+"</span>"+
								"</div>"+ 
								"<div class=\"menu_date\">"+
									"<span>"+val.date+"</span>"+
								"</div>"+
							"</div>"+ 
						"</div>"+
						"<div class=\"clear\"></div>"+
					"</li>"; 
					$(".review"+aid+" ul").html(html); 
				});	 
			});
		}
		$(".review"+aid).show();
	}else{
		$(".review"+aid).hide();
	} 
} 
function for_comment(aid,qid,url,comurl){
	var content=$.trim($("#comment_"+aid).val()); 
	if(content=="" || content=="undefined"){
		layermsg('�������ݲ���Ϊ�գ�');return false; 
	}else{
		$.post(url,{aid:aid,qid:qid,content:content},function(msg){
			if(msg=='1'){
				$("#comment_"+aid).val("");
				var com_num=$("#com_num_"+aid).html();  
				com_num=parseInt(com_num)+parseInt(1);
				$("#com_num_"+aid).html(com_num); 
				get_comment(aid,'1',comurl);
			}else if(msg=='0'){
				layermsg('����ʧ�ܣ�');return false; 
			}else if(msg=='no_login'){ 
				layermsg('���ȵ�¼��');return false; 
			}else{
				layermsg(msg);return false; 
			}
		});
	}
} 
function support(aid,url){
	$.post(url,{aid:aid},function(msg){
		if(msg=='0'){
			layermsg('�ύʧ�ܣ�');return false; 
		}else if(msg=='1'){
			var num=$("#support_num_"+aid).html(); 
			$("#support_num_"+aid).html(parseInt(num)+parseInt(1)); 
			layermsg('ͶƱ�ɹ���');return false; 
		}else if(msg=='2'){
			layermsg('�����ظ�ͶƱ��');return false; 
		}
	});
}  
function checkform(){	
	var title=$.trim($("input[name='title']").val());
	var cid=$("input[name='cid']").val();
	var content=$.trim($("textarea[name='content']").val());
	var authcode=$("#ask_CheckCode").val();
	if(title==''){
		layermsg('����д���⣡'); return false;
	}else if(cid==''){
		layermsg('��ѡ�����'); return false;
	}else if(authcode==''){
		layermsg('����д��֤�룡');return false;
	}
	$.post(wapurl+"/index.php?c=ask&a=addquestions",{title:title,cid:cid,content:content,authcode:authcode},function(data){
		if(data=='0'){
			layermsg('��֤�����',2,function(){check_code()});return false; 
		}else if(data==1){
			layermsg('��������ɹ���',2,function(){window.location.href = 'index.php?c=ask'});return false; 
		}else if(data==2){
			layermsg('��������ʧ�ܣ�');return false; 
		}else if(data==3){
			layermsg(pricename+'���㣬�޷�������');return false; 
		}
	});	
}
function getclass(id,name,url){
	$(".quiz_box_first li").removeClass('tw_current');
	$(".qc"+id).addClass('tw_current');
	$(this).parent().attr('class','tw_current');
 	$.post(url,{id:id},function(data){
 		var datas = Array();
		var html='';
		datas = eval("("+data+")"); 
		$.each(datas,function(key,val){
			html +="<li class=\"qc"+key+"\"><a href='javascript:void(0)' onclick=\"selectclass('"+key+"','"+val+"')\">"+val+"</a></li>"; 
		}); 
		//$(".quiz_box_second .quiz_box_title").html(name+"���ࣺ");
		$(".quiz_box_second .quiz_select").html(html);
		$(".quiz_box_second").show();		
		$('.quiz_box_first').hide();
	
	});
}
function selectclass(id,name){
	$(".quiz_box_second li").removeClass('tw_current');
	$(".qc"+id).addClass('tw_current');
	$(".tw_bx_z>span").html(name);
	$(".tw_bx_list_down").hide();
	$("input[name='cid']").val(id); 
}
$(document).ready(function(){
	$("input[name='cid']").val('');
	/*
	$("input[name='keyword']").focus(function(){
		$(".seek_menu").hide();
	},function(){ 
		searchli();
		$(".seek_menu").show(); 
	});
	$("input[name='keyword']").keyup(function(){
		searchli();
	});
	*/
	$(".menu_p1_nrtj span").click(function(){
		var aid=$(this).attr('aid');
		$(".review"+aid).hide();
	});
	$('body').click(function(evt) {
		if(evt.target.name!='keyword'){
			$(".seek_menu").hide();
		}
		if($(evt.target).parents(".tw_bx_z").length==0) {
			$('.tw_bx_list_down').hide();
		}
	});
	$(".tw_bx_z span").click(function(){ 
		$(".quiz_box_first").show();
		$(".quiz_box_second").hide();
		$(".tw_bx_list_down").show();
	});
});
function attention_user(uid,type,url){
	$.post(url,{id:uid},function(msg){ 
		if(msg=='4'){
			layermsg('���ܹ�ע�Լ���');return false; 
		}else if(msg=='3'){
			layermsg('���ȵ�¼��');return false; 
		}else if(type=='remove'){
			$(".atn"+uid).remove();
		}else{   
			var fans=$(".fans"+uid).attr('fans');
			if(msg=='1'){ 
				fans=parseInt(fans)+parseInt(1); 
				$(".user"+uid+">a").attr("class","watch_qxgz");
				$(".user"+uid+">a").html("ȡ����ע");
			}else if(msg=='2'){ 
				fans=parseInt(fans)-parseInt(1); 
				$(".user"+uid+">a").attr("class","watch_gz");
				$(".user"+uid+">a").html("+ ��ע");
			}
			$(".fans"+uid).attr('fans',fans);
			$(".fans"+uid+">span").html(fans);
		}
	});
}
function searchli(){
	var keyword=$.trim($("input[name='keyword']").val());
	var html='';
	$(".seek_menu .option>a").attr("href",wapurl+"&keyword="+keyword);
	$(".seek_menu .option>a").html(keyword);
	if(keyword){ 
		$.post(searchurl,{keyword:keyword},function(data){
			if(data){
				var datas = Array();			
				datas = eval("("+data+")"); 
				$.each(datas,function(key,val){
					html +="<li><p><a href=\""+val.url+"\" target=\"_blank\">"+val.title+"</a></p><span>"+val.answer_num+"���ظ�</span></li>"; 
				});
			}
			$(".searchli").html(html); 
			
		});
	}else{
		$(".searchli").html(''); 
		$(".seek_menu>span").html(''); 
	}
}
function checkanswer(uid){
	var id=$("input[name='id']").val();
	var content=$.trim($("textarea[name='content']").val());
	var authcode=$("#authcode").val();
	if(uid==""){
		layermsg('���ȵ�¼��');return false;
	}
	if($.trim($("textarea[name='content']").val())==""){
		layermsg('�ش����ݲ���Ϊ�գ�'); return false;
	}
	if($.trim($("#authcode").val())==""){
		layermsg('��֤�벻��Ϊ�գ�'); return false;
	}
	$.post(wapurl+"/index.php?c=ask&a=answer",{id:id,content:content,authcode:authcode},function(data){
		if(data=='0'){
			layermsg('��֤�����',2,function(){check_code()});return false; 
		}else if(data==1){
			layermsg('�����ش�ɹ���',2,function(){window.location.reload();});return false; 
		}else if(data==2){
			layermsg('�����ش�ʧ�ܣ�');return false; 
		}
	});	
}
function reason(url){
	var reason=$("#reasonid").val(); 
	var eid=$("#eid").val(); 
	$.post(url,{reason:reason,eid:eid},function(data){ 
		layer.closeAll();
		if(data=='0'){
			layermsg('�ٱ�ʧ�ܣ�');return false;
		}else if(data=='1'){
			layermsg('�ٱ��ɹ���');return false;
		}else if(data=='2'){
			layermsg('���Ѿٱ��������⣡');return false;
		}else if(data=='3'){
			layermsg('�������ѱ����˾ٱ���');return false;
		}else if(data=='no_login'){
			layermsg('���ȵ�¼��');return false;
		}
	});
} 
function ckReason(val){
	$("#reasonid").val(val);
}