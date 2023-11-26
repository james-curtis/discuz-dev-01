<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: supplement.inc.php 2914 2017-06-29 10:28:15Z wang11291895@163.com $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$_lang = lang('plugin/dc_pay');
loadcache('dc_paysecurity');
$code = $_G['cache']['dc_paysecurity']['code'];
if(empty($code))cpmsg($_lang['securityempty'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'error');
$securitycode = getcookie('dcpaysecurity');
if(!$securitycode||authcode($securitycode)!=md5($code.FORMHASH)){
	if(submitcheck('submitcheck')){
		$securitycode = trim($_GET['securitycode']);
		if($code!=md5(md5($securitycode).$_G['cache']['dc_paysecurity']['salt']))
			cpmsg($_lang['securitycodeerror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=supplement', 'error');
		$authcode = authcode(md5($code.FORMHASH),'ENCODE');
		dsetcookie('dcpaysecurity', $authcode, 1800, 1, true);
	}else{
		$url = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=supplement';
		$str = '<div class="infobox"><form method="post" action="'.$url.'"><input type="hidden" name="formhash" value="'.FORMHASH.'"><input type="hidden" name="submitcheck" value="true">
					<br />'.$_lang['securitycode_1'].'<br />
					<input name="securitycode" value="" type="password"/>
					<br />
					<p class="margintop"><input type="submit" class="btn" name="submit" value="'.cplang('submit').'">
					</p></form><br /></div>';
		echo $str;
		die();
	}
	
}
if($_GET['act']=='search'){
	$orderid = trim($_GET['orderid']);
	$order = C::t('#dc_pay#dc_pay_order')->getbyorderid($orderid);
	if(!$order||$order['status']==1)cpmsg($_lang['supplement_error']);
	$payfor = explode(':',$order['payorderid']);
	require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
	$payobj = new PayMent('dc_pay');
	$paytype = $payobj->GetPayType();
	$paytypeselect = ''; 
	foreach($paytype as $p){
		$payobj->SetPayType($p);
		$pinfo = $payobj->GetPayInfo($p);
		$paytypeselect .='<option value="'.$p.'">'.$pinfo['title'].'</option>';
	}
	$plugindata = C::t('common_plugin')->fetch_by_identifier($order['plugin']);
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=supplement&act=pass&oid='.$order['id']);
	showtableheader($_lang['supplement_title'], '');
	showtablerow('', array('width="80"'), array($_lang['order_id'],$order['orderid']));
	showtablerow('', array(), array($_lang['order_api'],$plugindata['name']?$plugindata['name']:$_lang['order_noapi']));
	showtablerow('', array(), array($_lang['order_subject'],$order['subject']));
	showtablerow('', array(), array($_lang['order_price'],$order['price'].$_lang['yuan']));
	showtablerow('', array(), array($_lang['order_username'],$order['username']));
	showtablerow('', array(), array($_lang['order_dateline'],dgmdate($order['dateline'], 'dt')));
	showtablerow('', array(), array($_lang['transfer_type'],'<select name="paytype" >'.$paytypeselect.'</select>'));
	showtablerow('', array(), array($_lang['transfer_payid'],'<input name="payorderid" value="" type="text">'));
	showsubmit('submit', $_lang['supplement_title']);
	showformfooter();
	die();
}elseif($_GET['act']=='pass'){
	$oid = dintval($_GET['oid']);
	$order = C::t('#dc_pay#dc_pay_order')->fetch($oid);
	if(!$order||$order['status']==1)cpmsg($_lang['supplement_error']);
	if(submitcheck('submit')){
		$paytype = trim($_GET['paytype']);
		$payorderid = trim($_GET['payorderid']);
		$api = C::t('#dc_pay#dc_pay_api')->fetch($order['plugin']);
		if(!$api)cpmsg($_lang['error']);
		C::t('#dc_pay#dc_pay_order')->update($order['id'],array('status'=>1,'finishdateline'=>TIMESTAMP,'payorderid'=>$paytype.':'.$payorderid));
		$apipath = DISCUZ_ROOT.'./source/plugin/'.$api['plugin'].'/'.$api['include'];
		if(!file_exists($apipath))cpmsg($_lang['error']);
		require_once $apipath;
		$_apiobj = $api['class'];
		$mobj = new $_apiobj();
		$method = $api['notifymethod'];
		if(in_array($method,get_class_methods($mobj))){
			$param = dunserialize($order['param']);
			$mobj->$method($order['orderid'],$param,$order['uid'],$order['username']);
		}
		cpmsg($_lang['supplementok'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=supplement', 'succeed');
	}
}
showtips($_lang['supplement_tips']);
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=supplement&act=search');
showtableheader($_lang['supplement_title'], '');
showsetting($_lang['order_id'], 'orderid', '','text','','',$_lang['supplement_orderid_msg']);
showsubmit('submit', 'search');
showtablefooter();
showformfooter();
?>