<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
if(!submitcheck('searchsubmit')) {

//加载discuz 时间js
    echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
//添加提示信息
    showtips(lang('plugin/xhw6_vip', 'tips'));
//表格开始
    showtableheader(lang('plugin/xhw6_vip', 'tableheader'));
    showformheader('plugins&operation=config&do='.$pluginid.'&pmod=buylog&identifier=xhw6_vip', 'testhd');
//showtableheader();  
//showsubmit('testhd', "aaaa", "订单状态".': <br/><input name="srchusername" value="'.htmlspecialchars($_GET['srchusername']).'" class="txt" />&nbsp;&nbsp;'.$Plang['repeat'].': <input name="srchrepeat" value="'.htmlspecialchars($_GET['srchrepeat']).'" class="txt" />', $searchtext);
    showsetting(lang('plugin/xhw6_vip', 'order_status'), array('order_status', array(
        array(0,lang('plugin/xhw6_vip', 'order_status_all')),
        array(1,lang('plugin/xhw6_vip', 'order_status_wait')),
        array(2, lang('plugin/xhw6_vip', 'order_status_sucess')),
    )), intval(order_status), 'select');
    showsetting( lang('plugin/xhw6_vip', 'username'),'username','','text');
    showsetting( lang('plugin/xhw6_vip', 'order_submitdate'), array('submitdatebegin', 'submitdateend'), array($sstarttime, $sendtime), 'daterange');
    showsetting( lang('plugin/xhw6_vip', 'order_confirmdate'), array('confirmbegin', 'confirmend'), array($cstarttime, $cendtime), 'daterange');
    showsubmit('searchsubmit');
    showformfooter();
//表格结束
    showtablefooter();

}
if(submitcheck('searchsubmit', 1)) {

    showtips(lang('plugin/xhw6_vip', 'tips'));
//表格开始
    showtableheader(lang('plugin/xhw6_vip', 'tableheader'));
    showtablerow("",'',array(lang('plugin/xhw6_vip', 'order_id'),lang('plugin/xhw6_vip', 'order_trade_no'),lang('plugin/xhw6_vip', 'order_status'),lang('plugin/xhw6_vip', 'username'),lang('plugin/xhw6_vip', 'order_group_name'),lang('plugin/xhw6_vip', 'order_valitidy'),lang('plugin/xhw6_vip', 'order_price'),lang('plugin/xhw6_vip', 'order_submitdate'),lang('plugin/xhw6_vip', 'order_confirmdate')));
    $order_status = $_GET['order_status'];
    $username = $_GET['username'];
    $submitdatebegin = $_GET['submitdatebegin'];

    $submitdateend = $_GET['submitdateend'];

    $confirmbegin = $_GET['confirmbegin'];

    $confirmend = $_GET['confirmend'];

    $alldata = C::t('#xhw6_vip#xhw6_vip')->fetch_orderCondition($order_status,$username,$submitdatebegin,$submitdateend,$confirmbegin,$confirmend);

    foreach($alldata as $value){

        if($value['validity'] ==0){
            $value['validity'] =lang('plugin/xhw6_vip', 'validityinfo');
        }
        switch ($value['order_status']) {
            case 1:
                $value['order_status'] =lang('plugin/xhw6_vip', 'order_status_wait');
                break;
            case 2:
                $value['order_status'] =lang('plugin/xhw6_vip', 'order_status_sucess');
                break;
        }
        if($value['submitdate']  ==0){
            $value['submitdate']  ='N/A';
        }else{
            $value['submitdate']  =  date('Y-m-d H:i:s',$value['submitdate'] );
        }

        if($value['confirmdate']  ==0){
            $value['confirmdate']  ='N/A';
        }else{
            $value['confirmdate']  = date('Y-m-d H:i:s',$value['confirmdate'] );
        }

        showtablerow("",'',array($value['order_id'],$value['trade_no'],$value['order_status'],$value['username'],$value['group_name'],$value['validity'],$value['price'],$value['submitdate'],$value['confirmdate']));
    }
    showtablefooter();
}