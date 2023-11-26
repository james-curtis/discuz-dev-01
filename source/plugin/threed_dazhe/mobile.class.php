<?php
/**
 *	[附件打折和下载限制(threed_dazhe.{modulename})] (C)2015-2099 Powered by 3D设计者.
 *	Version: 商业版
 *	Date: 2015-5-18 12:12
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class mobileplugin_threed_dazhe
{
    //TODO - Insert your code here

    //TODO - Insert your code here
    public function getattachinfo($pid)
    {
        $pid = dintval($pid);
        $tableid = DB::result_first("SELECT tableid FROM " . DB::table('forum_attachment') .
            " WHERE pid='$pid' LIMIT 1");
        $tableid = $tableid >= 0 && $tableid < 10 ? intval($tableid) : 127;
        $table = "forum_attachment_" . $tableid;
        $sql = "SELECT * FROM " . DB::table($table) . " WHERE pid=" . $pid .
            " and isimage=0 ORDER BY aid asc ";
        return DB::fetch_all($sql);
    }
    function discuzcode($value)
    { //预先把所有附件都先处理，使之不被解析，方便后面解析
        global $_G;
        $pan_option = $_G['cache']['plugin']['threed_dazhe'];
        $pan_forum =unserialize($pan_option["thd_forums"]);
        $thd_back = $pan_option['thd_back'];
        if (!$thd_back && !$value[param][15])
            return array();
        if(!in_array($_G['fid'],$pan_forum))return;
        //print_r($pan_zhekou);
        if ($value[caller] == "discuzcode") {
            $pid = $value[param][12];
            if (!$pid)
                return array();
            $pan_msg = $_G['discuzcodemessage'];
            $attachlist = $this->getattachinfo($pid);
            //print_r($attachlist);
            foreach ($attachlist as $k => $attach) {
                $str ="[oneattachatt]".$attach[aid]."[/oneattachatt]";
                if (preg_match("/\[attach\]" . $attach[aid] . "\[\/attach\]/", $pan_msg)) {
                    $pan_msg = preg_replace("/\[attach\]" . $attach[aid] . "\[\/attach\]/", $str, $pan_msg);
                }
            }
        }
        //echo $pan_msg;
        $_G['discuzcodemessage'] = $pan_msg;

    }
}

class mobileplugin_threed_dazhe_forum extends mobileplugin_threed_dazhe
{
    function viewthread_posttop_output()
    {
        global $_G, $postlist, $post;
        $pan_option = $_G['cache']['plugin']['threed_dazhe'];
        $thd_back = $pan_option['thd_back'];
        $pan_user = $pan_option["thd_user"];
        $pan_forum =unserialize($pan_option["thd_forums"]);
        if(!in_array($_G['fid'],$pan_forum))return;

        require_once libfile('function/threed','plugin/threed_dazhe');
        foreach ($postlist as $id => $post) {
            if (!$thd_back && !$post['first'])
                return;
            $pan_message = $post['message'];
            //print_r($post['attachments']);
            foreach ($post['attachments'] as $k => $attach) {
                if (!$attach['isimage']) {
                        $str =  show_attach($attach);
                    $pan_message = preg_replace("/\[oneattachatt\]" . $attach[aid] . "\[\/oneattachatt\]/",$str, $pan_message);
                }
            }
            $post['message']=$pan_message;
            $postlist[$id] =$post;
        }
    }
}

?>