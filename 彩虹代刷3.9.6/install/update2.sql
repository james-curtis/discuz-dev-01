ALTER TABLE `shua_tools`
ADD COLUMN `cid` int(11) NULL DEFAULT '0',
ADD COLUMN `goods_id` int(11) NOT NULL DEFAULT '0',
ADD COLUMN `goods_type` int(11) NOT NULL DEFAULT '0',
ADD COLUMN `goods_param` varchar(100) DEFAULT NULL;

ALTER TABLE `shua_kms`
ADD COLUMN `orderid` int(11) NULL DEFAULT '0';

ALTER TABLE `shua_pay`
ADD COLUMN `zid` int(11) NOT NULL DEFAULT '1';

DROP TABLE IF EXISTS `shua_site`;
CREATE TABLE `shua_site` (
  `zid` int(11) NOT NULL AUTO_INCREMENT,
  `upzid` int(11) NOT NULL DEFAULT '0',
  `domain` varchar(20) NOT NULL,
  `domain2` varchar(20) DEFAULT NULL,
  `user` varchar(20) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(11) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) DEFAULT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  `qq` varchar(12) DEFAULT NULL,
  `sitename` varchar(80) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `kaurl` varchar(50) DEFAULT NULL,
  `anounce` text DEFAULT NULL,
  `bottom` text DEFAULT NULL,
  `modal` text DEFAULT NULL,
  `price` text DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

DROP TABLE IF EXISTS `shua_tixian`;
CREATE TABLE `shua_tixian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_account` varchar(50) NOT NULL,
  `pay_name` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `shua_class`;
CREATE TABLE `shua_class` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `active` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `shua_points`;
CREATE TABLE `shua_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL,
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bz` varchar(1024) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `shua_config` VALUES ('fenzhan_tixian', '0');
INSERT INTO `shua_config` VALUES ('tixian_rate', '90');
INSERT INTO `shua_config` VALUES ('tixian_min', '10');
