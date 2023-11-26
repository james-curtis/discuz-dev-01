<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: api.inc.php 956 2015-01-21 10:28:15Z wang11291895@163.com $
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$_lang = lang('plugin/dc_pay');
$data = C::t('#dc_pay#dc_pay_api')->range(0,0);
$plugins = DB::fetch_all('SELECT identifier, name FROM %t', array('common_plugin'), 'identifier');
$act = $_GET['act'];
if($act=='add'){
	if(submitcheck('submit')){
		$identifier = trim($_GET['api_identifier']);
		if($identifier&&$plugins[$identifier]){
			if($data[$identifier])cpmsg($_lang['api_isready'], '', 'error',array('pluginname'=>$plugins[$identifier]['name']));
			$file = trim($_GET['api_file']);
			$class = trim($_GET['api_class']);
			$notifymethod = trim($_GET['api_notifymethod']);
			$returnmethod = trim($_GET['api_returnmethod']);
			$payok = trim($_GET['api_payok']);
			if(!preg_match("/^[a-z0-9_\-.]+$/i", $file))cpmsg($_lang['error'], '', 'error');
			if(!preg_match("/^[a-z0-9_\-]+$/i", $class))cpmsg($_lang['error'], '', 'error');
			if(!preg_match("/^[a-z0-9_\-]+$/i", $notifymethod))cpmsg($_lang['error'], '', 'error');
			if(!preg_match("/^[a-z0-9_\-]+$/i", $returnmethod))cpmsg($_lang['error'], '', 'error');
			if($payok&&!preg_match("/^[a-z0-9_\-]+$/i", $payok))cpmsg($_lang['error'], '', 'error');
			if(!file_exists(DISCUZ_ROOT.'./source/plugin/'.$identifier.'/'.$file)||strlen($file)<10||substr($file,-9)!='class.php')cpmsg($_lang['api_noclassfile'], '', 'error');
			$d = array(
				'plugin'=>$identifier,
				'include'=>$file,
				'class'=>$class,
				'returnmethod'=>$notifymethod,
				'notifymethod'=>$returnmethod,
				'payok'=>$payok,
				'ishand'=>1,
			);
			C::t('#dc_pay#dc_pay_api')->insert($d);
			cpmsg($_lang['add'].$_lang['succeed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api', 'succeed');
		}
		cpmsg($_lang['error'], '', 'error');
	}
	$pluginsarr = array();
	foreach($plugins as $p){
		if(!$data[$p['identifier']])
			$pluginsarr[] = array($p['identifier'],$p['name']);
	}
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api&act=add');
	showtableheader($_lang['add'].$_lang['api'],'');
	showsetting($_lang['api_pluginname'],array('api_identifier',$pluginsarr),'','select');
	showsetting($_lang['api_file'],'api_file','','text');
	showsetting($_lang['api_class'],'api_class','','text');
	showsetting($_lang['api_notifymethod'],'api_notifymethod','donotify','text');
	showsetting($_lang['api_returnmethod'],'api_returnmethod','doreturn','text');
	showsetting($_lang['api_payok'],'api_payok','','text','','',$_lang['api_payok_msg']);
	showsubmit('submit', 'submit');
	showtablefooter();
	showformfooter();
	dexit();
	
}elseif($act=='delete'){
	$pid = trim($_GET['pid']);
	$plugin = C::t('#dc_pay#dc_pay_api')->fetch($pid);
	if(empty($plugin)||!$plugin['ishand'])cpmsg($_lang['error'], '', 'error');
	if(submitcheck('submit')){
		C::t('#dc_pay#dc_pay_api')->delete($pid);
		cpmsg($_lang['delok'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api', 'succeed');
	}
	cpmsg($_lang['api_delcheck'],'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api&act=delete&pid='.$pid.'&submit=yes','form',array('pluginname'=>$plugins[$pid]['name']));
	dexit();
}
showtips($_lang['apitips'], '');
showtableheader($_lang['api'].'(<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api&act=add">'.$_lang['add'].'</a>)', '');
showsubtitle(array('',$_lang['api_pluginname'], $_lang['api_file'],$_lang['api_class'],$_lang['api_notifymethod'],$_lang['api_returnmethod'],$_lang['caozuo']));
foreach($data as $k => $d){
	if(!$plugins[$k]){
		C::t('#dc_pay#dc_pay_api')->delete($k);
	}else{
		showtablerow('', array('width="20"'), array('',$plugins[$k]['name'],$d['include'],$d['class'],$d['notifymethod'],$d['returnmethod'],$d['ishand']?' [<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=api&act=delete&pid='.$d['plugin'].'">'.$_lang['delete'].'</a>]':'----'));
	}
	
}
showtablefooter();
?>