<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$keke_group_orderlog= DB::table("keke_group_orderlog");
$keke_group= DB::table("keke_group");
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `$keke_group_orderlog` (
  `orderid` char(24) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `usname` varchar(255) NOT NULL,
  `money` int(32) NOT NULL,
  `groupid` int(50) unsigned NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `groupvalidity` int(10) NOT NULL,
  `groupinvalid` int(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `state` int(10) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `zftime` int(10) unsigned NOT NULL,
  `sn` varchar(80) NOT NULL,
  `opid` char(32) NOT NULL,
  PRIMARY KEY  (`orderid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `$keke_group` (
  `id` int(50) NOT NULL auto_increment,
  `groupid` char(24) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `ico` varchar(150) NOT NULL,
  `money` int(200) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `tequan` varchar(255) NOT NULL,
  `display` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `hot` int(10) NOT NULL,
  `give` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

EOF;
runquery($sql);

include_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
$phr = array(
    'plugin'=>'keke_group', //插件标识符
    'include'=>'xhw6.class.php', //注册类文件
    'class'=>'xhw6', //注册类名称
    'return'=>'doreturn', //注册页面跳转同步通知方法
    'notify'=>'donotify', //注册服务器异步通知方法
    'payok'=>'keke_group&p=sw', //支付成功后跳转页面，新增
);
PayHook::Register($phr); //注册


$finish = true;

?>