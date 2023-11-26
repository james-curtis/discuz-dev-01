<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: upload.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';

$uid = (int)gpc('uid','GP',0);
$token = trim(gpc('token','P',''));
$uid = $uid ? $uid : (int)$_G[uid];
$folder_id = (int)gpc('folder_id','P',0);

if($task =='doupload'){
	if($uid){
		$set = DB::fetch_first("select settings from phpdisk_mini_myset where userid='$uid' limit 1");
		$myset = unserialize($set[settings]);
	}

	$token_md5 = md5(md5($uid.$settings[encrypt_key]));
	if(!$uid || $token_md5<>$token){
		echo 'PHPDisk Token Error';
		exit;
	}
	$file = $_FILES['upload_file'];

	$file_real_path = PHPDISK_ROOT.$settings[file_path].'/';
	$file_store_path = date('Y/m/d/');
	make_dir($file_real_path.$file_store_path);

	if(!is_utf8()){
		$file['name'] = convert_str('utf-8','gbk',$file['name']);
	}
	$file_extension = get_extension($file['name']);
	$esp = strlen($file_extension)+1;
	if($file_extension){
		$file_name = substr($file['name'],0,strlen($file['name'])-$esp);
	}else{
		$file_name = $file['name'];
	}
	$file_real_name = md5(uniqid(mt_rand(),true).microtime().$uid);

	$file_ext = get_real_ext($file_extension);
	$dest_file = $file_real_path.$file_store_path.$file_real_name.$file_ext;

	if(upload_file($file['tmp_name'],$dest_file) && chk_extension_ok($file_extension)){
		$ins = array(
		'file_name' => $file_name,
		'file_extension' => $file_extension,
		'file_key' => random(8),
		'file_description' => '',
		'file_store_path' => $file_store_path,
		'file_real_name' => $file_real_name,
		'file_size' => $file[size],
		'file_time' => $timestamp,
		'in_share' => (int)$myset[upload_in_share],
		'userid' => $uid,
		'folder_id' => $folder_id,
		'is_checked' => (int)$settings[share_to_check] ? 0 : 1,
		'ip' => $onlineip,
		);
		DB::query("insert into phpdisk_mini_files set ".sql_array($ins)."");
		//DB::insert('phpdisk_mini_files',$ins);

		$file_id = DB::insert_id();
		//write_file(PHPDISK_ROOT.'system/a.php',var_export($myset,true));
	}else{
		$str = '<?php exit; ?> '."\t".$file[name]."\t".date('Y-m-d H:i:s').LF;
		write_file(PHPDISK_ROOT.'system/upload.log.php',$str,'ab');
	}
	@unlink($file['tmp_name']);
	$a_downfile = $_G[siteurl].urr("plugin","id=phpdisk_mini:view&file_id=$file_id");
	$ctn_str = lang('plugin/phpdisk_mini','file_name').':'.$file['name'].'\r\n'.lang('plugin/phpdisk_mini','file size').':'.get_size($file[size]).'\r\n'.lang('plugin/phpdisk_mini','file addr').':[url='.$a_downfile.']'.$a_downfile.'[/url]\r\n\r\n';
	$ctn_2 = str_replace(array('"',"'"),'_',$ctn_str);
	$ctn = str_replace(array('"',"'"),'_',str_ireplace('\r\n','<br>',$ctn_str));
	$rtn = 'true|'.$ctn.'|'.$ctn_2;
	echo is_utf8() ? $rtn : iconv('gbk','utf-8',$rtn);
}else{
	$max_file_queue = 30;
	if($auth[pd_auth] && $settings[open_multi_server]){
		$rs = DB::fetch_first("select * from phpdisk_mini_servers where is_default=1 limit 1");
		$token = md5(md5($_G[uid].$settings[encrypt_key].$rs[server_key]));
		$upload_url = $rs[server_host].'rc_upload.php';
	}else{
		$token = md5(md5($uid.$settings[encrypt_key]));
		$upload_url = 'plugin.php?id=phpdisk_mini:upload';
	}
	include template('phpdisk_mini:default/upload');
}

?>
