<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: phpdisk.inc.php 32 2014-10-17 06:57:36Z along $
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
	case 'settings':
		$my_subnav = '<em>&raquo;</em>'.lang('plugin/phpdisk_mini','mydisk settings');
		if($task=='settings'){
			form_auth(gpc('formhash','P',''),formhash());
			$setting = array(
			'frame_mode' => 1,
			'show_subfolder' => 1,
			'upload_in_share' => 1,
			);

			$settings = gpc('setting','P',$setting);

			update_myset($settings);

			$sysmsg = lang('plugin/phpdisk_mini','mydisk settings update success');
			showmessage($sysmsg,'plugin.php?id=phpdisk_mini:phpdisk');
		}else{
			$navtitle = lang('plugin/phpdisk_mini','mydisk settings');
			include template('phpdisk_mini:default/settings');
		}
		break;
	case 'edit_desc':
		$file_id = (int)gpc('file_id','GP',0);
		if(!$file_id){
			showmessage(lang('plugin/phpdisk_mini','error file id'));
			exit;
		}
		$am_sql = $_G[adminid]==1 ? "" : "and userid='{$_G[uid]}'";
		if($task=='edit_desc'){
			form_auth(gpc('formhash','P',''),formhash());
			$ref = trim(gpc('ref','P',''));
			$file_name = trim(gpc('file_name','P',''));
			$folder_id = (int)gpc('folder_id','P',0);
			$file_credit = (int)gpc('file_credit','P',0);
			$in_share = (int)gpc('in_share','P',0);
			$file_pwd = trim(gpc('file_pwd','P',''));
			if($auth[pd_auth]){
				$file_remote_url = trim(gpc('file_remote_url','P',''));
			}else{
				$file_remote_url = '';
			}
			$file_description = trim(gpc('file_description','P',''));


			if(checklength($file_name,1,100)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','file name error');
			}else{
				/*$num = DB::result_first("select count(*) from phpdisk_mini_files where userid='$uid' and file_name='$file_name' and file_id<>'$file_id'");
				if($num){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','file name exists');
				}*/
			}

			if($file_description && checklength($file_description,1,9000)){
				$error = true;
				$sysmsg = lang('plugin/phpdisk_mini','file desc error');
			}else{
				$file_description = preg_replace("/<(\/?i?frame.*?)>/si","",$file_description);
				$file_description = preg_replace("/<(\/?script.*?)>/si","",$file_description);
			}

			$ref = isset($ref) ? $ref : 'plugin.php?id=phpdisk_mini:phpdisk';
			if(!$error){
				$ins = array(
				'file_name'=>$file_name,
				'folder_id'=>$folder_id,
				'in_share'=>$in_share,
				'file_pwd'=>$file_pwd,
				'file_remote_url'=>$file_remote_url,
				'is_checked'=>(int)$settings[share_to_check] ? 0 : 1,
				'file_description'=>$file_description,
				);
				DB::query("update phpdisk_mini_files set ".sql_array($ins)." where file_id='$file_id' $am_sql");
				$sysmsg = lang('plugin/phpdisk_mini','file edit success');
				showmessage($sysmsg,str_replace('&amp;','&',$ref));
			}else{
				showmessage($sysmsg,str_replace('&amp;','&',$ref));
			}
		}else{
			$file = DB::fetch_first("select file_id,file_name,folder_id,file_description,file_extension,in_share,file_credit,is_checked,file_pwd,file_remote_url from phpdisk_mini_files where file_id='$file_id' $am_sql limit 1");
			if(!$file){
				showmessage(lang('plugin/phpdisk_mini','error file id'));
				exit;
			}else{
				$myfolder_option = get_folder_option(0,$file[folder_id],0,$uid);
				$file_status = $file[is_checked] ? '<span class="txtblue">'.lang('plugin/phpdisk_mini','checked file').'</span>' : '<span class="txtred">'.lang('plugin/phpdisk_mini','uncheck file').'</span>';
				$tmp_ext = $file[file_extension] ? '.'.$file[file_extension] : '';
				$file[file_description] = str_replace(array('<br>','<br />'),LF,$file[file_description]);
				$navtitle = lang('plugin/phpdisk_mini','edit file');
				$my_subnav = '<em>&raquo;</em>'.lang('plugin/phpdisk_mini','edit file').' - '.$file[file_name].$tmp_ext;
				$ref = $_SERVER['HTTP_REFERER'];
				include template('phpdisk_mini:default/file_desc');
			}
		}
		break;
	case 'view':
	case 'search':
	default:
		if($action=='search'){
			$navtitle = lang('plugin/phpdisk_mini','search file');
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
			}else{
				$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'plugin.php?id=phpdisk_mini';
				showmessage(lang('plugin/phpdisk_mini','search file error'),$ref);
				exit;
			}
		}else{
			$navtitle = lang('plugin/phpdisk_mini','mydisk');
			$folder_id = (int)gpc('folder_id','GP',0);

			$folder_description = DB::result_first("select folder_description from phpdisk_mini_folders where folder_id='$folder_id' and userid='{$_G[uid]}' limit 1");

			$sql_ext = $folder_id ? " and folder_id='$folder_id' " : " and folder_id='0' ";

			if($myset[show_subfolder]){
				$sub_folder = array();
				$q = DB::query("select folder_name,folder_id,in_time from phpdisk_mini_folders where parent_id='$folder_id' and userid='{$_G[uid]}'");
				while ($rs = DB::fetch($q)) {
					$rs[a_view] = urr("plugin","id=phpdisk_mini:phpdisk&action=view&folder_id=$rs[folder_id]");
					$rs['folder_time'] = date('Y-m-d',$rs['in_time']);
					$sub_folder[] = $rs;
				}
			}
			$nav_cp = tree_path($folder_id,0);
		}

		$perpage = 20;
		$start = ($page-1)*$perpage;
		$file_list = array();
		$query = DB::query("SELECT * FROM phpdisk_mini_files where userid='{$_G[uid]}' and is_del=0 $sql_ext ORDER BY file_time DESC LIMIT $start, $perpage");
		$count = DB::result_first("SELECT count(*) FROM phpdisk_mini_files where userid='{$_G[uid]}' $sql_ext");
		while($rs = DB::fetch($query)) {
			//$rs[a_dl] = create_down_url($rs);
			$rs['file_icon'] = file_icon($rs['file_extension']);
			$tmp_ext = $rs['file_extension'] ? '.'.$rs['file_extension'] : '';
			if($rs[is_checked] && $rs[in_share]){
				$rs[style] = 'txtgreen';
			}else{
				$rs[style] = '';
			}
			$tmp_st = $rs[in_share] ? lang('plugin/phpdisk_mini','shareing') : lang('plugin/phpdisk_mini','unshare');
			$tmp_st2 = $rs[is_checked] ? lang('plugin/phpdisk_mini','checked file') : lang('plugin/phpdisk_mini','uncheck file');
			$rs[a_dl] = urr("plugin","id=phpdisk_mini:down_box&file_id=$rs[file_id]");
			$rs[a_view] = urr("plugin","id=phpdisk_mini:view&file_id=$rs[file_id]");
			$rs['a_share'] = urr("plugin","id=phpdisk_mini:file&action=share&file_id=$rs[file_id]");
			$rs['a_pwd'] = urr("plugin","id=phpdisk_mini:file&action=file_pwd&file_id=$rs[file_id]");
			$rs['a_edit'] = urr("plugin","id=phpdisk_mini:phpdisk&action=edit_desc&file_id=$rs[file_id]");
			$rs['a_del'] = urr("plugin","id=phpdisk_mini:file&action=del&file_id=$rs[file_id]");
			$rs['tips'] = lang('plugin/phpdisk_mini','status').":".$tmp_st.','.$tmp_st2;
			$rs['file_name'] = $rs['file_name'].$tmp_ext;
			$rs['file_time'] = date('Y-m-d',$rs['file_time']);
			$rs['file_size'] = get_size($rs['file_size']);
			$file_list[] = $rs;
		}
		unset($rs);
		if($action=='search'){
			$multi = multi($count, $perpage, $page, "plugin.php?id=phpdisk_mini:phpdisk&action=search&word=".base64_encode($word));
		}else{
			$multi = multi($count, $perpage, $page, "plugin.php?id=phpdisk_mini:phpdisk&folder_id=$folder_id");
		}
		$folder_list = get_folder_tree(0,$folder_id,0);
		$subnav = '<em>&raquo;</em>'.lang('plugin/phpdisk_mini','file view');

		include template('phpdisk_mini:default/phpdisk');
		break;
}

?>
