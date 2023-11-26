<?php
/*
 *讯幻网：www.xhkj5.com
 *更多商业插件/模版免费下载 就在讯幻网
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_dc_downlimit
{
    var $cvar = null;

    function __construct()
    {
        global $_G;
        $this->cvar = $_G['cache']['plugin']['dc_downlimit'];
    }

    function global_usernav_extra3()
    {
        global $_G;
        if (!$this->cvar['open'])
            return;
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit') . " where fid='" . $_G['fid'] . "'");
        $data = dunserialize($r['data']);
        if (!$data[$_G['groupid']]['free'])
            return;
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit_user') . " where uid='" . $_G['uid'] . "' and fid='" . $_G['fid'] . "' and from_unixtime(dateline,'%Y%m%d')='" . dgmdate(TIMESTAMP, 'Ymd') . "'");
        if (!$r)
            $r['times'] = 0;
        if ($this->cvar['freemsg'])
            $fmsg = $this->cvar['freemsg'];
        else
            $fmsg = $this->plang('freeinfo');
        return str_replace(array('{total}', '{count}', '{times}'), array($data[$_G['groupid']]['free'], $data[$_G['groupid']]['free'] > $r['times'] ? $data[$_G['groupid']]['free'] - $r['times'] : 0, $r['times']), $fmsg);
    }

    protected function _download()
    {//echo 2;exit;
        global $_G;
        $ck = $_GET['ck'];
        @list($aid, $k, $t, $uid, $tid) = explode('|', base64_decode($_GET['aid']));
        if (!$ck) {//if ($_G['groupid']==1)echo $ck;exit;
            $att = DB::fetch_first("SELECT * FROM " . DB::table('forum_attachment') . " where aid='" . $aid . "' AND uid='" . $_G['uid'] . "'");
            if (!empty($att))
                return;
            $thread = C::t('forum_thread')->fetch_by_tid_displayorder($tid, 0, '>=', null, 0);
            $fid = $thread['fid'];
            $r = DB::fetch_first("select * from " . DB::table('dc_downlimit') . " where fid='$fid'");
            $data = dunserialize($r['data']);
            if (!$data[$_G['groupid']])
                return;
            $r = DB::fetch_first("select * from " . DB::table('dc_downlimit_user') . " where uid='" . $_G['uid'] . "' and fid='" . $fid . "' and from_unixtime(dateline,'%Y%m%d')='" . dgmdate(TIMESTAMP, 'Ymd') . "'");

            if (!$r) {
                DB::query("REPLACE INTO " . DB::table('dc_downlimit_user') . "(uid,fid,times,dateline) VALUES('" . $_G['uid'] . "','" . $fid . "','1','" . TIMESTAMP . "')");
                if ($data[$_G['groupid']]['free'])
                    dheader('location: forum.php?mod=attachment&aid=' . aidencode($aid, 0, $tid) . '&ck=' . substr(md5($aid . TIMESTAMP . md5($_G['config']['security']['authkey'])), 0, 8));
            } else {
                if ($r['times'] < $data[$_G['groupid']]['max'] || !$data[$_G['groupid']]['max']) {
                    DB::query("update " . DB::table('dc_downlimit_user') . " set times=times+1 where uid='" . $_G['uid'] . "' and fid='" . $fid . "'");
                    if ($r['times'] < $data[$_G['groupid']]['free'] && !empty($data[$_G['groupid']]['free']))
                        dheader('location: forum.php?mod=attachment&aid=' . aidencode($aid, 0, $tid) . '&ck=' . substr(md5($aid . TIMESTAMP . md5($_G['config']['security']['authkey'])), 0, 8));
                } else {
                    if ($this->cvar['msg'])
                        $msg = $this->cvar['msg'];
                    else
                        $msg = $this->plang('downinfo');
                    showmessage(str_replace('{count}', $data[$_G['groupid']]['max'], $msg));
                }
            }
        } else {
            if ($ck != substr(md5($aid . $t . md5($_G['config']['security']['authkey'])), 0, 8)) {
                showmessage($this->plang('error'));
            }
        }
    }

    public function _ddownload()
    {
        global $_G;
        $ck = $_GET['ck'];
        //@list($aid, $k, $t, $uid, $tid) = explode('|', base64_decode($_GET['aid']));
        $aid = $_GET['aid'];
        $k = $_GET['k'];
        $t = $_GET['t'];
        $uid = $_GET['uid'];
        $tid = $_GET['tableid'];
        if (!$ck) {
            $thread = C::t('forum_thread')->fetch_by_tid_displayorder($tid, 0, '>=', null, 0);
            $fid = $thread['fid'];
            $r = DB::fetch_first("select * from " . DB::table('dc_downlimit') . " where fid='$fid'");
            $data = dunserialize($r['data']);
            //print_r($thread);exit;
            if (!$data[$_G['groupid']])
                return;
            $r = DB::fetch_first("select * from " . DB::table('dc_downlimit_user') . " where uid='" . $_G['uid'] . "' and fid='" . $fid . "' and from_unixtime(dateline,'%Y%m%d')='" . dgmdate(TIMESTAMP, 'Ymd') . "'");

            if (!$r) {
                DB::query("REPLACE INTO " . DB::table('dc_downlimit_user') . "(uid,fid,times,dateline) VALUES('" . $_G['uid'] . "','" . $fid . "','1','" . TIMESTAMP . "')");
                if ($data[$_G['groupid']]['free'])
                    dheader('location: forum.php?mod=attachment&aid=' . aidencode($aid, 0, $tid) . '&ck=' . substr(md5($aid . TIMESTAMP . md5($_G['config']['security']['authkey'])), 0, 8));
            } else {
                if ($r['times'] < $data[$_G['groupid']]['max'] || !$data[$_G['groupid']]['max']) {
                    DB::query("update " . DB::table('dc_downlimit_user') . " set times=times+1 where uid='" . $_G['uid'] . "' and fid='" . $fid . "'");
                    if ($r['times'] < $data[$_G['groupid']]['free'] && !empty($data[$_G['groupid']]['free']))
                        dheader('location: forum.php?mod=attachment&aid=' . aidencode($aid, 0, $tid) . '&ck=' . substr(md5($aid . TIMESTAMP . md5($_G['config']['security']['authkey'])), 0, 8));
                } else {
                    if ($this->cvar['msg'])
                        $msg = $this->cvar['msg'];
                    else
                        $msg = $this->plang('downinfo');
                    showmessage(str_replace('{count}', $data[$_G['groupid']]['max'], $msg));
                }
            }
        } else {
            if ($ck != substr(md5($aid . $t . md5($_G['config']['security']['authkey'])), 0, 8)) {
                showmessage($this->plang('error'));
            }
        }
    }

    function _pandownload($tid)
    {
        global $_G;
        $thread = C::t('forum_thread')->fetch_by_tid_displayorder($tid, 0, '>=', null, 0);
        $fid = $thread['fid'];
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit') . " where fid='$fid'");
        $data = dunserialize($r['data']);
        //print_r($thread);exit;
        if (!$data[$_G['groupid']])
            return;
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit_user') . " where uid='" . $_G['uid'] . "' and fid='" . $fid . "' and from_unixtime(dateline,'%Y%m%d')='" . dgmdate(TIMESTAMP, 'Ymd') . "'");
        if (!$r) {
            DB::query("REPLACE INTO " . DB::table('dc_downlimit_user') . "(uid,fid,times,dateline) VALUES('" . $_G['uid'] . "','" . $fid . "','1','" . TIMESTAMP . "')");
        } else {
            if ($r['times'] < $data[$_G['groupid']]['max'] || !$data[$_G['groupid']]['max']) {
                DB::query("update " . DB::table('dc_downlimit_user') . " set times=times+1 where uid='" . $_G['uid'] . "' and fid='" . $fid . "'");
            } else {
                if ($this->cvar['msg'])
                    $msg = $this->cvar['msg'];
                else
                    $msg = $this->plang('downinfo');
                showmessage(str_replace('{count}', $data[$_G['groupid']]['max'], $msg));
            }
        }
    }

    //<p><em>剩余次数：</em><b><a style="color: #1a87f3;" title="剩余下载次数">{allow_count}</a>&nbsp;/&nbsp;<a style="color: #1a87f3;" title="今日可用下载次数">{total_count}</a></b>  &nbsp;&nbsp;<font color="#FF6633">(今日还可以下载 {allow_count} 次附件)</font><br></p>
    function _get_1314attachad($uid,$group_id,$tid)
    {
        $returnd = <<<EOF
<p><em>&#21097;&#20313;&#27425;&#25968;&#65306;</em><b><a style="color: #1a87f3;" title="&#21097;&#20313;&#19979;&#36733;&#27425;&#25968;">{allow_count}</a>&nbsp;/&nbsp;<a style="color: #1a87f3;" title="&#20170;&#26085;&#21487;&#29992;&#19979;&#36733;&#27425;&#25968;">{total_count}</a></b>  &nbsp;&nbsp;<font color="#FF6633">(&#20170;&#26085;&#36824;&#21487;&#20197;&#19979;&#36733;&nbsp;{allow_count}&nbsp;&#27425;&#38468;&#20214;)</font><br></p>
EOF;
        $thread = C::t('forum_thread')->fetch_by_tid_displayorder($tid, 0, '>=', null, 0);
        $fid = $thread['fid'];
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit') . " where fid='$fid'");
        $data = dunserialize($r['data']);
        if (!$data[$group_id])
        {
            return str_replace(array('{allow_count}','{total_count}'),array('&#26080;&#38480;','&#26080;&#38480;'),$returnd);
        }
        $total_count = $data[$group_id]['max'];
        $r = DB::fetch_first("select * from " . DB::table('dc_downlimit_user') . " where uid='" . $uid . "' and fid='" . $fid . "' and from_unixtime(dateline,'%Y%m%d')='" . dgmdate(TIMESTAMP, 'Ymd') . "'");
        $allow_count = $r['times'];
        if ($total_count == 0)
        {
            return str_replace(array('{allow_count}','{total_count}'),array('&#26080;&#38480;','&#26080;&#38480;'),$returnd);
        }
        return str_replace(array('{allow_count}','{total_count}'),array($total_count-$allow_count,$total_count),$returnd);
    }

    function plang($str)
    {
        return lang('plugin/dc_downlimit', $str);
    }
}

class plugin_dc_downlimit_forum extends plugin_dc_downlimit
{
    /*function attachment_down()
    {
        if (!$this->cvar['open'])
            return;
        global $_G;
        loadcache('bineoo_storage_setting');
        $oss_set = dunserialize($_G['cache']['bineoo_storage_setting'])['Access_Key_ID'];
        if ($oss_set)
        {
            $this->_ddownload();
        }
        else {
            $this->_download();
        }

    }*/
}

?>