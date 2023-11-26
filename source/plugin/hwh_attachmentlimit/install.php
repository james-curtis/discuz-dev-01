<?php
defined('IN_DISCUZ') && defined('IN_ADMINCP') || exit('Powered by Hymanwu.Com');
$config = include 'config.php';
$plugin_id = $config['ID'];

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_{$plugin_id}_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `tid` mediumint(8) unsigned NOT NULL,
  `useip` varchar(15) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

EOF;

$finish = TRUE;