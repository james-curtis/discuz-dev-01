<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc && plugin by zhanmishu.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Author: zhanmishu.com $
 *    	qq:87883395 $
 */	

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

function get_langdata($langtype){
	$data = C::t('common_syscache')->fetch($langtype);

	return $data;
}
function get_pluginlang(){
	$data = get_langdata('pluginlanguage_script');
	return $data;
}

function get_templatelang(){
	$data = get_langdata('pluginlanguage_template');
	return $data;
}

function get_installlang(){
	$data = get_langdata('pluginlanguage_install');

	return $data;
}

function get_avaplugin(){
	$plugin = DB::fetch_all('select * from '.DB::table('common_plugin').' where available=1 order by pluginid desc',array(),'pluginid');
	return $plugin;
}

function zmsshowtitle($name,$array=array()){
	if (empty($array)) {
		return '';
	}

	$str = '<div class="itemtitle"><h3>'.$name.'</h3><ul class="tab1">';
	foreach ($array as $key => $value) {
		$class=$value[2] =='1'?' class="current"':'';
		$str .= '<li'.$class.'><a href='.'"admin.php?action='.$value[1].'"><span>'.$value[0].'</span></a></li>';
	}
	$str .='</ul></div>';
	echo $str;
}

function zhanmishu_showinput($langdata=array()){
	if (empty($langdata) || !is_array($langdata)) {
		return false;
	}
	foreach ($langdata as $key => $value) {
		showsetting($value,$key,$value,'area',0,0,0,'');
	}
}
?>