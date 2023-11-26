<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


defined('IN_DISCUZ') or exit('Powered by Hymanwu.Com');

class table_hwh_attachmentlimit_log extends discuz_table {

    public function __construct() {
        $this->_table = 'hwh_attachmentlimit_log';
        $this->_pk    = 'id';
        parent::__construct();
    }

    public function fetch_range($start = 0, $limit = 20, $condition = array(), $sort = 'ASC') {
        if ($condition) {
            foreach ($condition as $field => $value) {
                $condition[$field] = DB::field($field,$value);
            }
            $condition_sql = ' WHERE '.join(' AND ', $condition);
        }
        return DB::fetch_all('SELECT * FROM %t %i ORDER BY '.DB::order($this->_pk, $sort) . DB::limit($start, $limit), array($this->_table, $condition_sql));
    }

    public function clear_table() {
        return DB::query('TRUNCATE TABLE %t', array($this->_table));
    }

}