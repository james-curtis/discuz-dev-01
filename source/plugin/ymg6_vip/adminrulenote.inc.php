<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//加载discuz 时间js
echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
//添加提示信息
showtips(lang('plugin/ymg6_vip', 'tips'));
//表格开始
showtableheader(lang('plugin/ymg6_vip', 'ruletitle'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote00'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote01'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote02'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote03'));
showtablefooter();
?>