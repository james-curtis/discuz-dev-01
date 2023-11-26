<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if($check=='aid'&&$aid) echo multi($count,$pagenum,$page,ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=nimba_attachlog&pmod=data&check=aid&aid=".$aid);
elseif($check=='uid'&&$uid) echo multi($count,$pagenum,$page,ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=nimba_attachlog&pmod=data&check=uid&uid=".$uid);
else echo multi($count,$pagenum,$page,ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=nimba_attachlog&pmod=data");
?>