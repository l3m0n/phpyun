<?php
//�ٶ��Զ����͹���
function smarty_function_baidu($paramer,&$smarty){
		global $config;
		$content="<script>
(function(){
	var bp = document.createElement('script');
	bp.src = '//push.zhanzhang.baidu.com/push.js';
	var s = document.getElementsByTagName(\"script\")[0];
	s.parentNode.insertBefore(bp, s);
})();
</script>
";
		if($config['sy_zhanzhang_baidu']==1){
			return $content;
		}
	}
?>