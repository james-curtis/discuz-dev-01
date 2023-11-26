<?php

if(!defined('IN_ADMINCP') || !defined('IN_DISCUZ')) {
exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/function/tool.func.php';
$finish = check_interface_and_install('keke_group',array('payok'=>'keke_group&p=sw'));
