<?php
if(!defined('IN_API')) {
	exit('Access Denied');
}
if(!defined('DISABLEXSSCHECK')) {
	define('DISABLEXSSCHECK', true);
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
$order = C::t('#dc_pay#dc_pay_order')->getbyorderid(ORDERID);
if(!$order)die($payinfo['fail']);
if($order['status']==1)die($payinfo['succeed']);
$paychk = $obj->notifycheck();
if(!$paychk)die($payinfo['fail']);
if(!$obj->pricecheck($order['price']))die($payinfo['fail']);
$api = C::t('#dc_pay#dc_pay_api')->fetch($order['plugin']);
if(!$api)die($payinfo['fail']);
//交易成功
C::t('#dc_pay#dc_pay_order')->update($order['id'],array('status'=>1,'finishdateline'=>TIMESTAMP,'payorderid'=>PAYTYPE.':'.TRADENO));
//回调插件
$apipath = DISCUZ_ROOT.'./source/plugin/'.$api['plugin'].'/'.$api['include'];
if(!file_exists($apipath))die($payinfo['fail']);
require_once $apipath;
$_apiobj = $api['class'];
$mobj = new $_apiobj();
$method = $api['notifymethod'];
if(in_array($method,get_class_methods($mobj))){
	$param = dunserialize($order['param']);
	$mobj->$method(ORDERID,TRADENO,$param,$order['uid'],$order['username']);
}
die($payinfo['succeed']);
?>