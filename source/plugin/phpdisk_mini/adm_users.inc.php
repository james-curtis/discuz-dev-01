<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: adm_users.inc.php 10 2014-08-02 14:34:18Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('[PHPDISK] Access Denied');
}
require 'includes/commons.inc.php';

$pmod = 'adm_users';
$curr_url = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=$pmod";

switch($act){
	default:
		$perpage = 50;
		$start = ($page-1)*$perpage;
		$users = array();
		$query = DB::query("SELECT pm.*,cm.username FROM phpdisk_mini_myset pm,".DB::table('common_member')." cm where pm.userid=cm.uid ORDER BY active_time DESC LIMIT $start, $perpage");
		while($rs = DB::fetch($query)) {
			$rs[a_space] = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=adm_files&uid=".$rs[userid];
			$rs[active_time] = date('Y-m-d H:i:s',$rs[active_time]);			
			$users[] = $rs;
		}
		unset($rs);
		$multi = multi($count, $perpage, $page, $curr_url);
		include template("phpdisk_mini:admin/$pmod");
}

?>