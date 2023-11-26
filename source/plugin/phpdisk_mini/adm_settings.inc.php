<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: adm_settings.inc.php 10 2014-08-02 14:34:18Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('[PHPDISK] Access Denied');
}
require 'includes/commons.inc.php';

$pmod = 'adm_settings';
$curr_url = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=$pmod";

switch ($act){
	default:
		if($task=='setting'){
			form_auth(gpc('formhash','P',''),formhash());
			$set = array(
			'file_path' => '',
			'encrypt_key' => '',
			'share_to_check'=>0,
			'open_rewrite'=>0,
			'charset'=>$_G[charset],
			);
			$sets = gpc('set','P',$set);

			if(!$error){
				$sets[rsync_time] = $sets[rsync_time] ? (int)$sets[rsync_time] : 600;
				$old_dir = PHPDISK_ROOT.$settings[file_path].'/';
				$new_dir = PHPDISK_ROOT.$sets[file_path].'/';
				if($settings[file_path] && is_dir($old_dir)){
					@rename($old_dir,$new_dir);
				}else{
					make_dir($new_dir);
				}
				settings_cache($sets);

				admincp_msg(lang('plugin/phpdisk_mini','mydisk_setting_update_success'),$curr_url);
			}else{
				admincp_msg(lang('plugin/phpdisk_mini','mydisk_setting_update_fail'),$curr_url,0);
			}
		}else{
			include template("phpdisk_mini:admin/$pmod");
		}
}

?>