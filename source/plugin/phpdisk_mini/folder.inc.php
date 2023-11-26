<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: folder.inc.php 32 2014-10-17 06:57:36Z along $
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
	case 'del':
		$box_title = lang('plugin/phpdisk_mini','del folder');
		$folder_id = (int)gpc('folder_id','GP',0);
		if($task=='del'){
			form_auth(gpc('formhash','P',''),formhash());
			if(!is_numeric($folder_id)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','unknown error');
			}else{
				$folder_ids = substr(get_child_folders($folder_id),0,-1);
			}
			if(!$error){
				$file_ids = get_all_files($folder_ids) ? substr(get_all_files($folder_ids),0,-1) : '';
				if($file_ids){
					DB::query("update phpdisk_mini_files set is_del=1 where file_id in ($file_ids) and userid='{$_G[uid]}'");
				}
				DB::query("delete from phpdisk_mini_folders where folder_id in ($folder_ids) and userid='{$_G[uid]}'");
				echo 'true|'.lang('plugin/phpdisk_mini','folder and file del success');
			}else{
				echo $sysmsg;
			}
		}else{
			$folder_name = DB::result_first("select folder_name from phpdisk_mini_folders where userid='$uid' and folder_id='$folder_id'");
			include template('phpdisk_mini:default/folder');
		}
		break;
	case 'edit':
		$box_title = lang('plugin/phpdisk_mini','edit folder');
		$folder_id = (int)gpc('folder_id','GP',0);
		if($task=='edit'){
			form_auth(gpc('formhash','P',''),formhash());
			$parent_id = (int)gpc('parent_id','P',0);
			$folder_name = trim(gpc('folder_name','P',''));
			$folder_description = trim(gpc('folder_description','P',''));

			if($parent_id==$folder_id){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','do not set own folder');
			}
			if(checklength($folder_name,1,150)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','folder name error');
			}else{
				/*$num = DB::result_first("select count(*) from phpdisk_mini_folders where userid='$uid' and folder_name='$folder_name' and folder_id<>$folder_id");
				if($num){
					$error = true;
					$sysmsg = lang('plugin/phpdisk_mini','folder name exist');
				}*/
			}
			if($folder_description && checklength($folder_description,1,255)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','folder desc error');
			}

			if(!$error){
				$ins = array(
				'folder_name'=>$folder_name,
				'folder_description'=>$folder_description,
				'parent_id'=>$parent_id,
				);
				DB::query("update phpdisk_mini_folders set ".sql_array($ins)." where folder_id='$folder_id' and userid='$uid'");
				$sysmsg = lang('plugin/phpdisk_mini','folder edit success');
				echo 'true|'.$sysmsg;
			}else{
				echo $sysmsg;
			}
		}else{
			$p = DB::fetch_first("select * from phpdisk_mini_folders where userid='$uid' and folder_id='$folder_id'");
			$myfolder_option = get_folder_option(0,$p[parent_id],0,$uid);
			include template('phpdisk_mini:default/folder');
		}
		break;
	default:
	case 'add':
		$box_title = lang('plugin/phpdisk_mini','new folder');
		if($task=='add'){
			form_auth(gpc('formhash','P',''),formhash());
			$parent_id = (int)gpc('parent_id','P',0);
			$folder_name = trim(gpc('folder_name','P',''));
			$folder_description = trim(gpc('folder_description','P',''));

			if(checklength($folder_name,1,150)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','folder name error');
			}else{
				/*$num = DB::result_first("select count(*) from phpdisk_mini_folders where userid='$uid' and folder_name='$folder_name'");
				if($num){
					$error = true;
					$sysmsg = lang('plugin/phpdisk_mini','folder name exist');
				}*/
			}
			if($folder_description && checklength($folder_description,1,255)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','folder desc error');
			}

			if(!$error){
				$ins = array(
				'folder_name'=>$folder_name,
				'folder_description'=>$folder_description,
				'parent_id'=>$parent_id,
				'userid'=>$uid,
				'in_time'=>$timestamp,
				);
				DB::query("insert into phpdisk_mini_folders set ".sql_array($ins));
				$sysmsg = lang('plugin/phpdisk_mini','folder add success');
				echo 'true|'.$sysmsg;
			}else{
				echo $sysmsg;
			}
		}else{
			$myfolder_option = get_folder_option(0,0,0,$uid);
			include template('phpdisk_mini:default/folder');
		}
		break;

}

?>
