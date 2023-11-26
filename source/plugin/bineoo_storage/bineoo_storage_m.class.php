<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_bineoo_storage {
	
	function common(){
		global $_G;
		require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/function.php';
		list($_G['bineoo_storage']['ossClient'],$_G['bineoo_storage']['oss_set']) = oss_client();
		bineoo_cache();
	}

	function avatar($param){
		global $_G;
		$param = $param['param'];
		$uid = $param[0];
		$size = $param[1];
		$returnsrc = $param[2];
		$real = $param[3];
		$static = $param[4];
		$ucenterurl = $param[5];

		$url = 'plugin.php?id=bineoo_storage:misc&action=avatar';
		static $staticavatar;
		if($staticavatar === null) {
			$staticavatar = $_G['setting']['avatarmethod'];
		}

		$ucenterurl = empty($ucenterurl) ? $_G['setting']['ucenterurl'] : $ucenterurl;
		$ucenter_status = 0;
		if(stripos($ucenterurl, $_G['siteurl']) !== false){
			$ucenter_status = 1;
		}
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
		$uid = abs(intval($uid));
		if(!$staticavatar && !$static) {
			$avatar_url = $ucenterurl.'/avatar.php?uid='.$uid.'&size='.$size.($real ? '&type=real' : '');
			if($ucenter_status){
				$avatar_url = $url.'&ucenter='.urlencode(str_replace($_G['siteurl'], '', $ucenterurl)).'&uid='.$uid.'&size='.$size.($real ? '&type=real' : '');
			}
			$_G['hookavatar'] = $returnsrc ? $avatar_url : '<img src="'.$avatar_url.'" />';
		} else {
			$uid = sprintf("%09d", $uid);
			$dir1 = substr($uid, 0, 3);
			$dir2 = substr($uid, 3, 2);
			$dir3 = substr($uid, 5, 2);
			$file = $ucenterurl.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
			if($ucenter_status){
				$file = object_url(str_replace($_G['siteurl'], '', $file));
			}
			$_G['hookavatar'] = $returnsrc ? $file : '<img src="'.$file.'" onerror="this.onerror=null;this.src=\''.object_url(str_replace($_G['siteurl'], '', $ucenterurl.'/images/noavatar_'.$size.'.gif')).'\'" />';
		}
	}
}

class mobileplugin_bineoo_storage_forum extends mobileplugin_bineoo_storage {

	function ajax_bineoo_storage(){
		global $_G;
		if(!$_G['bineoo_storage']['ossClient']){
			return false;
		}
		if($_GET['action'] == 'deleteattach'){
			if(is_array($_GET['aids'])){
				$aids = array();
				foreach (DB::fetch_all('SELECT * FROM '.DB::table('forum_attachment').' WHERE '.DB::field('aid',$_GET['aids'])) as $data) {
					$aids[$data['tableid']][] = $data['aid'];
				}
				for ($i=0; $i <=10 ; $i++) { 
					if($i == 10){
						$i = 127;
					}
					if($aids[$i]){
						foreach (C::t('forum_attachment_n')->fetch_all($i,$aids[$i]) as $data) {
							$_G['bineoo_storage']['ossClient']->deleteObject($_G['bineoo_storage']['oss_set']['bucket'], $_G['bineoo_storage']['oss_set']['attachurl'].'forum/'.$data['attachment']);
						}
					}
				}
			}
		}else if($_GET['action'] == 'setthreadcover'){
			$aid = intval($_GET['aid']);
			$imgurl = $_GET['imgurl'];
			require_once libfile('function/post');
			$tid = intval($_GET['tid']);
			$pid = intval($_GET['pid']);
			if(!$aid){
				$threadimage = C::t('forum_attachment_n')->fetch_max_image('tid:'.$tid, 'tid', $tid);
				$aid = $threadimage['aid'];
			}else{
				$threadimage = C::t('forum_attachment_n')->fetch('aid:'.$aid, $aid);
				$tid = $threadimage['tid'];
				$pid = $threadimage['pid'];
			}
			if($_G['forum'] && ($aid || $imgurl)) {

				if($tid && $pid) {
					$thread =get_thread_by_tid($tid);
				} else {
					$thread = array();
				}
				if(empty($thread) || (!$_G['forum']['ismoderator'] && $_G['uid'] != $thread['authorid'])) {
					if($_GET['newthread']) {
						showmessage('set_cover_faild', '', array(), array('msgtype' => 3));
					} else {
						showmessage('set_cover_faild', '', array(), array('closetime' => 3));
					}
				}
				$imgurl = $imgurl ? $imgurl : object_url($_G['bineoo_storage']['oss_set']['attachurl'].'forum/'.$threadimage['attachment']);
				if(setthreadcover($pid, $tid, $aid, 0, $imgurl)) {
					$object_cover = $_G['bineoo_storage']['oss_set']['attachurl'].'forum/threadcover/'.substr(md5($tid), 0, 2).'/'.substr(md5($tid), 2, 2).'/'.$tid.'.jpg';
					if(is_file( $_G['bineoo_storage']['oss_set']['attachdir'].'forum/threadcover/'.substr(md5($tid), 0, 2).'/'.substr(md5($tid), 2, 2).'/'.$tid.'.jpg')){
						$_G['bineoo_storage']['ossClient']->multiuploadFile($_G['bineoo_storage']['oss_set']['bucket'], $object_cover, $object_cover);
						if($_G['bineoo_storage']['ossClient']->doesObjectExist($_G['bineoo_storage']['oss_set']['bucket'], $object_cover)){
							@unlink(DISCUZ_ROOT.'./'.$object_cover);
						}
					}
					if(empty($_GET['imgurl'])) {
						C::t('forum_threadimage')->delete_by_tid($threadimage['tid']);
						C::t('forum_threadimage')->insert(array(
							'tid' => $threadimage['tid'],
							'attachment' => $threadimage['attachment'],
							'remote' => $threadimage['remote'],
						));
					}
					if($_GET['newthread']) {
						showmessage('set_cover_succeed', '', array(), array('msgtype' => 3));
					} else {
						showmessage('set_cover_succeed', '', array(), array('alert' => 'right', 'closetime' => 1));
					}
				}
			}
			if($_GET['newthread']) {
				showmessage('set_cover_faild', '', array(), array('msgtype' => 3));
			} else {
				showmessage('set_cover_faild', '', array(), array('closetime' => 3));
			}
		}
	}

	function post_message($param){
		global $_G;
		$param = $param['param'];
		$aids = array();
		foreach ($_GET['attachnew'] as $aid => $attach) {
			$aids[] = $aid;
		}
		foreach (C::t('forum_attachment_n')->fetch_all('tid:'.$param[2]['tid'], $aids) as $attach) {
			$object = $_G['bineoo_storage']['oss_set']['attachurl'].'forum/'.$attach['attachment'];
			
			if($_G['bineoo_storage']['ossClient']->doesObjectExist($_G['bineoo_storage']['oss_set']['bucket'], $object) && is_file($object)){
				@unlink($object);
			}
		}
	}

	function image_bineoo_storage(){
		global $_G;
		
		if($_G['bineoo_storage']['ossClient']){
			import_module('forum_image');
		}
	}

	function attachment_bineoo_storage(){
		global $_G;
		
		if($_G['bineoo_storage']['ossClient']){
			import_module('forum_attachment');
		}
	}
}

class mobileplugin_bineoo_storage_misc extends mobileplugin_bineoo_storage {

	function swfupload_bineoo_storage(){
		global $_G;
		
		if($_G['bineoo_storage']['ossClient']){
			import_module('misc_swfupload');
		}
	}

}

class mobileplugin_bineoo_storage_portal extends mobileplugin_bineoo_storage {

	function attachment_bineoo_storage(){
		global $_G;
		
		if($_G['bineoo_storage']['ossClient']){
			import_module('portal_attachment');
		}
	}

	function portalcp_bineoo_storage(){
	}
}