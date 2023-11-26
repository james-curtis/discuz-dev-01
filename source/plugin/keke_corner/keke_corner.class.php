<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_keke_corner{
	
	function global_header(){
		global $_G;				
		$keke_corner = $_G['cache']['plugin']['keke_corner'];	
		$link=dhtmlspecialchars(empty($keke_corner['link'])? $_G['siteurl'] : $keke_corner['link']);
		$color=dhtmlspecialchars(empty($keke_corner['color'])? "#FFFFFF" : $keke_corner['color']);
		$content=dhtmlspecialchars(empty($keke_corner['content'])? "This is a text ad!" : $keke_corner['content']);
		
		include template('keke_corner:index');	
		return $return;
		
	}
}
