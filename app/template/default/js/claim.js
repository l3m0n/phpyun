function savepost(){
	 var username = $("#username").val();
	 var password=$.trim($("input[name=password]").val());
	 var passwordconfirm=$.trim($("input[name=passwordconfirm]").val());
	 if($.trim(username)=="") {
		layer.msg('�����µ��û�����', 2, 8);return false;
	 }else if(password.length<2 || password.length>16){
		 layer.msg('�û�������ӦΪ2-16λ��', 2, 8);return false;
	 }
	 if(password.length<6 || password.length>20){
		layer.msg('���볤��ӦΪ6-20λ��', 2, 8);return false;
	 }
	 if(password!=passwordconfirm){
		layer.msg('�����������벻һ�£�', 2, 8);return false;
	 }
}