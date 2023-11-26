<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
//添加提示信息
showtips(lang('plugin/xhw6_vip', 'tips'));
//表格开始
showtableheader(lang('plugin/xhw6_vip', 'ruletitle'));
showtablerow('','',lang('plugin/xhw6_vip', 'rulenote00'));
showtablerow('','',lang('plugin/xhw6_vip', 'rulenote01'));
showtablerow('','',lang('plugin/xhw6_vip', 'rulenote02'));
showtablerow('','',lang('plugin/xhw6_vip', 'rulenote03'));
showtablefooter();