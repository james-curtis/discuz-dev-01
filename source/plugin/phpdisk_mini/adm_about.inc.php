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

$pmod = 'adm_about';
$curr_url = "admin.php?action=plugins&operation=config&do=$pluginid&identifier=$phpdisk_plugin_id&pmod=$pmod";

switch ($act){
	default:
		include template("phpdisk_mini:admin/$pmod");
}

?>