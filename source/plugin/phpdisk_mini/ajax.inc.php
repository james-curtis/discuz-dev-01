<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: ajax.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ')) {
	exit('[PHPDisk] Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
switch ($action){
	case 'get_myfile':
		$folder_id = (int)gpc('folder_id','GP',0);

		$nav_cp = tree_path($folder_id,1);

		$perpage = 10;
		$sql_do = "phpdisk_mini_files where userid='{$_G[uid]}' and is_del=0 and folder_id='$folder_id'";
		$rs = DB::fetch_first("select count(*) as total_num from {$sql_do}");
		$total_num = $rs[total_num];
		$start_num = ($page-1) * $perpage;
		$q = DB::query("select * from $sql_do order by file_id desc limit $start_num,$perpage");

		$files = array();
		while ($rs = DB::fetch($q)) {
			$rs[file_url] = $_G[siteurl].urr("plugin","id=phpdisk_mini:view&file_id={$rs['file_id']}");
			if($rs[server_oid]>1){
				$rs2 = DB::fetch_first("select server_host,server_dl_host from phpdisk_mini_servers where server_oid='{$rs[server_oid]}' limit 1");
				if($rs2[server_dl_host]){
					$arr = explode(LF,trim($rs2[server_dl_host]));
					$server_host = trim($arr[0]);
				}else{
					$server_host = trim($rs2[server_host]);
				}
				$rs[dl] = $server_host.create_down_url($rs);
			}else{
				$rs[dl] = create_down_url($rs);
			}
			$rs['a_downfile'] = $rs[file_url];
			$tmp_ext = $rs['file_extension'] ? '.'.$rs['file_extension'] : "";
			$rs['file_name_all'] = $rs['file_name'].$tmp_ext;
			$rs['file_name'] = cutstr($rs['file_name'].$tmp_ext,50);
			$rs['file_time_all'] = date('Y-m-d H:i:s',$rs['file_time']);
			$rs['file_time'] = date('Y-m-d',$rs['file_time']);
			$rs['file_size'] = get_size($rs['file_size']);
			$files[] =$rs;
		}
		unset($rs);
		$str = '<div class="ft_wrap_box">'.LF;
		$str .= '<div class="l"><li style="position:fixed; background:#FFFFFF; padding:0 3px; width:176px;">'.lang('plugin/phpdisk_mini','myfolder').'</li><div style="padding:1px;padding-top:18px;">'.get_folder_tree(0,$folder_id).'</div></div>'.LF;
		$str .= '<div class="r">'.LF;
		$str .= '<h2><a href="javascript:;" onclick="get_myfile(0,1)"><img src="'.PHPDISK_PLUGIN_DIR.'/images/disk.gif" align="absmiddle" border="0" />'.lang('plugin/phpdisk_mini','root_folder').'</a>&nbsp;&raquo;&nbsp;'.$nav_cp.' </h2>'.LF;
		if(count($files)){
			$str .= '<div class="fl_list">'.LF;
			$str .= '<div class="f1">'.lang('plugin/phpdisk_mini','file_name').'</div>'.LF;
			$str .= '<div class="f2">'.lang('plugin/phpdisk_mini','file size').'</div>'.LF;
			$str .= '<div class="f3">'.lang('plugin/phpdisk_mini','upload time').'</div>'.LF;
			$str .= '<div class="f4">'.lang('plugin/phpdisk_mini','download').'</div>'.LF;
			$str .= '</div>'.LF;
			$str .= '<div class="clear"></div>'.LF;
			$count = count($files);
			foreach($files as $k => $v){
				$ctn_str = lang('plugin/phpdisk_mini','file_name').':'.$v['file_name_all'].'\r\n'.lang('plugin/phpdisk_mini','file size').':'.$v[file_size].'\r\n'.lang('plugin/phpdisk_mini','file addr').':[url='.$v['a_downfile'].']'.$v['a_downfile'].'[/url]\r\n\r\n';
				$ctn_2 = str_replace(array('"',"'"),'_',$ctn_str);
				$ctn = str_replace(array('"',"'"),'_',str_ireplace('\r\n','<br>',$ctn_str));
				$str .= '<div class="fl_list">'.LF;
				if($v[in_share] && $v[is_checked]==1){
					$str .= '<div class="f1"><a href="javascript:;" onclick="pd_add2editor(\''.$ctn.'\',\''.$ctn_2.'\');" title="'.lang('plugin/phpdisk_mini','add_to_post_tips').'" target="_blank">'.file_icon($v['file_extension']).' <span class="txtgreen">'.$v['file_name_all'].'</span></a></div>'.LF;
				}else{
					$str .= '<div class="f1"><a href="javascript:;" onclick="pd_add2editor(\''.$ctn.'\',\''.$ctn_2.'\');" title="'.lang('plugin/phpdisk_mini','none_share_or_checked').'" target="_blank">'.file_icon($v['file_extension']).' '.$v['file_name_all'].'</a></div>'.LF;
				}

				$str .= '<div class="f2"><span class="txtgray">'.$v[file_size].'</span></div>'.LF;
				$str .= '<div class="f3"><span class="txtgray" title="'.$v[file_time_all].'">'.$v['file_time'].'</span></div>'.LF;
				$str .= '<div class="f4"><a href="'.$v[dl].'" target="_blank" title="'.lang('plugin/phpdisk_mini','down file').'"><img style="padding-top:6px;" src="'.PHPDISK_PLUGIN_DIR.'/images/down_icon.gif" align="absmiddle" border="0"/></a></div>'.LF;
				$str .= '</div>'.LF;
				$str .= '<div class="clear"></div>'.LF;
			}
		}else{
			$str .= '<div class="fl_list" align="center">'.LF;
			$str .= lang('plugin/phpdisk_mini','file not found').LF;
			$str .= '</div>'.LF;
			$str .= '<div class="clear"></div>'.LF;
		}
		$page_nav = multi_ajax('get_myfile',$folder_id,$total_num, $perpage, $page );
		$str .= '<div align="right">'.$page_nav.'</div>';
		$str .= '</div>'.LF;
		$str .= '<div class="clear"></div>'.LF;
		echo $str;
		break;
	case 'add_cate':
		$uid = (int)gpc('uid','P','');
		$cate_name = trim(gpc('cate_name','P',''));
		if(checklength($cate_name,1,150)){
			$error = true;
			$rtn = lang('plugin/phpdisk_mini','folder_name_error');
		}
		$num = DB::result_first("select count(*) from phpdisk_mini_folders where userid='$uid' and folder_name='$cate_name'");
		if($num){
			$error = true;
			$rtn = lang('plugin/phpdisk_mini','folder_name_exists');
		}
		if(!$error){
			$ins = array(
			'folder_name' => $cate_name,
			'userid' => $uid,
			'in_time'=>$timestamp,
			);
			DB::query("insert into phpdisk_mini_folders set ".sql_array($ins)."");
			$id = DB::insert_id();
			echo 'true|'.$id;
		}else{
			echo $rtn;
		}
		break;
	case 'load_cate':
		$uid = (int)gpc('uid','P','');
		$sel_id = (int)gpc('sel_id','P','');

		$str = '<select id="folder_id" name="folder_id" style="width:180px">'.LF;
		$str .= '<option value="0">'.lang('plugin/phpdisk_mini','root_folder2').'</option>'.LF;
		$str .= get_folder_option(0,$sel_id,0,$uid);
		$str .= '</select>';
		echo $str;
		break;
}


?>