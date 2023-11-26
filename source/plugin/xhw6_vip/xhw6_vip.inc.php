<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/xhw6_vip/xhw6_vip.class.php';

function handlercjxixi_rule($arrayrule)
{
    //var_dump($arrayrule);
    $htmlshowInfo = array();
//	/$i=0;
    foreach($arrayrule as $value){
        $tmpArray = explode("||",$value);
        //	$tmpArray[id]=++$i;
        array_push($htmlshowInfo,$tmpArray);
    }
//	var_dump($htmlshowInfo);
    return $htmlshowInfo;

}

$pay_class = new xhw6_vip();
$set =  $_G['cache']['plugin']['xhw6_vip'];
$rule = handlercjxixi_rule(explode(PHP_EOL,$set['rule']));
$note =explode("\r\n",$set['note']);
$vipshow = $set['vipshow'];

$myname = $_G['username'];
$uid = $_G['uid'];
$grouptitle = $_G['group']['grouptitle'];

if($vipshow ==1) {
    $order_completed_data = C::t('#xhw6_vip#xhw6_vip')->fetch_ordercompleted_data();

    for ($i = 0; $i < count($order_completed_data); $i++) {
        $order_completed_data[$i]['submitdate'] = date('Y-m-d', $order_completed_data[$i]['submitdate']);
    }

}

if($_GET['action'] == 'buy'){
    if(!$_G['uid']) {
        showmessage('not_loggedin', NULL, array(), array('login' => 1));
    }

    if(!$pay_class->getPayType()){
        showmessage(lang('plugin/xhw6_vip', 'eccontractinfo'), NULL, array(), array());
        return;
    }

    $buyIdKey = $_GET['buyId'] ;
    $all_pay_types = $pay_class->getAllPayInfo();
    include template('common/header_ajax');
    @include template('xhw6_vip:go_pay_money');
    include template('common/footer_ajax');
}


if($_POST['action'] == 'toPay')
{
    if(!submitcheck('action')){
        echo 'Access Denied';
        return;
    }
    
    $buyIdKey = $_POST['IDKey'] ;
    if(!isset($rule[$buyIdKey]))
    {
        echo 'Access Denied';
        return;
    }
    //echo lang('plugin/xhw6_vip', 'skip_info');
    $subject ='';
    if($rule[$buyIdKey][3] ==0){
        $subject = $_G['member']['username'].' - '.lang('plugin/xhw6_vip', 'order_group_name').$rule[$buyIdKey][0].lang('plugin/xhw6_vip', 'validityinfo');
    }else{
        $subject = $_G['member']['username'].' - '.lang('plugin/xhw6_vip', 'order_group_name').$rule[$buyIdKey][0].$rule[$buyIdKey][3].lang('plugin/xhw6_vip', 'day');
    }

    $pay_type = in_array($_POST['bank_type'],$pay_class->getPayType())?$_POST['bank_type']:$pay_class->getPayType()[0];
    $price = floatval($rule[$buyIdKey][2]);
    $orderid = $pay_class->setOrder($price,$subject,$pay_type);

    $insertdata = array(
        'order_id' => $orderid,
        'order_status' =>1,
        'uid' =>$uid,
        'username'=>$myname,
        'trade_no'=>'',
        'group_id'=>$rule[$buyIdKey][1],
        'group_name'=>$rule[$buyIdKey][0],
        'extcreditstitle'=>$rule[$buyIdKey][4],
        'extcredits'=>$rule[$buyIdKey][5],
        'price'=>$rule[$buyIdKey][2],
        'validity'=>$rule[$buyIdKey][3],
        'submitdate'=>time(),
        'confirmdate'=>0,
        'ip'=>$_G['clientip'],
        'pay_type'=>$pay_type,
    );
    C::t('#xhw6_vip#xhw6_vip')->insert_data($insertdata);

    $pay_class->goPay();

}

include template('xhw6_vip:index');