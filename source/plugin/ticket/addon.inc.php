<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$addon = DISCUZ_ROOT . './source/plugin/ticket/extend/addon_admin.php';
if(!file_exists($addon)) cpmsg(lang('plugin/ticket', 'slang27'), '', 'error');
loadcache('plugin');
if(!$_G['cache']['plugin']['dzlab_developer']) cpmsg(lang('plugin/ticket', 'slang28'), '', 'error');
require $addon;
?>