<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$sql = <<<EOF
CREATE TABLE `pre_dc_pay_api` (
  `plugin` varchar(45) NOT NULL,
  `include` varchar(255) DEFAULT NULL,
  `class` varchar(45) DEFAULT NULL,
  `notifymethod` varchar(45) DEFAULT NULL,
  `returnmethod` varchar(45) DEFAULT NULL,
  `payok` varchar(45) DEFAULT NULL,
  `ishand` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`plugin`)
);

CREATE TABLE `pre_dc_pay_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(45) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `plugin` varchar(45) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  `finishdateline` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `payorderid` varchar(45) DEFAULT NULL,
  `param` text,
  PRIMARY KEY (`id`,`orderid`)
);
EOF;
runquery($sql);
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
$phr = array(
	'plugin'=>'dc_pay',
	'include'=>'paycredit.class.php',
	'class'=>'paycredit',
	'return'=>'doreturn',
	'notify'=>'donotify',
	'payok'=>'payok',
);
PayHook::Register($phr);
$returnplugin = getcookie('returnplugin'); //用来做合并安装
if($returnplugin){
	ob_end_clean();
	ob_start();
	dsetcookie('returnplugin');
	dheader('location:admin.php?action=plugins&operation=import&dir='.$returnplugin);
}
$finish = true;
?>