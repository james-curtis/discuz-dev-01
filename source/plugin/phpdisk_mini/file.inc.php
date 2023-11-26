<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: file.inc.php 27 2014-08-29 12:58:24Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
switch($action){
	case 'bat_del':
		$box_title = lang('plugin/phpdisk_mini','del file');

		$file_ids = trim(gpc('file_ids','GP',''));
		$file_str = get_ids($file_ids);
		if($task=='bat_del'){
			form_auth(gpc('formhash','P',''),formhash());

			if($file_str){
				DB::query("update phpdisk_mini_files set is_del=1 where file_id in ($file_str) and userid='{$_G[uid]}'");
				echo 'true|'.lang('plugin/phpdisk_mini','folder and file del success');
			}else{
				echo lang('plugin/phpdisk_mini','del file fail');
			}
		}else{
			if($file_str){
				$query = DB::query("select file_id,file_name,file_extension from phpdisk_mini_files where userid='$uid' and file_id in ($file_str) order by file_id desc");
				$file_array = array();
				while($rs = DB::fetch($query)) {
					$tmp_ext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
					$rs[file_name] = file_icon($rs[file_extension]).$rs[file_name].$tmp_ext;
					$file_array[] = $rs;
				}
			}
			include template('phpdisk_mini:default/file');
		}
		break;
	case 'bat_move':
		$box_title = lang('plugin/phpdisk_mini','move file');

		$file_ids = trim(gpc('file_ids','GP',''));
		$file_str = get_ids($file_ids);
		if($task=='bat_move'){
			form_auth(gpc('formhash','P',''),formhash());
			$folder_id = (int)gpc('folder_id','GP',0);

			if($file_str){
				DB::query("update phpdisk_mini_files set folder_id='$folder_id' where file_id in ($file_str) and userid='$uid'");
				echo 'true|'.lang('plugin/phpdisk_mini','file move success');
			}else{
				echo lang('plugin/phpdisk_mini','file move fail');
			}
		}else{
			if($file_str){
				$query = DB::query("select file_id,file_name,file_extension from phpdisk_mini_files where userid='$uid' and file_id in ($file_str) order by file_id desc");
				$file_array = array();
				while($rs = DB::fetch($query)) {
					$tmp_ext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
					$rs[file_name] = file_icon($rs[file_extension]).$rs[file_name].$tmp_ext;
					$file_array[] = $rs;
				}
			}
			$myfolder_option = get_folder_option(0,0,0,$uid);
			include template('phpdisk_mini:default/file');
		}
		break;
	case 'del':
		$box_title = lang('plugin/phpdisk_mini','del file');
		$file_id = (int)gpc('file_id','GP',0);
		$am_sql = $_G[adminid]==1 ? "" : "and userid='{$_G[uid]}'";
		if($task=='del'){
			form_auth(gpc('formhash','P',''),formhash());
			if($file_id){
				DB::query("update phpdisk_mini_files set is_del=1 where file_id='$file_id' $am_sql");
				echo 'true|'.lang('plugin/phpdisk_mini','folder and file del success');
			}else{
				echo lang('plugin/phpdisk_mini','del file fail');
			}
		}else{
			$rs = DB::fetch_first("select file_name,file_extension from phpdisk_mini_files where file_id='$file_id' $am_sql ");
			$tmp_ext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
			$file_name = file_icon($rs[file_extension]).$rs[file_name].$tmp_ext;
			include template('phpdisk_mini:default/file');
		}
		break;
	
	case 'file_pwd':
		$box_title = lang('plugin/phpdisk_mini','share pwd');
		$file_id = (int)gpc('file_id','GP',0);
		if($file_id){
			$file = DB::fetch_first("select file_id,file_name,folder_id,file_description,file_extension,in_share,file_credit,is_checked,file_pwd,file_remote_url from phpdisk_mini_files where file_id='$file_id' and userid='{$_G[uid]}' limit 1");
		}
		include template('phpdisk_mini:default/file');
		break;

}

?>
