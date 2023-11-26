<?php
/**
 *	[网盘伪装成本地附件(threed_attach.{modulename})] (C)2015-2099 Powered by 3D设计者.
 *	Version: 商业版
 *	Date: 2015-5-18 12:12
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;
if($_GET['formhash']!=FORMHASH)showmessage(lang('plugin/threed_attach', 'downld2'),  array(), array(), array('alert' => 'error'));
$aid=base64_decode($_GET['aid']);
$aid=intval($aid);
$aid=($aid-131)/7-3;
if(!is_int($aid))showmessage(lang('plugin/threed_attach', 'downld2'),  array(), array(), array('alert' => 'error'));
$info = DB::result_first("SELECT tableid,tid FROM " . DB::table('forum_attachment') .
            " WHERE aid='$aid' LIMIT 1");
$tableid = $info['tableid'];
$tid = $info['tid'];
$tableid = $tableid >= 0 && $tableid < 10 ? intval($tableid) : 127;
$table = "forum_attachment_" . $tableid;
$attach=DB::fetch_first( "SELECT * FROM " . DB::table($table) . " WHERE aid=" . $aid .
" and isimage<>1 ORDER BY aid asc ");

$buycount = DB::result_first("SELECT count(*) FROM " . DB::table('common_credit_log') .
        " WHERE relatedid='$attach[aid]' AND  uid='$uid' AND operation='BAC'");

if(empty($attach))showmessage(lang('plugin/threed_attach', 'downld4'),  array(), array(), array('alert' => 'error'));
//print_r($attach);
if ($_G['cache']['plugin']['dc_downlimit']['open'] == 1)
{
    include_once DISCUZ_ROOT.'./source/plugin/dc_downlimit/hook.class.php';
    $dc_downlimit = new plugin_dc_downlimit();
    $dc_downlimit->_pandownload($tid);

}
$pan_srl=$attach['attachment'];
$pan_type=0;
if(!$_G['cache']['plugin']['threed_attach']['thd_tiao']||$_GET['mobile']==2){
    DB::query('update '.DB::table('forum_attachment').' set downloads=downloads+1 where aid='.$aid);
    header("location:{$pan_srl}");
	die();
}
if(substr($pan_srl,0,20)=='http://pan.baidu.com'||substr($pan_srl,0,21)=='https://pan.baidu.com'||substr($pan_srl,0,22)=='https://eyun.baidu.com'){					
    $pan_type=1;
}elseif (substr($pan_srl, 0, 23) == 'http://share.weiyun.com'||substr($pan_srl, 0, 24) == 'https://share.weiyun.com') {
    $pan_type = 2;
}elseif(substr($pan_srl,0,16)=='http://yunpan.cn'||substr($pan_srl,0,17)=='https://yunpan.cn'){
    $pan_type=3;
}else {
    $pan_type=0;
}
$pan_srlname=str_replace('.pan','',$attach['filename']);
$pan_kofen=$_G['cache']['plugin']['threed_attach']['thd_koufen'];
$pan_user=unserialize($_G['cache']['plugin']['threed_attach']["thd_user"]);
$pan_auth=$attach['uid'];
$navtitle =$pan_srlname.lang('plugin/threed_attach', 'downld1');
$panbox_w=array(0,700,700,700,700,700,700,700);//下载框的长度
$panbox_h=array(0,390,300,400,240,400,400,400);//下载框的高度
$panbox_x=array(0,-30,40,-130,-30,-30,-20,-20);//X方向偏移距离
$panbox_y=array(0,-80,-120,-130,-40,-80,-80,-80);//Y方面偏移距离
$iframe_w=$panbox_w[$pan_type]-$panbox_x[$pan_type];
$iframe_h=$panbox_h[$pan_type]-$panbox_y[$pan_type];

if((!in_array($_G['groupid'], $pan_user))&&$pan_kofen&&$pan_auth!=$_G['uid']){
	$user_creditnum=DB::result_first("select extcredits".$_G['setting']['creditstransextra'][1]." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
    $kofen_info='';
	if($user_creditnum<=$pan_kofen){
	showmessage($_G['cache']['plugin']['threed_attach']['thd_credit'],  array(), array(), array('alert' => 'info'));
	}
	$buy_credit = $_G['setting']['creditstransextra'][1];
	$buy_creditname = $_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]]['title'];
	updatemembercount($_G['uid'],array('extcredits'.$buy_credit=>'-'.$pan_kofen));
    $kofen_info =$buy_creditname.'-'.$pan_kofen;
	$kofen_info.='&nbsp;,'.$_G['cache']['plugin']['threed_attach']['thd_downld'];
}else{
	$kofen_info=$_G['cache']['plugin']['threed_attach']['thd_downld'];
}
DB::query('update '.DB::table('forum_attachment').' set downloads=downloads+1 where aid='.$aid);//echo $_G['cache']['plugin']['dc_downlimit']['open'];exit;
include template('diy:downld', 0, 'source/plugin/threed_attach/template');
//TODO - Insert your code here
?>