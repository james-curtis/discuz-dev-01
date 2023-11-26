<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$url = 'action=plugins&operation=config&identifier=bineoo_storage&pmod=admin_setting';
loadcache('bineoo_storage_setting');
$oss_set = dunserialize($_G['cache']['bineoo_storage_setting']);
if(submitcheck('editsubmit')) {
	if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
		cpmsg(lang('plugin/bineoo_storage', 'error_hash'),$url,'error');
	}
	$data = $oss_set;
	$data['Access_Key_ID'] = addslashes(trim(strip_tags($_GET['Access_Key_ID'])));
	$data['Access_Key_Secret'] = addslashes(trim(strip_tags($_GET['Access_Key_Secret'])));
	$data['region_list'] = addslashes(trim(strip_tags($_GET['region_list'])));
	$data['manage_uids'] = addslashes(trim(strip_tags($_GET['manage_uids'])));
	$data['direct_upload'] = intval($_GET['direct_upload']);
	$data['direct_oss'] = intval($_GET['direct_oss']);
	$data['close_max'] = intval($_GET['close_max']);
	$data['cache_tag'] = addslashes(trim(strip_tags($_GET['cache_tag'])));
	$data['except_tag'] = addslashes(trim(strip_tags($_GET['except_tag'])));
	$data['extra_tag'] = addslashes(trim(strip_tags($_GET['extra_tag'])));

	if(stripos($data['Access_Key_ID'], '********') !== false){
		$data['Access_Key_ID'] = $oss_set['Access_Key_ID'];
	}
	if(stripos($data['Access_Key_Secret'], '********') !== false){
		$data['Access_Key_Secret'] = $oss_set['Access_Key_Secret'];
	}
	savecache('bineoo_storage_setting',serialize($data));
	cpmsg(lang('plugin/bineoo_storage', 'admin_setting_succeed'), $url, 'succeed');
}


$oss_set['Access_Key_ID'] = $oss_set['Access_Key_ID'] ? substr($oss_set['Access_Key_ID'],0,5).'********'.substr($oss_set['Access_Key_ID'],-5) : '';
$oss_set['Access_Key_Secret'] = $oss_set['Access_Key_ID'] ? substr($oss_set['Access_Key_Secret'],0,5).'********'.substr($oss_set['Access_Key_Secret'],-5) : '';
$oss_set['except_tag'] = stripslashes($oss_set['except_tag']);
showformheader('plugins&operation=config&identifier=bineoo_storage&pmod=admin_setting', 'enctype');
	showtips(lang('plugin/bineoo_storage', 'admin_setting_tips'), 'tips', true, 'tips');
	showtableheader(lang('plugin/bineoo_storage', 'admin_setting_title'),'','');
		showsetting(lang('plugin/bineoo_storage', 'manage_uids'), 'manage_uids', $oss_set['manage_uids'], 'text','',0,lang('plugin/bineoo_storage', 'manage_uids_content'));
		showsetting('Access Key ID', 'Access_Key_ID', $oss_set['Access_Key_ID'], 'text','',0,lang('plugin/bineoo_storage', 'Access_Key_ID_content'));
		showsetting('Access Key Secret', 'Access_Key_Secret', $oss_set['Access_Key_Secret'], 'text','',0,lang('plugin/bineoo_storage', 'Access_Key_Secret_content'));
		showsetting(lang('plugin/bineoo_storage', 'region_list_title'), 'region_list', $oss_set['region_list'], 'textarea','',0,lang('plugin/bineoo_storage', 'region_list_content'));
		showsetting(lang('plugin/bineoo_storage', 'direct_upload'), 'direct_upload', $oss_set['direct_upload'], 'radio','',0,lang('plugin/bineoo_storage', 'direct_upload_content'));
		showsetting(lang('plugin/bineoo_storage', 'direct_oss'), 'direct_oss', $oss_set['direct_oss'], 'radio','',0,lang('plugin/bineoo_storage', 'direct_oss_content'));
	showtablefooter();

	showtableheader(lang('plugin/bineoo_storage', 'rep_mod'));
		showsetting(lang('plugin/bineoo_storage', 'close_max_mod'), 'close_max', $oss_set['close_max'], 'radio', 0, 1);
		showsetting(lang('plugin/bineoo_storage', 'cache_tag'), 'cache_tag', $oss_set['cache_tag'], 'textarea','',0,lang('plugin/bineoo_storage', 'cache_tag_content'));
	showtagfooter('tbody');

	showtableheader(lang('plugin/bineoo_storage', 'except_tag_head'));
		showsetting(lang('plugin/bineoo_storage', 'except_tag_title'), 'except_tag', $oss_set['except_tag'], 'textarea','',0,lang('plugin/bineoo_storage', 'except_tag_content'));
		showsetting(lang('plugin/bineoo_storage', 'extra_tag_title'), 'extra_tag', $oss_set['extra_tag'], 'textarea','',0,lang('plugin/bineoo_storage', 'extra_tag_content'));
	showtagfooter('tbody');
	showsubmit('editsubmit');
showformfooter();