<?php

/**
 *	[积分购买视频教程(threed_ckplayer.{modulename})] (C)2014-2099 Powered by 3D设计者.
 *	Version: 商业版
 *	Date: 2014-12-3 21:54
 */

if (!defined('IN_DISCUZ')) {
    exit('');
}
function show_attach($attach)
{
    global $_G;
    $pan_option = $_G['cache']['plugin']['threed_dazhe'];
    $thd_panurl = $pan_option['thd_panurl'];
    $thd_youke = $pan_option['thd_youke'];
    $uid = $_G['uid'];
    //附件处理
    $aidencode = packaids($attach);
    $is_archive = $_G['forum_thread']['is_archived'] ? '&fid=' . $_G['fid'] .
        '&archiveid=' . $_G[forum_thread][archiveid] : '';
    //用户名处理
    $groupsname = lang('plugin/threed_dazhe', 'attach1');
    $groupid = 1;
    if ($attach[readperm] == 0) {
        $groupsname = lang('plugin/threed_dazhe', 'attach2');
        $groupid = $_G[groupid];
    } else {
        foreach ($_G['cache']['usergroups'] as $av => $groups) {
            if ($groups['readaccess'] == $attach[readperm]) {
                $groupsname = $groups['grouptitle'];
                $groupid = $av;
                break;
            } else {
                continue;
            }
        }
    }

    //统计已下载次数
    $daytimenow = $_G['timestamp'];
    $daytimeup = $_G['timestamp'] + 43200;
    $daytimedown = $_G['timestamp'] - 43200;
    $daydownnum = DB::result_first("SELECT count(*) FROM " . DB::table('common_credit_log') .
        " WHERE uid='$uid' AND operation='BAC' AND dateline between $daytimedown AND $daytimeup");

    //折扣预处理
    $pan_user = $_G['cache']['plugin']['threed_dazhe']["thd_user"];
    $pan_zhekou = array();
    $pan_zheokou_temp = explode(",", $pan_user);
    foreach ($pan_zheokou_temp as $listk => $listv) {
        $listv_temp = explode("|", $listv);
        $pan_zhekou[0][$listk] = intval($listv_temp[0] ? $listv_temp[0] : 1);
        $pan_zhekou[1][$listk] = round(($listv_temp[1] ? $listv_temp[1] : 0), 1);
        $pan_zhekou[2][$listk] = intval($listv_temp[2] ? $listv_temp[2] : 0);
        $pan_zhekou[3][$listk] = round(($listv_temp[3] ? $listv_temp[3] : 0), 1);
    }

    //权限处理和下载次数处理
    $pan_yuanjia = $pan_zhejia = $attach['price'];
    foreach ($pan_zhekou[0] as $listk => $listv) {
        if ($_G['groupid'] == $listv) {
            $pan_downnum = $pan_zhekou[2][$listk];
            $pan_over = $pan_zhekou[3][$listk];
            if ($daydownnum >= $pan_downnum) {
                if ($pan_over < 0) {
                    $pan_zhejia = $pan_yuanjia;
                } else {
                    $pan_zhejia = intval($pan_zhekou[3][$listk] * $pan_yuanjia / 10);
                }
            } else {
                $pan_zhejia = intval($pan_zhekou[1][$listk] * $pan_yuanjia / 10);
            }
            break;
        }
    }


    //判断是否为网盘
    if ($attach['ext'] == 'pan') {
        $attach_url = base64_encode($attach['attachment']);
        $attach['filename'] = str_replace('.pan', '', $attach['filename']);
         $aid=($attach['aid']+3)*7+131;
         $aid=base64_encode($aid);  
    }
    if($_GET['mobile']){
        include_once template('threed_dazhe:attach');
    }else{
    include_once template('threed_dazhe:'.'attach_'.$pan_option['thd_tmp']);
    }
    $return=get_attach_temp($attach,$pan_zhejia,$pan_yuanjia,$daydownnum,$pan_downnum,$aid,$aidencode,$is_archive,$groupsname);
    return $return;
}


function make_request($url, $params, $timeout = 30)
{
    set_time_limit(0);
    $str = "";
    if ($params != "") {
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $kv => $vv) {
                    $str .= '&' . $k . '[' . $kv . ']=' . urlencode($vv);
                }
            } else {
                $str .= '&' . $k . '=' . urlencode($v);
            }
        }
    }
    if (function_exists('curl_init')) {
        // Use CURL if installed...
        $ch = curl_init();
        $header = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($timeout > 0)
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        return $result;
    } else {
        $context = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n" .
                    'Content-length: ' . strlen($str),
                'content' => $str));
        if ($timeout > 0)
            $context['http']['timeout'] = $timeout;
        $contextid = stream_context_create($context);
        $sock = @fopen($url, 'r', false, $contextid);
        if ($sock) {
            $result = '';
            while (!feof($sock)) {
                $result .= fgets($sock, 8192);
            }
            fclose($sock);
        } else {
            return 'TimeOut';
        }
    }
    return $result;
}


function checksite()
{
    global $_G;
    $cert = array(
        'rid' => '59778',
        'sn' => '2017071614NDQAPpFkCv',
        'date' => '1511355601',
        'siteurl' => 'http://www.wuaishare.cn/',
        'siteid' => '48459799-27A9-A0DA-ECA9-2EE3EC1E61BA',
        'qqid' => '90BF1FFC-7C34-4122-170E-0D20D5569D87',
        'md5srrs' => '1b56f06f1dcb442ba2a3f1943a75b133');
    if (stripos($cert['siteurl'], $_G['siteurl']) > 0 || $cert['siteurl'] == $_G['siteurl']) {
        return true;
    } else {
        $url = 'http://dz.3dcader.com/plugin.php?id=application:auth' . $url;
        $url = $url . '&sn=' . urlencode($cert['sn']);
        $url = $url . '&rid=' . urlencode($cert['rid']);
        $url = $url . '&date=' . urlencode($cert['date']);
        $url = $url . '&siturl=' . urlencode($cert['siteurl']);
        $url = $url . '&client=' . urlencode($_G['siteurl']);
        $url = $url . '&siteid=' . urlencode($cert['SiteId']);
        $url = $url . '&qqid=' . urlencode($cert['qqid']);
        $mesg = make_request($url);
        //DB::query("DROP TABLE IF EXISTS ".DB::table('threed_ckbuy')."");
        //echo($mesg);
        //exit($mesg);
    }
}

?>