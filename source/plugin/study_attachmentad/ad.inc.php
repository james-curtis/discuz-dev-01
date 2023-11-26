<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once libfile('function/attachment');
$lang = lang('plugin/study_attachmentad');
list($aid) = explode('|', base64_decode($_G['gp_aid']));
$aid = intval($aid);
$url = $_G['siteurl'] . "forum.php?mod=attachment&aid=" . $_G['gp_aid'];
$attinfo = DB::fetch_first("SELECT tid,downloads FROM " . DB::table('forum_attachment') . " WHERE aid='$aid'");
if ($attinfo['tid']) {
    $file_info = DB::fetch_first("SELECT * FROM " . DB::table(getattachtablebytid($attinfo['tid'])) . " WHERE aid='$aid'");#www_discuz_1314study_com
    $file_infos['filename'] = $file_info['filename'];
    $file_infos['filesize'] = sizecount($file_info['filesize']) . '<br>';
    $file_infos['dateline'] = gmdate("Y-m-d", $file_info['dateline'] + $timeoffset * 3600) . '<br>';
    $file_infos['downloads'] = $attinfo['downloads'] . '<br>';
    if ($_G['cache']['plugin']['dc_downlimit']['open'] == 1)
    {
        @include_once DISCUZ_ROOT.'./source/plugin/dc_downlimit/hook.class.php';
        $dc_downlimit = new plugin_dc_downlimit();
        $file_infos['count'] = $dc_downlimit->_get_1314attachad($_G['uid'],$_G['groupid'],$attinfo['tid']);
    }
    $file_infos['attachtype'] = attachtype(strtolower(fileext($file_info['filename'])) . "\t" . $file_info['filetype']);
}
$S_a = $_G['cache']['plugin']['study_attachmentad'];
$top_px = $S_a['top_px'] ? $S_a['top_px'] : '10';
$bottom_px = $S_a['bottom_px'] ? $S_a['bottom_px'] : '10';
include template("study_attachmentad:ad");
