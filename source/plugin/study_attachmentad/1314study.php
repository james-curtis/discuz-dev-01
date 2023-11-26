<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: 1314study.php 639 2018-03-28 20:59:06Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */
if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}
function study_ad($post) {
$preg = '#<a href="forum.php\?mod=attachment([^"]+)" target="_blank">#iUs';#www_discuz_1314study_com
preg_match_all($preg,$post['message'],$arr);#From www.1314study.com
if(!empty($arr)){
foreach($arr[0] as $k => $ar) {	
$find = $ar;#版权：www.1314study.com
$replace = '<a onclick="javascript: showWindow(\'study_attachmentad\',\'plugin.php?id=study_attachmentad:ad'.$arr[1][$k].'\');return false;" href="#">';/*1314学习网*/
$post['message'] = str_replace($find,$replace,$post['message']);
}
}
return $post;#1.3.1.4.学.习.网
}/*13742*/


//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: 1314study.php 1083 2018-03-28 12:59:06Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。