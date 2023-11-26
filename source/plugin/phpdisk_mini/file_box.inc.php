<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: file_box.inc.php 10 2014-08-02 14:34:18Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/


if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
$max_file_queue = 30;
$upload_url = urr("plugin","id=phpdisk_mini:upload&action=plugin_box");

include template('phpdisk_mini:default/file_box');


?>
