<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/function.php';

if(function_exists('check_bineoo_md5')){
	check_bineoo_md5();
}

$sql = '';
$query = DB::query("SHOW COLUMNS FROM ".DB::table('bineoo_storage_bucket'));
$fieldarr = array();
while($temp = DB::fetch($query)) {
	$fieldarr[] = $temp['Field'];
}
if(!in_array('image_style',$fieldarr)){
	$sql .= "ALTER TABLE pre_bineoo_storage_bucket ADD image_style varchar(255) NOT NULL DEFAULT '';";
}

if($sql) {
	runquery($sql);
}

$finish = true;