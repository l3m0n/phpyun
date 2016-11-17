<?php
class Smarty_Internal_Compile_Partlist extends Smarty_Internal_CompileBase{
    public $required_attributes = array('item');
    public $optional_attributes = array('name', 'key', 'limit','ispage','provinceid','cityid','three_cityid','type','keyword','order','noid','billing_cycle','namelen','rec','keyword');
    public $shorttag_order = array('from', 'item', 'key', 'name');
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);

        $from = $_attr['from'];
        $item = $_attr['item'];
        $name = $_attr['item'];
        $name=str_replace('\'','',$name);
        $name=$name?$name:'List';$name='$'.$name;
        if (!strncmp("\$_smarty_tpl->tpl_vars[$item]", $from, strlen($item) + 24)) {
            $compiler->trigger_template_error("item variable {$item} may not be the same variable as at 'from'", $compiler->lex->taglineno);
        }

        //�Զ����ǩ START
        $OutputStr='global $db,$db_config,$config;
		$time = time();


		//����������
        eval(\'$paramer='.str_replace('\'','\\\'',ArrayToString($_attr,true)).';\');
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[arr];
        $Purl =  $ParamerArr[purl];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }

		if($config[sy_web_site]=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[cityid]>0 && $config[cityid]!=""){
				$paramer[cityid] = $config[cityid];
			}
			if($config[three_cityid]>0 && $config[three_cityid]!=""){
				$paramer[three_cityid] = $config[three_cityid];
			}
		}
		$where = "`deadline`>\'$time\' and `state`=1";
		if($paramer[noid]){
			$where.=" and `id`<>\'".$paramer[\'noid\']."\'";
		}
		
		//�ؼ���
		if($paramer[keyword]){
			$where .= " AND `name` LIKE \'%".$paramer[keyword]."%\'";
		}
		//���д���
		if($paramer[provinceid]){
			$where .= " AND `provinceid` = $paramer[provinceid]";
		}
		//��������
		if($paramer[\'cityid\']){
			$where .= " AND (`provinceid`=$paramer[cityid] or `cityid`=$paramer[cityid] or `three_cityid`=$paramer[cityid])";
		}
		//������������
		if($paramer[\'three_cityid\']){
			$where .= " AND (`three_cityid` IN ($paramer[three_cityid]))";
		}
		if($paramer[\'cityin\']){
			$where .= " AND `three_cityid` IN ($paramer[cityin])";
		}
		//�Ƽ���ְ
		if($paramer[rec]){
			$where.=" AND `rec_time`>".time();
		}
		//��������
		if($paramer[type]){
			$where .= " AND `type` = $paramer[type]";
		}
		//��������
		if($paramer[billing_cycle]){
			$where .= " AND `billing_cycle` = $paramer[billing_cycle]";
		}
		//����ְλ����ƥ��
		/*if($paramer[keyword]){
			$where1[]="`name` LIKE \'%".$paramer[keyword]."%\'";
			include PLUS_PATH."/city.cache.php";
			foreach($city_name as $k=>$v){
				if(strpos($v,$paramer[keyword])!==false){
					$cityid[]=$k;
				}
			}
			if(is_array($cityid)){
				foreach($cityid as $value){
					$class[]= "(provinceid = \'".$value."\' or cityid = \'".$value."\')";
				}
				$where1[]=@implode(" or ",$class);
			}
			$where.=" AND (".@implode(" or ",$where1).")";
		}*/

		

		//��ѯ����
		if($paramer[limit]){
			$limit = " limit ".$paramer[limit];
		}
		if($paramer[ispage]){
			$limit = PageNav($paramer,$_GET,"partjob",$where,$Purl,"",$paramer[limit]?$paramer[limit]:"6",$_smarty_tpl);
            $_smarty_tpl->tpl_vars["firmurl"]=new Smarty_Variable;
			$_smarty_tpl->tpl_vars["firmurl"]->value = $config[\'sy_weburl\']."/index.php?m=com".$ParamerArr[firmurl];
		}
		//�����ֶ�Ĭ��Ϊ����ʱ��
		if($paramer[order] && $paramer[order]!="lastdate"){
			$order = " ORDER BY ".str_replace("\'","",$paramer[order])."  ";
		}else{
			$order = " ORDER BY `lastupdate` ";
		}
		//������� Ĭ��Ϊ����
		if($paramer[sort]){
			$sort = $paramer[sort];
		}else{
			$sort = " DESC";
		}
		$where.=$order.$sort;
		'.$name.' = $db->select_all("partjob",$where.$limit);
		if(is_array('.$name.')){
			foreach('.$name.' as $key=>$value){
				'.$name.'[$key] = $db->part_array_action($value,$cache_array);
				'.$name.'[$key][stime] = date("Y-m-d",$value[sdate]);
				'.$name.'[$key][etime] = date("Y-m-d",$value[edate]);
				'.$name.'[$key][lastupdate] = date("Y-m-d",$value[lastupdate]);
				$time=$value[\'lastupdate\'];
				//���쿪ʼʱ���
				$beginToday=mktime(0,0,0,date(\'m\'),date(\'d\'),date(\'Y\'));
				//���쿪ʼʱ���
				$beginYesterday=mktime(0,0,0,date(\'m\'),date(\'d\')-1,date(\'Y\'));
				//һ����ʱ���
				$week=strtotime(date("Y-m-d",strtotime("-1 week")));
				if($time>$week && $time<$beginYesterday){
					'.$name.'[$key][\'time\'] = "һ����";
				}elseif($time>$beginYesterday && $time<$beginToday){
					'.$name.'[$key][\'time\'] = "����";
				}elseif($time>$beginToday){
					'.$name.'[$key][\'time\'] = "����";
				}else{
					'.$name.'[$key][\'time\'] = date("Y-m-d",$value[\'lastupdate\']);
				}

				//��ȡְλ����
				if($paramer[namelen]){
					'.$name.'[$key][name_n] = mb_substr($value[\'name\'],0,$paramer[namelen],"GBK");
				}
				//����ְλα��̬URL
				'.$name.'[$key][job_url] = Url("part",array("c"=>"show","id"=>$value[id]),"1");
				if($paramer[keyword]){
					'.$name.'[$key][name]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$value[name]);
					'.$name.'[$key][name_n]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",'.$name.'[$key][name_n]);
					'.$name.'[$key][job_city_one]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[provinceid]]);
					/*'.$name.'[$key][job_city_two]=str_replace($paramer[keyword],"<font color=#FF6600 >".$paramer[keyword]."</font>",$city_name[$value[cityid]]);*/
				}
			}
			if(is_array('.$name.')){
				if($paramer[keyword]!=""&&!empty('.$name.')){
					addkeywords(\'2\',$paramer[keyword]);
				}
			}
		}';
        global $DiyTagOutputStr;
        $DiyTagOutputStr[]=$OutputStr;
        return SmartyOutputStr($this,$compiler,$_attr,'partlist',$name,'',$name);
    }
}
class Smarty_Internal_Compile_Partlistelse extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);

        list($openTag, $nocache, $item, $key) = $this->closeTag($compiler, array('partlist'));
        $this->openTag($compiler, 'partlistelse', array('partlistelse', $nocache, $item, $key));

        return "<?php }\nif (!\$_smarty_tpl->tpl_vars[$item]->_loop) {\n?>";
    }
}
class Smarty_Internal_Compile_Partlistclose extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }

        list($openTag, $compiler->nocache, $item, $key) = $this->closeTag($compiler, array('partlist', 'partlistelse'));

        return "<?php } ?>";
    }
}
