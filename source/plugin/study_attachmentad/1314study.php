<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: 1314study.php 639 2018-03-28 20:59:06Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */
if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}
function study_ad($post) {
$preg = '#<a href="forum.php\?mod=attachment([^"]+)" target="_blank">#iUs';#www_discuz_1314study_com
preg_match_all($preg,$post['message'],$arr);#From www.1314study.com
if(!empty($arr)){
foreach($arr[0] as $k => $ar) {	
$find = $ar;#��Ȩ��www.1314study.com
$replace = '<a onclick="javascript: showWindow(\'study_attachmentad\',\'plugin.php?id=study_attachmentad:ad'.$arr[1][$k].'\');return false;" href="#">';/*1314ѧϰ��*/
$post['message'] = str_replace($find,$replace,$post['message']);
}
}
return $post;#1.3.1.4.ѧ.ϰ.��
}/*13742*/


//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: 1314study.php 1083 2018-03-28 12:59:06Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��