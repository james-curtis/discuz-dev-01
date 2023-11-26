<?php
defined('IN_DISCUZ') or exit('Powered by Hymanwu.Com');

class hwh_attachmentlimit {
    public $cfg                   = array();
    public $in_mobile, $plugin_id = 'hwh_attachmentlimit';

    public function __construct() {
        global $_G;
        $this->cfg       = $_G['cache']['plugin'][$this->plugin_id];
        $this->in_mobile = defined('IN_MOBILE') ? 1 : 0;
    }

    protected function db($table = '', $plugin = true){
    	$table = strtolower($table);
	    return C::t($plugin ? '#' . $this->plugin_id . '#' . $this->plugin_id . '_' . $table : $table);
    }

    protected function _operation($_aid=null, $_k=null, $_t=null, $_uid=null, $_tid=null){
        global $_G;
        $cache_conf_path = DISCUZ_ROOT . 'data/sysdata/cache_plugin_' . $this->plugin_id . '_conf.php';
        $cache_data_path = DISCUZ_ROOT . 'data/sysdata/cache_plugin_' . $this->plugin_id . '_log.php';
        $cache_conf      = is_file($cache_conf_path) ? include $cache_conf_path : array();
        $cache_data      = is_file($cache_data_path) ? include $cache_data_path : array();
        @list($_aid, $_k, $_t, $_uid, $_tid) = daddslashes(explode('|', base64_decode($_GET['aid'])));
        $fid = 0;//默认为全局
        $today    = date('Ymd', TIMESTAMP);
        if (count($cache_data)>1 && $cache_data[$today]) {//如果缓存数据大于1天则可以重写该数据
            $cache_data_today = $cache_data[$today];
            unset($cache_data);
            $cache_data[$today] = $cache_data_today;
        }
        $att_info = C::t('forum_attachment')->fetch_all_by_id('aid', $_aid);//附件基础信息
        $att_desc = C::t('forum_attachment_n')->fetch($att_info[$_aid]['tableid'], $_aid);//附件详情

        if ($this->cfg['mode'] == 2) {//版块细分
            $tid_info = C::t('forum_thread')->fetch($att_info[$_aid]['tid']);
            $pid_info = C::t('forum_post')->fetch($tid_info['posttableid'],$att_info[$_aid]['pid']);
            $fid      = $pid_info['fid'];
        }

        if ($_uid) $att_user = getuserbyuid($_uid);//通过下载地址的uid获取这个链接用户的信息
        $groupid = $_uid ? $att_user['groupid'] : 7;//游客用户组为7
        $user_key = $_uid ? $_uid : $_G['clientip'];//如果是游客则用ip做记录
        $max_num = $cache_conf[$fid][$groupid];
        $att_price = $att_desc['price'];//附件售价

        if ($this->cfg['att_type']==2) {#收费资源
            $condition = $att_price > 0;
        }elseif ($this->cfg['att_type']==3) {
            $condition = $att_price == 0;#免费资源
        }elseif ($this->cfg['att_type']==1) {#所有资源
            $condition = true;
        }

        if (in_array($_aid, $cache_data[$today][$fid][$user_key]) || $max_num==='' || !isset($max_num)) {//如果已经下载过该附件或者未设置该会员用户组的配置则不做处理
            return;
        } elseif (count($cache_data[$today][$fid][$user_key]) >= intval($max_num) && $condition) {//如果该会员今天的下载量大于等于今天最大量则提示
            $log_data = array(
                'aid'=>$_aid,
                'uid'=>$_uid,
                'tid'=>$att_info[$_aid]['tid'],
                'useip'=>$_G['clientip']
            );
            $res_num = getcount($this->plugin_id.'_log',$log_data);
            $log_data['dateline'] = TIMESTAMP;
            $this->cfg['log_allow'] && !$res_num ? $this->db('log')->insert($log_data) : '';//插入日志
            $warning_str = str_replace('{max}', intval($max_num), $this->cfg['warning_str']);
            showmessage($warning_str,$this->cfg['warning_forward_url'],null,array('alert'=>'error','refreshtime'=>intval($this->cfg['location_time'])));
        } else {
            require_once libfile('function/cache');
            $cache_data[$today][$fid][$user_key][$_aid] = $_aid;
            if ($cache_data) {
                $cache_array .= "return " . arrayeval($cache_data, 1) . ";\n";
                writetocache('plugin_' . $this->plugin_id . '_log', $cache_array);
            }
        }
    }
}

class plugin_hwh_attachmentlimit extends hwh_attachmentlimit {
}

class plugin_hwh_attachmentlimit_forum extends plugin_hwh_attachmentlimit {

    public function attachment_log() {
       $this->_operation();
    }
}

class mobileplugin_hwh_attachmentlimit extends hwh_attachmentlimit {
}

class mobileplugin_hwh_attachmentlimit_forum extends plugin_hwh_attachmentlimit {
    public function attachment_log_mobile() {
        $this->_operation();
    }
}