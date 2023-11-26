<?php 
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: install.php 27 2014-08-29 12:58:24Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
if(!defined('IN_DISCUZ')) {
	exit('[PHPDISK] Access Denied');
}
$encrypt_key = random(12);
$sql = <<<EOF
CREATE TABLE  `phpdisk_mini_nodes` (
`node_id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `subject` VARCHAR( 200 ) NOT NULL ,
 `parent_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
 `server_oid` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT  '0',
 `host` VARCHAR( 150 ) NOT NULL ,
 `icon` VARCHAR( 255 ) NOT NULL ,
 `down_type` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
 `show_order` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '0',
 `is_hidden` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
PRIMARY KEY (  `node_id` ) ,
KEY  `parent_id` (  `parent_id` )
) ENGINE = MYISAM;

CREATE TABLE `phpdisk_mini_downstat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `downcount` int(10) unsigned NOT NULL DEFAULT '0',
  `intime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM ;

CREATE TABLE  `phpdisk_mini_servers` (
`server_id` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `server_oid` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT  '0',
 `server_name` VARCHAR( 50 ) NOT NULL ,
 `server_host` VARCHAR( 100 ) NOT NULL ,
 `server_dl_host` TEXT NOT NULL ,
 `server_closed` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
 `server_store_path` VARCHAR( 50 ) NOT NULL ,
 `server_key` VARCHAR( 50 ) NOT NULL ,
 `is_default` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
PRIMARY KEY (  `server_id` ) ,
KEY  `server_oid` (  `server_oid` )
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS `phpdisk_mini_downlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` char(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `downcount` int(10) unsigned NOT NULL default '0',
  `intime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `hash` (`hash`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `phpdisk_mini_files` (
  `file_id` bigint(20) unsigned NOT NULL auto_increment,
  `file_name` varchar(100) NOT NULL,
  `file_key` char(8) NOT NULL,
  `file_extension` varchar(10) NOT NULL,
  `is_image` tinyint(1) unsigned NOT NULL default '0',
  `file_description` text NOT NULL,
  `file_store_path` varchar(255) NOT NULL,
  `file_real_name` varchar(100) NOT NULL,
  `file_md5` char(32) NOT NULL,
  `file_size` int(10) unsigned NOT NULL default '0',
  `file_time` int(10) unsigned NOT NULL default '0',
  `server_oid` smallint(5) unsigned NOT NULL default '0',
  `file_views` int(10) unsigned default '0',
  `file_downs` int(10) unsigned NOT NULL default '0',
  `file_remote_url` TEXT NOT NULL,
  `file_last_view` int(10) unsigned default '0',
  `file_credit` tinyint(2) unsigned NOT NULL default '0',
  `report_status` tinyint(1) unsigned NOT NULL default '0',
  `in_share` tinyint(1) unsigned NOT NULL default '0',
  `good_count` mediumint(8) unsigned NOT NULL default '0',
  `bad_count` mediumint(8) unsigned NOT NULL default '0',
  `is_locked` tinyint(1) unsigned NOT NULL default '0',
  `is_checked` tinyint(1) unsigned NOT NULL default '0',
  `is_public` tinyint(1) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `folder_id` bigint(20) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `is_del` tinyint(1) NOT NULL default '0',
  `file_pwd` VARCHAR( 20 ) NOT NULL,
  PRIMARY KEY  (`file_id`),
  KEY `userid` (`userid`),
  KEY `folder_id` (`folder_id`),
  KEY `file_name` (`file_name`),
  KEY `is_checked` (`is_checked`),
  KEY `in_share` (`in_share`),
  KEY `server_oid` (`server_oid`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `phpdisk_mini_folders` (
  `folder_id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` bigint(20) NOT NULL default '0',
  `folder_name` varchar(50) NOT NULL,
  `folder_description` varchar(255) NOT NULL,
  `in_recycle` tinyint(1) unsigned NOT NULL default '0',
  `in_share` tinyint(1) unsigned NOT NULL default '0',
  `folder_order` smallint(5) unsigned NOT NULL default '0',
  `folder_size` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `in_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`folder_id`),
  KEY `userid` (`userid`),
  KEY `parent_id` (`parent_id`),
  KEY `folder_order` (`folder_order`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `phpdisk_mini_myset` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `settings` text NOT NULL,
  `active_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `phpdisk_mini_settings` (
  `vars` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`vars`)
) ENGINE=MyISAM;

INSERT INTO `phpdisk_mini_settings` (`vars`, `value`) VALUES
('phpdisk_upload_title', 'PHPDisk Mini Upload'),
('file_path', 'filestores'),
('encrypt_key', '$encrypt_key'),
('site_title','PHPDisk Mini');

INSERT INTO `phpdisk_mini_servers` (`server_id`, `server_oid`, `server_name`, `server_host`, `server_dl_host`, `server_closed`, `server_store_path`, `server_key`, `is_default`) VALUES
(1, 0, 'Local Server', '-', '', 0, '-', '', 9),
(2, 2, 'Upload Server', 'http://localhost/bbs/dx3/phpdisk_sub/', '', 0, 'filestores', 'Evn9XztDLSlo', 1);

INSERT INTO `phpdisk_mini_nodes` (`node_id`, `subject`, `parent_id`, `server_oid`, `host`, `icon`, `down_type`, `show_order`, `is_hidden`) VALUES
(1, 'Top download', 0, 2, '', '', 0, 0, 0),
(2, 'download1', 1, 2, 'http://localhost/bbs/dx3/phpdisk_sub/', '', 0, 0, 1),
(3, 'download2', 1, 2, 'http://localhost/bbs/dx3/phpdisk_sub/', '', 0, 0, 0);

EOF;

runquery($sql);

$finish = TRUE;

?>