<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']) showmessage('to_login', '', array(), array('login' => 1));
$mod = @$_GET['mod']?$_GET['mod']:"types";
$modArray = array('types','add','view','do','admin','list','upload');
if(!in_array($mod, $modArray)) $mod = 'types';
$pVars = $_G['cache']['plugin']['ticket'];
$groups = dunserialize($pVars['groups']);
$admin = in_array($_G['groupid'], $groups)?true:false;

$status = array(
	0 => lang('plugin/ticket', 'slang37'),
	1 => lang('plugin/ticket', 'slang38'),
	2 => lang('plugin/ticket', 'slang39'),
	4 => lang('plugin/ticket', 'slang30'),
	5 => lang('plugin/ticket', 'slang41'),
	6 => lang('plugin/ticket', 'slang42'),
	7 => lang('plugin/ticket', 'slang43'),
	8 => lang('plugin/ticket', 'slang44'),
	9 => lang('plugin/ticket', 'slang45'),
);

require DISCUZ_ROOT . './source/plugin/ticket/module/' . $mod . '.php';
include template("ticket:main");
?>