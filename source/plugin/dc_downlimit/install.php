<?php
/*
 *Ѷ������www.xhkj5.com
 *������ҵ���/ģ��������� ����Ѷ����
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
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