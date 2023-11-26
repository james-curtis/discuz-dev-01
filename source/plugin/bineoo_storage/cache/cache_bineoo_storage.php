<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function build_cache_plugin_bineoo_storage() {
	$lock = DISCUZ_ROOT.'./data/template/bineoo_cache.php';
	if(is_file($lock)){
		@unlink($lock);
	}
}