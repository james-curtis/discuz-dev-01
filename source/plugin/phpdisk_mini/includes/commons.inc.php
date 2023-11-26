<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: commons.inc.php 29 2014-09-26 04:16:30Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
//error_reporting(E_ERROR |E_PARSE);
function get_runtime($start,$end='') {
	static $_ps_time = array();
	if(!empty($end)) {
		if(!isset($_ps_time[$end])) {
			$mtime = explode(' ', microtime());
		}
		return number_format(($mtime[1] + $mtime[0] - $_ps_time[$start]), 6);
	}else{
		$mtime = explode(' ', microtime());
		$_ps_time[$start] = $mtime[1] + $mtime[0];
	}
}
get_runtime('start');
$timestamp = time();
@set_magic_quotes_runtime(0);
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define('LF',"\r\n");
}else{
	define('LF',"\n");
}
define('NOW_YEAR','2014');
define('PHPDISK_ROOT', substr(dirname(__FILE__),0,-8));
define('IN_PHPDISK',TRUE);
define('PHPDISK_PLUGIN_ID','phpdisk_mini');
define('PHPDISK_PLUGIN_DIR','source/plugin/'.PHPDISK_PLUGIN_ID);

$phpdisk_plugin_id = PHPDISK_PLUGIN_ID;

if(file_exists(PHPDISK_ROOT.'includes/phpdisk.auth2.inc.php')){
	require_once PHPDISK_ROOT.'includes/phpdisk.auth2.inc.php';
}elseif(file_exists(PHPDISK_ROOT.'includes/phpdisk.auth1.inc.php')){
	require_once PHPDISK_ROOT.'includes/phpdisk.auth1.inc.php';
}
require_once PHPDISK_ROOT.'includes/phpdisk_version.inc.php';
require_once PHPDISK_ROOT.'includes/global.func.php';
require_once PHPDISK_ROOT.'includes/cache.func.php';

function addslashes_array(&$array,$cv=1) {
	if(is_array($array)){
		foreach($array as $k => $v) {
			$array[$k] = addslashes_array($v,$cv);
		}
	}elseif(is_string($array)){
		if($cv){
			$array = is_utf8() ? addslashes($array) : convert_str('utf-8','gbk',addslashes($array));
		}else{
			$array = addslashes($array);
		}
	}
	return $array;
}

$p_formhash = trim(gpc('formhash','P',''));
$action = trim(gpc('action','GP',''));
$act = trim(gpc('act','GP',''));
$task = trim(gpc('task','GP',''));
$error = false;

parse_str($_SERVER['QUERY_STRING']);
if(in_array($pmod,array('adm_about','adm_ads','adm_ajax','adm_files','adm_nodes','adm_plans','adm_servers','adm_settings','adm_stat','adm_users'))){
	$_GET = addslashes_array($_GET,0);
	$_POST = addslashes_array($_POST,0);
	$_COOKIE = addslashes_array($_COOKIE,0);
}else{
	$_GET = addslashes_array($_GET);
	if($action=='edit_desc' && $_G[charset]=='gbk'){
		$_POST = addslashes_array($_POST,0);
	}else{
		$_POST = addslashes_array($_POST);
	}
	$_COOKIE = addslashes_array($_COOKIE);
}

$sysmsg = $settings = $myset = array();
update_myset();
if($_G[uid]){
	$set = DB::fetch_first("select settings from phpdisk_mini_myset where userid='{$_G[uid]}' limit 1");
	$myset = unserialize($set[settings]);
}

$uid = (int)$_G[uid];
$setting_file = PHPDISK_ROOT.'system/settings.inc.php';
file_exists($setting_file) ? require_once $setting_file : settings_cache();

$page = (int)gpc('page','GP',0);
if($page<1) $page=1;

$formhash = formhash();
$onlineip = get_ip();
$my_sid = session_id();

?>