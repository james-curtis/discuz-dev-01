<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//获取aid信息
@list($_GET['aid'], $_GET['k'], $_GET['t'], $_GET['uid'], $_GET['tableid']) = daddslashes(explode('|', base64_decode($_GET['aid'])));

//来路是否正确
$requestmode = !empty($_GET['request']) && empty($_GET['uid']);
$aid = intval($_GET['aid']);
$k = $_GET['k'];
$t = $_GET['t'];
$authk = !$requestmode ? substr(md5($aid.md5($_G['config']['security']['authkey']).$t.$_GET['uid']), 0, 8) : md5($aid.md5($_G['config']['security']['authkey']).$t);

//附件下载密文与计算密文不符
if($k != $authk) {
    if(!$requestmode) {
        //提示：没有该附件
        showmessage('attachment_nonexistence');
    } else {
        exit;
    }
}

//这个不是下载附件，而是寻找附件所在贴子及楼层
if(!empty($_GET['findpost']) && ($attach = C::t('forum_attachment')->fetch($aid))) {
	dheader('location: forum.php?mod=redirect&goto=findpost&pid='.$attach['pid'].'&ptid='.$attach['tid']);
}

//设置改用户以及用户组的一些信息，供showmessage()用
if($_GET['uid'] != $_G['uid'] && $_GET['uid']) {
	$_G['uid'] = $_GET['uid'] = intval($_GET['uid']);
	$member = getuserbyuid($_GET['uid']);
	loadcache('usergroup_'.$member['groupid']);
	$_G['group'] = $_G['cache']['usergroup_'.$member['groupid']];
	$_G['group']['grouptitle'] = $_G['cache']['usergroup_'.$_G['groupid']]['grouptitle'];
	$_G['group']['color'] = $_G['cache']['usergroup_'.$_G['groupid']]['color'];
}


$tableid = 'aid:'.$aid;

//如果设置了附件失效
if($_G['setting']['attachexpire']) {

	if(TIMESTAMP - $t > $_G['setting']['attachexpire'] * 3600) {
		$aid = intval($aid);
		if($attach = C::t('forum_attachment_n')->fetch($tableid, $aid)) {
			if($attach['isimage']) {
				dheader('location: '.$_G['siteurl'].'static/image/common/none.gif');
			} else {
				if(!$requestmode) {
					showmessage('attachment_expired', '', array('aid' => aidencode($aid, 0, $attach['tid']), 'pid' => $attach['pid'], 'tid' => $attach['tid']));
				} else {
					exit;
				}
			}
		} else {
			if(!$requestmode) {
				showmessage('attachment_nonexistence');
			} else {
				exit;
			}
		}
	}
}

//获取设置中读取附件模式
$readmod = getglobal('config/download/readmod');
$readmod = $readmod > 0 && $readmod < 5 ? $readmod : 2;

//referer解析掉
$refererhost = parse_url($_SERVER['HTTP_REFERER']);
//当前访问
$serverhost = $_SERVER['HTTP_HOST'];
//取当前访问URL"："前面的
if(($pos = strpos($serverhost, ':')) !== FALSE) {
	$serverhost = substr($serverhost, 0, $pos);
}

//验证来路
if(!$requestmode && $_G['setting']['attachrefcheck'] && $_SERVER['HTTP_REFERER'] && !($refererhost['host'] == $serverhost)) {
	showmessage('attachment_referer_invalid', NULL);
}

//验证是否禁止下载
periodscheck('attachbanperiods');

//获取archive ID
loadcache('threadtableids');
$threadtableids = !empty($_G['cache']['threadtableids']) ? $_G['cache']['threadtableids'] : array();
if(!in_array(0, $threadtableids)) {
	$threadtableids = array_merge(array(0), $threadtableids);
}
$archiveid = in_array($_GET['archiveid'], $threadtableids) ? intval($_GET['archiveid']) : 0;

//该附件在数据库中是否存在
$attachexists = FALSE;
if(!empty($aid) && is_numeric($aid)) {
	$attach = C::t('forum_attachment_n')->fetch($tableid, $aid);
	$thread = C::t('forum_thread')->fetch_by_tid_displayorder($attach['tid'], 0, '>=', null, $archiveid);
	if($_G['uid'] && $attach['uid'] != $_G['uid']) {
		if($attach) {
			$attachpost = C::t('forum_post')->fetch($thread['posttableid'], $attach['pid'], false);
			$attach['invisible'] = $attachpost['invisible'];
			unset($attachpost);
		}
		if($attach && $attach['invisible'] == 0) {
			$thread && $attachexists = TRUE;
		}
	} else {
		$attachexists = TRUE;
	}
}

//如果不存在
if(!$attachexists) {
	if(!$requestmode) {
		showmessage('attachment_nonexistence');
	} else {
		exit;
	}
}

//付费附件相关东西
if(!$requestmode) {
	$forum = C::t('forum_forumfield')->fetch_info_for_attach($thread['fid'], $_G['uid']);

	$_GET['fid'] = $forum['fid'];

	if($attach['isimage']) {
		$allowgetattach = !empty($forum['allowgetimage']) || (($_G['group']['allowgetimage'] || $_G['uid'] == $attach['uid']) && !$forum['getattachperm']) || forumperm($forum['getattachperm']);
	} else {
		$allowgetattach = !empty($forum['allowgetattach']) || (($_G['group']['allowgetattach']  || $_G['uid'] == $attach['uid']) && !$forum['getattachperm']) || forumperm($forum['getattachperm']);
	}
	if($allowgetattach && ($attach['readperm'] && $attach['readperm'] > $_G['group']['readaccess']) && $_G['adminid'] <= 0 && !($_G['uid'] && $_G['uid'] == $attach['uid'])) {
		showmessage('attachment_forum_nopermission', NULL, array(), array('login' => 1));
	}

	$ismoderator = in_array($_G['adminid'], array(1, 2)) ? 1 : ($_G['adminid'] == 3 ? C::t('forum_moderator')->fetch_uid_by_tid($attach['tid'], $_G['uid'], $archiveid) : 0);

	$ispaid = FALSE;
	$exemptvalue = $ismoderator ? 128 : 16;
	if(!$thread['special'] && $thread['price'] > 0 && (!$_G['uid'] || ($_G['uid'] != $attach['uid'] && !($_G['group']['exempt'] & $exemptvalue)))) {
		if(!$_G['uid'] || $_G['uid'] && !($ispaid = C::t('common_credit_log')->count_by_uid_operation_relatedid($_G['uid'], 'BTC', $attach['tid']))) {
			showmessage('attachment_payto', 'forum.php?mod=viewthread&tid='.$attach['tid']);
		}
	}

	$exemptvalue = $ismoderator ? 64 : 8;
	if($attach['price'] && (!$_G['uid'] || ($_G['uid'] != $attach['uid'] && !($_G['group']['exempt'] & $exemptvalue)))) {
		$payrequired = $_G['uid'] ? !C::t('common_credit_log')->count_by_uid_operation_relatedid($_G['uid'], 'BAC', $attach['aid']) : 1;
		$payrequired && showmessage('attachement_payto_attach', 'forum.php?mod=misc&action=attachpay&aid='.$attach['aid'].'&tid='.$attach['tid']);
	}
}

//图片附件相关
$isimage = $attach['isimage'];
$_G['setting']['ftp']['hideurl'] = $_G['setting']['ftp']['hideurl'] || ($isimage && !empty($_GET['noupdate']) && $_G['setting']['attachimgpost'] && strtolower(substr($_G['setting']['ftp']['attachurl'], 0, 3)) == 'ftp');

/**
 * 发送图片附件
 */
if(empty($_GET['nothumb']) && $attach['isimage'] && $attach['thumb']) {
	$db = DB::object();
	$db->close();
	!$_G['config']['output']['gzip'] && ob_end_clean();
	dheader('Content-Disposition: inline; filename='.getimgthumbname($attach['filename']));
	dheader('Content-Type: image/pjpeg');
	if($attach['remote']) {
		$_G['setting']['ftp']['hideurl'] ? getremotefile(getimgthumbname($attach['attachment'])) : dheader('location:'.$_G['setting']['ftp']['attachurl'].'forum/'.getimgthumbname($attach['attachment']));
	} else {
		getlocalfile($_G['setting']['attachdir'].'/forum/'.getimgthumbname($attach['attachment']));
	}
	exit();
}
//检查OSS附件是否存在
$filename = $_G['setting']['attachdir'].'/forum/'.$attach['attachment'];
$object = $_G['bineoo_storage']['oss_set']['attachurl'].'forum/'.$attach['attachment'];
if(!$attach['remote'] && !is_readable($filename) && !$_G['bineoo_storage']['ossClient']->doesObjectExist($_G['bineoo_storage']['oss_set']['bucket'], $object)) {
	$storageService = Cloud::loadClass('Service_Storage');
	$storageService->checkAttachment($attach);
	if(!$requestmode) {
		showmessage('attachment_nonexistence');
	} else {
		exit;
	}
}

//---------------------------------附件下载限制--dc_downlimit------------------------------------------begin
if ($_G['cache']['plugin']['dc_downlimit']['open'])
{
    @include_once DISCUZ_ROOT.'./source/plugin/dc_downlimit/hook.class.php';
    $dc_downlimit = new plugin_dc_downlimit();//echo 'ok1';exit;
    //$dc_downlimit->plugin_dc_downlimit();
    $dc_downlimit->_ddownload();
//    echo var_dump(file_exists(DISCUZ_ROOT.'./source/plugin/dc_downlimit/hook.class.php'));exit;
}
//---------------------------------附件下载限制--dc_downlimit------------------------------------------end


//---------------------------------附件统计--nimba_attachlog------------------------------------------begin
if ($_G['cache']['plugin']['nimba_attachlog']['open'])
{
    @include_once DISCUZ_ROOT.'./source/plugin/nimba_attachlog/hook.class.php';
    $nimba_attachlog = new plugin_nimba_attachlog_forum();
    $nimba_attachlog->attachment_log();
}
//---------------------------------附件统计--nimba_attachlog------------------------------------------end



//附件前缀
$top_prefix = $_G['cache']['plugin']['top_fjm']['top_prefix']?$_G['cache']['plugin']['top_fjm']['top_prefix']:'';

//检查权限,跳转下载
if(!$requestmode) {
	if(!$ispaid && !$forum['allowgetattach']) {
		if(!$forum['getattachperm'] && !$allowgetattach) {
			showmessage('getattachperm_none_nopermission', NULL, array(), array('login' => 1));
		} elseif(($forum['getattachperm'] && !forumperm($forum['getattachperm'])) || ($forum['viewperm'] && !forumperm($forum['viewperm']))) {
			showmessagenoperm('getattachperm', $forum['fid']);
		}
	}

	$exemptvalue = $ismoderator ? 32 : 4;
	if(!$isimage && !($_G['group']['exempt'] & $exemptvalue)) {
	    //操作积分
		$creditlog = updatecreditbyaction('getattach', $_G['uid'], array(), '', 1, 0, $thread['fid']);
		if($creditlog['updatecredit']) {
			if($_G['uid']) {
				$k = $_GET['ck'];
				$t = $_GET['t'];
				if(empty($k) || empty($t) || $k != substr(md5($aid.$t.md5($_G['config']['security']['authkey'])), 0, 8) || TIMESTAMP - $t > 3600) {
                    /**
                     * 跳转下载附件
                     */
					dheader('location: forum.php?mod=misc&action=attachcredit&aid='.$attach['aid'].'&formhash='.FORMHASH);
					exit();
				}
			} else {
				showmessage('attachment_forum_nopermission', NULL, array(), array('login' => 1));
			}
		}
	}

}

/**
 * 正式进入下载
 */

//断点续传
$range = 0;
if($readmod == 4 && !empty($_SERVER['HTTP_RANGE'])) {
	list($range) = explode('-',(str_replace('bytes=', '', $_SERVER['HTTP_RANGE'])));
}

//附件下载量加1
if(!$requestmode && !$range && empty($_GET['noupdate'])) {
	if($_G['setting']['delayviewcount']) {
		$_G['forum_logfile'] = './data/cache/forum_attachviews_'.intval(getglobal('config/server/id')).'.log';
		if(substr(TIMESTAMP, -1) == '0') {
			attachment_updateviews($_G['forum_logfile']);
		}

		if(@$fp = fopen(DISCUZ_ROOT.$_G['forum_logfile'], 'a')) {
			fwrite($fp, "$aid\n");
			fclose($fp);
		} elseif($_G['adminid'] == 1) {
			showmessage('view_log_invalid', '', array('logfile' => $_G['forum_logfile']));
		}
	} else {
		C::t('forum_attachment')->update_download($aid);
	}
}

//下载OSS附件，OSS没有则跳过
if($_G['bineoo_storage']['ossClient']->doesObjectExist($_G['bineoo_storage']['oss_set']['bucket'], $object)){
	require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/OssClient.php';
	$_G['bineoo_storage']['ossClient']->copyObject(
		$_G['bineoo_storage']['oss_set']['bucket'], $object, 
		$_G['bineoo_storage']['oss_set']['bucket'], $object, 
		array(
			OssClient::OSS_HEADERS => array('Content-Disposition' => 'attachment;filename="'.$top_prefix.$attach['filename'].'"')
		));
	dheader('location: '.$_G['bineoo_storage']['oss_set']['domain'].$object);
	exit();
}

//关闭数据库连接，下面不需要操作数据库了
$db = DB::object();
$db->close();
//如果没有开启gzip，则清空缓冲区
!$_G['config']['output']['gzip'] && ob_end_clean();

//下载FTP附件
if($attach['remote'] && !$_G['setting']['ftp']['hideurl'] && $isimage) {
	dheader('location:'.$_G['setting']['ftp']['attachurl'].'forum/'.$attach['attachment']);
}

/**
 * 下载本地附件相关
 */
$filesize = !$attach['remote'] ? filesize($filename) : $attach['filesize'];
$attach['filename'] = '"'.(strtolower(CHARSET) == 'utf-8' && strexists($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? urlencode($attach['filename']) : $attach['filename']).'"';

dheader('Date: '.gmdate('D, d M Y H:i:s', $attach['dateline']).' GMT');
dheader('Last-Modified: '.gmdate('D, d M Y H:i:s', $attach['dateline']).' GMT');
dheader('Content-Encoding: none');

if($isimage && !empty($_GET['noupdate']) || !empty($_GET['request'])) {
	dheader('Content-Disposition: inline; filename='.$top_prefix.$attach['filename']);
} else {
	dheader('Content-Disposition: attachment; filename='.$top_prefix.$attach['filename']);
}
if($isimage) {
	dheader('Content-Type: image');
} else {
	dheader('Content-Type: application/octet-stream');
}

dheader('Content-Length: '.$filesize);

$xsendfile = getglobal('config/download/xsendfile');
if(!empty($xsendfile)) {
	$type = intval($xsendfile['type']);
	$cmd = '';
	switch ($type) {
		case 1: $cmd = 'X-Accel-Redirect'; $url = $xsendfile['dir'].$attach['attachment']; break;
		case 2: $cmd = $_SERVER['SERVER_SOFTWARE'] <'lighttpd/1.5' ? 'X-LIGHTTPD-send-file' : 'X-Sendfile'; $url = $filename; break;
		case 3: $cmd = 'X-Sendfile'; $url = $filename; break;
	}
	if($cmd) {
		dheader("$cmd: $url");
		exit();
	}
}

if($readmod == 4) {
	dheader('Accept-Ranges: bytes');
	if(!empty($_SERVER['HTTP_RANGE'])) {
		$rangesize = ($filesize - $range) > 0 ?  ($filesize - $range) : 0;
		dheader('Content-Length: '.$rangesize);
		dheader('HTTP/1.1 206 Partial Content');
		dheader('Content-Range: bytes='.$range.'-'.($filesize-1).'/'.($filesize));
	}
}

$attach['remote'] ? getremotefile($attach['attachment']) : getlocalfile($filename, $readmod, $range);
exit;


function getremotefile($file) {
	global $_G;
	@set_time_limit(0);
	if(!@readfile($_G['setting']['ftp']['attachurl'].'forum/'.$file)) {
		$ftp = ftpcmd('object');
		$tmpfile = @tempnam($_G['setting']['attachdir'], '');
		if($ftp->ftp_get($tmpfile, 'forum/'.$file, FTP_BINARY)) {
			@readfile($tmpfile);
			@unlink($tmpfile);
		} else {
			@unlink($tmpfile);
			return FALSE;
		}
	}
	return TRUE;
}

function getlocalfile($filename, $readmod = 2, $range = 0) {
	if($readmod == 1 || $readmod == 3 || $readmod == 4) {
		if($fp = @fopen($filename, 'rb')) {
			@fseek($fp, $range);
			if(function_exists('fpassthru') && ($readmod == 3 || $readmod == 4)) {
				@fpassthru($fp);
			} else {
				echo @fread($fp, filesize($filename));
			}
		}
		@fclose($fp);
	} else {
		@readfile($filename);
	}
	@flush(); @ob_flush();
}

function attachment_updateviews($logfile) {
	$viewlog = $viewarray = array();
	$newlog = DISCUZ_ROOT.$logfile.random(6);
	if(@rename(DISCUZ_ROOT.$logfile, $newlog)) {
		$viewlog = file($newlog);
		unlink($newlog);
		if(is_array($viewlog) && !empty($viewlog)) {
			$viewlog = array_count_values($viewlog);
			foreach($viewlog as $id => $views) {
				if($id > 0) {
					$viewarray[$views][] = intval($id);
				}
			}
			foreach($viewarray as $views => $ids) {
				C::t('forum_attachment')->update_download($ids, $views);
			}
		}
	}
}