<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function handlercjxixi_rule($arrayrule)
{
	//var_dump($arrayrule);
	$htmlshowInfo = array();
//	/$i=0;
	foreach($arrayrule as $value){
		$tmpArray = explode("||",$value);
	//	$tmpArray[id]=++$i;
		array_push($htmlshowInfo,$tmpArray);
	}
//	var_dump($htmlshowInfo);
	return $htmlshowInfo;
	
}

?>