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
if ($_GET['ac'] == "nodown") {
    showmessage(lang('plugin/threed_dazhe', 'index2').$_GET['pannum'].lang('plugin/threed_dazhe', 'index1'),array(), array(), array('alert' => 'error'));
    };
if ($_GET['ac'] == "noaccess") {
    showmessage($_G['cache']['plugin']['threed_dazhe']['thd_power'], array(), array(), array('alert' => 'error','login'=>1));
}
//TODO - Insert your code here

?>