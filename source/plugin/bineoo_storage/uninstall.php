<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//require_once libfile('function/cloudaddons');
$sql = <<<EOF
	DROP TABLE IF EXISTS pre_bineoo_storage_bucket;
EOF;

runquery($sql);
//cloudaddons_cleardir(DISCUZ_ROOT . './source/plugin/bineoo_storage');
$finish = true;
?>