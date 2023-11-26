<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/common.php';

function check_bineoo_md5(){
	return true;
}

function check_authorization(){
	return true;
}

function oss_set(){
	global $_G;
	
	loadcache('bineoo_storage_setting');
	$oss_set = dunserialize($_G['cache']['bineoo_storage_setting']);
	$region_list = array();
	foreach (explode(PHP_EOL, $oss_set['region_list']) as $region) {
		$region = explode('==', $region);
		$region_list[$region[0]] = $region[1];
	}
	$oss_set['region_list'] = $region_list;
	$oss_set['acl_list'] = array(
		'private' => lang('plugin/bineoo_storage','private'),
		'public-read' => lang('plugin/bineoo_storage','public-read'),
		'public-read-write' => lang('plugin/bineoo_storage','public-read-write'),
	);
	$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  
	$oss_set['attachurl'] = attach_url($_G['setting']['attachurl']);
	$oss_set['attachdir'] = $_G['setting']['attachurl'];
	$oss_set['domain'] = ($oss_set['domain'] ? $http_type.$oss_set['domain'] : $http_type.$oss_set['bucket'].'.'.$oss_set['region'].'.aliyuncs.com').'/';
	$oss_set['cache_tag'] = explode(PHP_EOL, $oss_set['cache_tag']);
	$oss_set['except_tag'] = explode(PHP_EOL, $oss_set['except_tag']);
	$oss_set['image_style'] = stripslashes($oss_set['image_style']);
	$oss_set['extra_tag'] = explode(PHP_EOL, strtolower($oss_set['extra_tag']));
	return $oss_set;
}

function oss_client($region='',$internal=false){
	global $_G;
	
	$oss_set = oss_set();
	if(!$oss_set['Access_Key_ID'] || !$oss_set['Access_Key_Secret']){
		return array(false,$oss_set);
	}
	require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/OssClient.php';
	$region = $region ? $region : ($oss_set['region'] ? $oss_set['region'] : 'oss-cn-hangzhou');
	$ossClient = new OssClient($oss_set['Access_Key_ID'], $oss_set['Access_Key_Secret'], $region.(!$internal ? '' : '-internal').'.aliyuncs.com', false);
	return array($ossClient,$oss_set);
}

function attach_url($url=''){
	
	if(in_array(strtolower(substr($url, 0, 6)), array('http:/', 'https:', 'ftp://', 'rtsp:/', 'mms://'))) {
		$url = str_replace(array('http://','https://','ftp://','rtsp://','mms://'), '', $url);
		$url = substr($url, stripos($url,'/')+1);
	}
	if(in_array(substr($url, 0, 1), array('.','/'))){
		$url = attach_url(substr($url, 1));
	}
	if($url && substr($url, -1) !== '/'){
		$url .= '/';
	}
	return $url ? $url : '';
}

function import_module($file=''){
	global $_G;
	
	$file = DISCUZ_ROOT.'./source/plugin/bineoo_storage/module/'.$file.'.php';
	if(is_file($file)){
		require_once $file;
	}
}

function gmt_iso8601($time) {
	
	$dtStr = date("c", $time);
	$mydatetime = new DateTime($dtStr);
	$expiration = $mydatetime->format(DateTime::ISO8601);
	$pos = strpos($expiration, '+');
	$expiration = substr($expiration, 0, $pos);
	return $expiration."Z";
}

function oss_url($param=''){
	
	$url = 'plugin.php?id=bineoo_storage';
	if(is_array($param)){
		foreach ($param as $k => $v) {
			$url .= '&'.$k.'='.$v;
		}
	}else{
		$url .= $param;
	}
	return $url;
}

function sshowmessage($message, $url_forward = '', $values = array(), $extraparam = array(), $custom = 0){
	global $_G;
	
	require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/function_message.php';
	return ddshowmessage($message, $url_forward, $values, $extraparam, $custom);
}

function local_list($dir){
	
	$list = array();
	if($directory = @dir($dir)) {
		while($entry = $directory->read()) {
			if($entry != '.' && $entry != '..') {
				$filename = $dir.'/'.$entry;
				if(is_file($filename)) {
					$file = str_replace($dir, '', $filename);
					if(substr($file,0,1) == '/'){
						$file = substr($file,1);
					}
					$list['file'][] = $file;
				} else {
					$file = str_replace($dir, '', $filename);
					if(substr($file,0,1) == '/'){
						$file = substr($file,1);
					}
					$list['dir'][] = $file;
				}
			}
		}
		$directory->close();
	}
	return $list;
}

function upload_dir($ossClient,$bucket,$dir){
	
	if($directory = @dir(DISCUZ_ROOT.'./'.$dir)) {
		while($entry = $directory->read()) {
			$object = $dir.'/'.$entry;
			if($entry != '.' && $entry != '..' && is_dir($object)) {
				$ossClient->uploadDir($bucket, $object, $object);
				upload_dir($ossClient,$bucket,$object);
			}
		}
		$directory->close();
	}
	return true;
}

function bineoo_cache(){
	global $_G;
	
	$dir_list = local_list(DISCUZ_ROOT.'./data/template');
	if(!$dir_list['file'] || !is_array($dir_list['file'])){
		return false;
	}
	$bineoo_cache = array();
	if(is_file(DISCUZ_ROOT.'./data/template/bineoo_cache.php')){
		require_once DISCUZ_ROOT.'./data/template/bineoo_cache.php';
	}
	$timearr = array();
	for ($i=0; $i < rand(2,5) ; $i++) { 
		$time_key = md5('data/template/'.$i.'.php'.'bineoo_storage');
		$timearr[$time_key] = time()+1;
	}
	$file_i = 0;
	foreach ($dir_list['file'] as $file) {
		$time_key = md5('data/template/'.$file.'bineoo_storage:2791136338');
		$local_file = DISCUZ_ROOT.'./data/template/'.$file;
		$cache_tag = false;
		if($_G['bineoo_storage']['oss_set']['close_max']){
			foreach ($_G['bineoo_storage']['oss_set']['cache_tag'] as $tag) {
				if(stripos($file, $tag) !== false){
					$cache_tag = true;
				}
			}
			if($cache_tag){
				continue;
			}
		}
		if(is_file($local_file) && pathinfo($local_file, PATHINFO_EXTENSION) == 'php' && intval($bineoo_cache[$time_key]) < @filemtime($local_file) && stripos($file, 'discuzcode') === false){
			$content = file_get_contents($local_file);
			if(stripos($content, 'bineoo_output()') === false){
				$replace_status = false;
				if(stripos($content, 'output();') !== false){
					$content = str_replace('output();', "if(function_exists('bineoo_output')){bineoo_output();}", $content);
					$replace_status = true;
				}
				if(stripos($content, 'output_preview();') !== false){
					$content = str_replace('output_preview();', "if(function_exists('bineoo_output')){bineoo_output('preview');}", $content);
					$replace_status = true;
				}
				if(stripos($content, 'output_ajax();') !== false){
					$content = str_replace('output_ajax();', "bineoo_output('ajax');", $content);
					$replace_status = true;
				}
				if(!$replace_status){
					$content = $content."<?php if(function_exists('bineoo_output')){bineoo_output();}?>";
				}
				$file_i++;
				file_put_contents($local_file, $content);
			}
		}
		$timearr[$time_key] = time()+1;
	}
	if(!$file_i){
		return false;
	}
	for ($i=0; $i < rand(2,5) ; $i++) { 
		$time_key = md5('data/template/'.$i.$i.$i.'.php'.'2791136338');
		$timearr[$time_key] = time()+1;
	}
	file_put_contents(DISCUZ_ROOT.'./data/template/bineoo_cache.php', '<?php $bineoo_cache='.var_export($timearr,true).'; ?>');
}

function bineoo_output($type){
	global $_G;
	
	$starttime = explode(' ',microtime());
	$havedomain = implode('', $_G['setting']['domain']['app']);
	if($type == 'ajax'){
		$content = ob_get_contents();
		ob_end_clean();
		$content = preg_replace("/([\\x01-\\x08\\x0b-\\x0c\\x0e-\\x1f])+/", ' ', $content);
		$content = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $content);
		if(defined('DISCUZ_DEBUG') && DISCUZ_DEBUG && @include(libfile('function/debug'))) {
			function_exists('debugmessage') && $content .= debugmessage(1);
		}
		if($_G['setting']['rewritestatus'] || !empty($havedomain)) {
	        $content = output_replace($content);
		}
		$content = bineoo_replace_content($content);
		return $content;
	}else if($type == 'preview'){
		$content = ob_get_contents();
		ob_end_clean();
		ob_start();
		$content = bineoo_replace_content($content);
		echo $content;
	}else{

		if(!empty($_G['blockupdate'])) {
			block_updatecache($_G['blockupdate']['bid']);
		}

		if(defined('IN_MOBILE')) {
			require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/class/bineoo_helper_mobile.php';
			bineoo_helper_mobile::mobileoutput();
		}else{
			$content = ob_get_contents();
			if($_G['setting']['rewritestatus'] || !empty($havedomain)) {
				$content = output_replace($content);
			}
			$content = bineoo_replace_content($content);
			ob_end_clean();
			$_G['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
			echo $content;

			if(isset($_G['makehtml'])) {
				helper_makehtml::make_html();
			}

			if($_G['setting']['ftp']['connid']) {
				@ftp_close($_G['setting']['ftp']['connid']);
			}
			$_G['setting']['ftp'] = array();

			if(defined('CACHE_FILE') && CACHE_FILE && !defined('CACHE_FORBIDDEN') && !defined('IN_MOBILE') && !checkmobile()) {
				if(diskfreespace(DISCUZ_ROOT.'./'.$_G['setting']['cachethreaddir']) > 1000000) {
					if($fp = @fopen(CACHE_FILE, 'w')) {
						flock($fp, LOCK_EX);
						fwrite($fp, empty($content) ? ob_get_contents() : $content);
					}
					@fclose($fp);
					chmod(CACHE_FILE, 0777);
				}
			}

			if(defined('DISCUZ_DEBUG') && DISCUZ_DEBUG && @include(libfile('function/debug'))) {
				function_exists('debugmessage') && debugmessage();
			}

			$endtime = explode(' ',microtime());
			$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
			if($_G['bineoo_storage']['oss_set']['debug']){
				var_dump(round($thistime,5));
			}	
		}
	}
}

function bineoo_replace_content($content){
	if(!$content){
		return '';
	}
	
	$content = preg_replace_callback('/<img.*?src=["|\']([^"]*)["|\'][^>]*>/i', 'image_replace', $content);
	//$content = preg_replace_callback('/<a.*?href=["|\']([^"]*)["|\'][^>]*>/i', 'link_replace', $content);
	//$content = preg_replace_callback('/<script.*?src=["|\']([^"]*)["|\'][^>]*><\/script>/i', 'srcipt_replace', $content);
	return $content;
}