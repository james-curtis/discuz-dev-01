<?php 
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: uninstall.php 27 2014-08-29 12:58:24Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
if(!defined('IN_DISCUZ')) {
	exit('[PHPDISK] Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `phpdisk_mini_downlog`;
DROP TABLE IF EXISTS `phpdisk_mini_downstat`;
DROP TABLE IF EXISTS `phpdisk_mini_files`;
DROP TABLE IF EXISTS `phpdisk_mini_folders`;
DROP TABLE IF EXISTS `phpdisk_mini_myset`;
DROP TABLE IF EXISTS `phpdisk_mini_settings`;
DROP TABLE IF EXISTS `phpdisk_mini_nodes`;
DROP TABLE IF EXISTS `phpdisk_mini_servers`;
EOF;
runquery($sql);

$finish = TRUE;

?>