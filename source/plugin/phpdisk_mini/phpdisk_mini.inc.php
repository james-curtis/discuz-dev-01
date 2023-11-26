<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: phpdisk_mini.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';
if($action=='pub_search'){
	$url = str_ireplace(array('&action=pub_search','plugin.php?id=phpdisk_mini'),array('','plugin.php?id=phpdisk_mini:search'),$_SERVER['REQUEST_URI']);
	$arr = (parse_url($url));
	(parse_str($arr[query]));
	$arr2 = explode('&word=',$url);
	$url = $arr2[0].'&word='.rawurlencode(base64_encode($word));
	header('Location: '.$url);
}else if($action=='search'){
	$url = str_ireplace('plugin.php?id=phpdisk_mini','plugin.php?id=phpdisk_mini:phpdisk',$_SERVER['REQUEST_URI']);
	$arr = (parse_url($url));
	(parse_str($arr[query]));
	$arr2 = explode('&word=',$url);
	$url = $arr2[0].'&word='.rawurlencode(base64_encode($word));
	header('Location: '.$url);
}else{
	header('Location: plugin.php?id=phpdisk_mini:phpdisk');
}
?>
