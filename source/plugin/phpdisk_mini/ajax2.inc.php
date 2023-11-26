<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: ajax2.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/

if(!defined('IN_DISCUZ')) {
	exit('[PHPDisk] Access Denied');
}
require 'includes/commons.inc.php';

switch ($action){
	case 'down_process':
		$file_id = (int)gpc('file_id','P',0);
		$down_file = gpc('down_file_log','C',0);
		if($auth[pd_auth]){
			if(date('G')=='0'){
				$last_time = @DB::result_first("select intime from phpdisk_mini_downstat order by intime asc limit 1");
				if(date('Ymd')<>date('Ymd',$last_time)){
					DB::query("truncate table phpdisk_mini_downstat");
				}
			}
			if($uid && $settings[reg_user_day_downfile]){
				$rs = DB::fetch_first("select * from phpdisk_mini_downstat where uid='$uid' limit 1");
				if($rs){
					if(date('Ymd')==date('Ymd',$rs[intime])){
						if($rs[downcount]<$settings[reg_user_day_downfile]){
							DB::query("update phpdisk_mini_downstat set downcount=downcount+1 where id='{$rs[id]}'");
						}
					}else{
						$ins = array(
						'ip'=>$onlineip,
						'downcount'=>1,
						'intime'=>$timestamp,
						);
						DB::query("update phpdisk_mini_downstat set ".sql_array($ins)." where id='{$rs[id]}'");
					}
				}else{
					$ins = array(
					'uid'=>$uid,
					'ip'=>$onlineip,
					'downcount'=>1,
					'intime'=>$timestamp,
					);
					DB::query("insert into phpdisk_mini_downstat set ".sql_array($ins));
				}
			}
			if(!$uid && $onlineip && $settings[guest_day_downfile]){
				$rs = DB::fetch_first("select * from phpdisk_mini_downstat where ip='$onlineip' and uid=0 limit 1");
				if($rs){
					if(date('Ymd')==date('Ymd',$rs[intime])){
						if($rs[downcount]<$settings[guest_day_downfile]){
							DB::query("update phpdisk_mini_downstat set downcount=downcount+1 where id='{$rs[id]}'");
						}
					}else{
						$ins = array(
						'ip'=>$onlineip,
						'downcount'=>1,
						'intime'=>$timestamp,
						);
						DB::query("update phpdisk_mini_downstat set ".sql_array($ins)." where id='{$rs[id]}'");
					}
				}else{
					$ins = array(
					'uid'=>$uid,
					'ip'=>$onlineip,
					'downcount'=>1,
					'intime'=>$timestamp,
					);
					DB::query("insert into phpdisk_mini_downstat set ".sql_array($ins));
				}
			}
		}
		DB::query("update phpdisk_mini_files set file_last_view='$timestamp' where file_id='$file_id'");
		if(!$down_file/* && check_download_ok($my_sid,60)*/){
			pd_setcookie('down_file_log',1,120);
			DB::query("update phpdisk_mini_files set file_downs=file_downs+1 where file_id='$file_id'");
		}
		echo 'true';
		break;

	case 'pick_file':
		$file_id = (int)gpc('file_id','P',0);
		$code = trim(gpc('code','P',''));
		if($file_id && $code){
			$num = DB::result_first("select count(*) from phpdisk_mini_files where file_id='$file_id' and file_pwd='$code'");
			if(!$num){
				exit(lang('plugin/phpdisk_mini','pick file fail'));
			}else{
				pd_setcookie('pick_code',$file_id.','.$code);
				echo 'true|'.lang('plugin/phpdisk_mini','pick file success');
			}
		}else{
			exit('Error Param');
		}
		break;
	case 'load_down_addr1':
		$file_id = (int)gpc('file_id','P','');
		if(!$file_id){
			exit('Error ID');
		}
		if($auth[pd_auth]){
			if(date('G')=='0'){
				$last_time = @DB::result_first("select intime from phpdisk_mini_downstat order by intime asc limit 1");
				if(date('Ymd')<>date('Ymd',$last_time)){
					DB::query("truncate table phpdisk_mini_downstat");
				}
			}
			if($uid && $settings[reg_user_day_downfile]){
				$rs = DB::fetch_first("select * from phpdisk_mini_downstat where uid='$uid' limit 1");
				if($rs && date('Ymd')==date('Ymd',$rs[intime]) && $rs[downcount]>=$settings[reg_user_day_downfile]){
					exit('<span class="txtred f14">'.lang('plugin/phpdisk_mini','day_downfile_full').'</span>');
				}
			}
			if(!$uid && $onlineip && $settings[guest_day_downfile]){
				$rs = DB::fetch_first("select * from phpdisk_mini_downstat where ip='$onlineip' and uid=0 limit 1");
				if($rs && date('Ymd')==date('Ymd',$rs[intime]) && $rs[downcount]>=$settings[guest_day_downfile]){
					exit('<span class="txtred f14">'.lang('plugin/phpdisk_mini','day_downfile_full').'</span>');
				}
			}
		}
		$file = DB::fetch_first("select * from phpdisk_mini_files where file_id='$file_id' and is_del=0 limit 1");
		$file['dl'] = create_down_url($file,$file[server_oid]);
		$nodes = get_nodes($file[server_oid]);
		$str = '';
		if(count($nodes)){
			for($i = 0; $i < count($nodes); $i++) {
				if($nodes[$i]['parent_id'] == 0) {
					for($j = 0; $j < count($nodes); $j++) {
						if($nodes[$j]['parent_id'] == $nodes[$i]['node_id']) {
							if(!$file['file_time'] || $timestamp-$file['file_time']<(int)$settings[rsync_time]){
								$server_host = DB::result_first("select server_host from phpdisk_mini_servers where server_oid='$file[server_oid]' limit 1");
								$nodes[$j]['host'] = $server_host;
							}
							if($settings['open_thunder'] && $nodes[$j]['down_type']==1){
								$thunder_url = thunder_encode($nodes[$j]['host'].$file[dl]);
								$str .="<li><a oncontextmenu=ThunderNetwork_SetHref(this) onclick=\"down_process2('{$file[file_id]}');return OnDownloadClick_Simple(this,2,4)\" href=\"javascript:;\" thunderResTitle=\"{$file['file_name']}\" thunderType=\"\" thunderPid=\"{$settings['thunder_pid']}\" thunderHref=\"{$thunder_url}\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
							}elseif($settings['open_flashget'] && $nodes[$j]['down_type']==2){
								$flashget_url = flashget_encode($nodes[$j]['host'].$file[dl],$settings['flashget_uid']);
								$str .="<li><a href=\"javascript:;\" onClick=\"down_process2('{$file[file_id]}');ConvertURL2FG('{$flashget_url}','".$nodes[$j]['host'].$file[dl]."',{$settings['flashget_uid']});return false;\" oncontextmenu=\"Flashget_SetHref(this)\" fg=\"{$flashget_url}\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
							}else{
								$str .="<li><a href=\"".$nodes[$j]['host'].$file[dl]."\" onclick=\"down_process2('{$file[file_id]}');\" target=\"_blank\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
							}
						}
					}
				}
			}
			unset($nodes);
		}else{
			$str = "<li><a href=\"$file[dl]\" onclick=\"down_process2('{$file[file_id]}');\" class=\"pn\"><img src=\"".PHPDISK_PLUGIN_DIR."/images/down_icon.gif\" align=\"absmiddle\" border=\"0\" />".lang('plugin/phpdisk_mini','local down')."</a></li>";
		}
		if($file[file_remote_url]){
			$arr = explode(LF,$file[file_remote_url]);
			for($i=0;$i<count($arr);$i++){
				$arr2 = explode('|',$arr[$i]);
				$str .= "<li><a href=\"{$arr2[1]}\" onclick=\"down_process2('{$file[file_id]}');\" class=\"pn\" target=\"_blank\">".$arr2[0]."</a></li>";
			}
		}
		echo 'true|'.$str;
		break;
	case 'preview_file':
		$file_id = (int)gpc('file_id','G',0);
		if(!$settings[preview_file]){
			exit('Error preview_file');
		}
		if($file_id){
			$file = DB::fetch_first("select * from phpdisk_mini_files where file_id='$file_id'");
			if(!$file){
				exit('Error Param ID');
			}else{
				echo preview_file($file);
			}
		}else{
			exit('Error Param');
		}
		break;
}


?>