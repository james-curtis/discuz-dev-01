<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$cvar = $_G['cache']['plugin']['dc_pay'];
if(!$cvar['open']||!$_G['setting']['extcredits'][$cvar['extcredit']]||(defined('IN_MOBILE')&&!$cvar['touchopen']))showmessage('undefined_action');
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
if(defined('IN_MOBILE')){
	$payobj = new Mobile_PayMent('dc_pay');
}else{
	$payobj = new PayMent('dc_pay');
}
$paytypes = $payobj->GetPayType();
$_lang =lang('plugin/dc_pay');
if(submitcheck('buycreditsubmit')){
    $addfundamount = intval($_GET['addfundamount']);
	$amount = $addfundamount/$cvar['bl'];

    //付款金额是否小于0
	if($amount<0)showmessage($_lang['buycreditamounterror']);
	//购买积分数量是否小于允许最小值
	if($cvar['lower']&&$addfundamount<$cvar['lower'])showmessage($_lang['buycreditamounttolower'].$cvar['lower']);
    //购买积分数量是否大于允许最大值
	if($cvar['larger']&&$addfundamount>$cvar['larger'])showmessage($_lang['buycreditamounttolarger'].$cvar['larger']);
	//本月购买积分数是否超出允许的最大值
    if ($cvar['month_larger'] && $payobj->getAllPayByUid($_G['uid'])+$addfundamount > $cvar['month_larger'])showmessage($_lang['buycredituptomonth'].$cvar['month_larger']);


	$paytype = trim($_GET['paytype']);
	if(!in_array($paytype,$paytypes)||!$paytype)showmessage('undefined_action');
	$goodname = $_lang['buycredit'].$amount.$_lang['yuan'].$_G['setting']['extcredits'][$cvar['extcredit']]['title'].$cvar['bl']*$amount;
	$payobj->SetPayType($paytype);
	$payobj->SetOrder('',$amount,$goodname,'','',array('extcredit'=>intval($cvar['extcredit']),'credit'=>$addfundamount,'amount'=>$amount));
	$payurl = $payobj->GetPayUrl();
	$requesturl = $payurl[$paytype];
	if(defined('IN_MOBILE')){
		header('location:'.$requesturl);die();
	}else{
		include template('common/header_ajax');
		echo '
		<form action="'.$requesturl.'" id="payforto'.$paytype.'" method="post"></form>
		<script type="text/javascript" reload="1">$(\'payforto'.$paytype.'\').submit();</script>
		';
		include template('common/footer_ajax');
		dexit();
	}
}
$payinfo = array();
$defaulept ='';
foreach($paytypes as $p){
	$payinfo[$p] = $payobj->GetPayInfo($p);
	if(!$defaulept)$defaulept=$p;
}
if(defined('IN_MOBILE'))
	include template('dc_pay:buycredit');
?>