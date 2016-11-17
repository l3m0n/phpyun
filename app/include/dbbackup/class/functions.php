<?php
//取得文件内容
function ReadFileContent($filepath){
	$htmlfp=@fopen($filepath,"r");
	while($data=@fread($htmlfp,1000)){
		$string.=$data;
	}
	@fclose($htmlfp);
	return $string;
}
//写文件
function WriteString2File($filepath,$string){
	global $filechmod;
	$fp=@fopen($filepath,"w");
	@fputs($fp,$string);
	@fclose($fp);
	if(empty($filechmod)){
		@chmod($filepath,0777);
	}
}
//建立目录函数
function DoMkdir($path){
	global $public_r;
	//不存在则建立
	if(!file_exists($path)){
		//安全模式
		if($public_r[phpmode]){
			$pr[0]=$path;
			FtpMkdir($ftpid,$pr);
			$mk=1;
		}else{
			$mk=@mkdir($path,0777);
		}
		@chmod($path,0777);
		if(empty($mk)){
			//TODO:NotMkdir","history.go(-1)");
		}
	}
	return true;
}
//时间转换
function ToChangeUseTime($time){
	global $fun_r;
	$usetime=time()-$time;
	if($usetime<60){
		$tstr=$usetime.iconv('utf-8','gbk','秒');
	}else{
		$usetime=round($usetime/60);
		$tstr=$usetime.iconv('utf-8','gbk','分');
	}
	return $tstr;
}
//初使化备份
function BackupDatabaseInit($add){
	global $db,$fun_r,$phpyun_db_ver,$config,$adminDir;
    $res=$db->query("select VERSION()");$row=mysql_fetch_row($res);
    $phpyun_db_ver=$row[0];
	$dbname=$add['mydbname'];
    $bakpath=$add['backuppath'];
	if(empty($dbname)){
		//TODO:数据库名不能为空
	}
	$tablename=$add['tablename'];
	if(is_array($tablename)){
		$count=count($tablename);
	}else{
		$count=1;
		$tablename=array($tablename);
	}
	if(empty($count)){
		//TODO:数据库表数量不能为零
	}
	$add['baktype']=(int)$add['baktype'];
	$add['filesize']=(int)$add['filesize'];
	$add['bakline']=(int)$add['bakline'];
	$add['autoauf']=(int)$add['autoauf'];
	if((!$add['filesize']&&!$add['baktype'])||(!$add['bakline']&&$add['baktype'])){
        //TODO:每组备份大小不能为空
	}
	//目录名
	if(is_array($add['tablename'])){		
		if(empty($add['mypath'])){
			$add['mypath']=$dbname."_".date("YmdHis");
		}
	}else{		
		if(empty($add['mypath'])){
			$add['mypath']=$dbname."_".$add['tablename']."_".date("YmdHis");
		}
	}
    DoMkdir($bakpath."/".$add['mypath']);
	//生成说明文件
	$readme=$add['readme'];
	$rfile=$bakpath."/".$add['mypath']."/readme.txt";
	$readme.="\r\n\r\nBaktime: ".date("Y-m-d H:i:s");
	WriteString2File($rfile,$readme);

	$b_table="";
	$d_table="";
	for($i=0;$i<$count;$i++){
		$b_table.=$tablename[$i].",";
		$d_table.="\$tb[".$tablename[$i]."]=0;\r\n";
    }
	//去掉最后一个,
	$b_table=substr($b_table,0,strlen($b_table)-1);
	$bakstru=(int)$add['bakstru'];
	$bakstrufour=(int)$add['bakstrufour'];
	$beover=(int)$add['beover'];
	$waitbaktime=(int)$add['waitbaktime'];
	$bakdatatype=(int)$add['bakdatatype'];
	if($add['insertf']=='insert'){
		$insertf='insert';
	}else{
		$insertf='replace';
	}
	if($phpyun_db_ver=='4.0'&&$add['dbchar']=='auto'){
		$add['dbchar']='';
	}
	$string="<?php
	\$b_table=\"".$b_table."\";
	".$d_table."
	\$b_baktype=".$add['baktype'].";
	\$b_filesize=".$add['filesize'].";
	\$b_bakline=".$add['bakline'].";
	\$b_autoauf=".$add['autoauf'].";
	\$b_dbname=\"".$dbname."\";
	\$b_stru=".$bakstru.";
    \$b_version=\"".$phpyun_db_ver."\";
    \$b_time=\"".time()."\";
	\$b_strufour=".$bakstrufour.";
	\$b_dbchar=\"".addslashes($add['dbchar'])."\";
	\$b_beover=".$beover.";
	\$b_insertf=\"".addslashes($insertf)."\";
	\$b_autofield=\",".addslashes($add['autofield']).",\";
	\$b_bakdatatype=".$bakdatatype.";
	?>";
	$cfile=$bakpath."/".$add['mypath']."/config.php";
	WriteString2File($cfile,$string);
	if($add['baktype']){
		$phome='BackupDatabaseRecordNum';
	}else{
		$phome='BackupDatabaseFileSize';
	}
	echo "<script>self.location.href='".$config['sy_weburl']."/".$adminDir."/index.php?m=database&c=$phome&t=0&s=0&p=0&mypath=$add[mypath]&waitbaktime=$waitbaktime';</script>";
	exit();
}
//设置编码
function SetCharset($Charset){
	if($Charset&&$Charset!='auto'){
		@mysql_query('set character_set_connection='.$Charset.',character_set_results='.$Charset.',character_set_client=binary;');
	}
}
function Encode($string,$charset){
    $InputCoding=strtolower(mb_detect_encoding($string,array('utf-8','gbk')));
    if($charset==$InputCoding){
        return $string;
    }else{
        return yun_iconv($InputCoding,$charset,$string);
    }
}
//执行备份(按文件大小)
function BackupDatabaseFileSize($t,$s,$p,$mypath,$alltotal,$thenof,$fnum,$stime=0){	
	global $db,$bakpath,$limittype,$fun_r,$db_config,$config,$adminDir;
    header('Content-Type: text/html; charset=' . $db_config['charset']); //设定编码
    ob_start();
	if(empty($mypath)){
        //TODO:您来自的链接不存在
	}
    $b_dbname=$db_config['dbname'];
	$path=PLUS_PATH.'/bdata/'.$mypath;
	@include($path."/config.php");
	if(empty($b_table)){
		//TODO:您来自的链接不存在
	}
	$waitbaktime=(int)$_GET['waitbaktime'];
	if(empty($stime)){
		$stime=time();
	}
$header="<?php
require(dirname(dirname(dirname(dirname(dirname(__FILE__))))).\"/app/include/dbbackup/inc/header.php\");
";
	$footer="
require(dirname(dirname(dirname(dirname(dirname(__FILE__))))).\"/app/include/dbbackup/inc/footer.php\");
?>";
	$btb=explode(",",$b_table);
	$count=count($btb);
	$t=(int)$t;
	$s=(int)$s;
	$p=(int)$p;
	//备份完毕
	if($t>=$count){
		echo "<script>alert('".iconv("utf-8",'gbk',"备份成功！\\n\\n整个过程耗时：")."".ToChangeUseTime($stime)."');self.location.href='index.php?m=database';</script>";
		die;
    }
	//选择数据库
	$u=$db->query("use `$b_dbname`");
	//编码
	SetCharset($b_dbchar);
	if($s==0){
		//总记录数
		if($limittype){
			$num=-1;
		}else{
			$status_r=GetTableRows($b_dbname,$btb[$t]);
			$num=$status_r['Rows'];
		}
	}else{
		$num=(int)$alltotal;
	}
	//备份数据库结构
	$dumpsql.=GetTableStructSql($btb[$t],$b_strufour);
	$sql=$db->select_only($btb[$t],'1 limit '.$s.','.$num);
	//取得字段数
	if(empty($fnum)){
		$return_fr=GetTableFields($b_dbname,$btb[$t],$b_autofield);
		$fieldnum=$return_fr['num'];
		$noautof=$return_fr['autof'];
	}else{
		$fieldnum=$fnum;
		$noautof=$thenof;
	}
	//完整插入
	$inf='';
	if($b_beover==1){
		$inf='('.GetTableInsertFields($b_dbname,$btb[$t]).')';
	}
	//十六进制
	$hexf='';
	if($b_bakdatatype==1){
		$hexf=GetTableStringFields($b_dbname,$btb[$t]);
	}
	$b=0;
	foreach($sql as $k=>$r){
		echo Encode('<script>document.write("正在备份'.$btb[$t].'的第'.($k+1).'条记录<br/>");if(document.documentElement.scrollTop){document.documentElement.scrollTop='.($k*30).';}else{document.body.scrollTop='.($k*30).';}</script>','gbk');  
        ob_flush();
        flush();  
        //ob_end_flush();
		$b=1;
		$s++;
		$dumpsql.="ExcuteSQL(\"insert into `".$btb[$t]."`".$inf." values(";
		$first=1;
		for($i=0;$i<$fieldnum;$i++){
			//首字段
			if(empty($first)){
				$dumpsql.=',';
			}else{
				$first=0;
			}
			$myi=$i+1;
			if(!isset($r[$i])||strstr($noautof,','.$myi.',')){
				$dumpsql.='NULL';
			}else{
				$dumpsql.=GetFieldContent($r[$i],$b_bakdatatype,$myi,$hexf);
			}
		}
		$dumpsql.=");\");\r\n";
		//是否超过限制
		if(strlen($dumpsql)>=$b_filesize*1024){
			$p++;
			$sfile=$path."/".$btb[$t]."_".$p.".php";
			$dumpsql=$header.$dumpsql.$footer;
			WriteString2File($sfile,$dumpsql);
			$db->free($sql);
			echo Encode("<meta http-equiv=\"refresh\" content=\"".$waitbaktime.";url=".$config['sy_weburl']."/".$adminDir."/index.php?m=database&c=BackupDatabaseFileSize&phome=BakExe&s=$s&p=$p&t=$t&mypath=$mypath&alltotal=$num&thenof=$noautof&fieldnum=$fieldnum&stime=$stime&waitbaktime=$waitbaktime&collation=$collation\">".$fun_r['BakOneDataSuccess'],'gbk').EchoBackupProcesser($btb[$t],$count,$t,$num,$s);
			exit();
		}
	}
	//最后一个备份
	if(empty($p)||$b==1){
		$p++;
		$sfile=$path."/".$btb[$t]."_".$p.".php";
		$dumpsql=$header.$dumpsql.$footer;
		WriteString2File($sfile,$dumpsql);
	}
	FetchFileNumber($p,$btb[$t],$path);
	$t++;
	$db->free($sql);
	//进入下一个表
	echo"<meta http-equiv=\"refresh\" content=\"".$waitbaktime.";url=".$config['sy_weburl']."/".$adminDir."/index.php?m=database&c=BackupDatabaseFileSize&phome=BakExe&s=0&p=0&t=$t&mypath=$mypath&stime=$stime&waitbaktime=$waitbaktime\">".$fun_r['OneTableBakSuccOne'].$btb[$t-1].$fun_r['OneTableBakSuccTwo'];
	exit();
}
//执行备份（按记录）
function BackupDatabaseRecordNum($t,$s,$p,$mypath,$alltotal,$thenof,$fnum,$auf='',$aufval=0,$stime=0){
	global $db,$bakpath,$limittype,$fun_r,$adminDir;
	if(empty($mypath)){
		//TODO:您来自的链接不存在
	}
	$path=PLUS_PATH.'/bdata/'.$mypath;
	@include($path."/config.php");
	if(empty($b_table)){
		//TODO:您来自的链接不存在
	}
	$waitbaktime=(int)$_GET['waitbaktime'];
	if(empty($stime)){
		$stime=time();
	}
	$header="<?php
require(LIB_PATH.\"dbbackup/inc/header.php\");
";
	$footer="
require(LIB_PATH.\"dbbackup/inc/footer.php\");
?>";
	$btb=explode(",",$b_table);
	$count=count($btb);
	$t=(int)$t;
	$s=(int)$s;
	$p=(int)$p;
	//备份完毕
	if($t>=$count){
		echo"<script>alert('".$fun_r['BakSuccess']."\\n\\n".$fun_r['TotalUseTime'].ToChangeUseTime($stime)."');self.location.href='".$config['sy_weburl']."/".$adminDir."/index.php?m=database';</script>";
		exit();
    }
	//选择数据库
	$u=$db->query("use `$b_dbname`");
	//编码
	if($b_dbchar=='auto'){
		if(!empty($s)){
			$status_r=GetTableRows($b_dbname,$btb[$t]);
			$collation=GetCharset($status_r['Collation']);
			SetCharset($collation);
			//总记录数
			$num=$limittype?-1:$status_r['Rows'];
		}else{
			$collation=$_GET['collation'];
			SetCharset($collation);
			$num=(int)$alltotal;
		}
		$dumpsql.=ExcuteSetCharset($collation);
	}else{
		SetCharset($b_dbchar);
		if(!empty($s)){
			//总记录数
			if($limittype){
				$num=-1;
			}else{
				$status_r=GetTableRows($b_dbname,$btb[$t]);
				$num=$status_r['Rows'];
			}
		}else{
			$num=(int)$alltotal;
		}
	}
	//备份数据库结构
	if($b_stru&&$s){
		$dumpsql.=GetTableStructSql($btb[$t],$b_strufour);
	}
	//取得字段数
	if(empty($fnum)){
		$return_fr=GetTableFields($b_dbname,$btb[$t],$b_autofield);
		$fieldnum=$return_fr['num'];
		$noautof=$return_fr['autof'];
		$auf=$return_fr['auf'];
	}else{
		$fieldnum=$fnum;
		$noautof=$thenof;
	}
	//自动识别自增项
	$aufval=(int)$aufval;
	if($b_autoauf==1&&$auf){
		$sql=$db->query("select * from `".$btb[$t]."` where ".$auf.">".$aufval." order by ".$auf." limit $b_bakline");
	}else{
		$sql=$db->query("select * from `".$btb[$t]."` limit $s,$b_bakline");
	}
	//完整插入
	$inf='';
	if($b_beover==1){
		$inf='('.GetTableInsertFields($b_dbname,$btb[$t]).')';
	}
	//十六进制
	$hexf='';
	if($b_bakdatatype==1){
		$hexf=GetTableStringFields($b_dbname,$btb[$t]);
	}
	$b=0;
	while($r=$db->fetch($sql)){
		if($auf){
			$lastaufval=$r[$auf];
		}
		$b=1;
		$s++;
		$dumpsql.="ExcuteSQL(\"into `".$btb[$t]."`".$inf." values(";
		$first=1;
		for($i=0;$i<$fieldnum;$i++){
			//首字段
			if(empty($first)){
				$dumpsql.=',';
			}else{
				$first=0;
			}
			$myi=$i+1;
			if(!isset($r[$i])||strstr($noautof,','.$myi.',')){
				$dumpsql.='NULL';
			}else{
				$dumpsql.=GetFieldContent($r[$i],$b_bakdatatype,$myi,$hexf);
			}
		}
		$dumpsql.=");\");\r\n";
	}
	if(empty($b)){
		//最后一个备份
		if(empty($p)){
			$p++;
			$sfile=$path."/".$btb[$t]."_".$p.".php";
			$dumpsql=$header.$dumpsql.$footer;
			WriteString2File($sfile,$dumpsql);
		}
		FetchFileNumber($p,$btb[$t],$path);
		$t++;
		$db->free($sql);
		//进入下一个表
		//echo $fun_r['OneTableBakSuccOne'].$btb[$t].$fun_r['OneTableBakSuccTwo']."<script>self.location.href='phomebak.php?phome=&s=0&p=0&t=$t&mypath=$mypath&stime=$stime';</script>";

		echo"<meta http-equiv=\"refresh\" content=\"".$waitbaktime.";url=".$config['sy_weburl']."/".$adminDir."/index.php?m=database&c=BackupDatabaseRecordNum&s=0&p=0&t=$t&mypath=$mypath&stime=$stime&waitbaktime=$waitbaktime\">".$fun_r['OneTableBakSuccOne'].$btb[$t-1].$fun_r['OneTableBakSuccTwo'];
		exit();
	}
	//进入下一组
	$p++;
	$sfile=$path."/".$btb[$t]."_".$p.".php";
	$dumpsql=$header.$dumpsql.$footer;
	WriteString2File($sfile,$dumpsql);
	$db->free($sql);

	echo"<meta http-equiv=\"refresh\" content=\"".$waitbaktime.";url=".$config['sy_weburl']."/".$adminDir."/index.php?m=database&c=BackupDatabaseRecordNum&s=$s&p=$p&t=$t&mypath=$mypath&alltotal=$num&thenof=$noautof&fieldnum=$fieldnum&auf=$auf&aufval=$lastaufval&stime=$stime&waitbaktime=$waitbaktime&collation=$collation\">".$fun_r['BakOneDataSuccess'].EchoBackupProcesser($btb[$t],$count,$t,$num,$s);
	exit();
}
//输出备份进度条
function EchoBackupProcesser($tbname,$tbnum,$tb,$rnum,$r){
	$table=($tb+1).'/'.$tbnum;
	$record=$r;
	if($rnum!=-1){
		$record=$r.'/'.$rnum;
	}
	/*?>
	<br><br>
	<table width="90%" border="0" align="center" cellpadding="3" cellspacing="1">
		<tr><td height="25">Table Name&nbsp;:&nbsp;<b><?=$tbname?></b></td></tr>
		<tr><td height="25">Table&nbsp;:&nbsp;<b><?=$table?></b></td></tr>
		<tr><td height="25">Record&nbsp;:&nbsp;<b><?=$record?></b></td></tr>
	</table><br><br>
	<?*/
}
//输出恢复进度条
function EchoRecoverProcesser($tbname,$tbnum,$tb,$pnum,$p){
	$table=($tb+1).'/'.$tbnum;
	$record=$p.'/'.$pnum;
	/*?>
	<br><br>
	<table width="90%" border="0" align="center" cellpadding="3" cellspacing="1">
		<tr><td height="25">Table Name&nbsp;:&nbsp;<b><?=$tbname?></b></td></tr>
		<tr><td height="25">Table&nbsp;:&nbsp;<b><?=$table?></b></td></tr>
		<tr><td height="25">File&nbsp;:&nbsp;<b><?=$record?></b></td></tr>
	</table><br><br>
	<?*/
}
//取得表记录数
function GetTableRows($dbname,$tbname){
	global $db;

	$tr=$db->DB_query_all("SHOW TABLE STATUS LIKE '".$tbname."';");
	return $tr;
}
//返回字符集set
function GetCharset($char){
	global $db;
	if(empty($char)){
		return '';
	}
	$r=$db->DB_query_all("SHOW COLLATION LIKE '".$char."';");
	return $r[0]['Charset'];
}
//返回表字段信息
function GetTableFields($dbname,$tbname,$autofield){
	global $db;
	$sql=$db->query("SHOW FIELDS FROM `".$tbname."`");
	$i=0;//字段数
	$autof=",";//去除自增字段列表
	$f='';//自增字段名
    while($r=$db->fetch_array($sql)){
		$i++;
		if(strstr($autofield,",".$tbname.".".$r[Field].",")){
			$autof.=$i.",";
	    }
		if($r['Extra']=='auto_increment'){
			$f=$r['Field'];
		}
    }
	$return_r['num']=$i;
	$return_r['autof']=$autof;
	$return_r['auf']=$f;
	return $return_r;
}
//返回插入字段
function GetTableInsertFields($dbname,$tbname){
	global $db;
	$sql=$db->query("SHOW FIELDS FROM `".$tbname."`");
	$f='';
	$dh='';
	while($r=$db->fetch($sql)){
		$f.=$dh.'`'.$r['Field'].'`';
		$dh=',';
    }
	return $f;
}
//返回字符字段
function GetTableStringFields($dbname,$tbname){
	global $db;
	$sql=$db->query("SHOW FIELDS FROM `".$tbname."`");
	$i=0;
	$f='';
	$dh='';
	while($r=$db->fetch($sql)){
		$i++;
		if(!(stristr($r[Type],'char')||stristr($r[Type],'text'))){
			continue;
		}
		$f.=$dh.$i;
		$dh=',';
    }
	if($f){
		$f=','.$f.',';
	}
	return $f;
}
//字符过虑
function EscapeString($str){
	$str=mysql_real_escape_string($str);
	$str=str_replace('\\\'','\'\'',$str);
	$str=str_replace("\\\\","\\\\\\\\",$str);
	$str=str_replace('$','\$',$str);
	return $str;
}
//返回字段内容
function GetFieldContent($str,$bakdatatype,$i,$tbstrf){
	if($bakdatatype==1&&!empty($str)&&strstr($tbstrf,','.$i.',')){
		$restr='0x'.bin2hex($str);
	}else{
		$restr='\''.EscapeString($str).'\'';
	}
	return $restr;
}
//替换文件数
function FetchFileNumber($p,$table,$path){
	if(empty($p))
	{$p=0;}
	$file=$path."/config.php";
	$text=ReadFileContent($file);
	$rep1="\$tb[".$table."]=0;";
	$rep2="\$tb[".$table."]=".$p.";";
	$text=str_replace($rep1,$rep2,$text);
	WriteString2File($file,$text);
}
//执行SQL
function ExcuteSQL($sql){
	global $db;
	$db->query($sql);
}
//建立表
function CreateTable($sql){
	global $db;
	$db->query(FetchDbcharset($sql));
}
//转为Mysql4.0格式
function Convert2Mysql4($query){
	$exp="ENGINE=";
	if(!strstr($query,$exp)){
		return $query;
	}
	$exp1=" ";
	$r=explode($exp,$query);
	//取得表类型
	$r1=explode($exp1,$r[1]);
	$returnquery=$r[0]."TYPE=".$r1[0];
	return $returnquery;
}
//返回数据库结构
function GetTableStructSql($table,$strufour){
	global $db;
	$dumpsql.="ExcuteSQL(\"DROP TABLE IF EXISTS `".$table."`;\");\r\n";
	//设置引号
	$usql=$db->query("SET SQL_QUOTE_SHOW_CREATE=1;");
	//数据表结构
    $CreatTable = $db->query("SHOW CREATE TABLE $table");
    $r=$db->fetch_array($CreatTable);
	$create=str_replace("\"","\\\"",$r[1]);
	//转为4.0格式
	if($strufour){
		$create=Convert2Mysql4($create);
	}
	$dumpsql.="CreateTable(\"".$create."\");\r\n";
	return $dumpsql;
}
//返回设置编码
function ExcuteSetCharset($char){
	if(empty($char)){
		return '';
	}
	$dumpsql="ExcuteSQL('set names \'".$char."\'');\r\n";
	return $dumpsql;
}
//去除字段中的编码
function DeleteDbcharset($sql){
	global $phpyun_db_ver;
	if($phpyun_db_ver=='4.0'&&strstr($sql,' character set ')){
		$preg_str="/ character set (.+?) collate (.+?) /is";
		$sql=preg_replace($preg_str,' ',$sql);
	}
	return $sql;
}
//加编码
function FetchDbcharset($sql){
	global $phpyun_db_ver,$phpyun_db_char,$b_dbchar;
	//加编码
	if($phpyun_db_ver>='4.1'&&!strstr($sql,'ENGINE=')&&($phpyun_db_char||$b_dbchar)&&$b_dbchar!='auto'){
		$dbcharset=$b_dbchar?$b_dbchar:$phpyun_db_char;
		$sql=GetCreateTableSql($sql,$phpyun_db_ver,$dbcharset);
	}elseif($phpyun_db_ver=='4.0'&&strstr($sql,'ENGINE=')){
		$sql=Convert2Mysql4($sql);
	}
	//去除字段中的编码
	$sql=DeleteDbcharset($sql);
	return $sql;
}
//建表
function GetCreateTableSql($sql,$mysqlver,$dbcharset){
	$type=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU","\\2",$sql));
	$type=in_array($type,array('MYISAM','HEAP'))?$type:'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU","\\1",$sql).
		($mysqlver>='4.1'?" ENGINE=$type DEFAULT CHARSET=$dbcharset":" TYPE=$type");
}
//导入数据
function RecoverData($add,$mypath){
	global $db,$bakpath,$config;
	if(empty($mypath)||empty($add[mydbname])){
		//EmptyReData","history.go(-1)");
	}
    $path=PLUS_PATH.'/bdata/'.$mypath;
	if(!file_exists($path)){
		//PathNotExists","history.go(-1)");
    }
	@include($path."/config.php");
	if(empty($b_table)){
		//FailBakVar","history.go(-1)");
	}
	$waitbaktime=(int)$add['waitbaktime'];
	$btb=explode(",",$b_table);
	$nfile='data/plus/bdata/'.$mypath."/".$btb[0]."_1.php?t=0&p=0&mydbname=$add[mydbname]&mypath=$mypath&waitbaktime=$waitbaktime";
	Header("Location:".$config['sy_weburl']."/$nfile");
	exit();
}
?>