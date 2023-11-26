<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$_lang = lang('plugin/dc_pay');
$status = intval($_GET['status']);
$page = intval($_GET['page']);
$perpage = 20;
$start = ($page-1)*$perpage;
$where = array();
if(in_array($status,array(1,2,3))){
	$where = array('status'=>$status-1);
}
$plugins = DB::fetch_all('SELECT identifier, name FROM %t', array('common_plugin'), 'identifier');
$orders = C::t('#dc_pay#dc_pay_order')->getrange($where,$start,$perpage,'DESC');
$count = C::t('#dc_pay#dc_pay_order')->getcount($where);
$todaystart = strtotime(dgmdate(TIMESTAMP,'Y-m-d'));
$yesterdaystart = $todaystart-86400;
$nowday = DB::result_first('SELECT sum(price) FROM '.DB::table('dc_pay_order').' WHERE status=1 and dateline>'.$todaystart);
$yesterday = DB::result_first('SELECT sum(price) FROM '.DB::table('dc_pay_order').' WHERE status=1  and dateline>'.$yesterdaystart.' and dateline<'.$todaystart);
$mpurl = 'plugin.php?id=dc_pay';
$multipage = multi($count, $perpage, $page, $mpurl);
include template('dc_pay:index');
?>