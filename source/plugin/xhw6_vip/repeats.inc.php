<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
//加载discuz 时间js
echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
//表格开始
if(!submitcheck('insertData')) {

    showtips(lang('plugin/xhw6_vip', 'tips'));
    showtableheader(lang('plugin/xhw6_vip', 'moni_data'));
    showformheader('plugins&operation=config&do='.$pluginid.'&pmod=repeats&identifier=xhw6_vip', 'testhd');
    showsetting(lang('plugin/xhw6_vip', 'user_id'),'userid','','text');
    showsetting(lang('plugin/xhw6_vip', 'group_id'),'groupid','','text');
    showsubmit('insertData');
    showformfooter();
//表格结束
    showtablefooter();

}
if(submitcheck('insertData', 1)) {


    $uid = $_GET['userid'];
    $groupid = $_GET['groupid'];

    $userName = C::t('common_member')->fetch_all_username_by_uid(array($uid));
    $groupinfo = C::t('common_usergroup')->fetch_all($groupid);

    showtips(lang('plugin/xhw6_vip', 'tips'));
    showtableheader(lang('plugin/xhw6_vip', 'moni_data'));
    if(!$userName){
        showtablerow ("","","<font color='red'><b>UserID is  not found</b></font>");
        exit();
    }
    if(!$groupinfo){
        showtablerow("","","<font color='red'><b>GroupID is not found</b></font>");
        exit();
    }
    $insertdata = array(
        'order_id' =>dgmdate(TIMESTAMP, 'YmdHis').random(20),
        'order_status' =>2,
        'uid' =>$uid,
        'username'=>$userName[$uid],
        'trade_no'=>dgmdate(TIMESTAMP, 'YmdHis').rand(10, 99),
        'group_id'=>$groupid,
        'group_name'=>$groupinfo[$groupid]['grouptitle'],
        'extcreditstitle'=> lang('plugin/xhw6_vip', 'extcreditstitle'),
        'extcredits'=> lang('plugin/xhw6_vip', 'extcredits'),
        'price'=>0.01,
        'validity'=>0,
        'submitdate'=>time(),
        'confirmdate'=>time(),
        'ip'=>$_G['clientip'],
        'pay_type'=>'alipay',
    );
    C::t('#xhw6_vip#xhw6_vip')->insert_data($insertdata);

    $strtmp= lang('plugin/xhw6_vip', 'tishi_info');
    showtablerow('','',"<font color='red'><b>$strtmp</b></font>");
    showtablefooter();


}