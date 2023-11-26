<?php
/**
 *	[附件打折和下载限制(threed_dazhe.{modulename})] (C)2015-2099 Powered by 3D设计者.
 *	Version: 商业版
 *	Date: 2015-5-18 12:12
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
global $_G;
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");

$pan_option = $_G['cache']['plugin']['threed_dazhe'];
if ($_GET['formhash'] != FORMHASH)
    showmessage(lang('plugin/threed_dazhe', 'downld2'), array(), array(), array('alert' =>
            'error'));
$uid = $_G['uid'];
require_once libfile('function/threed','plugin/threed_dazhe');
checksite();
$aid = intval($_GET['aid']);
$daydownnum =intval($_GET['daynum']); //代表24小时内的下载数
if(!$aid) showmessage(lang('plugin/threed_dazhe', 'downld2'), array(), array(), array('alert' =>
                   'error'));
$tableid = DB::result_first("SELECT tableid FROM " . DB::table('forum_attachment') .
    " WHERE aid='$aid' LIMIT 1");
$tableid = $tableid >= 0 && $tableid < 10 ? intval($tableid) : 127;
$table = "forum_attachment_" . $tableid;
$attach = DB::fetch_first("SELECT * FROM " . DB::table($table) . " WHERE aid=" .
    $aid . " and isimage<>1 ORDER BY aid asc ");
//print_r($_G['cache']['usergroup_11']);
if(empty($attach)) showmessage(lang('plugin/threed_dazhe', 'downld2'), array(), array(), array('alert' =>
                   'error'));
$tid = intval($attach['tid']);
//if ($_G['cache']['usergroup_' . $_G['groupid']]['readaccess'] < $attach['readperm'])
//    showmessage($pan_option['thd_power'], array(), array(), array('alert' => 'error'));
$pan_user = $pan_option["thd_user"];
$pan_zhekou = array();
$pan_zheokou_temp = explode(",", $pan_user);
foreach ($pan_zheokou_temp as $listk => $listv) {
    $listv_temp = explode("|", $listv);
    $pan_zhekou[0][$listk] = intval($listv_temp[0]?$listv_temp[0]:1);
    $pan_zhekou[1][$listk] = round(($listv_temp[1]?$listv_temp[1]:0),1);
    $pan_zhekou[2][$listk] = intval($listv_temp[2]?$listv_temp[2]:0);
    $pan_zhekou[3][$listk] = round(($listv_temp[3]?$listv_temp[3]:0),1);
}
$pan_yuanjia = $pan_zhejia=$attach['price'];
$pan_downnum=0;
foreach ($pan_zhekou[0] as $listk => $listv) {
    if ($_G['groupid'] == $listv) {
        $pan_downnum = $pan_zhekou[2][$listk];
        $pan_over=$pan_zhekou[3][$listk];
        if($daydownnum>=$pan_downnum){
            if($pan_over<0){
                showmessage(lang('plugin/threed_dazhe', 'index2').$pan_downnum,"forum.php?mod=viewthread&tid=$tid", array(), array(), array('alert' =>
                'info'));
            }else{
            $pan_zhejia = intval($pan_zhekou[3][$listk] * $pan_yuanjia / 10);
            }
        }else{
            $pan_zhejia = intval($pan_zhekou[1][$listk] * $pan_yuanjia / 10);
        }
        break;
    }
}

$buy_credit = $_G['setting']['creditstransextra'][1];
$user_creditnum = DB::result_first("select extcredits" . $buy_credit . " from " .
    DB::table('common_member_count') . " where uid=" . $uid);
$buy_creditname = $_G['setting']['extcredits'][$buy_credit]['title'];
$buy_username = DB::result_first("select username from " . DB::table('common_member') .
    " where uid=" . $uid);
$saleid = $attach['uid'];
$sale_username = DB::result_first("select username from " . DB::table('common_member') .
    " where uid=" . $saleid);
$yuxia = $user_creditnum - $pan_zhejia;
$sale_get = intval($pan_zhejia * (1 - $_G['setting']['creditstax']));


if ($_GET['ac'] == "buy") {
    if ($uid == 0) {
        showmessage(lang('plugin/threed_dazhe', 'downld3'), '', array(), array('alert' =>
                'info', 'login' => 1));
    } else {
        include template('threed_dazhe:pay');
    }
}
elseif ($_GET['ac'] == "pay") {
    if ($user_creditnum < $pan_zhejia) {
        showmessage($pan_option['thd_credit'], array(), array(), array('alert' => 'info'));
    }
    $buycount = DB::result_first("SELECT count(*) FROM " . DB::table('common_credit_log') .
        " WHERE relatedid='$attach[aid]' AND  uid='$uid' AND operation='BAC'");

    if ($buycount == 0) {
        $allcount = DB::result_first("SELECT count(*) FROM " . DB::table('common_credit_log') .
            " WHERE relatedid='$attach[aid]' AND operation='BAC'");
        $allcount = $allcount * $pan_zhejia;
        if ($allcount > $pan_option['thd_up']) {
            $sale_get = 0;
        }
        updatemembercount($uid,array('extcredits'.$buy_credit=>'-'.$pan_zhejia),true,'BAC',$attach['aid']);
        updatemembercount($saleid,array('extcredits'.$buy_credit=>'+'.$sale_get),true,'SAC',$attach['aid']);
        showmessage(lang('plugin/threed_dazhe', 'downld4'),"forum.php?mod=viewthread&tid=$tid", dreferer(), array(), array(
               'alert' => 'info',
               'showdialog' => 1,
                'locationtime' => false));

    } else {
        showmessage(lang('plugin/threed_dazhe', 'downld5'),"forum.php?mod=viewthread&tid=$tid", array(), array(), array('alert' =>
                'info'));
    }
}
else{
    showmessage(lang('plugin/threed_dazhe', 'downld2'),"forum.php?mod=viewthread&tid=$tid", array(), array(), array('alert' =>
                   'error'));
}
?>