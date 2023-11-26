<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: payorder.inc.php 2914 2015-02-1 10:28:15Z wang11291895@163.com $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/paycredit.class.php';
$paycredit = new paycredit();
$_lang = lang('plugin/dc_pay');
if(submitcheck('delete')){
	$oid = dintval($_GET['delete'],true);
	C::t('#dc_pay#dc_pay_order')->delete($oid);
	cpmsg($_lang['delok'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=payorder', 'succeed');
}
$perpage = 20;
$start = ($page-1)*$perpage;
$searchtype = trim($_GET['searchtype']);
$search = trim($_GET['search']);
$where = array();
if(in_array($searchtype,array('orderid','uid','username'))&&$search){
	$where = array($searchtype=>$search);
}
$vus = C::t('#dc_pay#dc_pay_order')->getrange($where,$start,$perpage,'DESC');
$count = C::t('#dc_pay#dc_pay_order')->getcount($where);
$plugins = DB::fetch_all('SELECT identifier, name FROM %t', array('common_plugin'), 'identifier');
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=payorder');
showtableheader($_lang['cpsearch'], '');
showtablerow('', array('width="30"', ''), array(
	$_lang['cpsearch'].":",
	'<select name="searchtype"><option value="orderid">'.$_lang['order_id'].'</option><option value="uid">UID</option><option value="username">'.$_lang['order_username'].'</option></select><input type="text" name="search" autocomplete="off" placeholder="" value="" /> <input type="submit" class="btn" name="submit" value="'.$_lang['cpsearch'].'">',
	)
);
showtablefooter();
showformfooter();
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=payorder');
showtableheader($_lang['order_list'], '');
showsubtitle(array('',$_lang['out_trade_on'],$_lang['order_api'],$_lang['order_price'],$_lang['order_username'],$_lang['order_dateline'],$_lang['order_finishdateline'],$_lang['pay_api'],$_lang['trade_no'],$_lang['order_status']));
foreach($vus as $v){
	showtablerow('', array('width="20"'), array(
		'<input type="checkbox" class="checkbox" name="delete[]" value="'.$v['id'].'">',
		$v['orderid'],
		$plugins[$v['plugin']]?$plugins[$v['plugin']]['name']:$_lang['order_noapi'],
		$v['price']>0?$v['price']:'-',
		'<a href="home.php?mod=space&uid='.$v['uid'].'" target="_blank">'.$v['username'].'</a>',
		dgmdate($v['dateline'], 'dt'),
		$v['finishdateline']?dgmdate($v['finishdateline'], 'dt'):'-',
		$v['payorderid']?$paycredit->getAllPayInfo()[explode(':',$v['payorderid'])[0]]['title']:'-',
		$v['payorderid']?explode(':',$v['payorderid'])[1]:'-',
		$v['status']==1?$_lang['order_ispay']:($v['status']==2?$_lang['order_wait']:$_lang['order_nopay']),
		)
	);
}
$mpurl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=payorder&searchtype='.$searchtype.'&search='.$search;
$multipage = multi($count, $perpage, $page, $mpurl);
showsubmit('submit', 'submit', 'del', '', $multipage);
showtablefooter();
showformfooter();
?>