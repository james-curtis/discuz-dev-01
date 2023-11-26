<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: view.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';

$file_id = (int)gpc('file_id','G',0);

if(!$file_id){
	$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'plugin.php?id=phpdisk_mini';
	showmessage(lang('plugin/phpdisk_mini','error referer'),$ref);
	exit;
}
$file = DB::fetch_first("select u.username,fl.* from phpdisk_mini_files fl,".DB::table('common_member')." u where file_id='$file_id' and u.uid=fl.userid and is_del=0 limit 1");
if($file){
	$file[dl] = create_down_url($file);
	$tmpext = $file[file_extension] ? '.'.$file[file_extension] : '';
	$file[file_name_all] = $file[file_name].$tmpext;
	//$fs = $file[file_size];
	$file[file_size] = get_size($file[file_size]);
	$file[file_time] = date('Y-m-d',$file[file_time]);
	$file[a_space] = 'home.php?mod=space&uid='.$file[userid];
	$file[file_url] = $_G[siteurl].urr("plugin","id=phpdisk_mini:view&file_id=$file_id");
	$file[file_description] = htmlspecialchars_decode($file[file_description]);
	if($file[server_oid]){
		$server_host = @DB::result_first("select server_host from phpdisk_mini_servers where server_oid='{$file[server_oid]}'");
		$file[preview_url] = $server_host."ajax2.php?action=preview_file&file_id=$file_id";
	}else{
		$file[preview_url] = urr("plugin","id=phpdisk_mini:ajax2&action=preview_file&file_id=$file_id");
	}
}else{
	$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'plugin.php?id=phpdisk_mini';
	showmessage(lang('plugin/phpdisk_mini','file not exists'),$ref);
	exit;
}
$pick_code = trim(gpc('pick_code','C',''));
$arrx = explode(',',$pick_code);
$pick_ok = ($arrx[0]==$file_id && $file[file_pwd]==$arrx[1]) ? true : false;

if($settings['show_relate'] && ($file[file_pwd] && $pick_ok)){
	$q = DB::query("select file_id,file_name,file_extension,file_size,file_time from phpdisk_mini_files where userid='{$file[userid]}' and in_share=1 and is_checked=1 and is_del=0 and file_id<>'$file_id' order by file_id desc limit 10");
	while ($rs = DB::fetch($q)) {
		$tmpext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
		$rs[file_name_all] = $rs[file_name].$tmpext;
		$rs[file_size] = get_size($rs[file_size]);
		$rs[file_time] = date('Y-m-d',$rs[file_time]);
		$rs[a_view] = urr("plugin","id=phpdisk_mini:view&file_id=$rs[file_id]");
		$rs[file_icon] = file_icon($rs[file_extension]);
		$rel_file[] = $rs;
	}
}
if((!$file[in_share] || !$file[is_checked]) && $file[userid]<>$uid && $_G[adminid]<>1){
	showmessage(lang('plugin/phpdisk_mini','file unshare or unchecked'));
	exit;
}elseif($file[file_pwd] && !$pick_ok){
	$navtitle = lang('plugin/phpdisk_mini','share file tips');
	include template('phpdisk_mini:default/view_pwd');
}else{
	$tmp_st = $file[in_share] ? lang('plugin/phpdisk_mini','shareing') : lang('plugin/phpdisk_mini','unshare');
	$tmp_st2 = $file[is_checked] ? lang('plugin/phpdisk_mini','checked file') : lang('plugin/phpdisk_mini','uncheck file');
	$add_title = $_G[adminid]==1 ? '['.lang('plugin/phpdisk_mini','status').':'.$tmp_st.','.$tmp_st2.']'.lang('plugin/phpdisk_mini','admin mode view') : '';
	$navtitle = $add_title.$file['file_name_all'].' '.lang('plugin/phpdisk_mini','file_down');
	$my_subnav = '<em>&raquo;</em>'.$file['file_name_all'].' - '.lang('plugin/phpdisk_mini','file_down');
	$metakeywords=$navtitle;
	$metadescription=cutstr(no_html($file[file_description]),80);

	include template('phpdisk_mini:default/view');
	views_stat($file_id);
}

function views_stat($file_id){
	$view_stat = gpc('view_stat','C','');
	if(!$view_stat){
		pd_setcookie('view_stat',1,60);
		DB::query("update phpdisk_mini_files set file_views=file_views+1 where file_id='$file_id' limit 1");
	}
}


?>
