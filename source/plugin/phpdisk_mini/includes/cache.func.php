<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: cache.func.php 9 2014-07-30 09:03:00Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/

if(!defined('IN_PHPDISK')) {
	exit('[PHPDisk] Access Denied');
}

function update_myset($settings=''){
	global $_G,$timestamp;
	if($_G[uid]){
		$num = DB::result_first("select count(*) from phpdisk_mini_myset where userid='{$_G[uid]}'");
		if(!$num){
			$ins = array(
			'userid'=>$_G[uid],
			'active_time'=>$timestamp,
			);
			DB::query("insert into phpdisk_mini_myset set ".sql_array($ins));
		}else{
			if(is_array($settings)){
				$ins = array(
				'settings'=>serialize($settings),
				);
				DB::query("update phpdisk_mini_myset set ".sql_array($ins)." where userid='{$_G[uid]}';");
			}
		}
	}
}
function myfolder_cache(){
	global $_G;
	$query = DB::query("select folder_id,folder_name,parent_id from phpdisk_mini_folders where userid='{$_G[uid]}'");
	$arr = array();
	while($rs = DB::fetch($query)) {
		$arr[] = $rs;
	}
	unset($rs);
	return $arr;
}
function myfolder_tree($data, $pid,$making=0){
	global $_G;

	$dir = PHPDISK_ROOT.'./system/cache/'.$_G[uid].'/';
	$cache_file = $dir.'myfolder_tree.php';
	if(!file_exists($cache_file) || $making){
		$html = '';
		foreach($data as $k => $v){
			if($v['parent_id'] == $pid){
				$repeat = get_tree_deep($v['folder_id'])>1 ? str_repeat('&nbsp;&nbsp;',(int)get_tree_deep($v['folder_id'])-1): '';
				$html .= '<option value="'.$v['folder_id'].'" id="fd_'.$v['folder_id'].'">'.$repeat.$v[folder_name].'</option>'.LF;
				$html .= myfolder_tree($data, $v['folder_id'],1);
			}
		}
		$str = "<?php".LF.LF;
		$str .= "// This is PHPDISK auto-generated file. Do NOT modify me.".LF;
		$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
		$str .=	"if(!defined('IN_PHPDISK')){".LF;
		$str .= "\texit('[PHPDisk] Access Denied');".LF;
		$str .= "}".LF.LF;
		$str .= "return ".var_export($html,true).';'.LF;
		$str .= "?>".LF;
		make_dir($dir);
		write_file($cache_file,$str);
		//chmod($cache_file,0777);
		return $html;
	}else{
		return require_once($cache_file);
	}
}
function pd_update_folder_tree(){
	global $_G;
	@unlink(PHPDISK_ROOT.'system/cache/'.$_G[uid].'/myfolder_tree.php');
}
function get_tree_deep($folder_id,$count=0){
	global $_G;
	$count++;
	$parent_id = DB::result_first("select parent_id from phpdisk_mini_folders where folder_id='$folder_id' and userid='{$_G[uid]}'");
	if($parent_id){
		return get_tree_deep($parent_id,$count);
	}else{
		return $count;
	}
}

?>