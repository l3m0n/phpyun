<?php
class Smarty_Internal_Compile_Fairs extends Smarty_Internal_CompileBase{
    public $required_attributes = array('item');
    public $optional_attributes = array('name', 'key', 'len', 'limit', 'ispage','state');
    public $shorttag_order = array('from', 'item', 'key', 'name');
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);

        $from = $_attr['from'];
        $item = $_attr['item'];
        $name = $_attr['item'];
        $name=str_replace('\'','',$name);
        $name=$name?$name:'list';$name='$'.$name;
        if (!strncmp("\$_smarty_tpl->tpl_vars[$item]", $from, strlen($item) + 24)) {
            $compiler->trigger_template_error("item variable {$item} may not be the same variable as at 'from'", $compiler->lex->taglineno);
        }
        
        //自定义标签START
        $OutputStr=''.$name.'=array();$time=time();eval(\'$paramer='.str_replace('\'','\\\'',ArrayToString($_attr,true)).';\');
		global $db,$db_config,$config;
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
		$Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		$where = "1";
		if($config[\'did\']){
			$where.=" and `did`=\'".$config[\'did\']."\'";
		}
		$time = date("Y-m-d",time());
		//未开始
		if($paramer[state]==\'1\'){//尚未开始
			$where .=" AND `starttime`>\'$time\'";
		}elseif($paramer[state]==\'2\'){//进行中
			$where .=" AND `starttime`<\'$time\' AND `endtime`>\'$time\'";
		}elseif($paramer[state]==\'3\'){//已结束
			$where .=" AND `endtime`<\'$time\'";
		}else{//尚未结束
			$where .=" AND `endtime`>\'$time\'";
		}
		
		
		//查询条数
		if($paramer[limit]){
			$limit=" LIMIT ".$paramer[limit];
		}else{
			$limit=" LIMIT 20";
		}
		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"zhaopinhui",$where,$Purl,"",6,$_smarty_tpl);
		}
		//排序字段（默认按照开始时间排序）
		if($paramer[order]){
			$where .= " ORDER BY $paramer[order] ";
		}else{
			$where .= " ORDER BY `starttime` ";
		}
		//排序规则（默认按照开始时间排序倒序）
		if($paramer[sort]){
			$where .= " $paramer[sort]";
		}else{
			$where .= " DESC ";
		}
		'.$name.'=$db->select_all("zhaopinhui",$where.$limit);
		if(is_array('.$name.')){
			foreach('.$name.' as $key=>$v){
				$array_zid[]=$v[id];
			}
			$rows=$db->select_all("zhaopinhui_com","`zid` in (".implode(\',\',$array_zid).") and `status`=1","`uid`,`zid`,`jobid`");
			$zph_com=array();
			foreach($rows as $key=>$v){ 
				$jobarr=explode(\',\',$v[jobid]);
				$zph_com[$v[zid]][comnum]++;
				$zph_com[$v[zid]][jobnum]=$zph_com[$v[zid]][jobnum]+count($jobarr);
			}
			
			foreach('.$name.' as $key=>$v){
				'.$name.'[$key]["stime"]=strtotime($v[starttime])-mktime();
				$comnum=$zph_com[$v[id]][comnum]?$zph_com[$v[id]][comnum]:0;
				$jobnum=$zph_com[$v[id]][jobnum]?$zph_com[$v[id]][jobnum]:0;
				'.$name.'[$key]["comnum"]=$comnum;
				'.$name.'[$key]["jobnum"]=$jobnum;
				
				'.$name.'[$key]["etime"]=strtotime($v[endtime])-mktime();
				if($paramer[len]){
					'.$name.'[$key]["title"]=mb_substr($v[\'title\'],0,$paramer[len],"GBK");
				}
				'.$name.'[$key]["url"]=Url("zph",array("c"=>\'show\',"id"=>$v[\'id\']),"1");
				 $weekarray=array("日","一","二","三","四","五","六",);            
                '.$name.'[$key]["week"] = $weekarray[date(\'w\',strtotime($v[\'starttime\']))];		
			}
		}';
        //自定义标签 END
        global $DiyTagOutputStr;
        $DiyTagOutputStr[]=$OutputStr;
        return SmartyOutputStr($this,$compiler,$_attr,'fairs',$name,'',$name);
    }
}
class Smarty_Internal_Compile_Fairselse extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);

        list($openTag, $nocache, $item, $key) = $this->closeTag($compiler, array('fairs'));
        $this->openTag($compiler, 'fairselse', array('fairselse', $nocache, $item, $key));

        return "<?php }\nif (!\$_smarty_tpl->tpl_vars[$item]->_loop) {\n?>";
    }
}
class Smarty_Internal_Compile_Fairsclose extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }

        list($openTag, $compiler->nocache, $item, $key) = $this->closeTag($compiler, array('fairs', 'fairselse'));

        return "<?php } ?>";
    }
}
