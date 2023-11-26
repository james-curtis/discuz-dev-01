<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: adm_ads.inc.php 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('[PHPDISK] Access Denied');
}
require 'includes/commons.inc.php';

$pmod = 'adm_ads';
$curr_url = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=$pmod";

switch($act){
	default:

		if($task =='save'){
			form_auth(gpc('formhash','P',''),formhash());
			$set = array(
			'viewfile_right' => '',
			'viewfile_top' => '',
			'viewfile_dl_header' => '',
			'viewfile_dl_footer' => '',
			'viewfile_bottom' => '',
			'viewfile_codes' => '',
			'search_right' => '',
			);
			$sets = gpc('set','P',$set,0);
			
			$sets[viewfile_top] = $sets[viewfile_top] ? base64_encode(trim($sets[viewfile_top])) : '';
			$sets[viewfile_dl_header] = $sets[viewfile_dl_header] ? base64_encode(trim($sets[viewfile_dl_header])) : '';
			$sets[viewfile_dl_footer] = $sets[viewfile_dl_footer] ? base64_encode(trim($sets[viewfile_dl_footer])) : '';
			$sets[viewfile_right] = $sets[viewfile_right] ? base64_encode(trim($sets[viewfile_right])) : '';
			$sets[viewfile_bottom] = $sets[viewfile_bottom] ? base64_encode(trim($sets[viewfile_bottom])) : '';
			$sets[viewfile_codes] = $sets[viewfile_codes] ? base64_encode(trim($sets[viewfile_codes])) : '';
			$sets[search_right] = $sets[search_right] ? base64_encode(trim($sets[search_right])) : '';

			if(!$error){
				settings_cache($sets);
				admincp_msg(lang('plugin/phpdisk_mini','ads_update_success'),$curr_url);

			}else{

				admincp_msg(lang('plugin/phpdisk_mini','ads_update_fail'),$curr_url,0);
			}

		}else{
			$settings[viewfile_right] = $settings[viewfile_right] ? base64_decode($settings[viewfile_right]) : '';
			$settings[viewfile_top] = $settings[viewfile_top] ? base64_decode($settings[viewfile_top]) : '';
			$settings[viewfile_dl_header] = $settings[viewfile_dl_header] ? base64_decode($settings[viewfile_dl_header]) : '';
			$settings[viewfile_dl_footer] = $settings[viewfile_dl_footer] ? base64_decode($settings[viewfile_dl_footer]) : '';
			$settings[viewfile_bottom] = $settings[viewfile_bottom] ? base64_decode($settings[viewfile_bottom]) : '';
			$settings[viewfile_codes] = $settings[viewfile_codes] ? base64_decode($settings[viewfile_codes]) : '';
			$settings[search_right] = $settings[search_right] ? base64_decode($settings[search_right]) : '';

			include template("phpdisk_mini:admin/$pmod");
		}
}

?>