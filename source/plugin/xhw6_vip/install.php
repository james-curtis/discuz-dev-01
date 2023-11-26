<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
if (!file_exists(DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php'))showmessage($installlang('error_dc_pay'));
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `{$_G['config']['db']['1']['tablepre']}xhw6_vip` (
  `order_id` char(38) NOT NULL,
  `order_status` char(3) NOT NULL,
  `uid` bigint(11) unsigned NOT NULL,
  `username`  varchar(20)  NOT NULL,
  `trade_no` char(50) NOT NULL,
  `group_id` mediumint(9) NOT NULL,
  `group_name` varchar(256) NOT NULL ,
  `extcreditstitle` varchar(256) NOT NULL,
  `extcredits` varchar(256) NOT NULL,
  `price` float(7,2) NOT NULL,
  `validity` char(10) NOT NULL,
  `submitdate` int(10) NOT NULL,
  `confirmdate` int(10) NOT NULL,
  `ip` char(15) NOT NULL,
  `pay_type` char(15) NOT NULL,
  UNIQUE KEY `order_id` (`order_id`),
  KEY `submitdate` (`submitdate`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;
EOF;
runquery($sql);

$phr = array(
    'plugin'=>'xhw6_vip', //插件标识符
    'include'=>'xhw6_vip.class.php', //注册类文件
    'class'=>'xhw6_vip', //注册类名称
    'return'=>'doreturn', //注册页面跳转同步通知方法
    'notify'=>'donotify', //注册服务器异步通知方法
    'payok'=>'xhw6_vip', //支付成功后跳转页面，新增
);
PayHook::Register($phr); //注册


$finish = TRUE;