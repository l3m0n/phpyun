<?php
/* *
* $Author ��PHPYUN�����Ŷ�
*
* ����: http://www.phpyun.com
*
* ��Ȩ���� 2009-2016 ��Ǩ�γ���Ϣ�������޹�˾������������Ȩ����
*
* ���������δ����Ȩǰ���£�����������ҵ��Ӫ�����ο����Լ��κ���ʽ���ٴη�����
*/
$arr_tpl = array ( 
	'emailupjob' => array (
		'name'=>'ְλδˢ������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'��ҵ��',    
		'{date}'=>'�ϴ�ˢ��ʱ��',
	    '{day}'=>'δ��������'
	),
	'emailaddjob' => array (
		'name'=>'��ҵδ����ְλ����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'��ҵ��',    
		'{date}'=>'�ϴη���ʱ��',
	    '{day}'=>'δ��������'    
	),
	'emailbirthday' => array (
		'name'=>'������������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'��ʵ����', 
		'{year}'=>'����', 		
		'{date}'=>'��������' 
	),
	'emailwebbirthday' => array (
		'name'=>'��վ��������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'��ʵ����',    
		'{year}'=>'����',    
		'{date}'=>'��������' 
	),
	'emailuserwdl' => array (
		'name'=>'���˻�Աδ��¼����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',  
		'{date}'=>'�ϴε�¼ʱ��' 
	), 
	'emailcomwdl' => array (
		'name'=>'��ҵδ��¼����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{name}'=>'��ҵ����',  
		'{date}'=>'�ϴε�¼ʱ��' 
	),
    'emailuserup' => array (
        'name'=>'���˻�Աδ���¼���',
        'type'=>'email',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'�û���',
        '{date}'=>'�ϴθ���ʱ��',
        '{day}'=>'δ��������'
    ),
    'emailuseradd' => array (
        'name'=>'���˻�Աδ��������',
        'type'=>'email',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'�û���',
        '{day}'=>'δ������������',
        '{date}'=>'ע��ʱ��'
    ),
	'emailltwdl' => array (
		'name'=>'��ͷδ��¼����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{name}'=>'��ͷ����',  
		'{date}'=>'�ϴε�¼ʱ��' 
	),
	'emailviped' => array (
		'name'=>'��ҵ��Ա��������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{name}'=>'��ҵ����', 
		'{retingname}'=>'��������', 
		'{date}'=>'����ʱ��',
	    '{day}'=>'������������'
	),
	'emailreg' => array (
		'name'=>'�ʼ�ע��ģ��',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{password}'=>'����',
		'{email}'=>'����'
	),
	'emailyqms' => array (
		'name'=>'��������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{company}'=>'��˾����',
		'{linkman}'=>'��ϵ��',
		'{comtel}'=>'��ϵ�绰',
		'{username}'=>'��ְ������',
		'{jobname}'=>'ְλ����',
		'{comemail}'=>'����'
	),
	'emailyqmshf' => array (
			'name'=>'�ظ���������',
			'type'=>'email',
			'{webname}'=>'��վ����',
			'{weburl}'=>'��վ����',
			'{webtel}'=>'��վ�绰',
			'{cusername}'=>'�ظ�������',
			'{typemsg}'=>'�ظ����ݣ��ܾ���ͬ��'
	),
	'emailgetpass' => array (
		'name'=>'�һ�����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{sendcode}'=>'��֤��'
	),
	'emailfkcg' => array (
		'name'=>'����ɹ�',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{order_id}'=>'���׵���',
		'{price}'=>'���',
		'{date}'=>'ʱ��'
	),
	'emaillock' => array (
		'name'=>'��Ա����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{lock_info}'=>'����ԭ��',
	),
	'emailuserstatus' => array (
		'name'=>'��Ա���',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{status_info}'=>'���ԭ��',
	),
	'emailzzshtg' => array (
		'name'=>'ְλ��˳ɹ�',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'emailzzshwtg' => array (
		'name'=>'ְλ���δͨ��',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����',
		'{status_info}'=>'���ԭ��'
	),
	'emailpartshtg' => array (
		'name'=>'��ְ��˳ɹ�',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'emailpartshwtg' => array (
		'name'=>'��ְ���δͨ��',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����',
		'{status_info}'=>'���ԭ��'
	),
	'emailsqzw' => array (
		'name'=>'����ְλ',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{jobname}'=>'ְλ����',
		'{date}'=>'����ʱ��'
	),
	'emailsqzwhf' => array (
			'name'=>'����ְλ�ظ�',
			'type'=>'email',
			'{webname}'=>'��վ����',
			'{weburl}'=>'��վ����',
			'{webtel}'=>'��վ�绰',
			'{company}'=>'��˾����',
			'{jobname}'=>'ְλ����'
		),
	'emailcert' => array (
		'name'=>'������֤',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{url}'=>'���ӵ�ַ',
		'{date}'=>'��֤ʱ��'
	),
		'emailcomcert' => array (
		'name'=>'��ҵ��֤',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{comname}'=>'��ҵ����',
		'{certinfo}'=>'���˵��'
	),
		'emailusercert' => array (
		'name'=>'������֤',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{certinfo}'=>'���˵��'
	),
		'emailjobed' => array (
		'name'=>'ְλ��������',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{com_name}'=>'��˾����',
		'{job_name}'=>'ְλ����'
	),
	'emailremind' => array (
		'name'=>'�ʼ�����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
	),
	'emailnotice' => array (
		'name'=>'�Զ�����ְλ֪ͨ',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{jobname}'=>'ְλ����',
	),
	'emailclaim' => array (
		'name'=>'�����Ա',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{url}'=>'�����ַ',
	),
	'emailrecharge' => array (
		'name'=>'��ֵ����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{name}'=>'����',
		'{price}'=>'���',
		'{time}'=>'ʱ��',
	),
	'emailpartapply' => array (
		'name'=>'��ְ����',
		'type'=>'email',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{username}'=>'�û���',
		'{jobname}'=>'ְλ����',
	),
    'emailzphshtg' => array (
        'name'=>'��Ƹ�����ͨ��',
        'type'=>'email',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{date}'=>'ͨ��ʱ��',
        '{zphname}'=>'��Ƹ������',
    ),
    'emailzphshwtg' => array (
        'name'=>'��Ƹ�����δͨ��',
        'type'=>'email',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{zphname}'=>'��Ƹ������',
        '{status_info}'=>'���ԭ��'
    ),
	'msgcert' => array (
		'name'=>'�ֻ���֤',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{code}'=>'��֤��',
		'{date}'=>'��֤ʱ��'
	),
	'msgreg' => array (
		'name'=>'����ע��ģ��',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{password}'=>'����',
		'{email}'=>'����'
	),
	'msgyqms' => array (
		'name'=>'������������',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{company}'=>'��˾����',
		'{jobname}'=>'����ְλ',
		'{linkman}'=>'��ϵ��',
		'{comtel}'=>'��ϵ�绰',
		'{username}'=>'��ְ������',
		'{jobname}'=>'ְλ����',
		'{comemail}'=>'����'
	),
	'msgyqmshf' => array (
			'name'=>'���Żظ���������',
			'type'=>'msg',
			'{webname}'=>'��վ����',
			'{weburl}'=>'��վ����',
			'{webtel}'=>'��վ�绰',
			'{cusername}'=>'�ظ�������',
			'{typemsg}'=>'�ظ����ݣ��ܾ���ͬ��'
	),
	'msgfkcg' => array (
		'name'=>'����ɹ�',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{order_id}'=>'���׵���',
		'{price}'=>'���',
		'{date}'=>'ʱ��'
	),
	'msgzzshtg' => array (
		'name'=>'ְλ��˳ɹ�',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'msgzzshwtg' => array (
		'name'=>'ְλ���δͨ��',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'msgpartshtg' => array (
		'name'=>'��ְ��˳ɹ�',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'msgpartshwtg' => array (
		'name'=>'��ְ���δͨ��',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{date}'=>'ͨ��ʱ��',
		'{jobname}'=>'ְλ����'
	),
	'msggetpass' => array (
		'name'=>'�һ�����',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{sendcode}'=>'��֤��'),
	'msgsqzw' => array (
		'name'=>'����ְλ',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{jobname}'=>'ְλ����',
		'{date}'=>'����ʱ��'
	),
	'msgsqzwhf' => array (
			'name'=>'����ְλ�ظ�',
			'type'=>'msg',
			'{webname}'=>'��վ����',
			'{weburl}'=>'��վ����',
			'{webtel}'=>'��վ�绰',
			'{company}'=>'��˾����',
			'{jobname}'=>'ְλ����'
		),
	'msgremind' => array (
		'name'=>'��������',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰'
	),
	'msgnotice' => array (
		'name'=>'�Զ�����ְλ֪ͨ',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{jobname}'=>'ְλ����',
	),
	'msgregcode' => array (
		'name'=>'ע����֤��',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{code}'=>'��֤��',
	),
	'msgrecharge' => array (
		'name'=>'��ֵ����',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{name}'=>'����',
		'{price}'=>'���',
		'{time}'=>'ʱ��',
	),
	'msgpartapply' => array (
		'name'=>'��ְ����',
		'type'=>'msg',
		'{webname}'=>'��վ����',
		'{weburl}'=>'��վ����',
		'{webtel}'=>'��վ�绰',
		'{username}'=>'�û���',
		'{jobname}'=>'ְλ����',
	),
    'msgbirthday' => array (
        'name'=>'������������',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'��ʵ����',
        '{year}'=>'����',
        '{date}'=>'��������'
    ),
    'msgwebbirthday' => array (
        'name'=>'��վ��������',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'��ʵ����',
        '{year}'=>'����',
        '{date}'=>'��������'
    ),
    'msgviped' => array (
        'name'=>'��ҵ��Ա��������',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{name}'=>'��ҵ����',
        '{retingname}'=>'��������',
        '{date}'=>'����ʱ��',
        '{day}'=>'������������'
    ),
    'msguseradd' => array (
        'name'=>'���˻�Աδ��������',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'�û���',
        '{day}'=>'δ������������',
        '{date}'=>'ע��ʱ��'
    ),
    'msguserup' => array (
        'name'=>'���˻�Աδ���¼���',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'�û���',
        '{date}'=>'�ϴθ���ʱ��',
        '{day}'=>'δ��������'
    ),
    'msgaddjob' => array (
        'name'=>'��ҵδ����ְλ����',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'��ҵ��',
        '{date}'=>'�ϴη���ʱ��',
        '{day}'=>'δ��������'
    ),
    'msgupjob' => array (
        'name'=>'��ҵְλδˢ������',
        'type'=>'msg',
        '{webname}'=>'��վ����',
        '{weburl}'=>'��վ����',
        '{webtel}'=>'��վ�绰',
        '{username}'=>'��ҵ��',
        '{date}'=>'�ϴ�ˢ��ʱ��',
        '{day}'=>'δ��������'
    )
);
?>
