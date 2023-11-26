<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS cdb_cjxixi_alipayusergroup;
CREATE TABLE IF NOT EXISTS `cdb_cjxixi_alipayusergroup` (
  `order_id` char(38) NOT NULL,
  `order_status` char(3) NOT NULL,
  `uid` bigint(11) unsigned NOT NULL,
  `username`  varchar(20)  NOT NULL,
  `trade_no` char(50) NOT NULL,
  `group_id` mediumint(9) NOT NULL,
  `group_name` varchar(256) NOT NULL ,
  `extcreditstitle` varchar(256) NOT NULL,
  `extcredits` varchar(256) NOT NULL,
  `price` float(7,2) NOT NULL,
  `validity` char(10) NOT NULL,
  `submitdate` int(10) NOT NULL,
  `confirmdate` int(10) NOT NULL,
  `ip` char(15) NOT NULL,
  `pay_type` char(15) NOT NULL,
  UNIQUE KEY `order_id` (`order_id`),
  KEY `submitdate` (`submitdate`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;
EOF;
runquery($sql);
$finish = TRUE;
?>