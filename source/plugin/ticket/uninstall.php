<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `cdb_ticket_main`;
DROP TABLE IF EXISTS `cdb_ticket_types`;
EOF;
runquery($sql);
$cacheFile = DISCUZ_ROOT.'./data/sysdata/cache_ticket_addon_tids.php';
@unlink($cacheFile);
$finish = true;
?>