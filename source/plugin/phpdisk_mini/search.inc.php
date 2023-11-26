<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: view.inc.php 28 2014-09-05 02:48:16Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';

$navtitle = lang('plugin/phpdisk_mini','search file');
$my_subnav = '<em>&raquo;</em>'.lang('plugin/phpdisk_mini','search file');
$word = filter_search(base64_decode(trim(gpc('word','GP',''))));

if($word){
	$w_arr = explode(' ',$word);
	$add_ext = '';
	if(count($w_arr)>1){
		for($i=0;$i<count($w_arr);$i++){
			$add_ext .= "file_name like '%{$w_arr[$i]}%' or ";
		}
	}else{
		$add_ext = "file_name like '%$word%' or";
	}
	$sql_ext = " and ( $add_ext file_extension like '%$word%')";

	$perpage = 20;
	$start = ($page-1)*$perpage;
	$file_list = array();
	$query = DB::query("SELECT * FROM phpdisk_mini_files where is_checked=1 and in_share=1 and is_del=0 $sql_ext ORDER BY file_time DESC LIMIT $start, $perpage");
	$count = DB::result_first("SELECT count(*) FROM phpdisk_mini_files where is_checked=1 and in_share=1 and is_del=0 $sql_ext");
	while($rs = DB::fetch($query)) {
		$rs['file_icon'] = file_icon($rs['file_extension']);
		$tmp_ext = $rs['file_extension'] ? '.'.$rs['file_extension'] : '';
		$rs[a_view] = urr("plugin","id=phpdisk_mini:view&file_id=$rs[file_id]");
		$rs['file_name'] = str_replace($word,'<span class="txtred">'.$word.'</span>',$rs['file_name'].$tmp_ext);
		$rs['file_time'] = date('Y-m-d',$rs['file_time']);
		$rs['file_size'] = get_size($rs['file_size']);
		$file_list[] = $rs;
	}
	unset($rs);

	$multi = multi($count, $perpage, $page, "plugin.php?id=phpdisk_mini:search&word=".base64_encode($word));
	$style = '';
	$size = 'size="50"';
}else{
	$style = 'align="center" style="height:250px; padding-top:50px"';
	$size = 'size="80"';
}
include template('phpdisk_mini:default/search');

?>
