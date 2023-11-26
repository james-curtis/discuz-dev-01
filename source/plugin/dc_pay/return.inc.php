<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if (empty($_lang))
{
    $_lang = lang('plugin/dc_pay');
}
$orderid = trim($_GET['orderid']);
$order = C::t('#dc_pay#dc_pay_order')->getbyorderid($orderid);
if(!$order)showmessage($_lang['errorcode'].'10001');
$api = C::t('#dc_pay#dc_pay_api')->fetch($order['plugin']);
if(!$api)showmessage($_lang['errorcode'].'10004');
if($order['status']==1){
	if($api['payok'])
		dheader('location:'.$_G['siteurl'].'plugin.php?id='.$order['plugin'].':'.$api['payok'].'&orderid='.$orderid);
	else
		dheader('location:'.$_G['siteurl'].'plugin.php?id=dc_pay:payok');
}
showmessage($_G['cache']['plugin']['dc_pay']['returnmsg'],'',array(),array('alert'=>'info'));
?>