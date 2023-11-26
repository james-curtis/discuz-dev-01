<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


defined('IN_DISCUZ') || exit('Powered by Hymanwu.Com');

function admin_url_hack($url = '', $type = 'msg') {
    switch ($type) {
    case 'form':
        $url = str_replace(ADMINSCRIPT . '?action=', '', $url);
        break;
    case 'msg':
    default:
        $url = str_replace(ADMINSCRIPT . '?', '', $url);
        break;
    }
    return $url;

}

function fid_replace($html = '', $fid) {
    return str_replace('&fid=0', '&fid='.$fid, $html);
}

function procforums($forums = array()) {
    $arr = array();
    if ($forums) {
        foreach ($forums as $gid => $v) {
            $arr[$gid]['id']   = $gid;
            $arr[$gid]['type'] = 'group';
            $arr[$gid]['name'] = $v['name'];
            if ($v['sub']) {
                foreach ($v['sub'] as $fid => $vv) {
                    $arr[$fid]['id']   = $fid;
                    $arr[$fid]['type'] = 'forum';
                    $arr[$fid]['name'] = $vv;
                    if ($v['child'][$fid]) {
                        foreach ($v['child'][$fid] as $subid => $vvv) {
                            $arr[$subid]['id']   = $subid;
                            $arr[$subid]['type'] = 'sub';
                            $arr[$subid]['name'] = $vvv;
                        }
                    }
                }
            }
        }
    }
    return $arr;
}