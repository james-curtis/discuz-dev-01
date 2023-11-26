<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

include_once DISCUZ_ROOT.'./source/plugin/zhanmishu_editlang/include/function.php';
$path = DISCUZ_ROOT.'data/sysdata/';
$head = 'cache_zhanmishu_editlang_$#';

$plugins = get_avaplugin();
$_GET['langtype'] = $_GET['langtype'] ? $_GET['langtype'] : 'pluginlanguage_template';
$url = 'admin.php?action=plugins&operation=config&identifier=zhanmishu_editlang&pmod=restore';

if (submitcheck('langrestoresubmit',true)) {
	$file = $path.$head.$_GET['langtype'].'$#'.$_GET['time'].'.php';
	require $file;
	if(!is_array($data) && empty($data)){
		cpmsg(lang('plugin/zhanmishu_editlang', 'cannot_restore'),'','error');
	}
	C::t("common_syscache")->update($_GET['langtype'],$data);
	cpmsg(lang('plugin/zhanmishu_editlang', 'restore_success'),'','success');
	exit();
}

if (submitcheck('langdelsubmit',true)) {
	$file = $path.$head.$_GET['langtype'].'$#'.$_GET['time'].'.php';

	$del = @unlink($file);

	if($del){
		cpmsg(lang('plugin/zhanmishu_editlang', 'del_success'),dreferer(),'success');
	}else{
		cpmsg(lang('plugin/zhanmishu_editlang', 'somethingwrong'),dreferer(),'error');
	}
	exit();
}

cpheader();
//read backups
$backs = array();

if(is_dir($path)){
	if($dh = opendir($path)){
		while(($file = readdir($dh)) != false){
			if(strpos($file,'zhanmishu_editlang')){
				$back = explode('$#',$file);
				$back[2] = intval($back[2]);
				if($back[2]){
					unset($back[0]);
					$backs[] = $back;
				}
			}

		}
		closedir($dh);
	}
}

showtableheader(lang('plugin/zhanmishu_editlang', 'backs'));
showsubtitle(array(lang('plugin/zhanmishu_editlang', 'back_cat'), lang('plugin/zhanmishu_editlang', 'back_time'), lang('plugin/zhanmishu_editlang', 'back_str'),lang('plugin/zhanmishu_editlang', 'act')));
foreach(array_reverse($backs) as $key=>$value){
	$value[0] = 'data/sysdata/'.$head.$value[1].'$#'.$value[2].'.php';
	
	$updateurl = $url.'&act=restore&langrestoresubmit=yes&time='.$value[2].'&formhash='.FORMHASH.'&langtype='.$value[1];
	$delurl = $url.'&langdelsubmit=yes&act=del&time='.$value[2].'&formhash='.FORMHASH.'&langtype='.$value[1];
	$value[1] = lang('plugin/zhanmishu_editlang', $value[1]);

	
	$value[3] = '<a href="'.$updateurl.'" onclick="url = this.href;showDialog(\''.lang('plugin/zhanmishu_editlang', 'isrestore').'\',\'confirm\',\'\',\'location.href=url\');return false;">'.lang('plugin/zhanmishu_editlang', 'restore').'</a>&nbsp&nbsp&nbsp&nbsp;<a href="'.$delurl.'" onclick="url = this.href;showDialog(\''.lang('plugin/zhanmishu_editlang', 'isdel').'\',\'confirm\',\'\',\'location.href=url\');return false;">'.lang('plugin/zhanmishu_editlang', 'del').'</a>';
	$value[2] = date("Y-m-d H:m:s",$value[2]);
	showtablerow('class="partition"',array('class="td15"', 'class="td28"'),$value);
}
showtablefooter();



?>