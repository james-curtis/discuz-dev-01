<?php
if (!defined('IN_ADMINCP') || !defined('IN_DISCUZ'))exit('Access Denied');

if(!submitcheck('insertData')) {

    showtableheader(lang('plugin/keke_group', 'lang56'));
    showformheader('plugins&operation=config&do='.$pluginid.'&pmod=repeats&identifier=keke_group');
    showsetting(lang('plugin/keke_group', 'lang54'),'userid','','text');
    showsetting(lang('plugin/keke_group', 'lang55'),'groupid','','text');
    showsetting(lang('plugin/keke_group', 'lang57'),'only_data','1');
    showsubmit('insertData');
    showformfooter();
    //表格结束
    showtablefooter();

}

if(submitcheck('insertData', 1)) {
    $keke_group = $_G['cache']['plugin']['keke_group'];
    include_once DISCUZ_ROOT . "./source/plugin/keke_group/common.php";
    $groupid = $_POST['groupid'];
    $gorupdata = getgroupdata($groupid);
    $orderid = _orderid();
    $tradeno = dgmdate(TIMESTAMP, 'YmdHis') . rand(10, 99);
    _instorder($orderid, $gorupdata['money'], 1, $gorupdata['groupid'], $gorupdata['groupname'], $gorupdata['time']);
    if ($_POST['only_data'] == 0)
    {
        _upuserdata($orderid,$tradeno);

    }
    else{
        $orderarr = array(
            'state' => 1,
            'zftime' => $_G['timestamp'],
            'sn' => $tradeno,
            'opid' => '',
            'groupinvalid' => TIMESTAMP
        );
        C::t('#keke_group#keke_group_orderlog')->update($orderid, $orderarr);
    }

    showtableheader(lang('plugin/keke_group', 'lang56'));
    showtablerow('','',lang('plugin/keke_group', 'lang58'));
    showtablefooter();
}