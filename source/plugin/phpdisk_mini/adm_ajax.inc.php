<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: adm_ajax.inc.php 10 2014-08-02 14:34:18Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/

if(!defined('IN_DISCUZ')) {
	exit('[PHPDISK] Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
switch ($action){
	case 'user_count':
		$userid = (int)gpc('userid','P',0);
		if($userid){
			$folder_count = (int)@DB::result_first("SELECT count(*) FROM phpdisk_mini_folders where userid='$userid'");
			$file_count = (int)@DB::result_first("SELECT count(*) FROM phpdisk_mini_files where userid='$userid' and is_del=0");
			echo 'true|'.$folder_count.'|'.$file_count;
		}else{
			echo 'error';
		}
		break;

	case 'get_ss_size':
		$server_oid = (int)gpc('server_oid','P',0);
		$rt = DB::fetch_first("select sum(file_size) as total from phpdisk_mini_files where server_oid='$server_oid'");
		echo 'true|'.get_size($rt[total]);
		break;

	case 'search_host':
		$server_oid = (int)gpc('server_oid','P',0);
		if($server_oid<2){
			exit(lang('plugin/phpdisk_mini','server_oid_not_exists'));
		}
		$rs = DB::fetch_first("select * from phpdisk_mini_servers where server_oid='$server_oid' limit 1");
		if($rs){
			$str = '<select id="sh_id" onchange="sel_host();">'.LF;
			$str .= '<option value="">'.lang('plugin/phpdisk_mini','pls select host').'</option>'.LF;
			if($rs[server_dl_host]){
				$arr = explode(LF,$rs[server_dl_host]);
				for($i=0;$i<count($arr);$i++){
					$str .= '<option value='.rawurlencode($arr[$i]).'>'.$arr[$i].'</option>'.LF;
				}
			}else{
				$str .= '<option value='.rawurlencode($rs[server_host]).'>'.$rs[server_host].'</option>'.LF;
			}
			$str .= '</select>'.LF;
			echo 'true|'.$str;
		}else{
			exit(lang('plugin/phpdisk_mini','server_oid_not_exists'));
		}
		break;
}


?>