/*
Navicat MySQL Data Transfer

Source Server         : local_no_local
Source Server Version : 50538
Source Host           : localhost:3306
Source Database       : phalcontest

Target Server Type    : MYSQL
Target Server Version : 50538
File Encoding         : 65001

Date: 2016-07-06 22:03:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'Имя',
  `phone` varchar(255) DEFAULT NULL COMMENT 'Телефон',
  `email` varchar(255) DEFAULT NULL COMMENT 'E-mail',
  `message` text COMMENT 'Сообщение',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES ('7', '2345', '+380123467890', 'test@example.com', '222', '2016-07-06 22:02:00');
INSERT INTO `messages` VALUES ('9', '323211111111', '+380674563256', 'test@example.com', '2323', '2016-07-06 22:08:00');
INSERT INTO `messages` VALUES ('10', '1212', '+380674563256', 'test@example.com', '121', '2016-07-06 22:40:00');
