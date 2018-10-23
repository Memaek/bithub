/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_menu`
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `menu_id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `parent_id` int(4) DEFAULT '0',
  `link` varchar(128) DEFAULT '',
  `icon` varchar(64) DEFAULT NULL,
  `order` int(4) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1:启用 2：禁用',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=903 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES ('1', '用户管理', '0', '#', 'icon iconfont icon-home7', '100', '1');
INSERT INTO `admin_menu` VALUES ('2', '设备管理', '0', '#', 'icon iconfont icon-home7', '200', '1');
INSERT INTO `admin_menu` VALUES ('3', '钱包管理', '0', '#', 'icon iconfont icon-home7', '300', '1');
INSERT INTO `admin_menu` VALUES ('9', '后台用户管理', '0', '#', 'icon iconfont icon-home7', '900', '1');
INSERT INTO `admin_menu` VALUES ('101', '用户列表', '1', '/bithub/user/userlist', null, '101', '1');
INSERT INTO `admin_menu` VALUES ('201', '设备列表', '2', '/bithub/device/devicelist', null, '201', '1');
INSERT INTO `admin_menu` VALUES ('301', '转账交易记录', '3', '/bithub/wallet/traderecordlist', null, '301', '1');
INSERT INTO `admin_menu` VALUES ('302', '用户资产列表', '3', '/bithub/wallet/usertreasure', null, '302', '1');
INSERT INTO `admin_menu` VALUES ('901', '用户管理', '9', '/admin/user', '', '901', '1');
INSERT INTO `admin_menu` VALUES ('902', '角色管理', '9', '/admin/role', '', '902', '1');


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_module`
-- ----------------------------
DROP TABLE IF EXISTS `admin_module`;
CREATE TABLE `admin_module` (
  `module_id` int(4) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `description` varchar(16) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`module_id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of admin_module
-- ----------------------------
INSERT INTO `admin_module` VALUES ('1', '1', '菜单查看', 'menu');
INSERT INTO `admin_module` VALUES ('2', '1', '菜单添加', 'menu_add');
INSERT INTO `admin_module` VALUES ('3', '1', '菜单编辑', 'menu_edit');
INSERT INTO `admin_module` VALUES ('4', '1', '菜单删除', 'menu_delete');


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_permission`
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission`;
CREATE TABLE `admin_permission` (
  `role_id` int(4) DEFAULT NULL,
  `module_id` int(4) DEFAULT NULL,
  `value` float(16,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of admin_permission
-- ----------------------------
INSERT INTO `admin_permission` VALUES ('1', '1', '901.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '902.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '9.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '1.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '2.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '101.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '201.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '3.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '301.00');
INSERT INTO `admin_permission` VALUES ('1', '1', '302.00');


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `role_id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `description` varchar(32) DEFAULT NULL,
  `role_right` int(4) DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES ('1', 'super_admin', '超级管理员', '9999');
INSERT INTO `admin_role` VALUES ('2', 'admin', '项目经理', '9998');
INSERT INTO `admin_role` VALUES ('3', 'operate', '运营', '999');



/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:36:51
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `user_id` int(4) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(32) DEFAULT NULL,
  `user_name` varchar(32) DEFAULT NULL,
  `role_id` int(8) NOT NULL,
  `salt` varchar(6) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `last_login_ip` varchar(16) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_version`
-- ----------------------------
DROP TABLE IF EXISTS `admin_version`;
CREATE TABLE `admin_version` (
  `version_id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `user` varchar(10) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`version_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_version
-- ----------------------------


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `device`
-- ----------------------------
DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `mac` char(12) NOT NULL DEFAULT '',
  `sn` char(8) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of device
-- ----------------------------
INSERT INTO `device` VALUES ('F82BC800F000', 'NWUZMTM5', null);
INSERT INTO `device` VALUES ('F82BC800F001', 'MJ54YMEZ', null);
INSERT INTO `device` VALUES ('F82BC800F002', 'N2Z4YWFK', null);
INSERT INTO `device` VALUES ('F82BC800F003', 'NTCYNGRJ', null);
INSERT INTO `device` VALUES ('F82BC800F004', 'ZWE3MMEZ', null);
INSERT INTO `device` VALUES ('F82BC800F005', 'YTU4NDA3', null);
INSERT INTO `device` VALUES ('F82BC800F006', 'MZ4YM2Y3', null);
INSERT INTO `device` VALUES ('F82BC800F007', 'ZTUWYJ5K', null);
INSERT INTO `device` VALUES ('F82BC800F008', 'N2EW6DMZ', null);
INSERT INTO `device` VALUES ('F82BC800F009', 'MGMWNJEY', null);




/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:29:57
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `img_url` varchar(255) NOT NULL COMMENT '图片地址',
  `details` longtext COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:30:07
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `trade_record`
-- ----------------------------
DROP TABLE IF EXISTS `trade_record`;
CREATE TABLE `trade_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_user_id` int(11) NOT NULL,
  `receipt_user_id` int(11) NOT NULL,
  `price` int(12) NOT NULL COMMENT '支付币数',
  `create_datetime` datetime NOT NULL COMMENT '交易时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:30:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(32) NOT NULL COMMENT '登录名',
  `password` varchar(32) DEFAULT NULL COMMENT '登录密码',
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL COMMENT '电话号码',
  `verify_code` varchar(10) DEFAULT NULL COMMENT '手机验证码',
  `verify_exptime` varchar(10) DEFAULT NULL COMMENT '手机验证码有效期',
  `tel_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '手机绑定状态',
  `auth_key` varchar(32) DEFAULT NULL COMMENT '用户唯一标识',
  `auth_secret` varchar(32) DEFAULT NULL COMMENT '加密密钥',
  `reg_ip` varchar(45) DEFAULT NULL COMMENT '注册IP',
  `reg_date` datetime DEFAULT NULL COMMENT '注册时间',
  `login_ip` varchar(45) DEFAULT NULL COMMENT '最后登录IP',
  `login_date` datetime DEFAULT NULL COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邮箱激活状态',
  `token` varchar(32) DEFAULT NULL COMMENT '邮箱激活码',
  `token_exptime` varchar(10) DEFAULT NULL COMMENT '激活码有效期',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-06 10:30:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `user_wallet`
-- ----------------------------
DROP TABLE IF EXISTS `user_wallet`;
CREATE TABLE `user_wallet` (
  `user_id` int(11) DEFAULT NULL,
  `coin` bigint(20) DEFAULT NULL COMMENT '比特币数量',
  `cash` bigint(20) DEFAULT NULL COMMENT '现金金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bithub

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-13 09:38:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bank`
-- ----------------------------
DROP TABLE IF EXISTS `bank`;
CREATE TABLE `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `number` varchar(32) NOT NULL COMMENT '银行卡卡号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

