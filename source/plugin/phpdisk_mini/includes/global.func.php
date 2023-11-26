<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: global.func.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_PHPDISK')) {
	exit('[PHPDisk] Access Denied');
}
function template_echo($tpl,$tpl_dir,$app='',$is_admin_tpl=0){
	if($app){
		$tpl_cache_dir = PHPDISK_ROOT."system/plugins/$app/";
		$tpl_src_dir = PHPDISK_ROOT."plugins/$app/";
	}else{
		$tpl_cache_dir = $tpl_cache_dir_tmp = PHPDISK_ROOT.'system/'.$tpl_dir;
		$tpl_src_dir = PHPDISK_ROOT.$tpl_dir;
		$tpl_default_dir = PHPDISK_ROOT.'templates/default/';
		$admin_tpl_dir = PHPDISK_ROOT.'templates/admin/';
	}
	if(strpos($tpl,'/')!==false){
		$tpl_cache_dir_tmp = $tpl_cache_dir_tmp.substr($tpl,0,strlen($tpl)-strlen(strrchr($tpl,'/'))).'/';
	}
	make_dir($tpl_cache_dir_tmp);
	make_dir($tpl_cache_dir);

	$tpl_cache_file = $tpl_cache_dir.$tpl.'.tpl.php';
	$tpl_src_file = $tpl_src_dir.$tpl.'.tpl.php';
	if(!$app){
		if($is_admin_tpl && !file_exists($tpl_src_file)){
			$tpl_src_file = $admin_tpl_dir.$tpl.'.tpl.php';
		}elseif(!file_exists($tpl_src_file)){
			$tpl_src_file = $tpl_default_dir.$tpl.'.tpl.php';
		}
	}
	if(@filemtime($tpl_cache_file) < @filemtime($tpl_src_file)){
		write_file($tpl_cache_file,template_parse($tpl_src_file));
		return $tpl_cache_file;
	}
	if(file_exists($tpl_cache_file)){
		return $tpl_cache_file;
	}else{
		$str = strrchr($tpl_cache_file,'/');
		$str = substr($str,1,strlen($str));
		die("PHPDisk Template: <b>$tpl_dir$tpl_cache_file</b> not Exists!");
	}

}

function template_parse($tpl){
	global $user_tpl_dir;
	if(!file_exists($tpl)){
		exit('Template ['.$tpl.'] not exists!');
	}
	$str = read_file($tpl);
	$str = preg_replace("/\<\!\-\-\#include (.+?)\#\-\-\>/si","<?php require_once template_echo('\\1','$user_tpl_dir'); ?>", $str);
	$str = preg_replace("/\<\!\-\-\#(.+?)\#\-\-\>/si","<?php \\1 ?>", $str);
	$str = preg_replace("/\{([A-Z_]+)\}/","<?=\\1?>",$str);
	$str = preg_replace("/\{(\\\$[a-z0-9_\'\"\[\]]+)\}/si", "<?=\\1?>", $str);
	$str = preg_replace("/\{\<\?\=(\\\$[a-z0-9_\'\"\[\]]+)\?\>\}/si","{\\1}",$str);
	$str = preg_replace("/\{\#(.+?)\#\}/si","<?=\\1?>", $str);

	$prefix = "<?php ".LF;
	$prefix .= "// This is PHPDISK auto-generated file. Do NOT modify me.".LF.LF;
	$prefix .= "// Cache Time:".date('Y-m-d H:i:s').LF.LF;
	$prefix .= "!defined('IN_PHPDISK') && exit('[PHPDisk] Access Denied');".LF.LF;
	$prefix .= "?>".LF;

	return $prefix.$str;
}
function file_icon($ext,$fd = 'filetype',$align='absmiddle'){
	$icon = PHPDISK_ROOT."images/{$fd}/".$ext.".gif";
	if(file_exists($icon)){
		$img = "<img src='".PHPDISK_PLUGIN_DIR."/images/{$fd}/{$ext}.gif' align='{$align}' border='0' />";
	}else{
		$img = "<img src='".PHPDISK_PLUGIN_DIR."/images/{$fd}/file.gif' align='{$align}' border='0' />";
	}
	return $img;
}
function pd_encode($string, $operation = 'ENCODE',$key = ''){
	global $settings;
	$ckey_length = 4;
	$key = md5($key ? $key : ($settings['encrypt_key'] ? $settings['encrypt_key'] : 'PHPDisk=Rc9o'));

	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d',0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$arr = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $arr[$i] + $rndkey[$i]) % 256;
		$tmp = $arr[$i];
		$arr[$i] = $arr[$j];
		$arr[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $arr[$a]) % 256;
		$tmp = $arr[$a];
		$arr[$a] = $arr[$j];
		$arr[$j] = $tmp;

		$result .= chr(ord($string[$i]) ^ ($arr[($arr[$a] + $arr[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {

		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function encrypt_key($txt, $key) {
	$md5_key = md5($key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($md5_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $md5_key[$ctr++];
	}
	return $tmp;
}
function defend_xss($val){
	return is_array($val) ? $val : htmlspecialchars($val);
}
function gpc($name,$w = 'GPC',$default = '',$d_xss=1){
	$i = 0;
	for($i = 0; $i < strlen($w); $i++) {
		if($w[$i] == 'G' && isset($_GET[$name])) return $d_xss ? defend_xss($_GET[$name]) : $_GET[$name];
		if($w[$i] == 'P' && isset($_POST[$name])) return $d_xss ? defend_xss($_POST[$name]) : $_POST[$name];
		if($w[$i] == 'C' && isset($_COOKIE[$name])) return $d_xss ? defend_xss($_COOKIE[$name]) : $_COOKIE[$name];
	}
	return $default;
}
function get_size($s,$u='B',$p=2){
	$us = array('B'=>'K','K'=>'M','M'=>'G','G'=>'T');
	return (($u!=='B')&&(!isset($us[$u]))||($s<1024))?(number_format($s,$p)." $u"):(get_size($s/1024,$us[$u],$p));
}
function get_byte_value($v){
	$v = trim($v);
	$l = strtolower($v[strlen($v) - 1]);
	switch($l){
		case 'g':
			$v *= 1024;

		case 'm':
			$v *= 1024;

		case 'k':
			$v *= 1024;
	}
	return $v;
}
function ifselected($int1,$int2,$type = 'int'){
	if($type == 'int'){
		if(intval($int1) == intval($int2)){
			return ' selected';
		}
	}elseif($type == 'str'){
		if(strval($int1) == strval($int2)){
			return ' selected';
		}
	}
}
function ifchecked($int1,$int2,$type = 'int'){
	if($type == 'int'){
		if(intval($int1) == intval($int2)){
			return ' checked';
		}
	}elseif($type == 'str'){
		if(strval($int1) == strval($int2)){
			return ' checked';
		}
	}
}
function urr($str,$vars){
	global $settings;
	if($settings['open_rewrite']){
		switch($str){
			case 'space':
				parse_str($vars);
				return 'space-'.rawurlencode($username).'.html';
				break;
			case 'plugin':
				parse_str($vars);
				if($id=='phpdisk_mini:view'){
					return "view-$file_id.html";
				}elseif($id=='phpdisk_mini:search' && !$word){
					return 'search.html';
				}else{
					return $vars ? $str.'.php?'.$vars : $str.'.php';
				}
				break;
			default:
				return $vars ? $str.'.php?'.$vars : $str.'.php';
		}
	}else{
		return $vars ? $str.'.php?'.$vars : $str.'.php';
	}
}

function form_auth($p_formhash,$formhash){
	if($p_formhash != $formhash){
		exit('phpdisk system error!');
	}
}

function checklength($str,$min,$max){
	if(!$str || strlen($str) > $max || strlen($str) < $min){
		return true;
	}
}
function checkemail($email) {
	if((strlen($email) > 6) && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
		return true;
	}else{
		return false;
	}
}

function make_dir($path,$write_file=1){
	if(!is_dir($path)){
		$str = dirname($path);
		if($str){
			make_dir($str.'/');
			@mkdir($path,0777);
			chmod($path,0777);
			if($write_file){
				write_file($path.'index.htm','PHPDisk');
			}
		}
	}
}
function get_child_folders($folder_id){
	global $_G;
	$str = $folder_id.',';
	if($folder_id){
		$q = DB::query("select folder_id from phpdisk_mini_folders where parent_id='$folder_id' and userid={$_G[uid]}");
		while($rs = DB::fetch($q)){
			$str .= $rs[folder_id] ? $rs[folder_id].',' : '';
			$q2 = DB::query("select folder_id from phpdisk_mini_folders where parent_id='{$rs[folder_id]}' and userid={$_G[uid]}");
			while($rs2 = DB::fetch($q2)){
				if($rs2[folder_id]){
					$str .= get_child_folders($rs2[folder_id]);
				}
			}
			unset($rs2);
		}
		unset($rs);
	}
	return $str;
}
function get_all_files($folder_ids){
	global $_G;
	$str = '';
	if($folder_ids){
		$q = DB::query("select file_id from phpdisk_mini_files where folder_id in ($folder_ids) and userid={$_G[uid]}");
		while($rs = DB::fetch($q)){
			$str .= $rs[file_id].',';
		}
		unset($rs);
	}
	return $str;
}
function login_auth($uid){
	global $_G,$auth;
	if($auth[pd_auth] && !in_array($_G[groupid],unserialize($_G['cache']['plugin']['phpdisk_mini']['use_disk_gid']))){
		showmessage(lang('plugin/phpdisk_mini','your group no power'),$_G[siteurl]);
		exit;
	}
	if(!$uid){
		header('Location: '.$_G[siteurl].'member.php?mod=logging&action=login&referer='.$_G[siteurl].'plugin.php?id=phpdisk_mini:phpdisk');
		exit;
	}
}
function filter_name($name){
	$srh = array('&');
	return str_replace($srh,'_',$name);
}
function get_down_status($ext){
	$arr = array('exe','zip','rar','7z','mp3','wma','wav','img','wmv','rm','rmvb','ape','flv','mp4','3gp');
	return in_array($ext,$arr);
}
function convert_str($in,$out,$str){
	if(function_exists("iconv")){
		$str = iconv($in,$out,$str);
	}elseif(function_exists("mb_convert_encoding")){
		$str = mb_convert_encoding($str,$out,$in);
	}
	return $str;
}
function is_utf8(){
	global $_G;
	return (strtolower($_G[charset]) == 'utf-8') ? true : false;
}
function is_windows(){
	return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 1 : 0;
}
function get_extension($name){
	return strtolower(trim(strrchr($name, '.'), '.'));
}
function get_real_ext($file_extension){
	$filter_extension = 'asp,asa,aspx,ascx,dtd,xsd,xsl,xslt,as,wml,java,vtm,vtml,jst,asr,php,php3,php4,php5,vb,vbs,jsf,jsp,pl,cgi,js,html,htm,xhtml,xml,css,shtm,cfm,cfml,shtml,bat,sh';
	$file_extension = trim($file_extension);
	if($file_extension){
		$exts = explode(',',$filter_extension);
		if(in_array($file_extension,$exts)){
			$file_ext = '.'.$file_extension.'.txt';
		}else{
			$file_ext = '.'.$file_extension;
		}
	}else{
		$file_ext = '.txt';
	}
	return $file_ext;
}
function add_real_ext($file_extension){
	$filter_extension = 'asp,asa,aspx,ascx,dtd,xsd,xsl,xslt,as,wml,java,vtm,vtml,jst,asr,php,php3,php4,php5,vb,vbs,jsf,jsp,pl,cgi,js,html,htm,xhtml,xml,css,shtm,cfm,cfml,shtml,bat,sh';
	$file_ext = '';
	$file_extension = trim($file_extension);
	if($file_extension){
		$exts = explode(',',$filter_extension);
		if(in_array($file_extension,$exts)){
			$file_ext = '.txt';
		}else{
			$file_ext = '';
		}
	}
	return $file_ext;
}
function get_ip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = addslashes($onlineip);
	@preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	unset($onlineipmatches);
	return $onlineip;
}
function sql_array ($arr){
	$ins = array();
	reset($arr);
	while(list($c, $v) = each($arr)){
		$ins[] = ($v === NULL ? sprintf('`%s`=NULL', $c) : sprintf('`%s`=\'%s\'', $c, $v));
	}
	return implode(', ', $ins);
}
function settings_cache($arr=0){
	global $settings;
	if(is_array($arr)){
		foreach($arr as $k => $v){
			$v = str_replace(array("'",'\\'),'',$v);
			$sqls .= "('$k','".trim($v)."'),";
		}
		$sqls = substr($sqls,0,-1);
		DB::query("replace into phpdisk_mini_settings (vars,value) values $sqls;");
	}
	$q = DB::query("select * from phpdisk_mini_settings order by vars");
	while($rs = DB::fetch($q)){
		$str_c .= "\t'".$rs['vars']."' => '".$rs['value']."',".LF;
	}

	$str = "<?php".LF.LF;
	$str .= "// This is PHPDISK auto-generated file. Do NOT modify me.".LF;
	$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
	$str .= "\$settings = array(".LF;
	$str .= $str_c;
	$str .= ");".LF.LF;
	$str .= "?>".LF;

	write_file(PHPDISK_ROOT."./system/settings.inc.php",$str);
}
function show_ads($pos){
	global $settings;
	echo $settings[$pos] ? stripslashes(base64_decode($settings[$pos])) : '';
}
function ip_encode($ip){
	global $G;
	if($G[adminid]==1){
		return $ip;
	}else{
		$arr = explode('.',$ip);
		for($i=0;$i<count($arr)-1;$i++){
			return $arr[0].'.*.*.*';
		}
	}
}
function pd_setcookie($var, $value, $expires = 0,$cookiepath = '/') {
	global $timestamp,$settings;
	$cookie_domain = $settings['cookie_domain'] ? '.'.$settings['cookie_domain'] : '';
	setcookie($var, $value,$expires ? ($timestamp + $expires) : 0,$cookiepath,$cookie_domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function read_file($f) {
	if (file_exists($f)) {
		if (PHP_VERSION >= "4.3.0") return file_get_contents($f);
		$fp = fopen($f,"rb");
		$fsize = filesize($f);
		$c = fread($fp, $fsize);
		fclose($fp);
		return $c;
	} else{
		exit("<b>$f</b> does not exist!");
	}
}

function write_file($f,$str,$mode = 'wb') {
	$fp = fopen($f,$mode);
	if (!$fp) {
		exit("Can not open file <b>$f</b> .code:1");
	}
	if(is_writable($f)){
		if(!fwrite($fp,$str)){
			exit("Can not write file <b>$f</b> .code:2");
		}
	}else{
		exit("Can not write file <b>$f</b> .code:3");
	}
	fclose($fp);
}
function upload_file($source, $target) {
	if (function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)) {
		@chmod($target, 0666);
		return $target;
	} elseif (@copy($source, $target)) {
		@chmod($target, 0666);
		return $target;
	} elseif (@is_readable($source)) {
		if ($fp = @fopen($source,'rb')) {
			@flock($fp,2);
			$filedata = @fread($fp,@filesize($source));
			@fclose($fp);
		}
		if ($fp = @fopen($target, 'wb')) {
			@flock($fp, 2);
			@fwrite($fp, $filedata);
			@fclose($fp);
			@chmod ($target, 0666);
			return $target;
		} else {
			return false;
		}
	}
}
function tree_path($folder_id,$ajax=0){
	global $_G;
	$str = '';
	$q = DB::query("select parent_id,folder_name,folder_id from phpdisk_mini_folders where folder_id='$folder_id' and userid='{$_G[uid]}'");
	if($rs = DB::fetch($q)){
		if($rs['parent_id']!=0){
			$str .= tree_path($rs['parent_id'],$ajax);
		}
		if($ajax){
			$str .= '<a href="javascript:;" onclick="get_myfile('.$folder_id.',1)"><img src="'.PHPDISK_PLUGIN_DIR.'/images/folder_open.gif" align="absmiddle" border="0" />'.$rs['folder_name'].'</a>&nbsp;&raquo;&nbsp;';
		}else{
			$str .= '<a href="'.urr("plugin","id=phpdisk_mini:phpdisk&action=view&folder_id=$folder_id").'"><img src="'.PHPDISK_PLUGIN_DIR.'/images/folder_open.gif" align="absmiddle" border="0" />'.$rs['folder_name'].'</a>&nbsp;&raquo;&nbsp;';
		}
	}
	unset($rs);
	return $str;
}
function get_folder_option($pid=0,$selID=0,$lv=0,$uid){
	$sql_do = $uid ? "where userid='$uid'" : '';
	$q = DB::query("select folder_id,folder_name,parent_id from phpdisk_mini_folders $sql_do order by folder_id asc");
	while ($rs = DB::fetch($q)) {
		$data[] = $rs;
	}
	unset($rs);
	if(count($data)){
		$html = '';
		foreach($data as $v){
			if($v['parent_id'] == $pid){
				$html .= '<option value="'.$v[folder_id].'"';
				$html .= $selID ? ifselected($selID,$v[folder_id]) : '';
				$html .= '>'.str_repeat('&nbsp;',$lv*2).$v[folder_name].'</option>'.LF;
				$lv++;
				$html .= get_folder_option($v['folder_id'],$selID,$lv,$uid);
				$lv--;
			}
		}
		return $html;
	}else{
		return '';
	}
}
function admincp_msg($msg,$go_url,$success=1,$timeout=2000){
	$ext = $success ? 'infotitle2' : 'infotitle3';
	if(is_array($msg)){
		$msgt = '';
		for($i=0;$i<count($msg);$i++){
			$msgt .= '<li>'.$msg[$i].'</li>'.LF;
		}
	}else{
		$msgt =$msg;
	}
	echo "<div class='infobox'><h4 class='$ext'>$msgt</h4><p class='marginbot'><a href='$go_url' class='lightlink'>".lang('plugin/phpdisk_mini','browser redirect')."</a></p><script type='text/JavaScript'>setTimeout('redirect(\"$go_url\");', $timeout);</script></div>";
}
function is_mobile(){
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') || strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'applewebkit')){
		return true;
	}else{
		return false;
	}
}
function check_download_ok($my_sid,$interval_time){
	global $onlineip,$timestamp;
	$rtn = false;
	DB::query("delete from phpdisk_mini_downlog where $timestamp-intime>3*86400");
	$rs = DB::fetch_first("select * from phpdisk_mini_downlog where hash='$my_sid' or ip='$onlineip'");
	if(!$rs[intime]){
		$ins = array(
		'hash'=>$my_sid,
		'ip'=>$onlineip,
		'intime'=>$timestamp,
		);
		DB::query("insert into phpdisk_mini_downlog set ".sql_array($ins));
		$rtn = true;
	}else{
		$interval_time = $interval_time ? $interval_time : 600;
		if($timestamp-$rs[intime]>$interval_time){
			DB::query("update phpdisk_mini_downlog set intime='$timestamp' where id='{$rs[id]}'");
			$rtn = true;
		}
		DB::query("update phpdisk_mini_downlog set downcount=downcount+1 where id='{$rs[id]}'");
	}
	return $rtn;
}
function get_nodes($server_oid){
	$q = DB::query("select * from phpdisk_mini_nodes where server_oid='$server_oid' and is_hidden=0 order by show_order asc,node_id asc");
	$nodes = array();
	while ($rs = DB::fetch($q)) {
		$rs[icon] = $rs[icon] ? '<img src="'.$rs[icon].'" align="absmiddle" border="0" />' : '&nbsp;';
		$rs[host] = $rs[host] ? $rs[host] : 'http://www.phpdisk.com/';
		$nodes[] = $rs;
	}
	unset($rs);
	return $nodes;
}
function get_system_upload_max_size($curr_disk=1){
	global $settings;
	$upload_max = get_byte_value(ini_get('upload_max_filesize'));
	$post_max = get_byte_value(ini_get('post_max_size'));
	$settings_max = $settings['max_file_size'] ? get_byte_value($settings['max_file_size']) : 0;
	$max_php_file_size = min($upload_max, $post_max);
	if($curr_disk){
		$max_file_size_byte = ($settings_max && $settings_max <= $max_php_file_size) ? $settings_max : $max_php_file_size;
		return get_size($max_file_size_byte,'B',0);
	}else{
		$max_file_size_byte = ($settings_max && $settings_max <= $max_php_file_size) ? $settings_max : $max_php_file_size;
		return get_size($max_php_file_size,'B',0);
	}
}
function get_ids_arr($arr,$msg,$str_in_db=0){
	$error = 0;
	if(!count($arr)){
		$error = 1;
		$strs = $msg;
	}else{
		for($i=0;$i<count($arr);$i++){
			if(is_numeric($arr[$i])){
				$strs .= $str_in_db ? (int)$arr[$i]."," : "'".(int)$arr[$i]."',";
			}
		}
		$strs = substr($strs,0,-1);
	}
	return array($error,$strs);
}
function get_ids($str){
	$str2 = '';
	if($str){
		$arr = explode(',',$str);
		for($i=0;$i<count($arr);$i++){
			if(is_numeric($arr[$i])){
				$str2 .= (int)$arr[$i].',';
			}
		}
		return $str2 ? substr($str2,0,-1) : '';
	}else{
		return $str2;
	}
}
if(!function_exists('dir_writeable')){
	function dir_writeable($dir) {
		if(!is_dir($dir)) {
			@mkdir($dir, 0777);
		}
		if(is_dir($dir)) {
			if($fp = @fopen("$dir/phpdisk.test", 'w')) {
				@fclose($fp);
				@unlink("$dir/phpdisk.test");
				$writeable = 1;
			} else {
				$writeable = 0;
			}
		}
		return $writeable;
	}
}
function get_user_file_size($gid){
	global $db,$tpf,$settings;
	if($gid){
		$group_set = $db->fetch_one_array("select * from {$tpf}groups where gid='$gid'");
		$upload_max = get_byte_value(ini_get('upload_max_filesize'));
		$post_max = get_byte_value(ini_get('post_max_size'));
		$settings_max = $settings['max_file_size'] ? get_byte_value($settings['max_file_size']) : 0;
		$max_php_file_size = min($upload_max, $post_max);
		$max_file_size_byte = ($settings_max && $settings_max <= $max_php_file_size) ? $settings_max : $max_php_file_size;
		if($group_set['max_filesize']){
			$group_set_max_file_size = get_byte_value($group_set['max_filesize']);
			$max_file_size_byte = ($group_set_max_file_size >=$max_file_size_byte) ? $max_file_size_byte : $group_set_max_file_size;
		}
		return get_size($max_file_size_byte,'B',0);
	}else{
		return '80 M';
	}
}
function create_down_url($file,$remote=0){
	global $_G,$settings,$auth,$timestamp,$onlineip;
	$file[file_url] = $_G[siteurl].urr("plugin","id=phpdisk_mini:view&file_id=$file[file_id]");
	$pp = $file['file_store_path'].$file['file_real_name'].get_real_ext($file['file_extension']);
	$fs = $file['file_size'];
	$hash = strtoupper(md5($file['file_id'].'_'.$file['file_size'].'_'.$file['file_store_path'].$file['file_real_name']));
	$tmp_ext = $file['file_extension'] ? '.'.$file['file_extension'] : "";
	$p_filename = filter_name($file['file_name'].$tmp_ext);
	$expire_time = $settings[dl_expire_time] ? ($settings[dl_expire_time]+$timestamp) : 0;
	$hash = $auth[pd_auth] ? md5($_SERVER['HTTP_USER_AGENT'].$onlineip) : '';
	if($remote){
		return urr("dl",'param='.rawurlencode(base64_encode(pd_encode("file_name=$p_filename&file_id={$file['file_id']}&fs=$fs&pp=$pp&expire_time=$expire_time&url=$file[file_url]&hash=$hash"))));
	}else{
		return urr("plugin","id=phpdisk_mini:dl&param=".rawurlencode(base64_encode(pd_encode("file_name=$p_filename&file_id={$file['file_id']}&fs=$fs&pp=$pp&expire_time=$expire_time&url=$file[file_url]&hash=$hash"))));
	}
}
function get_folder_tree($pid=0,$selID=0,$ajax=1,$lv=0,$folder_id=0){
	global $_G,$settings;
	$query = DB::query("select * from phpdisk_mini_folders where userid='{$_G[uid]}' order by folder_id asc");
	while ($rs = DB::fetch($query)) {
		$data[] = $rs;
	}
	unset($rs);
	if(count($data)){
		$html = '';
		foreach($data as $v){
			if($v['parent_id'] == $pid){
				$v[folder_name] = htmlspecialchars($v['folder_name']);
				$v[a_edit] = urr("plugin","id=phpdisk_mini:folder&action=edit&folder_id=$v[folder_id]");
				$v[a_del] = urr("plugin","id=phpdisk_mini:folder&action=del&folder_id=$v[folder_id]");
				$html .= '<li title="'.$v['folder_name'].'"';
				$html .= $selID==$v[folder_id] ? ' class="sel_li"' : '';
				if($ajax){
					$html .= '>'.str_repeat('&nbsp;',$lv*2).'<a href="javascript:;" onclick="get_myfile('.$v[folder_id].',1)">';
				}else{
					$html .= '><span style="float:right;display:none"><a href="javascript:;" onclick="showWindow(\'phpdisk_pbox\', \''.$v[a_edit].'\',\'get\',1);" title="'.lang('plugin/phpdisk_mini','edit').'"><img src="'.PHPDISK_PLUGIN_DIR.'/images/ico_edit.png" align="absmiddle" border="0" /></a>
					<a href="javascript:;" onclick="showWindow(\'phpdisk_pbox\', \''.$v[a_del].'\',\'get\',1);" title="'.lang('plugin/phpdisk_mini','del').'"><img src="'.PHPDISK_PLUGIN_DIR.'/images/ico_del.gif" align="absmiddle" border="0" /></a></span>'.str_repeat('&nbsp;',$lv*2).'<a href="'.urr("plugin","id=phpdisk_mini:phpdisk&action=view&folder_id=$v[folder_id]").'">';
				}
				$img = $selID==$v[folder_id] ? 'folder_open' : 'folder';
				$html .= '<img src="'.PHPDISK_PLUGIN_DIR.'/images/'.$img.'.gif" align="absmiddle" border="0"/>'.$v[folder_name].'</a></li>';
				$lv++;
				$html .= get_folder_tree($v['folder_id'],$selID,$ajax,$lv,$folder_id);
				$lv--;
			}
		}
		return $html;
	}else{
		return '<li>'.lang('plugin/phpdisk_mini','no folder').'</li>';
	}
}
function multi_ajax($action,$id,$total, $perpage, $curpage ) {
	$multipage = '';
	//$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($total > $perpage) {
		$pg = 10;
		$offset = 5;
		$pgs = @ceil($total / $perpage);
		if($pg > $pgs) {
			$from = 1;
			$to = $pgs;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $page- $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$to = $pg;
				}
			} elseif($to > $pgs) {
				$from = $curpage - $pgs + $to;
				$to = $pgs;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$from = $pgs - $pg + 1;
				}
			}
		}

		$multipage = ($curpage - $offset > 1 && $pgs > $pg ? '<a href="javascript:;" onclick="'.$action.'('.$id.',1);" class="p_redirect">&laquo;</a>' : '').($curpage > 1 ? '<a href="javascript:;" onclick="'.$action.'('.$id.','.($curpage - 1).');" class="p_redirect">&#8249;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<span class="p_curpage">'.$i.'</span>' : '<a href="javascript:;" onclick="'.$action.'('.$id.','.$i.');" class="p_num">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pgs ? '<a href="javascript:;" onclick="'.$action.'('.$id.','.($curpage + 1).');" class="p_redirect">&#8250;</a>' : '').($to < $pgs ? '<a href="javascript:;" onclick="'.$action.'('.$id.','.$pgs.');" class="p_redirect">&raquo;</a>' : '');
		$multipage = $multipage ? '<div class="p_bar">&nbsp;<span class="p_info">Total:&nbsp;<b>'.$total.'</b>&nbsp;</span>'.$multipage.'</div>' : '';
	}
	return $multipage;
}
function preview_file($file){
	global $settings;
	$rtn = '';
	if(!$settings[preview_file] || !$file[is_checked] || !$file[in_share]){
		exit('Error preview_file');
	}
	$file[real_file] = PHPDISK_ROOT.$settings[file_path].'/'.$file[file_store_path].$file[file_real_name].get_real_ext($file[file_extension]);

	if(in_array($file[file_extension],array('txt','text','bat','sh','htm','html','asp','php','jsp','java','js','css'))){
		$handle = @fopen ($file[real_file], "rb");
		if($handle){
			$rtn = @fread($handle, 300);
		}else{
			exit('Open file error!');
		}
		@fclose ($handle);
	}elseif(in_array($file[file_extension],array('png','jpg','jpeg','gif','bmp'))){
		$rtn = '<img src="'.$file[real_file].'" id="file_thumb" onload="resize_img(\'file_thumb\',400,300);" border="0">';
	}elseif(in_array($file[file_extension],array('zip'))){
		$rtn .= '<style type="text/css">
body{color:#444444;font-size:12px;padding:5px;font-family:Verdana, Arial, Helvetica, sans-serif;}
.tit{ margin-bottom:15px;font-size:14px;font-weight:700;}
li{list-style-type:none;border-bottom:1px dotted #efefef;height:22px;line-height:22px;padding:3px 0;}
</style>';
		include_once PHPDISK_PLUGIN_DIR.'/includes/zip.class.php';
		$zip = new zip;
		$arr = $zip->get_list($file[real_file]);
		foreach($arr as $v){
			if($v[folder]){
				$rtn .= '<li><span class="txtgray" style="float:right">-</span><img src="'.PHPDISK_PLUGIN_DIR.'/images/folder_open.gif" align="absmiddle" />'.substr($v[filename],0,-1).'</li>';
			}else{
				preg_match_all('/\//', $v[filename], $matches);
				$space = str_repeat('&nbsp;',count($matches[0])*3);
				$rtn .= '<li><span class="txtgray" style="float:right">'.get_size($v[size]).'</span>'.$space.file_icon(get_extension($v[filename])).$v[filename].'</li>';
			}
		}
	}elseif(in_array($file[file_extension],array('torrent'))){
		$rtn .= '<style type="text/css">
body{color:#444444;font-size:12px;padding:5px;font-family:Verdana, Arial, Helvetica, sans-serif;}
.tit{ margin-bottom:15px;font-size:14px;font-weight:700;}
.fn{margin-bottom:8px;}
</style>';
		include_once PHPDISK_PLUGIN_DIR.'/includes/BDecode.php';
		$arr = BDecode(read_file($file[real_file]));
		$name = is_utf8() ? $arr[info][name] : convert_str('utf-8','gbk',$arr[info][name]);
		$rtn .= '<div class="tit">'.file_icon($file[file_extension]).$name.'</div>';
		$f = $arr[info][files];
		$f2 = $arr[info];
		if(count($f)){
			foreach ($f as $v){
				$file_icon = file_icon(get_extension($v[path][count($v[path])-1]));
				$name = is_utf8() ? $v[path][count($v[path])-1] : convert_str('utf-8','gbk',$v[path][count($v[path])-1]);
				$rtn .= '<div class="fn">'.$file_icon.$name.'&nbsp;&nbsp;<span title="'.lang('plugin/phpdisk_mini','file size').'">('.str_replace(' ','',get_size($v[length])).')</span></div>';
			}
		}elseif(count($f2)){
			$file_icon = file_icon(get_extension($f2[name]));
			$name = is_utf8() ? $f2[name] : convert_str('utf-8','gbk',$f2[name]);
			$rtn .= '<div class="fn">'.$file_icon.$name.'&nbsp;&nbsp;<span title="'.lang('plugin/phpdisk_mini','file size').'">('.str_replace(' ','',get_size($f2[length])).')</span></div>';
		}
	}elseif(in_array($file[file_extension],array('mp3'))){
		$rtn = '<script type="text/javascript" src="'.PHPDISK_PLUGIN_DIR.'/includes/js/audio-player.js"></script>';
		$rtn .= '<script type="text/javascript">  ';
		$rtn .= '	AudioPlayer.setup("'.PHPDISK_PLUGIN_DIR.'/includes/js/audio-player.swf", {   ';
		$rtn .= '		width: 500,';
		$rtn .= '		transparentpagebg: "yes"      ';
		$rtn .= '	});   ';
		$rtn .= '</script>  ';
		$rtn .= '<p id="audioplayer_1">audioplayer online</p>  ';
		$rtn .= '<script type="text/javascript">  ';
		$rtn .= 'AudioPlayer.embed("audioplayer_1", {soundFile: "'.$file[real_file].'",titles: "'.$file['file_name'].'.'.$file[file_extension].'"});';
		$rtn .= '</script>';
	}else{
		$rtn = lang('plugin/phpdisk_mini','no_preview_info');
	}
	return $rtn;
}
function filter_search($str){
	return str_replace(array('%'),'',$str);
}
function no_html($str){
	return str_ireplace(array('&amp;','&nbsp;',"\r","\n","\t",' '),'',preg_replace("/<[\/\!]*?[^<>]*?>/si", '',$str));
}
function chk_extension_ok($ext){
	global $settings;
	$can_upload = false;
	if($settings[deny_extension]){
		$arr = explode(',',$settings[deny_extension]);
		if(in_array($ext,$arr)){
			$can_upload = true;
		}
	}
	if($settings[upload_file_status]){
		return $can_upload;
	}else{
		return !$can_upload;
	}
}
?>