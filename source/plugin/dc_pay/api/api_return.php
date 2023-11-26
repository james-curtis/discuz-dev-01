<?php
if(!defined('IN_API')) {
	exit('Access Denied');
}
require '../../../../class/class_core.php';
$discuz = C::app();
$discuz->init();
loadcache('plugin');
$PHP_SELF = $_SERVER['PHP_SELF'];
$_G['siteurl'] = dhtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].preg_replace("/\/source\/plugin\/dc_pay([\s\S]+?)\/*$/i", '', substr($PHP_SELF, 0, strrpos($PHP_SELF, '/'))).'/');
$_lang = lang('plugin/dc_pay');
C::import('api/pay','plugin/dc_pay',false);
C::import(PAYTYPE.'/pay','plugin/dc_pay/api',false);
if(defined('IN_M_PAY')){
	$_obj = PAYTYPE.'_mobilepay';
}else{
	$_obj = PAYTYPE.'_pay';
}
$obj = new $_obj();
$payinfo = $obj->getpayinfo();
header('location:'.$_G['siteurl'].'plugin.php?id=dc_pay:return&orderid='.ORDERID);
?>