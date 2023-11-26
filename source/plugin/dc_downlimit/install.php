<?php
/*
 *讯幻网：www.xhkj5.com
 *更多商业插件/模版免费下载 就在讯幻网
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_dc_downlimit` (
  `fid` int(11) NOT NULL,
  `data` text,
  PRIMARY KEY (`fid`)
);
CREATE TABLE IF NOT EXISTS `pre_dc_downlimit_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `times` int(11) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
EOF;

runquery($sql);
$finish = TRUE;
?>