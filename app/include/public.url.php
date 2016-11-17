<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2016 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
function get_seo_url($paramer,$config,$seo,$type=''){
	global $adminDir;
	$rewrite_url = '';
	if($paramer['url']){
		$urNewArr = @explode(',',$paramer['url']);
		foreach($urNewArr as $key=>$value){
			if($value){
				$valueNewArr = @explode(':',$value);
				$paramer[$valueNewArr[0]] = $valueNewArr[1];
			}
		}
		unset($paramer['url']);
	}

	if(!$paramer['m']){
		$paramer['m'] = 'index';
	}
	if($config['sy_'.$type.'domain'] && $type!='index'){
		$defaultUrl = "http://".$config['sy_'.$type.'domain'];
	}elseif(trim($config['sy_'.$type.'dir'])){
		$defaultUrl = $config['sy_weburl']."/".$config['sy_'.$type.'dir']."/";
	}else{
		$defaultUrl = $config['sy_weburl']."/";
	}

	if(trim($config['sy_'.$type.'dir'])){
        $typeDir=$type;
	}
	
	$url="index.php?";

	foreach($seo as $k=>$v){
		$v = reset($v);
		$urlFileds=array();
		if($v['rewrite_url'] && $v['php_url']){
			$vUrl = @explode('?',$v['php_url']);
			$urlArray = array();
			if($vUrl[1]){
				$urlArray = @explode("&",$vUrl[1]);
				foreach($urlArray as $key=>$value){
					$valueArray = @explode('=',$value);
					if($valueArray[0]){
						$urlFileds[$valueArray[0]] = $valueArray[1];
					}
				}
			}

			if($type!=''){

				if($config['sy_'.$type.'dir'])
				{
					$defaultUrl  = str_replace('/'.$config['sy_'.$type.'dir'].'/','/',$defaultUrl);

					$urlDir = array_filter(@explode('/',$vUrl[0]));

					if(reset($urlDir) == $typeDir){

						if($paramer['c']==$urlFileds['c'] && $paramer['a']==$urlFileds['a'])
						{
							$rewrite_url=$defaultUrl.(substr($v['rewrite_url'],0,1)=='/'?substr($v['rewrite_url'],1):$v['rewrite_url']);
							break;

						}

					}
				}

			}else{
				if(!$urlFileds['m']){
					$urlFileds['m'] = 'index';
				}
				if((!$paramer['c'] && $paramer['m']==$urlFileds['m'] && !$urlFileds['c']) || ($paramer['c'] && $paramer['m']==$urlFileds['m'] && $paramer['c']==$urlFileds['c'])){
					$rewrite_url=$type.$v['rewrite_url'];
					break;
				}
			}
		}
	}
	if($rewrite_url){
		foreach($paramer as $key=>$value){
			$rewrite_url = str_replace("{".$key."}",$value,$rewrite_url);
		}
        $model=(($config['sy_'.$m.'_web']==1)&&(trim($config['sy_'.$m.'dir']))?$config['sy_'.$m.'dir']:$paramer['m']);
		$rewrite_url = str_replace('{page}',"1", $rewrite_url);
		$rewrite_url = preg_replace('/{(.*?)}/',"", $rewrite_url);
        $rewrite_url=str_replace('m='.$paramer['m'],'m='.$model, $rewrite_url);
        $rewrite_url=str_replace('m_'.$paramer['m'],'m_'.$model, $rewrite_url);
        $rewrite_url=str_replace('/'.$paramer['m'].'/','/'.$model.'/', $rewrite_url);

		return $rewrite_url;
	}
	return null;
}
function formatparamer($paramer,$_smarty_tpl){
    foreach($paramer as $key=>$value){
        $NewUrl=$value;
        if(strstr($NewUrl,'`')){
            $NewValue='';
            $ValueList=explode('`',$NewUrl);
            foreach($ValueList as $k=>$v){
                if(trim($v)!=''){
                    if($k%2==1){
                        if(strstr($v,'$')){
                            $ValueList1=explode('$',$v);
                            $ValueList2=explode('.',$ValueList1[1]);
                            $CurrentValue=null;
                            foreach($ValueList2 as $kk=>$vv){
                                if(trim($vv)!=''){
                                    if($kk==0){
                                        $CurrentValue=$_smarty_tpl->tpl_vars[$vv]->value;
                                    }else{
                                        $CurrentValue=$CurrentValue[$vv];
                                    }
                                }
                            }
                            $NewValue.=$CurrentValue;
                        }
                    }else{
                        $NewValue.=$v;
                    }
                }
            }
            $paramer[$key]=$NewValue;
        }
    }
    return $paramer;
}
function get_url($paramer,$config,$seo,$type='',$index='',$_smarty_tpl){
	global $ModuleName,$adminDir;

    $paramer=formatparamer($paramer,$_smarty_tpl);

    if($type){
        if($config['sy_'.$type.'domain'] && $type!='index'){
            if(strpos($config['sy_'.$type.'domain'],"http://")===false)
			{
				$defaultUrl = "http://".$config['sy_'.$type.'domain'];
			}else{
				$defaultUrl = $config['sy_'.$type.'domain'];
			}
            $defaultUrlRewrite = $defaultUrl;
            unset($paramer['m']);
        }else{
			if(($ModuleName!=$adminDir && $ModuleName!='siteadmin') || !$ModuleName)
			{
				$typeDir = $config['sy_'.$type.'dir'];
			}

            $defaultUrl = $config['sy_weburl'];
            $defaultUrlRewrite = $config['sy_weburl'];
        }
    }else{
        $defaultUrl = $config['sy_weburl'];
        $defaultUrlRewrite = $config['sy_weburl'];
    }

	if($typeDir){
		$defaultUrl .= "/".$typeDir;
        $defaultUrlRewrite .= "/".$typeDir;
		unset($paramer['m']);
	}else{
		if(empty($paramer['m']) && (!$config['sy_'.$type.'domain'] || $type=='index')){
			$m='index';
		}else{
			$m=$paramer['m'];
		}
	}


	if(is_array($paramer)){
		foreach($paramer as $k=>$v){
			if($k!="m" && $k!="con"){
				$paramers[]=$k.":".$v;
			}
		}
	}

    if($index=='admin'){
        global $ModuleName;
        $url=$ModuleName.'/'.$url;
    }

    if($config['sy_seo_rewrite'] && $index!='admin' && $index!='member' && !$paramer['page'] && !$paramer['keyword'] && $paramer['m']!='ajax'){

		$seourl=get_seo_url($paramer,$config,$seo,$type);

        if($seourl){
            return $seourl;
        }

        if($m!='index' && !empty($m)){
            $urlarr['m']=str_replace('_','',str_replace('-','',$m));
        }
        if($paramers){
            $p='';
            foreach($paramers as $v){
                if(!empty($v)){
                    $url_info = @explode(":",$v);
					$urlarr[$url_info[0]]=str_replace('_','',str_replace('-','',$url_info[1]));
                }
            }
        }

        if($urlarr){
            foreach($urlarr as $k=>$v){
                $a[]=$k.'_'.$v;
            }
            $urltemp=@implode('-',$a);
            $url.=$urltemp.'.html';


            $url=$defaultUrlRewrite."/".$url;

        }else{
            $url=$defaultUrlRewrite."/";
        }


    }else{

        if($index=='member'){
            $url=$url.'member/';
        }

        if($index!='admin' && ($config['sy_'.$m.'_web']==1)&&(trim($config['sy_'.$m.'dir']))&&(!trim($config['sy_'.$m.'domain']))){
            $url=$config['sy_'.$m.'dir'].'/'.$url;unset($m);unset($paramer['m']);
        }

        if($m=='index'){
            $url.='index.php';
        }elseif($m=='member'){
            $url.='member/index.php?';
        }else{

			if($m)
			{
				$url.='index.php?'.($m?'&m='.$m:'');
			}
        }

        if($paramers){
            $p='';
            foreach($paramers as $v){
                if(!empty($v)){
					$url_info = @explode(":",$v);
					$p.='&'.$url_info[0].'='.$url_info[1];
                }
            }
            if(strpos($url,'?')){
                $url.=$p;
            }else if($m=='index'){
                $url.='?'.substr($p,1);
            }else{
            	$url.='index.php?'.substr($p,1);
            }
        }
        $url=$defaultUrl.'/'.$url;
    }

	$url=FormatUrl($url);
    return $url;
}
function FormatUrl($url){

    $url=str_replace('?&','?',$url);
    return $url;
}
function addkeywords($type,$keyword){
    global $db,$db_config,$config;
    $info = $db->DB_select_once("hot_key","`key_name`='$keyword' AND `type`='$type'");
    if(is_array($info)){
        $db->update_all("hot_key","`num`=`num`+1","`key_name`='$keyword' AND `type`='$type'");
    }else{
        $db->insert_once("hot_key","`key_name`='$keyword',`num`='1',`type`='$type',`check`='0'");
    }
}
function PageNav($paramer,$get,$table,$where,$Purl,$table2="",$islt='0',$_smarty_tpl){

    global $db,$db_config,$config;
	$url=array();
    if($paramer['islt']){
        $islt=$paramer['islt'];
    }
    $page=$get[page]<1?1:$get[page];
    if($get['c']){
        $urlarr["c"]=$get['c'];
        $Purl['c'] = $get['c'];
    }
    if($get['a']){
        $urlarr["a"]=$get['a'];
        $Purl["a"]	=$get['a'];
    }
    $urlarr["page"]="{{page}}";
    $Purl["page"]="{{page}}";
    if(is_array($Purl)){
        foreach($Purl as $key=>$value){
            if($value!=""){
                $urlarr[$key] = $value;
            }
        }
    }
    if($islt=="2"){
        foreach($urlarr as $k=>$v){
            $url[$k]=$v;
        }
        $pageurl = Url('ask',$url);
    }else if($islt=="3"){
        foreach($get as $k=>$v){
            $url[]=$k."=".$v;
        }
        $memberurl=@implode("&",$url);

        $pageurl = $config['sy_weburl']."/member/index.php?".$memberurl."&page={{page}} ";
    }elseif($islt=='4'){
        foreach($get as $k=>$v){
            if($k&&$k!='page'){
                $url[]=$k."=".$v;
            }
        }
        $memberurl=@implode("&",$url);
        $pageurl = $config['sy_weburl']."/wap/index.php?".$memberurl."&page={{page}} ";
    }elseif($islt=='5'){

        if($config['sy_news_rewrite']=='2'||$Purl['cache']){
            $pageurl = $config['sy_weburl']."/news/".$get['nid']."/{{page}}.html";
        }else{
            $urlarr['page'] = '*page*';
            $pageurl = Url('article',$urlarr,"1");
            $pageurl = str_replace('*page*',"{{page}}", $pageurl);
        }
    }else{

        foreach($Purl as $k=>$v){
            if(!trim($v)){
                unset($Purl[$k]);
            }
        }
		if(in_array($Purl['m'],array('job','resume','company'))){
			$pageurl = searchListRewrite($Purl,$config);
		}else{
			$pageurl = Url($Purl['m'],$Purl);	
		}
       
    }
	
	
    if($table2){
        $list = $db->select_alls($table,$table2,$where,"count(b.id) as count");
        $count = $list[0][count];
    }else{

		if($config['sy_indexpage']>0){
			if($table=='company'){
				$field = 'uid';
			}else{
				$field = 'id';
			}
			$isMax = $db->select_all($table,$where." LIMIT ".$config['sy_indexpage'].",1",$field);
			
		}
		if(!empty($isMax)){
			$count = $config['sy_indexpage'];
		}else{
			$count = $db->select_num($table,$where);
		}
    }
    $pagesize = PageLimit($page,$count,$paramer[limit],$pageurl,$paramer['notpl'],$_smarty_tpl);
	
	if($config['sy_indexpage']>0){

		if($config['sy_indexpage']<$paramer[limit]){
			$paramer[limit] = $config['sy_indexpage'];
		}
		$nowPageSize = ($config['sy_indexpage']-$pagesize)>0?($config['sy_indexpage']-$pagesize):0;
		if($paramer[limit]>$nowPageSize){
			
			$paramer[limit] = $nowPageSize;
		}	
	}
    return $limit = " limit $pagesize,$paramer[limit]";
}
function PageLimit($pagenum,$num,$limit,$pageurl,$notpl=false,$_smarty_tpl,$pagenavname='pagenav'){
    global $db,$db_config,$config;
	
    include_once(LIB_PATH."page.class.php");
	$pages=ceil($num/$limit);
	$pagenum=$pagenum<1?1:$pagenum;
	$pagenum=$pagenum<$pages?$pagenum:$pages;
    $ststrsql=($pagenum-1)*$limit; 
    $page = new page($pagenum,$limit,$num,$pageurl,2,true,$notpl);
    $pagenav=$page->numPage(1);
    if($_smarty_tpl){
        $_smarty_tpl->tpl_vars['limit'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['pages'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['total'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars[$pagenavname] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['limit']->value=$limit;
        $_smarty_tpl->tpl_vars['pages']->value=$pages;
        $_smarty_tpl->tpl_vars['total']->value=$num;
		$_smarty_tpl->tpl_vars['pagenum']->value=$pagenum;
        $_smarty_tpl->assignByRef('total',$num);
        if($pages>1){
      	   $_smarty_tpl->tpl_vars[$pagenavname]->value=$pagenav;
        }
    }
    return $ststrsql;
}
function Page($pagenum,$num,$limit,$pageurl,$notpl=false,$_smarty_tpl,$pagenavname='pagenav',$isadmin=false){
    global $db,$db_config,$config;
    include_once(LIB_PATH."page.class.php");
	
    $pagenum=$pagenum<1?1:$pagenum;
    $ststrsql=($pagenum-1)*$limit;
    $pages=ceil($num/$limit);
    $page = new page($pagenum,$limit,$num,$pageurl,2,true,$notpl);
    $pagenav=$page->numPage(1);
    if($_smarty_tpl){
        $_smarty_tpl->tpl_vars['limit'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['pages'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['total'] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars[$pagenavname] = new Smarty_Variable;
        $_smarty_tpl->tpl_vars['limit']->value=$limit;
        $_smarty_tpl->tpl_vars['pages']->value=$pages;
        $_smarty_tpl->tpl_vars['total']->value=$num;
        $_smarty_tpl->assignByRef('total',$num);
        $_smarty_tpl->tpl_vars[$pagenavname]->value=$pagenav;
    }
    return $pagenav;
}
function Url($m='index',$paramer=array(),$index=""){

    global $db,$db_config,$config,$seo;
    $paramer['m'] = $m;

    $url  =  get_url($paramer,$config,$seo,$m,$index);
    return $url;
}
function FormatPicUrl($paramer){
    global $config;
    $UploadPath=$paramer['path'];
    if(strstr($UploadPath,'http://')){
        if(!file_exists(str_replace($config['sy_weburl'],APP_PATH,$UploadPath))){
            $UploadPath='/'.$config['sy_lt_icon'];
        }else{
            return $UploadPath;
        }
        return $config['sy_weburl'].$UploadPath;
    }
    switch($paramer['type']){
        case 'ltlogo':
            $UploadPath=trim($UploadPath)?$UploadPath:$config['sy_lt_icon'];
            break;
    }
    $PathSplitList=explode('/',$UploadPath);
    switch(trim($PathSplitList[0])){
        case '.':$UploadPath=str_replace('../','/',$UploadPath);break;
        case '..':$UploadPath=str_replace('../','/',$UploadPath);break;
        case '':$UploadPath=$UploadPath;break;
        default:$UploadPath='/'.$UploadPath;break;
    }
    if($paramer['type']){
        if(!file_exists(APP_PATH.$PicUrl)){
            switch($paramer['type']){
                case 'ltlogo':
                    $PicUrl='/'.$config['sy_lt_icon'];
                    break;
            }
        }
    }
    if(!file_exists(APP_PATH.$UploadPath)&&(substr($UploadPath,0,7)=='/upload')){
        $UploadPath='/data'.$UploadPath;
    }
    return $config['sy_weburl'].$UploadPath;
}
function GetSmarty($arr,$get,$_smarty_tpl){
    $arr = str_replace("\"","",$arr);
    $arr = str_replace("'","",$arr);
    if(is_array($arr)){

        foreach($arr as $key=>$value){
            $val = mb_substr($value,0,5);
            if(preg_match ("/auto./i", $value)){
                $nval = str_replace("auto.","",$value);
                $purl[$key] = $get[$nval];
                $arr[$key] = $get[$nval];
                if($get[$nval]!=""){
                	if($key=="keyword"){
						$arr[$key]=trim($get[$key]);
                	}
                    $url.="&".$key."=".$get[$key];
                }
            }
            if(preg_match ("/@./i", $value)){

                $nval = str_replace("@","",$value);
                $nval = str_replace("\"","",$nval);
                $nval = @explode(".",$nval);

                if(is_array($nval)){
                    $smarty_val = $_smarty_tpl->tpl_vars;
                    foreach($nval as $k=>$v){

						if($smarty_val[$v]->value){
							$smarty_val = $smarty_val[$v]->value;
						}else{
							$smarty_val = $smarty_val[$v];
						}
                    }
                    $arr[$key] = $smarty_val;
                }
            }
            if(substr($value,0,5)=='Array'){
                eval('$arr[$key]='.str_replace('Array','$_smarty_tpl->tpl_vars',$value).';');
            }
        }
    }
    return array("purl"=>$purl,"arr"=>$arr,"firmurl"=>$url);
}
function SmartyOutputStr($smarty,$compiler,$_attr,$tagname,$from,$OutputStr,$from='$_from'){
    $item = $_attr['item'];
    if (isset($_attr['key'])){
        $key = $_attr['key'];
    } else {
        $key = null;
    }

    $smarty->openTag($compiler, $tagname, array($tagname, $compiler->nocache, $item, $key));
    $compiler->nocache = $compiler->nocache | $compiler->tag_nocache;
    if (isset($_attr['name'])) {
        $name = $_attr['item'];
        $has_name = true;
        $SmartyVarName = '$smarty.foreach.' . trim($name, '\'"') . '.';
    } else {
        $name = null;
        $has_name = false;
    }
    $ItemVarName = '$' . trim($item, '\'"') . '@';
    if ($has_name) {
        $usesSmartyFirst = strpos($compiler->lex->data, $SmartyVarName . 'first') !== false;
        $usesSmartyLast = strpos($compiler->lex->data, $SmartyVarName . 'last') !== false;
        $usesSmartyIndex = strpos($compiler->lex->data, $SmartyVarName . 'index') !== false;
        $usesSmartyIteration = strpos($compiler->lex->data, $SmartyVarName . 'iteration') !== false;
        $usesSmartyShow = strpos($compiler->lex->data, $SmartyVarName . 'show') !== false;
        $usesSmartyTotal = strpos($compiler->lex->data, $SmartyVarName . 'total') !== false;
    } else {
        $usesSmartyFirst = false;
        $usesSmartyLast = false;
        $usesSmartyTotal = false;
        $usesSmartyShow = false;
    }
    $usesPropFirst = $usesSmartyFirst || strpos($compiler->lex->data, $ItemVarName . 'first') !== false;
    $usesPropLast = $usesSmartyLast || strpos($compiler->lex->data, $ItemVarName . 'last') !== false;
    $usesPropIndex = $usesPropFirst || strpos($compiler->lex->data, $ItemVarName . 'index') !== false;
    $usesPropIteration = $usesPropLast || strpos($compiler->lex->data, $ItemVarName . 'iteration') !== false;
    $usesPropShow = strpos($compiler->lex->data, $ItemVarName . 'show') !== false;
    $usesPropTotal = $usesSmartyTotal || $usesSmartyShow || $usesPropShow || $usesPropLast || strpos($compiler->lex->data, $ItemVarName . 'total') !== false;

    $output = "<?php ";
    $output .= " \$_smarty_tpl->tpl_vars[$item] = new Smarty_Variable; \$_smarty_tpl->tpl_vars[$item]->_loop = false;\n";
    if ($key != null) {
        $output .= " \$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable;\n";
    }
    $output .= $OutputStr.$from." = $from; if (!is_array(".$from.") && !is_object(".$from.")) { settype(".$from.", 'array');}\n";
    if ($usesPropTotal) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->total= \$_smarty_tpl->_count(".$from.");\n";
    }
    if ($usesPropIteration) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->iteration=0;\n";
    }
    if ($usesPropIndex) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->index=-1;\n";
    }
    if ($usesPropShow) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->show = (\$_smarty_tpl->tpl_vars[$item]->total > 0);\n";
    }
    if ($has_name) {
        if ($usesSmartyTotal) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['total'] = \$_smarty_tpl->tpl_vars[$item]->total;\n";
        }
        if ($usesSmartyIteration) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['iteration']=0;\n";
        }
        if ($usesSmartyIndex) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['index']=-1;\n";
        }
        if ($usesSmartyShow) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['show']=(\$_smarty_tpl->tpl_vars[$item]->total > 0);\n";
        }
    }
    $output .= "foreach (".$from." as \$_smarty_tpl->tpl_vars[$item]->key => \$_smarty_tpl->tpl_vars[$item]->value) {\n\$_smarty_tpl->tpl_vars[$item]->_loop = true;\n";
    if ($key != null) {
        $output .= " \$_smarty_tpl->tpl_vars[$key]->value = \$_smarty_tpl->tpl_vars[$item]->key;\n";
    }
    if ($usesPropIteration) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->iteration++;\n";
    }
    if ($usesPropIndex) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->index++;\n";
    }
    if ($usesPropFirst) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->first = \$_smarty_tpl->tpl_vars[$item]->index === 0;\n";
    }
    if ($usesPropLast) {
        $output .= " \$_smarty_tpl->tpl_vars[$item]->last = \$_smarty_tpl->tpl_vars[$item]->iteration === \$_smarty_tpl->tpl_vars[$item]->total;\n";
    }
    if ($has_name) {
        if ($usesSmartyFirst) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['first'] = \$_smarty_tpl->tpl_vars[$item]->first;\n";
        }
        if ($usesSmartyIteration) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['iteration']++;\n";
        }
        if ($usesSmartyIndex) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['index']++;\n";
        }
        if ($usesSmartyLast) {
            $output .= " \$_smarty_tpl->tpl_vars['smarty']->value['foreach'][$name]['last'] = \$_smarty_tpl->tpl_vars[$item]->last;\n";
        }
    }
    $output .= "?>";

    return $output;
}
function FetchMCA2NavUrl($Url){
    if(!strpos($Url,'?')){
        return str_replace('index.php','',$Url.'?m=index&c=index&a=index');
    }else{
        $UrlSplit1=explode('&',substr($Url,strpos($Url,'?')+1));
        $ParamsMCA=array('m'=>'index','c'=>'index','a'=>'index');
        foreach($UrlSplit1 as $k1=>$v1){
            $UrlSplit2=explode('=',$v1);
            $ParamsUrl[$UrlSplit2[0]]=$UrlSplit2[1];
        }
        $ParamsUrl=array_merge($ParamsMCA,$ParamsUrl);
        $ParamsUrlNew=array('m'=>$ParamsUrl['m'],'c'=>$ParamsUrl['c'],'a'=>$ParamsUrl['a']);
        foreach($ParamsUrl as $k1=>$v1){
            if(!in_array($k1,array('m','c','a'))){
                 $ParamsUrlNew[$k1]=$v1;
            }
        }
        $UrlNew=substr($Url,0,strpos($Url,'?')+1);
        foreach($ParamsUrlNew as $k1=>$v1){
            $UrlNew.=$k1.'='.$v1.'&';
        }
        return substr($UrlNew,0,strlen($UrlNew)-1);
    }
}
function navcalss($menu,$classname){
    global $ModuleName,$config;


	$CurrentAllPath='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


	 if($menu['name']=="首页"){

		if($CurrentAllPath==$config['sy_weburl']."/" || $CurrentAllPath==$config['sy_weburl'])
		{
			return $classname;
		}

	 }else{

		$PathArr = @explode('?',$menu['url']);
		if(count($PathArr)>1)
		{
			$endpath = end($PathArr);
			$PathFile = @explode("&",$endpath);
			foreach($PathFile as $key=>$value){

				$Vfiles = @explode("=",$value);
				if(is_array($Vfiles) && !empty($Vfiles)){

					$VfilesArr[$Vfiles[0]] = $Vfiles[1];
				}

			}

			if($VfilesArr['a'] || $_GET['a'])
			{
				if($_GET['a'] == $VfilesArr['a'] && $_GET['c'] == $VfilesArr['c'] && $_GET['m'] == $VfilesArr['m']){

					return $classname;
				}
			}elseif($VfilesArr['c'] || $_GET['c']){
				if($_GET['c'] == $VfilesArr['c'] && $_GET['m'] == $VfilesArr['m']){

					return $classname;
				}
			}elseif($VfilesArr['m'] || $_GET['m']){

				if($_GET['m'] == $VfilesArr['m']){

					return $classname;
				}
			}
		}else{

			if(strpos($CurrentAllPath,$menu['url'])!==false)
			{
				return $classname;
			}
		}
	 }
}

function searchListRewrite($paramer,$config){
		
		
		if($paramer['type']=="job1"){
			$job=$paramer['v'];
		}elseif($paramer['type']=="job1_son" && $paramer['v']!=0){
			$job=$_GET['job1']."_".$paramer['v'];
		}elseif($paramer['type']=="job_post" && $paramer['v']!=0){
			$job=$_GET['job1']."_".$_GET['job1_son']."_".$paramer['v'];
		}else{
			if($_GET['job1']&&!$_GET['job1_son']&&!$_GET['job_post']){
				$job=$_GET['job1'];
			}elseif($_GET['job1_son']&&!$_GET['job_post']){
				$job=$_GET['job1']."_".$_GET['job1_son'];
			}elseif($_GET['job_post']){
				$job=$_GET['job1']."_".$_GET['job1_son']."_".$_GET['job_post'];
			}else{
				$job=0;	
			}	
		}
		if($paramer['untype']=="job1"){
		    $job=0;
		}elseif($paramer['untype']=="job1_son"){
		    $job=$_GET['job1'];
		}elseif($paramer['untype']=="job_post"){
		    $job=$_GET['job1']."_".$_GET['job1_son'];
		}
		
		if($paramer['t']=="index"){
			if($paramer['type']=='job1'){
				$job=$paramer['job1'];
			}elseif($paramer['type']=="job1_son"){
				$job=$paramer['job1']."_".$paramer['job1_son'];
			}elseif($paramer['type']=='job_post'){
				$job=$paramer['job1']."_".$paramer['job1_son']."_".$paramer['job_post'];
			}
		}
		
		if($paramer['type']=="provinceid"){
			$city=$paramer['v'];
		}elseif($paramer['type']=="cityid" && $paramer['v']!=0){
			$city=$_GET['provinceid']."_".$paramer['v'];
		}elseif($paramer['type']=="three_cityid" && $paramer['v']!=0){
			$city=$_GET['provinceid']."_".$_GET['cityid']."_".$paramer['v'];
		}else{
			if($_GET['provinceid']&&!$_GET['cityid']&&!$_GET['three_cityid']){
				$city=$_GET['provinceid'];
			}elseif($_GET['cityid']&&!$_GET['three_cityid']){
				$city=$_GET['provinceid']."_".$_GET['cityid'];
			}elseif($_GET['three_cityid']){
				$city=$_GET['provinceid']."_".$_GET['cityid']."_".$_GET['three_cityid'];
			}else{
				$city=0;	
			}	
		}
		
		if($paramer['untype']=="provinceid"){
		    $city=0;
		}elseif($paramer['untype']=="cityid"){
		    $city=$_GET['provinceid'];
		}elseif($paramer['untype']=="three_cityid"){
		    $city=$_GET['provinceid']."_".$_GET['cityid'];
		}
		
		if($paramer['type']=="city"){
			$city=$paramer['v'];
		}
		
		$hebing=array();
		$salary=$_GET['salary']?$_GET['salary']:0;
		$salary=$paramer['untype']=="salary"?0:$salary;
		$hebing[]=$paramer['type']=="salary"?$paramer['v']:$salary;
		
		
		$hy=$_GET['hy']?$_GET['hy']:0;
		$hy=$paramer['untype']=="hy"?0:$hy;
		$hebing[]=$hy=$paramer['type']=="hy"?$paramer['v']:$hy;
		
		$edu=$_GET['edu']?$_GET['edu']:0;
		$edu=$paramer['untype']=="edu"?0:$edu;
		$hebing[]=$paramer['type']=="edu"?$paramer['v']:$edu;
		
		$exp=$_GET['exp']?$_GET['exp']:0;
		$exp=$paramer['untype']=="exp"?0:$exp;
		$hebing[]=$paramer['type']=="exp"?$paramer['v']:$exp;
		
		$sex=$_GET['sex']?$_GET['sex']:0;
		$sex=$paramer['untype']=="sex"?0:$sex;
		$hebing[]=$paramer['type']=="sex"?$paramer['v']:$sex;
		
		$report=$_GET['report']?$_GET['report']:0;
		$report=$paramer['untype']=="report"?0:$report;
		$hebing[]=$paramer['type']=="report"?$paramer['v']:$report;
		
		$uptime=$_GET['uptime']?$_GET['uptime']:0;
		$uptime=$paramer['untype']=="uptime"?0:$uptime;
		$hebing[]=$paramer['type']=="uptime"?$paramer['v']:$uptime;
		
		
		if($paramer['m']=="resume"){

			$idcard=$_GET['idcard']?$_GET['idcard']:0;
			$idcard=$paramer['untype']=="idcard"?0:$idcard;
			$hebing[]=$paramer['type']=="idcard"?$paramer['v']:$idcard;
			
			$work=$_GET['work']?$_GET['work']:0;
			$work=$paramer['untype']=="work"?0:$work;
			$hebing[]=$paramer['type']=="work"?$paramer['v']:$work;

		}elseif($paramer['m']=="company"){

			$mun=$_GET['mun']?$_GET['mun']:0;
			$mun=$paramer['type']=="mun"?$paramer['v']:$mun;
			$pr=$_GET['pr']?$_GET['pr']:0;
			$pr=$paramer['type']=="pr"?$paramer['v']:$pr;
			$rec=$_GET['rec']?$_GET['rec']:0;
			$rec=$paramer['type']=="rec"?$paramer['v']:$rec;
			
			$city=$_GET[cityid];
			
		}else{
			$cert=$_GET['cert']?$_GET['cert']:0;
			$cert=$paramer['untype']=="cert"?0:$cert;
			$cert=$paramer['type']=="cert"?$paramer['v']:$cert;
			if($cert==0){
			    $certd='';
			    $cert='-0';
			}else{
			    $certd="&cert=".$cert;
			    $cert="-".$cert;
			}
		}
		
		$keyword=$_GET['keyword']?$_GET['keyword']:0;
		$keyword=$paramer['untype']=="keyword"?0:$keyword;
		$keyword=$paramer['type']=="keyword"?$paramer['v']:$keyword;
		$keyword=urlencode($keyword);
		
		$hebing=implode("_",$hebing);
		
		$tp=$_GET['tp']?$_GET['tp']:0;
		$tp=$paramer['type']=="tp"?$paramer['v']:$tp;
		
		$order=$_GET['order']?$_GET['order']:0;
		$order=$paramer['type']=="order"?$paramer['v']:$order;

		$page = $_GET['page']?$_GET['page']:1;
		$page =$paramer['page']?$paramer['page']:$page;
		

		if($config['sy_seo_rewrite']==1){
			$url="list/".$job."-".$city."-".$hebing."-".$tp.$cert."-".$order."-".$keyword."-".$page.".html";
		}else{
			if($job)
			 $url[]='job='.$job;
			if($city)
			 $url[]='city='.$city;
			if($order)
			 $url[]='order='.$order;
			  if($keyword)
			 $url[]='keyword='.$keyword;

			  
			  if(!empty($url)){
				$url="index.php?".implode('&',$url)."&all=".$hebing."&tp=".$tp.$certd;
				if($page){
					$url .='&page='.$page;
				}
			 }else{
				$url="index.php?all=".$hebing."&tp=".$tp.$certd;
				if($page){
					$url .='&page='.$page;
				}
			 }
			
		}
		if($paramer['m']=="company"){
			if($config['sy_seo_rewrite']==1){
				$url="list/".$city."-".$mun."-".$hy."-".$pr."-".$rec."-".$keyword."-".$page.".html";
			}else{
				if($city)
				 $urln[]='city='.$city;
				 
				if($mun)
				 $urln[]='mun='.$mun;
				 
				 if($hy)
				 $urln[]='hy='.$hy;
				 
				 if($pr)
				 $urln[]='pr='.$pr;
				 
				 if($rec)
				 $urln[]='rec='.$rec;
				 
				 if($keyword)
				 $urln[]='keyword='.$keyword;

				 if($page){
					$urln[]='page='.$page;
				 }
				 if(!empty($urln)){
					$url="index.php?".implode('&',$urln);
				 }
				
			}
		}
		$m=$paramer['m']?$paramer['m']:"job";
		return $config['sy_weburl'].'/'.$m.'/'.$url;
	
	}
?>