<?php

define('IN_API', true);
define('CURSCRIPT', 'api');
define('DISABLEXSSCHECK', true);
define('ALLDIR','./source/plugin/bineoo_storage');

require './../../../../source/class/class_core.php';
$discuz = C::app();
$discuz->init();

if(!$_GET['sign'] || $_GET['sign'] != md5($_G['config']['security']['authkey'].md5($_GET['uid'].$_GET['timestamp']))){
	header("http/1.1 403 Forbidden");
	exit();
}
loadcache('groupreadaccess');
require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/function.php';

list($ossClient,$oss_set) = oss_client();

$aid = C::t('forum_attachment')->insert(array(
	'uid'=>intval($_GET['uid']),
	'tableid'=>127,
),true);

$objectMeta = $ossClient->getObjectMeta($oss_set['bucket'], $_GET['filename']);

$filename = substr($objectMeta['content-disposition'],21,strlen($objectMeta['content-disposition'])-22);

if(currentlang() == 'SC_GBK' || currentlang() == 'TC_BIG5'){
	$filename = diconv($filename,'UTF-8',$_G['charset']);
}
$attach = array(
	'aid'=>$aid,
	'uid'=>intval($_GET['uid']),
	'dateline'=>TIMESTAMP,
	'filename'=>$filename,
	'filesize'=>intval($_GET['filesize']),
	'attachment'=>str_replace($oss_set['attachurl'].'forum/', '', $_GET['filename']),
	'isimage'=>$_GET['type'] == 'image' ? 1 : (intval($_GET['width']) ? -1 : 0),
	'width'=>intval($_GET['width']),
);
C::t('forum_attachment_unused')->insert($attach);

require_once libfile('function/attachment');

$attach['filetype'] = attachtype(str_replace('jpeg', 'jpg', fileext($attach['filename']))."\t");
$attach['review_url'] = getforumimg($attach['aid'], 1, 300, 300, 'fixnone').'&ramdom='.random(5);

include_once template('bineoo_storage:attach_list');
if($_GET['type'] == 'image'){
	$html = image_result($attach);
}else{
	$html = attach_result($attach);
}
//$ossClient->putBucketAcl($bucket,'private');
header("Content-Type: application/json");
echo json_encode(array(
	'Status'=>'Ok',
	'aid'=>$aid,
	'html'=>diconv($html,$_G['charset'],'UTF-8'),
	'sign'=>md5($aid),
));
exit;