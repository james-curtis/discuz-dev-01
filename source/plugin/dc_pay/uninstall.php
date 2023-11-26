<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_dc_pay_api`;
DROP TABLE IF EXISTS `pre_dc_pay_order`;
EOF;
runquery($sql);
$finish = true;
?>