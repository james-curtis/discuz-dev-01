<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: down_box.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
$file_id = (int)gpc('file_id','G',0);
if(!$file_id){
	exit('Error ID');
}
$file = DB::fetch_first("select * from phpdisk_mini_files where file_id='$file_id' and userid='{$_G[uid]}' and is_del=0 limit 1");
$file['dl'] = create_down_url($file,$file[server_oid]);
$nodes = get_nodes($file[server_oid]);
$tmp_ext = $file[file_extension] ? '.'.$file[file_extension] : '';
$file_name = file_icon($file[file_extension]).$file[file_name].$tmp_ext;
$a_view = $_G[siteurl].urr("plugin","id=phpdisk_mini:view&file_id=$file_id");
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
						$str .="<li><a oncontextmenu=ThunderNetwork_SetHref(this) onclick=\"return OnDownloadClick_Simple(this,2,4)\" href=\"javascript:;\" thunderResTitle=\"{$file['file_name']}\" thunderType=\"\" thunderPid=\"{$settings['thunder_pid']}\" thunderHref=\"{$thunder_url}\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
					}elseif($settings['open_flashget'] && $nodes[$j]['down_type']==2){
						$flashget_url = flashget_encode($nodes[$j]['host'].$file[dl],$settings['flashget_uid']);
						$str .="<li><a href=\"javascript:;\" onClick=\"ConvertURL2FG('{$flashget_url}','".$nodes[$j]['host'].$file[dl]."',{$settings['flashget_uid']});return false;\" oncontextmenu=\"Flashget_SetHref(this)\" fg=\"{$flashget_url}\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
					}else{
						$str .="<li><a href=\"".$nodes[$j]['host'].$file[dl]."\" target=\"_blank\" class=\"down_btn\"><span>".$nodes[$j]['icon'].$nodes[$j]['subject']."</span></a></li>";
					}
				}
			}
		}
	}
	unset($nodes);
}else{
	$str = "<li><a href=\"{$file[dl]}\" class=\"pn\" target=\"_blank\"><img src=\"".PHPDISK_PLUGIN_DIR."/images/down_icon.gif\" align=\"absmiddle\" border=\"0\" />".lang('plugin/phpdisk_mini','local down')."</a></li>";
}
if($file[file_remote_url]){
	$arr = explode(LF,$file[file_remote_url]);
	for($i=0;$i<count($arr);$i++){
		$arr2 = explode('|',$arr[$i]);
		$str .= "<li><a href=\"{$arr2[1]}\" onclick=\"down_process2('{$file[file_id]}');\" class=\"pn\" target=\"_blank\">".$arr2[0]."</a></li>";
	}
}
include template('phpdisk_mini:default/down_box');


?>
