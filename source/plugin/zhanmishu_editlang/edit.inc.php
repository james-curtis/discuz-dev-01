<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

include_once DISCUZ_ROOT.'./source/plugin/zhanmishu_editlang/include/function.php';
$plugins = get_avaplugin();
$_GET['langtype'] = $_GET['langtype'] ? $_GET['langtype'] : 'pluginlanguage_template';
$pluginsdefaultvalue = current($plugins);
$_GET['pluginid'] = $_GET['pluginid'] ? $_GET['pluginid'] : $pluginsdefaultvalue['pluginid'];


if (submitcheck('editlangsubmit') && $_GET['langtype']) {
	$input = daddslashes($_GET);
	$input['val'] = diconv($input['val'], 'UTF-8', CHARSET);
	$data = get_langdata($input['langtype']);

	//save lang backup
	require_once libfile('function/cache');
	$datacache = "\$data=".arrayeval($data).";\n";
	writetocache('zhanmishu_editlang_$#'.$input['langtype'].'$#'.TIMESTAMP, $datacache);
	if ($data[$plugins[$input['pluginid']]['identifier']][$input['lang']]) {
		$data[$plugins[$input['pluginid']]['identifier']][$input['lang']] = $input['val'];
		C::t("common_syscache")->update($_GET['langtype'],$data);
		exit();
	}
	exit();
}
cpheader();
showtips(lang('plugin/zhanmishu_editlang', 'editlangtips'));
include_once template('zhanmishu_editlang:admin');
$fmtplugins = array();
$url = 'plugins&operation=config&identifier=zhanmishu_editlang&pmod=edit&pluginid=';
foreach ($plugins as $key => $value) {
	$fmtplugins[$value['pluginid']] = array($value['name'],$url.$value['pluginid'],$status=$_GET['pluginid']==$value['pluginid']?'1':'0');
}
zmsshowtitle(lang('plugin/zhanmishu_editlang', 'please_choose_plugin'),$fmtplugins);
$url = $url.$_GET['pluginid'];
zmsshowtitle(lang('plugin/zhanmishu_editlang', 'please_choose_cat'),array(
	array(lang('plugin/zhanmishu_editlang', 'pluginlanguage_template'),$url.'&langtype=pluginlanguage_template',$status = $_GET['langtype'] =='pluginlanguage_template'?'1':'0'),
	array(lang('plugin/zhanmishu_editlang', 'pluginlanguage_script'),$url.'&langtype=pluginlanguage_script',$status = $_GET['langtype'] =='pluginlanguage_script'?'1':'0'),
	array(lang('plugin/zhanmishu_editlang', 'pluginlanguage_install'),$url.'&langtype=pluginlanguage_install',$status = $_GET['langtype'] =='pluginlanguage_install'?'1':'0'),
));

$data = get_langdata($_GET['langtype']);
$langdata = $data[$plugins[$_GET['pluginid']]['identifier']];

showtableheader();
foreach ($langdata as $key => $value) {

	echo '<tr><td colspan="2" class="td27" s="1">{'.$key.'}</td></tr>';
	echo '<tr class="noborder"><td class="vtop rowform"><textarea name="'.$key.'" rows="3" cols="30">'.$value.'</textarea></td><td><a class="btn editsubmit" href="javascript:;" title="'.$key.'">'.lang('plugin/zhanmishu_editlang', 'please_edit').'</a></td></tr>';
}
showtablefooter();


?>