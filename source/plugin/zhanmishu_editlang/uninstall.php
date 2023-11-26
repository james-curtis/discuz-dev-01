<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc && plugin by zhanmishu.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Author: zhanmishu.com $
 *    qq:87883395 $
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$path = DISCUZ_ROOT.'data/sysdata/';
if(is_dir($path)){
	if($dh = opendir($path)){
		while(($file = readdir($dh)) != false){
			if(strpos($file,'zhanmishu_editlang')){
				$a = @unlink($path.$file);
				echo $file;
				var_dump($a);
			}

		}
		closedir($dh);
	}
}



$finish = true;


?>