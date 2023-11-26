<?php 
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: dl.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
error_reporting(0);
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define('OS_WIN',true);
	define('LF',"\r\n");
}else{
	define('OS_WIN',false);
	define('LF',"\n");
}
$timestamp = time();
define('PHPDISK_ROOT', dirname(__FILE__).'/');
define('IN_PHPDISK',TRUE);
define('PHPDISK_PLUGIN_ID','phpdisk_mini');
define('PHPDISK_PLUGIN_DIR','source/plugin/'.PHPDISK_PLUGIN_ID);
$setting_file = PHPDISK_ROOT.'system/settings.inc.php';
file_exists($setting_file) && require_once $setting_file;
if(file_exists(PHPDISK_ROOT.'includes/phpdisk.auth2.inc.php')){
	require_once PHPDISK_ROOT.'includes/phpdisk.auth2.inc.php';
}
define('FILE_PATH',$settings[file_path]);

@set_time_limit(0);
@ignore_user_abort(true);
@set_magic_quotes_runtime(0);

function check_ref(){
	global $settings;
	$arr = explode('/',$_SERVER['HTTP_REFERER']);
	$arr2 = explode('/',$settings[phpdisk_url]);
	if($_SERVER['HTTP_HOST']!='localhost'){
		if(!$_SERVER['HTTP_REFERER'] || $arr[2]!=$arr2[2]){
			header('Location: '.$settings[phpdisk_url]);
			exit;
		}
	}
}
//check_ref();

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
function get_real_ext($file_extension){
	$file_ext = '';
	if($file_extension){
		$exts = explode(',','asp,asa,aspx,ascx,dtd,xsd,xsl,xslt,as,wml,java,vtm,vtml,jst,asr,php,php3,php4,php5,vb,vbs,jsf,jsp,pl,cgi,js,html,htm,xhtml,xml,css,shtm,cfm,cfml,shtml,bat,sh');
		if(in_array($file_extension,$exts)){
			$file_ext = '.txt';
		}
	}else{
		$file_ext = '.txt';
	}
	return $file_ext;
}
function get_extension($name){
	return strtolower(trim(strrchr($name, '.'), '.'));
}

$param = trim($_GET[param]);
if(!$param){
	exit('PHPDisk Download Error!');
}

parse_str(pd_encode(base64_decode(rawurldecode($param)),'DECODE'));
if($auth[pd_auth] && $hash!=md5($_SERVER['HTTP_USER_AGENT'].get_ip())){
	header("Content-Type: text/html; charset=$_G[charset]");
	echo '<p>'.lang('plugin/phpdisk_mini','download error please retry').': <a href="'.$url.'" target="_blank">'.$url.'</a></p>';
	echo '<p style="color:#ff0000">'.lang('plugin/phpdisk_mini','download link error sign').'</p>';
	exit;
}
if($auth[pd_auth] && ($expire_time && $expire_time<$timestamp)){
	header("Content-Type: text/html; charset=$_G[charset]");
	echo '<p>'.lang('plugin/phpdisk_mini','download error please retry').': <a href="'.$url.'" target="_blank">'.$url.'</a></p>';
	echo '<p style="color:#ff0000">'.lang('plugin/phpdisk_mini','download link expire').'</p>';
	exit;
}
$pp = $pp.get_real_ext(get_extension($pp));
//exit($pp);
if(!file_exists(PHPDISK_ROOT.FILE_PATH.'/'.$pp)){
	header("Content-Type: text/html; charset=$_G[charset]");
	echo '<p style="padding:10px; font-size:12px;">'.lang('plugin/phpdisk_mini','file id').': '.$file_id.'<br>';
	echo '['.$file_name.'] '.lang('plugin/phpdisk_mini','file not found pls contact admin').'<br><br>';
	echo ''.lang('plugin/phpdisk_mini','contact us').':'.$settings[contact_us].'</p>';
}else{
	$file_name = str_replace("+", "%20",$file_name);

	ob_end_clean();
	$ua = $_SERVER["HTTP_USER_AGENT"];
	if(preg_match("/MSIE/i", $ua)){
		header('Content-disposition: attachment;filename="'.iconv('utf-8','gbk',$file_name).'"');
	}else{
		header('Content-disposition: attachment;filename="'.$file_name.'"');
	}
	header('Content-type: application/octet-stream');
	if($settings[open_xsendfile]==2){
		header('X-Accel-Redirect: /'.PHPDISK_PLUGIN_DIR.'/'.FILE_PATH.'/'.$pp);
	}elseif($settings[open_xsendfile]==1){
		header('X-sendfile: ./'.PHPDISK_PLUGIN_DIR.'/'.FILE_PATH.'/'.$pp);
	}else{
		header('Content-Encoding: none');
		header('Content-Transfer-Encoding: binary');
		header('Content-length: '.$fs);
		@readfile('./'.PHPDISK_PLUGIN_DIR.'/'.FILE_PATH.'/'.$pp);
	}
}
exit;
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
?>