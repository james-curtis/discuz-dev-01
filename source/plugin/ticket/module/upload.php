<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$t_id = intval($_GET['t_id']);
if(!$t_id) showError(lang('plugin/ticket', 'slang21'));
$file = $_FILES['wangEditorH5File'];
if(!$file) showError(lang('plugin/ticket', 'slang22'));
if($file['error']) showError(lang('plugin/ticket', 'slang21'));
$upload = new discuz_upload();
$upload->init($file);
$upload->save();
//移动文件
$pic = $upload->attach['attachment'];
if(!$pic){
    showError(lang('plugin/ticket', 'slang23'));
}
$attachUrl = 'source/plugin/ticket/attach/'.$t_id."/";
dmkdir($attachUrl.date("Ym/d/"));
$res = rename("data/attachment/temp/".$pic,$attachUrl.date("Ym/d/").$pic);
if(!$res){
	showError(lang('plugin/ticket', 'slang23'));
}
echo $attachUrl.date("Ym/d/").$pic;exit;
function showError($msg){
	echo "error|".$msg; exit;
 
}
?>