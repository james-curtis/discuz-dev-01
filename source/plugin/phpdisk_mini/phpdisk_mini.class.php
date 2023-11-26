<?php
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: phpdisk_mini.class.php 27 2014-08-29 12:58:24Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ')) {
	exit('[PHPDISK] Access Denied');
}
class plugin_phpdisk_mini {

}

class plugin_phpdisk_mini_forum extends plugin_phpdisk_mini {
	function post_editorctrl_left() {
		global $_G;
		//print_r($_G['cache']['plugin']['phpdisk']);
		if(in_array($_G[groupid],unserialize($_G['cache']['plugin']['phpdisk_mini']['use_disk_gid']))){
			$phpdisk_plugin_dir = 'source/plugin/phpdisk_mini';
			$str = '<!-- phpdisk_mini upload plugin v2.5 -->
<link rel="stylesheet" href="'.$phpdisk_plugin_dir.'/images/plugin.css"/>
<script type="text/javascript" src="'.$phpdisk_plugin_dir.'/includes/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">var jq = jQuery.noConflict();</script>
<style type="text/css">#a_phpdisk_mini_btn{background:url("'.$phpdisk_plugin_dir.'/images/updisk.gif") no-repeat;width:30px; height:15px; display:block; float:left;}.b2r #a_phpdisk_mini_btn{background:url("'.$phpdisk_plugin_dir.'/images/upload_file_icon.gif") no-repeat scroll center;width:20px; height:20px; display:block; float:left;}</style>
<a href="javascript:;" onclick="showWindow(\'phpdisk_pbox\', \'plugin.php?id=phpdisk_mini:file_box&action=plugin_box\',\'get\',1);" id="a_phpdisk_mini_btn" title="'.$_G['cache']['plugin']['phpdisk_mini']['phpdisk_title'].'"></a>
<script type="text/javascript">';
			if($_G['cache']['plugin']['phpdisk_mini']['hide_attach']){
				$str .= 'jq("#e_attach").hide();';
			}
			$str .= '</script>
<!-- phpdisk_mini .. end -->';
		}else{
			$str = '';
		}
		return $str;
	}

}

?>
