<?php 
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: uninstall.php 9 2014-07-30 09:03:00Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
if(!defined('IN_DISCUZ')) {
	exit('[PHPDISK] Access Denied');
}
$sql = <<<EOF
ALTER TABLE  `phpdisk_mini_files` ADD  `file_pwd` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE  `phpdisk_mini_files` CHANGE  `file_remote_url`  `file_remote_url` TEXT NOT NULL;
CREATE TABLE IF NOT EXISTS `phpdisk_mini_downstat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `downcount` int(10) unsigned NOT NULL DEFAULT '0',
  `intime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM ;
EOF;
runquery($sql);

$finish = TRUE;

?>