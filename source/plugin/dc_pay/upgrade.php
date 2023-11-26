<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!$fromversion)
	$fromversion = trim($_GET['fromversion']);
if($fromversion<'v1.0.4'){
	$sql = <<<EOF
	ALTER TABLE `pre_dc_pay_api` ADD COLUMN `payok` varchar(45) NULL;
	ALTER TABLE `pre_dc_pay_api` ADD COLUMN `ishand` TINYINT NULL DEFAULT 0;
	ALTER TABLE `pre_dc_pay_order` ADD COLUMN `param` text NULL;
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
}
$finish = TRUE;
?>
