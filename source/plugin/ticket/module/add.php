<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$t_id = intval($_GET['type']);
if(!$t_id) showmessage(lang('plugin/ticket', 'slang07'));
$type = C::t("#ticket#types")->fetch($t_id);
if(!$type) showmessage(lang('plugin/ticket', 'slang07'));
if($admin) showmessage(lang('plugin/ticket', 'slang08'));

$extend = str_replace("\r\n", "\n", $pVars['extend']);
$ruler = explode("\n",$extend);
$extends = array();
foreach ($ruler as $v) {
	$extends[] = explode("=", $v);
}

$addon = DISCUZ_ROOT . './source/plugin/ticket/extend/addon.php';
if(file_exists($addon) && $_G['cache']['plugin']['dzlab_developer']) require $addon;

if(submitcheck("submit")){
	$data = array();
	if(!strip_tags($_GET['content'])) showmessage(lang('plugin/ticket', 'slang09'));
	//扩展字段处理
	$extendArray = array();
	foreach ($extends as $v) {
		$extendArray[$v[1]] = addslashes($_GET[$v[1]]);
		if($v[3] && $_GET[$v[1]] && !preg_match_all("/".$v[3]."/", $_GET[$v[1]], $matches)){
			showmessage($v[0].lang('plugin/ticket', 'slang46'));
		}
		if($v[2] && !$_GET[$v[1]])
			showmessage(lang('plugin/ticket', 'slang10').$v[0]);
	}
	if($addons){
		if(!$_GET['addon']) showmessage(lang('plugin/ticket', 'slang11'));
		if(!$_GET['siteuid']) showmessage(lang('plugin/ticket', 'slang12'));
		$result = checkUser($_GET['addon'],$_GET['siteuid']);
		$extendArray['dzlab_addon'] = $result['addon'];
		$extendArray['dzlab_siteurl'] = $result['siteurl'];
	}
	$data['extends']	= serialize($extendArray);
	$data['content'] 	= addslashes($_GET['content']);
	$data['dateline']	= $_G['timestamp'];
	$data['lastline']	= $_G['timestamp'];
	$data['uid']		= $_G['uid'];
	$data['username']	= $_G['username'];
	$data['t_id']		= $t_id;
	C::t("#ticket#main")->insert($data);
	showmessage(lang('plugin/ticket', 'slang13'),"plugin.php?id=ticket&mod=list&type=1");
}
?>