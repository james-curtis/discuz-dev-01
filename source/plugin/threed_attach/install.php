<?php
/**
 *	[�������ۺ���������(threed_dazhe.{modulename})] (C)2015-2099 Powered by 3D�����.
 *	Version: ��ҵ��
 *	Date: 2015-5-18 12:12
 */
if(!defined('IN_ADMINCP')) exit('Access Denied');

$data="<?jsp exit('Access Denied');?>";
filedelate(DISCUZ_ROOT . './source/plugin/threed_attach/upgrade.php',$data);
filedelate(DISCUZ_ROOT . './source/plugin/threed_attach/install.php',$data);
function filedelate($filename,$data){
	if($fp=@fopen($filename,'wb')){
		fwrite($fp,$data);
		fclose($fp);
		return TRUE;
	}
	return FALSE;
}
//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>