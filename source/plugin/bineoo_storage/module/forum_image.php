<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//header('location: '.object_url('static/image/common/none.gif'));
if(!defined('IN_DISCUZ') || empty($_GET['aid']) || empty($_GET['size']) || empty($_GET['key'])) {
	header('location: '.object_url('static/image/common/none.gif'));
	exit;
}
global $_G;
$oss_set = $_G['bineoo_storage']['oss_set'];
$nocache = !empty($_GET['nocache']) ? 1 : 0;
$daid = intval($_GET['aid']);
$type = !empty($_GET['type']) ? $_GET['type'] : 'fixwr';
list($w, $h) = explode('x', $_GET['size']);
$dw = intval($w);
$dh = intval($h);
$attach = C::t('forum_attachment_n')->fetch('aid:'.$daid, $daid, array(1, -1));
$object = $oss_set['attachurl'].'forum/'.$attach['attachment'];
if($oss_set['direct_oss'] == 1) {
    if ($oss_set['image_style'])
    {
        dheader('location: '.$oss_set['domain'].$object.'/'.$oss_set['image_style']);
    }
    else
    {
        dheader('location: '.$oss_set['domain'].$object."?x-oss-process=image/resize,m_fill,h_$dw,w_$dh");
    }
	exit;
} else {
	if($_G['bineoo_storage']['ossClient']->doesObjectExist($oss_set['bucket'], $object)){
        if ($oss_set['image_style'])
        {
            dheader('location: '.$oss_set['domain'].$object.'/'.$oss_set['image_style']);
        }
        else
        {
            dheader('location: '.$oss_set['domain'].$object."?x-oss-process=image/resize,m_fill,h_$dw,w_$dh");
        }
        exit;
	}
}
	


$thumbfile = 'image/'.helper_attach::makethumbpath($daid, $dw, $dh);
$attachurl = helper_attach::attachpreurl();
if(!$nocache) {
	if(file_exists($_G['setting']['attachdir'].$thumbfile)) {
		dheader('location: '.$attachurl.$thumbfile);
	}
}
define('NOROBOT', TRUE);

$id = !empty($_GET['atid']) ? $_GET['atid'] : $daid;
if(dsign($id.'|'.$dw.'|'.$dh) != $_GET['key']) {
	dheader('location: '.object_url('static/image/common/none.gif'));
}

if($attach = C::t('forum_attachment_n')->fetch('aid:'.$daid, $daid, array(1, -1))) {
	if(!$dw && !$dh && $attach['tid'] != $id) {
	       dheader('location: '.object_url('static/image/common/none.gif'));
	}
        dheader('Expires: '.gmdate('D, d M Y H:i:s', TIMESTAMP + 3600).' GMT');
	if($attach['remote']) {
		$filename = $_G['setting']['ftp']['attachurl'].'forum/'.$attach['attachment'];
	} else {
		$filename = $_G['setting']['attachdir'].'forum/'.$attach['attachment'];
	}
	require_once libfile('class/image');
	$img = new image;
	if($img->Thumb($filename, $thumbfile, $w, $h, $type)) {
		if($nocache) {
			dheader('Content-Type: image');
			@readfile($_G['setting']['attachdir'].$thumbfile);
			@unlink($_G['setting']['attachdir'].$thumbfile);
		} else {
			dheader('location: '.$attachurl.$thumbfile);
		}
	} else {
		dheader('Content-Type: image');
		@readfile($filename);
	}
}
exit;