<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: adm_files.inc.php 11 2014-08-04 02:05:15Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('[PHPDISK] Access Denied');
}
require 'includes/commons.inc.php';

$pmod = 'adm_files';
$curr_url = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=$pmod";

switch($act){
	case 'recycle_del':
		if($task=='safe_del' || $task=='nosafe_del'){
			$file_id = (int)gpc('file_id','G',0);
			$msg = '';
			if($file_id){
				$q = DB::query("select * from phpdisk_mini_files where file_id='$file_id' and is_del=1");
				$rs = DB::fetch($q);
				if($rs){
					$tmp_ext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
					$pp = $settings[file_path].'/'.$rs[file_store_path].'/'.$rs[file_real_name].get_real_ext($rs[file_extension]);
					$file_name = $rs[file_name].$tmp_ext;
					if($rs[server_oid]>1){
						$rs2 = DB::fetch_first("select * from phpdisk_mini_servers where server_oid='{$rs[server_oid]}' limit 1");
						if($rs2){
							$msg = '';
							if($rs2[server_dl_host]){
								$arr = explode(LF,$rs2[server_dl_host]);
								for($i=0;$i<count($arr);$i++){
									$msg .= '<script type="text/javascript" src="'.$arr[$i].'/phpdisk_del_process.php?param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]")).'"></script>'.LF;
								}
							}
							$up_del_url = $rs2[server_host].'phpdisk_del_process.php?param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]"));
							$msg .= '<script type="text/javascript" src="'.$up_del_url.'"></script>'.LF;
						}else{
							exit('Error param!');
						}
					}else{
						$str = $_G[siteurl].'plugin.php?id=phpdisk_mini:phpdisk_del_process&param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]"));
						$msg = '<script type="text/javascript" src="'.$str.'"></script>'.LF;
					}
					$del_act = 'one_file';
				}else{
					exit('Error File ID');
				}
			}else{
				$q = DB::query("select * from phpdisk_mini_files where is_del=1 order by file_id desc limit 2");
				$count = DB::result_first("select count(*) from phpdisk_mini_files where is_del=1 order by file_id desc limit 2");
				while($rs = DB::fetch($q)) {
					$tmp_ext = $rs[file_extension] ? '.'.$rs[file_extension] : '';
					$pp = $settings[file_path].'/'.$rs[file_store_path].'/'.$rs[file_real_name].get_real_ext($rs[file_extension]);
					$file_name = $rs[file_name].$tmp_ext;
					$file_id = (int)$rs[file_id];
					if($rs[server_oid]>1){
						$rs2 = DB::fetch_first("select * from phpdisk_mini_servers where server_oid='{$rs[server_oid]}' limit 1");
						if($rs2){
							$msg = '';
							if($rs2[server_dl_host]){
								$arr = explode(LF,$rs2[server_dl_host]);
								for($i=0;$i<count($arr);$i++){
									$msg .= '<script type="text/javascript" src="'.$arr[$i].'/phpdisk_del_process.php?param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]")).'"></script>'.LF;
								}
							}
							$up_del_url = $rs2[server_host].'phpdisk_del_process.php?param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]"));
							$msg .= '<script type="text/javascript" src="'.$up_del_url.'"></script>'.LF;
						}else{
							exit('Error param!');
						}
					}else{
						$str = $_G[siteurl].'plugin.php?id=phpdisk_mini:phpdisk_del_process&param='.rawurlencode(pd_encode("pp=$pp&file_id=$file_id&file_name={$file_name}&task=$task&adminid=$_G[adminid]"));
						$msg = '<script type="text/javascript" src="'.$str.'"></script>'.LF;
					}
				}
				$del_act = 'multi_file';
				if($count){
					echo '<script type="text/javascript">'.LF;
					echo 'setTimeout(function(){'.LF;
					echo 'document.location.reload();'.LF;
					echo '},5000);'.LF;
					echo '</script>'.LF;
				}
			}
			include template("phpdisk_mini:admin/$pmod");
		}else{
			admincp_msg('[phpdisk] Invalid Action!',"$curr_url&act=$act",0);
		}
		break;
	case 'recycle':
	case 'check':
	case 'uncheck':
	default:
		if(in_array($task,array('restore','to_recycle','to_check','to_uncheck','move_oid'))){
			form_auth(gpc('formhash','P',''),formhash());
			$file_ids = gpc('file_ids','P',array());

			$ids_arr = get_ids_arr($file_ids,lang('plugin/phpdisk_mini','please select operate file'));
			if($ids_arr[0]){
				$error = true;
				$sysmsg = $ids_arr[1];
			}else{
				$file_str = $ids_arr[1];
			}
		}
		if($task=='restore'){
			if(!$error){
				DB::query("update phpdisk_mini_files set is_del=0 where file_id in ($file_str)");
				admincp_msg(lang('plugin/phpdisk_mini','file restore success'),"$curr_url&act=$act");
			}else{
				admincp_msg($sysmsg,"$curr_url&act=$act",0);
			}
		}elseif($task =='to_recycle'){
			if(!$error){
				DB::query("update phpdisk_mini_files set is_del=1 where file_id in ($file_str)");
				admincp_msg(lang('plugin/phpdisk_mini','del to recycle success'),"$curr_url&act=$act");
			}else{
				admincp_msg($sysmsg,"$curr_url&act=$act",0);
			}
		}elseif($task =='to_check'){
			if(!$error){
				DB::query("update phpdisk_mini_files set is_checked=1 where file_id in ($file_str)");
				admincp_msg(lang('plugin/phpdisk_mini','check file success'),"$curr_url&act=$act");
			}else{
				admincp_msg($sysmsg,"$curr_url&act=$act",0);
			}
		}elseif($task =='to_uncheck'){
			if(!$error){
				DB::query("update phpdisk_mini_files set is_checked=0 where file_id in ($file_str)");
				admincp_msg(lang('plugin/phpdisk_mini','uncheck file success'),"$curr_url&act=$act");
			}else{
				admincp_msg($sysmsg,"$curr_url&act=$act",0);
			}
		}elseif($task=='move_oid'){
			$server_oid = (int)gpc('server_oid','P',0);
			if(!$error){
				DB::query("update phpdisk_mini_files set server_oid=$server_oid where file_id in ($file_str)");
				admincp_msg(lang('plugin/phpdisk_mini','move oid success'),"$curr_url&act=$act");
			}else{
				admincp_msg($sysmsg,"$curr_url&act=$act",0);
			}
		}else{
			$sql_1 = $act=='recycle' ? " pf.is_del=1 and "  : " pf.is_del=0 and ";
			if($act=='check'){
				$sql_2 = " pf.is_checked=1 and ";
			}elseif($act=='uncheck'){
				$sql_2 = " pf.is_checked=0 and ";
			}else{
				$sql_2 = "";
			}

			$uid = (int)gpc('uid','G',0);
			$sql_ext = ' where';
			if($uid){
				$subject_txt = lang('plugin/phpdisk_mini','username').' '.DB::result_first("select username from ".DB::table('common_member')." where uid='$uid'");
				$sql_ext = " where pf.userid='$uid' and";
			}else{
				$subject_txt = lang('plugin/phpdisk_mini','last file');
			}
			$perpage = 50;
			$start = ($page-1)*$perpage;
			$file_arr = array();
			$query = DB::query("SELECT pf.*,cm.username FROM phpdisk_mini_files pf,".DB::table('common_member')." cm $sql_ext $sql_1 $sql_2 pf.userid=cm.uid ORDER BY file_id DESC LIMIT $start, $perpage");
			$count = DB::result_first("SELECT count(*) FROM phpdisk_mini_files pf,".DB::table('common_member')." cm $sql_ext $sql_1 $sql_2 pf.userid=cm.uid");
			while($rs = DB::fetch($query)) {
				$rs['file_icon'] = file_icon($rs['file_extension']);
				$tmp_ext = $rs['file_extension'] ? '.'.$rs['file_extension'] : '';
				$rs['file_name'] = $rs['file_name'].$tmp_ext;
				$rs[a_space] = "$curr_url&uid=".$rs[userid];
				$rs[file_size] = get_size($rs[file_size]);
				$rs[style] = $rs[in_share] ? 'txtgreen' : '';
				$rs[status] = $rs[is_checked] ? '<span class="txtblue">'.lang('plugin/phpdisk_mini','checked file').'</span>' : '<span class="txtred">'.lang('plugin/phpdisk_mini','uncheck file').'</span>';
				$rs[file_time] = date('Y-m-d H:i:s',$rs[file_time]);
				$rs[real_store] = $rs[file_store_path].$rs[file_real_name].get_real_ext($rs['file_extension']);
				$file_arr[] = $rs;
			}
			unset($rs);
			$multi = multi($count, $perpage, $page, "$curr_url&act=$act&uid=$uid");
			include template("phpdisk_mini:admin/$pmod");
		}
}
function get_servers(){
	$query = DB::query("select server_name,server_oid from phpdisk_mini_servers where server_oid>1 order by server_id asc");
	while($rs = DB::fetch($query)) {
		$arr[] = $rs;
	}
	return $arr;
}
?>