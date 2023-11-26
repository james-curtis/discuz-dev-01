/*
MySQL Backup
Source Server Version: 5.7.18
Source Database: auth
Date: 2017-09-03 16:31:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `auth_block`
-- ----------------------------
DROP TABLE IF EXISTS `auth_block`;
CREATE TABLE `auth_block` (
  `url` varchar(150) NOT NULL,
  `date` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `db` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_config`
-- ----------------------------
DROP TABLE IF EXISTS `auth_config`;
CREATE TABLE `auth_config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `switch` int(1) DEFAULT '1',
  `update` int(1) DEFAULT '1',
  `vip0` float DEFAULT NULL COMMENT 'vip0',
  `vip1` float DEFAULT NULL COMMENT 'vip1',
  `vip2` float DEFAULT NULL COMMENT 'vip2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `auth_config` (`vip0`, `vip1`, `vip2`) VALUES
(50, 30, 25);

-- ----------------------------
--  Table structure for `auth_kms`
-- ----------------------------
DROP TABLE IF EXISTS `auth_kms`;
CREATE TABLE `auth_kms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kind` tinyint(1) NOT NULL DEFAULT '1',
  `km` varchar(255) DEFAULT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `zt` tinyint(1) DEFAULT '0',
  `user` varchar(50) DEFAULT NULL,
  `usetime` datetime DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `km` (`km`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_km`
-- ----------------------------
DROP TABLE IF EXISTS `auth_km`;
CREATE TABLE `auth_km` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `km` varchar(255) DEFAULT NULL,
  `zt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_log`
-- ----------------------------
DROP TABLE IF EXISTS `auth_log`;
CREATE TABLE `auth_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_order`
-- ----------------------------
DROP TABLE IF EXISTS `auth_order`;
CREATE TABLE `auth_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `user` varchar(150) DEFAULT NULL COMMENT '登录账号',
  `pass` varchar(150) DEFAULT NULL COMMENT '登录密码',
  `trade_no` varchar(255) DEFAULT NULL COMMENT '订单号,挺重要的',
  `url` varchar(255) DEFAULT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL COMMENT '商品价格',
  `type` varchar(255) DEFAULT NULL COMMENT '支付方式',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT '0' COMMENT '支付状态，1为成功，0失败',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_gl`
-- ----------------------------
DROP TABLE IF EXISTS `auth_gl`;
CREATE TABLE `auth_gl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `con` text,
  `fbTime` datetime DEFAULT NULL,
  `is` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_pay`
-- ----------------------------
DROP TABLE IF EXISTS `auth_pay`;
CREATE TABLE `auth_pay` (
  `k` varchar(32) NOT NULL,
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_site`
-- ----------------------------
DROP TABLE IF EXISTS `auth_site`;
CREATE TABLE `auth_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `uid` varchar(20) DEFAULT NULL,
  `user` varchar(150) NOT NULL,
  `pass` varchar(150) NOT NULL,
  `user_jf` int(11) NOT NULL,
  `dateline` date NULL,
  `dayes` text NULL,
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vip` int(11) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `authcode` varchar(100) DEFAULT NULL,
  `sign` varchar(20) DEFAULT NULL,
  `syskey` varchar(40) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `auth_user`
-- ----------------------------
DROP TABLE IF EXISTS `auth_user`;
CREATE TABLE `auth_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(150) DEFAULT NULL COMMENT '授权商账号',
  `pass` varchar(150) DEFAULT NULL COMMENT '授权商密码',
  `last` datetime DEFAULT NULL COMMENT '授权商登录时间',
  `dlip` varchar(15) DEFAULT NULL COMMENT '授权商IP',
  `per_sq` int(1) DEFAULT NULL COMMENT '授权商权限',
  `per_db` int(1) DEFAULT NULL COMMENT '授权商权限',
  `active` int(1) DEFAULT NULL COMMENT '授权商权限',
  `qq` varchar(150) DEFAULT NULL COMMENT '授权商QQ',
  `ips` varchar(255) DEFAULT NULL COMMENT '允许登陆IP列表',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `auth_user` VALUES ('1','admin','123456',NULL,NULL,'1','1','1','760611885',NULL);
INSERT INTO `auth_pay` VALUES ('template_index', 'default');