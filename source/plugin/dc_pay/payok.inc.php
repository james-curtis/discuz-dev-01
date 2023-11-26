<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: payok.inc.php 349 2015-01-21 10:28:15Z wang11291895@163.com $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$_lang = lang('plugin/dc_pay');
$payurl_referer = getcookie('payurl_referer');
dsetcookie('payurl_referer','',-1);
showmessage($_lang['pay_succeed'],$payurl_referer?$payurl_referer:$_G['siteurl']);
?>