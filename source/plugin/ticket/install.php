<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `cdb_ticket_main`;
CREATE TABLE `cdb_ticket_main` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pid` int(8) DEFAULT '0',
  `t_id` int(8) DEFAULT NULL,
  `uid` int(8) DEFAULT NULL,
  `username` varchar(60) DEFAULT NULL,
  `content` text,
  `extends` text,
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT NULL,
  `lastline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
DROP TABLE IF EXISTS `cdb_ticket_types`;
CREATE TABLE `cdb_ticket_types` (
  `t_id` int(5) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(60) DEFAULT NULL,
  `t_desc` varchar(255) DEFAULT NULL,
  `t_order` int(5) DEFAULT '0',
  PRIMARY KEY (`t_id`)
) ENGINE=MyISAM;
EOF;
runquery($sql);
$finish = true;
?>