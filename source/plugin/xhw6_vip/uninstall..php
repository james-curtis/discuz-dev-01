<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS cdb_cjxixi_alipayusergroup;
EOF;
include_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
PayHook::UnRegister('xhw6_vip'); //卸载

$finish = TRUE;