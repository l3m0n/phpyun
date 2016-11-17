<?php
class Smarty_Internal_Compile_Comlist extends Smarty_Internal_CompileBase{
    public $required_attributes = array('item');
    public $optional_attributes = array('name', 'key', 'ispage', 'isjob', 'firm', 'isnews', 'isshow','hy', 'pr', 'mun', 'provinceid', 'cityid', 'three_cityid', 'keyword', 'order', 'limit', 'logo', 'comlen', 'namelen', 'firmpic', 'ismsg', 'rec','islt', 'uptime' ,'cityin');
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

        //TODO:$this->company_rating,�˱�����������ʲô��Ӧ�ü���ʲô�ط���
        //�Զ����ǩ START
        $OutputStr='global $db,$db_config,$config;eval(\'$paramer='.str_replace('\'','\\\'',ArrayToString($_attr,true)).';\');'.$name.'=array();
		
		$time = time();
		//��������������ҹ����ҳ����
		$ParamerArr = GetSmarty($paramer,$_GET,$_smarty_tpl);
		$paramer = $ParamerArr[\'arr\'];
		$Purl =  $ParamerArr[\'purl\'];
        global $ModuleName;
        if(!$Purl["m"]){
            $Purl["m"]=$ModuleName;
        }
		//�Ƿ����ڷ�վ��
		if($config[\'sy_web_site\']=="1"){
			if($config[province]>0 && $config[province]!=""){
				$paramer[provinceid] = $config[province];
			}
			if($config[\'cityid\']>0 && $config[\'cityid\']!=""){
				$paramer[\'cityid\']=$config[\'cityid\'];
			}
			if($config[\'hyclass\']>0 && $config[\'hyclass\']!=""){
				$paramer[\'hy\']=$config[\'hyclass\'];
			}
		} 
		$where="`name`<>\'\' and `hy`<>\'\'"; 
		/*if(!is_array($this->company_rating)){
			$comrat = $db->select_all($db_config[\'def\']."company_rating");
			$this->company_rating=$comrat;
		}else{
			$comrat = $this->company_rating;
		}*/
		//�ؼ���
		if($paramer[\'keyword\']){
			$where.=" AND `name` LIKE \'%".$paramer[\'keyword\']."%\'";
		}				
		//��˾��ҵ
		if($paramer[\'hy\']){
			$where .= " AND `hy` = \'".$paramer[\'hy\']."\'";
		}
		//��˾����
		if($paramer[\'pr\']){
			$where .= " AND `pr` = \'".$paramer[\'pr\']."\'";
		}
		//��˾��ģ
		if($paramer[\'mun\']){
			$where .= " AND `mun` = \'".$paramer[\'mun\']."\'";
		}
		//��˾�ص�
		if($paramer[\'provinceid\']){
			$where .= " AND `provinceid` = \'".$paramer[\'provinceid\']."\'";
		}
		//���ڵ� ����
		if($paramer[\'cityid\']){
			$where .= " AND (`provinceid` = \'".$paramer[\'cityid\']."\' OR `cityid` = \'".$paramer[\'cityid\']."\')";
		}
		
		//���ڵ� ����
		if($paramer[\'cityin\']){
			$where .= " AND (`provinceid` in(".$paramer[\'cityin\'].") OR `cityid` in(".$paramer[\'cityin\'].") or `three_cityid` in(".$paramer[\'cityin\']."))";
		}
		//��ϵ�˲�Ϊ��
		if($paramer[\'linkman\']){
			$where .= " AND `linkman`<>\'\'";
		}
		//��ϵ�˵绰��Ϊ��
		if($paramer[\'linktel\']){
			$where .= " AND `linktel`<>\'\'";
		}
		//��ϵ�����䲻Ϊ��
		if($paramer[\'linkmail\']){
			$where .= " AND `linkmail`<>\'\'";
		}
		//�Ƿ�����ҵLOGO
		if($paramer[\'logo\']){
			$where .= " AND `logo`<>\'\'";
		}
		//�Ƿ�����
		if($paramer[\'r_status\']){
			$where .= " AND `r_status`=\'".$paramer[\'r_status\']."\'";
		}else{
			$where .= " AND `r_status`<>\'2\'";
		}
		//�Ƿ��Ѿ���֤
		if($paramer[\'cert\']){
			$where .= " AND `yyzz_status`=\'1\'";
		}
		//����ʱ������
		if($paramer[\'uptime\']){
			$uptime = $time-$paramer[\'uptime\']*3600;
			$where.=" AND `lastupdate`>\'".$uptime."\'";
		}
		if($paramer[\'jobtime\']){
			$where.=" AND `jobtime`<>\'\'";
		}
		//�Ƽ�����ͷҳ��չʾ
		
		if($paramer[\'rec\']){
			$Purl["rec"]=\'1\';
			$where.=" AND `rec`=\'1\' AND `hottime`>\'".time()."\'";
		}
		
       
		//��ѯ����
		if($paramer[\'limit\']){
			$limit=" limit ".$paramer[\'limit\'];
		}
		
		//�Զ����ѯ������Ĭ��ȡ�������κβ���ֱ��ʹ�ø����
		if($paramer[\'where\']){
			$where = $paramer[\'where\'];
		}
		//��������ֶ�
		$cache_array = $db->cacheget();
		if($paramer[\'ispage\']){ 
			if($paramer[\'rec\']==1&&$Purl["m"]=="lietou"){
				$limit = PageNav($paramer,$_GET,"company",$where,$Purl,"","1",$_smarty_tpl);
			}else{
				$limit = PageNav($paramer,$_GET,"company",$where,$Purl,"","0",$_smarty_tpl);
			}

            $_smarty_tpl->tpl_vars[\'firmurl\']=new Smarty_Variable;
			$_smarty_tpl->tpl_vars[\'firmurl\']->value = $ParamerArr[\'firmurl\'];
		}
		//�����ֶ�Ĭ��Ϊ����ʱ��
		if($paramer[\'order\']){
			if($paramer[\'order\']=="last��pdate"){
				$paramer[\'order\']="lastupdate";
			}
			$order = " ORDER BY `".$paramer[\'order\']."`  ";
		}else{
			$order = " ORDER BY `jobtime` ";
		}
		//������� Ĭ��Ϊ����
		if($paramer[\'sort\']){
			$sort = $paramer[\'sort\'];
		}else{
			$sort = " DESC";
		}
		$where.=$order.$sort;
		
		$Query = $db->query("SELECT * FROM $db_config[def]company where ".$where.$limit);
		$ListId=array();
		'.$name.'=array();
		while($rs = $db->fetch_array($Query)){
			'.$name.'[] = $db->array_action($rs,$cache_array);
			$ListId[] = $rs[\'uid\'];
		}  
		//���û�Ա�ȼ�
		include PLUS_PATH."/comrating.cache.php";
		if(!empty($ListId)){
		$statis = $db->select_all("company_statis","`uid` in (".@implode(",",$ListId).")","`uid`,`rating`");
		foreach($ListId as $key=>$value){
		       foreach($statis as $v){
		               foreach($comrat as $val){
			                if($value==$v[\'uid\'] && $val[\'id\']==$v[\'rating\']){						
							'.$name.'[$key][\'color\'] = $val[\'com_color\'];
							'.$name.'[$key][\'ratlogo\'] = $val[\'com_pic\'];
							'.$name.'[$key][\'ratname\'] = $val[\'name\'];
						    }
					  }
				}
			}
		}
		//��Ӧ����
		if($paramer[\'ismsg\']){
			$Msgid = @implode(",",$ListId);
			$msglist = $db->select_alls("company_msg","resume","a.`cuid` in ($Msgid) and a.`uid`=b.`uid` order by a.`id` desc","a.cuid,a.content,b.name,b.photo,b.def_job");
			if(is_array($ListId) && is_array($msglist)){
				foreach('.$name.' as $key=>$value){
					foreach($msglist as $k=>$v){
						if($value[\'uid\']==$v[\'cuid\']){
							'.$name.'[$key][\'msg\'][$k][\'content\'] = $v[\'content\'];
							'.$name.'[$key][\'msg\'][$k][\'name\'] = $v[\'name\'];
							'.$name.'[$key][\'msg\'][$k][\'photo\'] = $v[\'photo\'];
							'.$name.'[$key][\'msg\'][$k][\'eid\'] = $v[\'def_job\'];
						}
					}
				}
			}
		}
		//�Ƿ���Ҫ��ѯ��Ӧְλ
		if($paramer[\'isjob\']){
			//��ѯְλ
			$JobId = @implode(",",$ListId);
			$JobList=$db->select_all("company_job","`uid` IN ($JobId) and `edate`>\'".mktime()."\' and r_status<>\'2\' and status<>\'1\' and state=1  order by `lastupdate` desc");
			if(is_array($ListId) && is_array($JobList)){
				foreach('.$name.' as $key=>$value){
					'.$name.'[$key][\'jobnum\'] = 0;
					foreach($JobList as $k=>$v){
						if($value[\'uid\']==$v[\'uid\']){
							$id = $v[\'id\'];
							'.$name.'[$key][\'newsjob\'] = $v[\'name\'];
							'.$name.'[$key][\'newsjob_status\'] = $v[\'status\'];
							'.$name.'[$key][\'r_status\'] = $v[\'r_status\'];

							$v = $db->array_action($value,$cache_array);
							$v[\'job_url\'] = Url("job",array("c"=>"comapply","id"=>$JobList[$k][\'id\']),"1");
							$v[\'id\']= $id;
							$v[\'name\'] = '.$name.'[$key][\'newsjob\'];
							'.$name.'[$key][\'joblist\'][] = $v;
							'.$name.'[$key][\'jobnum\'] = '.$name.'[$key][\'jobnum\']+1;
						}
					}
					/*
					foreach($comrat as $k=>$v){
						if($value[\'rating\']==$v[\'id\']){
							'.$name.'[$key][\'color\'] = $v[\'com_color\'];
							'.$name.'[$key][\'ratlogo\'] = $v[\'com_pic\'];
						}
					}*/
				}
			}
		}
		//�Ƿ���Ҫ��ѯ��Ӧ��Ѷ
		if($paramer[\'isnews\']){
			//��ѯ��Ѷ
			$JobId = @implode(",",$ListId);
			$NewsList=$db->select_all("company_news","`uid` IN ($JobId) and status=1  order by `id` desc");
			if(is_array($ListId) && is_array($NewsList)){
				foreach('.$name.' as $key=>$value){
					'.$name.'[$key][\'newsnum\'] = 0;
					foreach($NewsList as $k=>$v){
						if($value[\'uid\']==$v[\'uid\']){
							'.$name.'[$key][\'newslist\'][] = $v;
							'.$name.'[$key][\'newsnum\'] = '.$name.'[$key][\'newsnum\']+1;
						}
					}
				}
			}
		}
		//�Ƿ���Ҫ��ѯ��Ӧ����չʾ
		if($paramer[\'isshow\']){
			//��ѯ����չʾ
			$JobId = @implode(",",$ListId);
			$ShowList=$db->select_all("company_show","`uid` IN ($JobId) order by `id` desc");
			if(is_array($ListId) && is_array($ShowList)){
				foreach('.$name.' as $key=>$value){
					'.$name.'[$key][\'shownum\'] = 0;
					foreach($ShowList as $k=>$v){
						if($value[\'uid\']==$v[\'uid\']){
							'.$name.'[$key][\'showlist\'][] = $v;
							'.$name.'[$key][\'shownum\'] = '.$name.'[$key][\'shownum\']+1;
						}
					}
				}
			}
		} 
		//��ҵ��ҳ �Ƿ��ע  201305_gl
		if($paramer[\'firm\']){
			if($_COOKIE[uid]){$atnlist = $db->select_all("atn","`uid`=\'$_COOKIE[uid]\'");}
			if(is_array('.$name.')){
				foreach('.$name.' as $key=>$value){
					if(!empty($atnlist)){
						foreach($atnlist as $v){
							if($value[\'uid\'] == $v[\'sc_uid\']){
								'.$name.'[$key][\'atn\'] = "ȡ����ע";
                                '.$name.'[$key][\'atnstatus\'] = "1";
								break;
							}else{
								'.$name.'[$key][\'atn\'] = "��ע";
							}
						}
					}else{
						'.$name.'[$key][\'atn\'] = "��ע";
					}
				}
			}
		}
		if(is_array('.$name.')){
			foreach('.$name.' as $key=>$value){
				'.$name.'[$key][\'com_url\'] = Url("company",array("c"=>"show","id"=>$value[\'uid\']));
				'.$name.'[$key][\'joball_url\'] = Url("company",array("c"=>"show","id"=>$value[\'uid\'],"tp"=>"post")); 
				if($value[\'logo\']!=""){
					'.$name.'[$key][\'logo\'] = str_replace("./",$config[\'sy_weburl\']."/",$value[\'logo\']);
				}else{
					'.$name.'[$key][\'logo\'] = $config[\'sy_weburl\']."/".$config[\'sy_unit_icon\'];
				} 
				
			}
			if($paramer[\'keyword\']!=""&&!empty('.$name.')){
				addkeywords(\'4\',$paramer[\'keyword\']);
			}
		}';
        //�Զ����ǩ END
        global $DiyTagOutputStr;
        $DiyTagOutputStr[]=$OutputStr;
        return SmartyOutputStr($this,$compiler,$_attr,'comlist',$name,'',$name);
    }
}
class Smarty_Internal_Compile_Comlistelse extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);

        list($openTag, $nocache, $item, $key) = $this->closeTag($compiler, array('comlist'));
        $this->openTag($compiler, 'comlistelse', array('comlistelse', $nocache, $item, $key));

        return "<?php }\nif (!\$_smarty_tpl->tpl_vars[$item]->_loop) {\n?>";
    }
}
class Smarty_Internal_Compile_Comlistclose extends Smarty_Internal_CompileBase{
    public function compile($args, $compiler, $parameter){
        $_attr = $this->getAttributes($compiler, $args);
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }

        list($openTag, $compiler->nocache, $item, $key) = $this->closeTag($compiler, array('comlist', 'comlistelse'));

        return "<?php } ?>";
    }
}
