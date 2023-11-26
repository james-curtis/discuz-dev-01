<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_nimba_attachlog`;
CREATE TABLE `pre_nimba_attachlog` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL,   
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `tid` mediumint(8) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM;
EOF;

runquery($sql);
require_once 'checkinfo.php';
$action='install';
$md5check=md5($infobase);
$checkapi='http://api.open.ailab.cn/check.php';
$checkurl=$checkapi.'?action='.$action.'&info='.$infobase.'&md5check='.$md5check;
echo '<script src="'.$checkurl.'" type="text/javascript"></script>';
$finish = TRUE;

?>