<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: security.inc.php 1943 2016-12-02 19:04:15Z wang11291895@163.com $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$_lang = lang('plugin/dc_pay');
loadcache('dc_paysecurity');
$code = $_G['cache']['dc_paysecurity']['code'];
if(submitcheck('submit')){
	if($code){
		$securitycode = trim($_GET['securitycode']);
		if($code!=md5(md5($securitycode).$_G['cache']['dc_paysecurity']['salt']))
			cpmsg($_lang['securitycodererror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'error');
	}
	$newsecuritycode = trim($_GET['newsecuritycode']);
	$renewsecuritycode = trim($_GET['renewsecuritycode']);
	if(strlen($newsecuritycode)<6)cpmsg($_lang['securitycode_1_msg'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'error');
	if($newsecuritycode!=$renewsecuritycode)cpmsg($_lang['securitycodeseterror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'error');
	$seccode['salt'] = random(6);
	$seccode['code'] = md5(md5($newsecuritycode).$seccode['salt']);
	savecache('dc_paysecurity', $seccode);
	cpmsg($_lang['securitycodesetsucceed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'succeed');
}
showtips($_lang['securitytips']);
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security');
showtableheader($_lang['securitycode'], '');
if($code)
	showsetting($_lang['securitycode_0'], 'securitycode', '','password');
showsetting($_lang['securitycode_1'], 'newsecuritycode', '','password','','',$_lang['securitycode_1_msg']);
showsetting($_lang['securitycode_2'], 'renewsecuritycode', '','password');
showtablefooter();
showsubmit('submit');
showformfooter();
?>