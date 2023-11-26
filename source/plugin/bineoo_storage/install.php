<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/function.php';
require_once libfile('function/cloudaddons');
$request_url=str_replace('&step='.$_GET['step'],'',$_SERVER['QUERY_STRING']);
showsubmenusteps($pluginarray['plugin']['name'], array(
	array($installlang['step_1'], !$_GET['step']),
	array($installlang['step_2'], $_GET['step'] == 'sql'),
	array($installlang['step_3'], $_GET['step'] == 'stat' || $_GET['step']=='ok'),
));
if($_GET['step']){
	sleep(1);
}
switch($_GET['step']){
	default:
	case 'check':
		/*$addonid = $pluginarray['plugin']['identifier'].'.plugin';
		$array = cloudaddons_getmd5($addonid);
		if(cloudaddons_open('&mod=app&ac=validator&addonid='.$addonid.($array !== false ? '&rid='.$array['RevisionID'].'&sn='.$array['SN'].'&rd='.$array['RevisionDateline'] : '')) === '0') {
			cloudaddons_cleardir(DISCUZ_ROOT . './source/plugin/bineoo_storage');
			cpmsg('cloudaddons_file_read_error', '', 'error', array('addonid' => $addonid));
		}*/
		cpmsg($installlang['step_4'], "{$request_url}&step=sql", 'loading', array());
		break;
	case 'sql':
		$sql = <<<EOF
		
		CREATE TABLE IF NOT EXISTS pre_bineoo_storage_bucket (
			`bucket` varchar(255) NOT NULL DEFAULT '',
			`region` varchar(255) NOT NULL DEFAULT '',
			`acl` varchar(255) NOT NULL DEFAULT '',
			`domain` varchar(255) NOT NULL DEFAULT '',
			`create_time` int(11) unsigned NOT NULL DEFAULT '0',
			`image_style` varchar(255) NOT NULL DEFAULT '',
			`internal` tinyint(1) NOT NULL DEFAULT '0',
			`default` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`bucket`)
		) ENGINE=MyISAM;
EOF;

		runquery($sql);
		$data = array(
			'Access_Key_ID' => '',
			'Access_Key_Secret' => '',
			'region_list' => $installlang['region_list'],
			'manage_uids' => 1,
			'close_max' => 1,
			'cache_tag' => 'footer',
			'except_tag' => "'",
		);
		savecache('bineoo_storage_setting',serialize($data));
		if(function_exists('check_bineoo_md5')){
			check_bineoo_md5();
		}
		cpmsg($installlang['step_5'], "{$request_url}&step=stat", 'loading', array('operation' => $s_installlang[$operation]));
		break;
	case 'stat':
		cpmsg($installlang['step_6'], "{$request_url}&step=ok", 'loading', array('stat_code' => $code));
		break;
	case 'ok':
		$finish = true;
		break;

}
?>