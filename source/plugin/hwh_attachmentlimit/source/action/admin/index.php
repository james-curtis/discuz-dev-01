<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

defined('IN_DISCUZ') && defined('IN_ADMINCP') || exit('Access Denied');

if ($_GET['op'] == 'hymanwu') {
	echo '<style type="text/css">.current font{color:#FFF!important;}</style><iframe src="http://www.hymanwu.com/pages/intoplugin/" width="100%" height="636" frameborder="0" scrolling="no"></iframe>';
}else{
	hwh_404();
}