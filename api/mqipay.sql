-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: mqipay
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_owner_channels`
--

DROP TABLE IF EXISTS `account_owner_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_owner_channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_owner_id` int NOT NULL,
  `channel_id` int NOT NULL,
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_owner_channels`
--

LOCK TABLES `account_owner_channels` WRITE;
/*!40000 ALTER TABLE `account_owner_channels` DISABLE KEYS */;
INSERT INTO `account_owner_channels` VALUES (1,4,1,0.60),(2,4,2,0.70),(3,4,3,0.80),(4,8,2,0.10),(5,9,2,0.10),(6,9,5,1.00),(7,9,5,1.00),(8,13,2,2.50),(9,14,2,0.00);
/*!40000 ALTER TABLE `account_owner_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_type_channels`
--

DROP TABLE IF EXISTS `account_type_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_type_channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int NOT NULL,
  `account_type_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_type_channels`
--

LOCK TABLES `account_type_channels` WRITE;
/*!40000 ALTER TABLE `account_type_channels` DISABLE KEYS */;
INSERT INTO `account_type_channels` VALUES (1,1,1),(3,3,3),(5,2,2),(7,5,6);
/*!40000 ALTER TABLE `account_type_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_types`
--

DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_types`
--

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` VALUES (1,'支付宝H5','alipay_h5',''),(2,'银联直扫','alipay_qr',''),(3,'银行卡','bank',''),(4,'聚富宝','jufubao_pay',''),(5,'当面付','dangmianfu',''),(6,'支付宝个人码','alipay_x','');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_owner_id` int NOT NULL,
  `account_type_id` int NOT NULL,
  `name` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `param1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `param2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `param3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `param4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `param5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `param6` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount_max_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_min_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_day_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `times_day_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('开启','关闭') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (13,9,2,'凤翔','http://api.henghaochi.com/storage/2025-09-26/68d62e0ae4deb.png','','','','','',500.00,100.00,30000.00,200.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(20,9,2,'杰','http://api.henghaochi.com/storage/2025-09-28/68d8b2b0162e2.jpg','','','','','',500.00,100.00,30000.00,200.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(33,9,6,'支付宝个码','2025-10-16/68f01efd74dee.png','','','','','',0.00,0.00,0.00,0.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(34,9,6,'11','https://api.henghaochi.com/storage/2025-10-16/68f0263247813.png','','','','','',0.00,0.00,0.00,0.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(35,9,6,'test','https://api.henghaochi.com/storage/2025-10-25/68fc77cb1c914.jpg','https://qr.alipay.com/fkx19993csxybhbnkwiob31','','','','',0.00,0.00,0.00,0.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(36,14,2,'范鸡通','https://api.henghaochi.com/storage/2025-10-28/69006935d2400.png','','','','','',500.00,100.00,2222.00,222.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(37,14,2,'渔品汇','https://api.henghaochi.com/storage/2025-10-28/69006a05e52ec.png','','','','','',500.00,100.00,22222.00,222.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(38,14,2,'齐天大圣','https://api.henghaochi.com/storage/2025-10-28/69006ac33258f.png','','','','','',500.00,100.00,22222.00,222.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(39,14,2,'美食店','https://api.henghaochi.com/storage/2025-10-28/69006bd065803.png','','','','','',500.00,100.00,55555.00,222.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(40,14,2,'有来有去','https://api.henghaochi.com/storage/2025-10-28/69006c9e3b133.png','','','','','',500.00,100.00,22222.00,222.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(41,14,2,'桐桐','https://api.henghaochi.com/storage/2025-10-28/69006d0a00b1a.png','','','','','',500.00,100.00,22222.00,222.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(42,13,6,'吴','https://api.henghaochi.com/storage/2025-10-28/6900875817966.jpg','https://qr.alipay.com/fkx15569nimobfdpjviope2','','','','',2999.00,0.00,20000.00,100.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(43,9,2,'未来可期（真）','https://api.henghaochi.com/storage/2025-10-28/6900c7991192d.png','','','','','',500.00,100.00,10000.00,200.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(44,9,2,'喜多多（**真）','https://api.henghaochi.com/storage/2025-10-28/6900c99eeb2bd.png','','','','','',500.00,100.00,10000.00,200.00,'','开启','2025-11-18 16:00:00','2025-11-18 16:00:00'),(45,9,6,'金腾烟酒行(飞）','https://api.henghaochi.com/storage/2025-10-28/6900cb670676a.png','https://qr.alipay.com/fkx145867ntlmgzm1zh8m6a','','','','',500.00,100.00,20000.00,200.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00'),(46,9,2,'mandy','https://api.henghaochi.com/storage/2025-11-10/6911a37d60454.png','','','','','',500.00,50.00,50000.00,100.00,'','关闭','2025-11-18 16:00:00','2025-11-18 16:00:00');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_merchants`
--

DROP TABLE IF EXISTS `agent_merchants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_merchants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL,
  `merchant_id` int NOT NULL,
  `channel_id` int NOT NULL,
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_merchants`
--

LOCK TABLES `agent_merchants` WRITE;
/*!40000 ALTER TABLE `agent_merchants` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_merchants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cashflows`
--

DROP TABLE IF EXISTS `cashflows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cashflows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cashflow_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_before` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_after` decimal(12,2) NOT NULL DEFAULT '0.00',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `daifu_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `daifu_amount_before` decimal(12,2) NOT NULL DEFAULT '0.00',
  `daifu_amount_after` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=753 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cashflows`
--

LOCK TABLES `cashflows` WRITE;
/*!40000 ALTER TABLE `cashflows` DISABLE KEYS */;
/*!40000 ALTER TABLE `cashflows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_max_limit` int NOT NULL DEFAULT '0',
  `amount_min_limit` int NOT NULL DEFAULT '0',
  `amount_day_limit` int NOT NULL DEFAULT '0',
  `floating_amount` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_amounts` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('开启','关闭') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channels`
--

LOCK TABLES `channels` WRITE;
/*!40000 ALTER TABLE `channels` DISABLE KEYS */;
INSERT INTO `channels` VALUES (1,'支付宝H5','alipay_h5',0,0,0,0,'','开启'),(2,'银联直扫','bank_qrcode',0,0,0,0,'','开启'),(3,'银行卡转账','bank_trans',0,0,0,0,'','开启'),(5,'支付宝扫码','alipay_qr',111,111,111,0,'','开启');
/*!40000 ALTER TABLE `channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daifu_trades`
--

DROP TABLE IF EXISTS `daifu_trades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daifu_trades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `daifu_trade_id` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '平台代付单号（内部唯一标识）',
  `out_daifu_trade_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户代付单号（外部系统传入）',
  `merchant_id` int NOT NULL COMMENT '商户ID（关联商户表）',
  `amount` decimal(12,2) NOT NULL COMMENT '代付金额',
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收款人姓名',
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收款银行名称',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收款账户（银行卡号/钱包地址）',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注（代付用途说明）',
  `notify_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商户异步回调地址',
  `status` enum('等待处理','处理成功','处理失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '代付处理状态',
  `notify_status` enum('等待回调','回调成功','回调失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '回调状态',
  `success_at` timestamp NULL DEFAULT NULL COMMENT '代付成功时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daifu_trades`
--

LOCK TABLES `daifu_trades` WRITE;
/*!40000 ALTER TABLE `daifu_trades` DISABLE KEYS */;
/*!40000 ALTER TABLE `daifu_trades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daifus`
--

DROP TABLE IF EXISTS `daifus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daifus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `daifu_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `out_daifu_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_id` int NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `notify_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fancha_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `call_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('等待反查','反查成功','反查失败','处理成功','处理失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notify_status` enum('等待回调','回调成功','回调失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `success_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voucher` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `account_owner_id` int NOT NULL DEFAULT '0',
  `received_at` timestamp NULL DEFAULT NULL,
  `receive_status` enum('待接单','待提交','已提交') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daifus`
--

LOCK TABLES `daifus` WRITE;
/*!40000 ALTER TABLE `daifus` DISABLE KEYS */;
/*!40000 ALTER TABLE `daifus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_records`
--

DROP TABLE IF EXISTS `login_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7703 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_records`
--

LOCK TABLES `login_records` WRITE;
/*!40000 ALTER TABLE `login_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `menuId` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menuType` tinyint(1) NOT NULL DEFAULT '0',
  `sortNumber` int NOT NULL DEFAULT '0',
  `authority` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `active` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,0,'首页','/admin/dashboard','/admin/dashboard',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(2,0,'用户中心','/user/profile','/admin/user/profile',0,0,'admin','_self',NULL,NULL,1,'',NULL),(3,0,'系统管理','/admin/system','',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(4,3,'管理员管理','/admin/system/admin','/admin/system/admin',0,3,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(5,3,'登录日志','/admin/system/login_log','/admin/system/login-record',0,5,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(6,0,'订单流水','/admin/trades','',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(7,6,'订单管理','/admin/trade/trade','/admin/trade/trade',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(8,6,'流水管理','/admin/trade/cashflow','/admin/trade/cashflow',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(9,0,'商户管理','/admin/merchant','',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(10,9,'商户管理','/admin/merchant/merchant','/admin/merchant/merchant',0,3,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(11,9,'提现管理','/admin/merchant/daifu_trade','/admin/merchant/daifu_trade',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(12,9,'代付管理','/admin/merchant/daifu','/admin/merchant/daifu',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(13,9,'数据统计','/admin/merchant/cashflow','/admin/merchant/cashflow',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(14,9,'代理管理','/admin/merchant/agent','/admin/merchant/agent',0,3,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(15,0,'通道管理','/admin/channel','',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(16,15,'支付码类型','/admin/channel/account_type','/admin/channel/account_type',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(17,15,'支付通道','/admin/channel/channel','/admin/channel/channel',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(18,15,'通道统计','/admin/channel/statistic','/admin/channel/statistic',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(19,0,'码商管理','/admin/account_owner','',0,0,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(20,19,'码商管理','/admin/account_owner/account_owner','/admin/account_owner/account_owner',0,3,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(21,19,'支付码管理','/admin/account_owner/account','/admin/account_owner/account',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(22,19,'码商统计','/admin/account_owner/statistic','/admin/account_owner/statistic',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(23,19,'支付码统计','/admin/account_owner/account_statistic','/admin/account_owner/account_statistic',0,1,'admin','_self','el-icon-monitor',NULL,0,'',NULL),(24,0,'用户中心','/user/profile','/merchant/user/profile',0,0,'merchant','_self',NULL,NULL,1,'',NULL),(25,0,'订单管理','/merchant/trade','/merchant/trade',0,0,'merchant','_self','el-icon-monitor',NULL,0,'',NULL),(26,0,'提现管理','/merchant/daifu_trade','/merchant/daifu_trade',0,1,'merchant','_self','el-icon-monitor',NULL,0,'',NULL),(27,0,'代付管理','/merchant/daifu','/merchant/daifu',0,1,'merchant','_self','el-icon-monitor',NULL,0,'',NULL),(28,0,'流水','/merchant/cashflow','/merchant/cashflow',0,3,'merchant','_self','el-icon-monitor',NULL,0,'',NULL),(29,0,'首页','/agent/dashboard','/agent/dashboard',0,0,'agent','_self','el-icon-monitor',NULL,0,'',NULL),(30,0,'用户中心','/user/profile','/agent/user/profile',0,0,'agent','_self',NULL,NULL,1,'',NULL),(31,0,'商户管理','/agent/merchant','/agent/merchant',0,0,'agent','_self','el-icon-monitor',NULL,0,'',NULL),(32,0,'订单管理','/agent/trade','/agent/trade',0,0,'agent','_self','el-icon-monitor',NULL,0,'',NULL),(33,0,'代付管理','/agent/daifu_trade','/agent/daifu_trade',0,1,'agent','_self','el-icon-monitor',NULL,0,'',NULL),(34,0,'流水','/agent/cashflow','/agent/cashflow',0,3,'agent','_self','el-icon-monitor',NULL,0,'',NULL),(35,0,'用户中心','/user/profile','/account_owner/user/profile',0,0,'account_owner','_self',NULL,NULL,1,'',NULL),(36,0,'付款码管理','/account_owner/account','/account_owner/account',0,0,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(37,0,'订单管理','/account_owner/trade','/account_owner/trade',0,0,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(38,0,'充值管理','/account_owner/recharge','/account_owner/recharge',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(39,0,'代付管理','/account_owner/daifu_trade','/account_owner/daifu_trade',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(40,0,'代付抢单','/account_owner/daifu_realtime','/account_owner/daifu_realtime',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(41,0,'团队成员','/account_owner/member','/account_owner/member',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(42,0,'团队订单','/account_owner/member_trade','/account_owner/member_trade',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(43,0,'团队数据统计','/account_owner/member_statistic','/account_owner/member_statistic',0,1,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL),(44,0,'流水','/account_owner/cashflow','/account_owner/cashflow',0,3,'account_owner','_self','el-icon-monitor',NULL,0,'',NULL);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `merchant_channels`
--

DROP TABLE IF EXISTS `merchant_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `merchant_channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` int NOT NULL,
  `channel_id` int NOT NULL,
  `rate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `schedule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merchant_channels`
--

LOCK TABLES `merchant_channels` WRITE;
/*!40000 ALTER TABLE `merchant_channels` DISABLE KEYS */;
INSERT INTO `merchant_channels` VALUES (1,2,1,1.00,''),(3,2,3,3.00,''),(4,2,4,1.00,''),(5,10,2,5.00,''),(6,2,2,2.00,''),(7,11,2,0.00,''),(8,2,5,1.00,''),(9,12,2,0.00,''),(10,16,2,0.00,'');
/*!40000 ALTER TABLE `merchant_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_04_15_042312_create_menus',1),(2,'2024_04_15_042425_create_users',1),(3,'2024_04_15_042441_create_roles',1),(4,'2024_04_15_042457_create_role_menus',1),(5,'2024_04_15_042526_create_login_records',1),(6,'2024_04_15_042556_create_operation_records',1),(7,'2024_04_20_040343_create_trades',1),(8,'2024_04_20_040443_create_channels',1),(9,'2024_04_20_040539_create_cashflows',1),(10,'2024_04_20_040713_create_accounts',1),(11,'2024_04_20_040844_create_recharges',1),(12,'2024_04_20_040910_create_daifu_trades',1),(13,'2024_04_20_040939_create_withdraws',1),(14,'2024_04_21_091413_create_merchant_channels',1),(15,'2024_04_28_161631_create_account_types',1),(16,'2024_04_28_163212_create_account_type_channels',1),(17,'2024_05_03_071539_create_account_owner_channels',1),(18,'2024_05_06_195736_create_agent_merchants',1),(19,'2024_05_07_090026_create_trade_agents',1),(20,'2024_05_08_053803_create_trade_account_owners',1),(21,'2024_05_15_081652_create_daifus',1),(22,'2024_05_25_044014_add_payer',1),(23,'2024_05_25_044241_add_voucher',1),(24,'2024_05_26_123512_add_daifu',1),(25,'2024_06_19_163123_add_level',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_records`
--

DROP TABLE IF EXISTS `operation_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operation_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `spend_time` int NOT NULL,
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_records`
--

LOCK TABLES `operation_records` WRITE;
/*!40000 ALTER TABLE `operation_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `operation_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recharges`
--

DROP TABLE IF EXISTS `recharges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recharges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `recharge_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_owner_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `receipts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('等待处理','处理成功','处理失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` int NOT NULL DEFAULT '0',
  `success_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recharges`
--

LOCK TABLES `recharges` WRITE;
/*!40000 ALTER TABLE `recharges` DISABLE KEYS */;
/*!40000 ALTER TABLE `recharges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_menus`
--

DROP TABLE IF EXISTS `role_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_menus`
--

LOCK TABLES `role_menus` WRITE;
/*!40000 ALTER TABLE `role_menus` DISABLE KEYS */;
INSERT INTO `role_menus` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(9,1,9),(10,1,10),(11,1,11),(12,1,12),(13,1,13),(14,1,14),(15,1,15),(16,1,16),(17,1,17),(18,1,18),(19,1,19),(20,1,20),(21,1,21),(22,1,22),(23,1,23),(24,2,24),(25,2,25),(26,2,26),(27,2,27),(28,2,28),(29,3,29),(30,3,30),(31,3,31),(32,3,32),(33,3,33),(34,3,34),(35,4,35),(36,4,36),(37,4,37),(38,4,38),(39,4,39),(40,4,40),(41,4,41),(42,4,42),(43,4,43),(44,4,44);
/*!40000 ALTER TABLE `role_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'管理员','admin','管理员'),(2,'商户','merchant','商户'),(3,'代理','agent','代理'),(4,'码商','account_owner','码商');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trade_account_owners`
--

DROP TABLE IF EXISTS `trade_account_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trade_account_owners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trade_id` int NOT NULL,
  `account_owner_id` int NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trade_account_owners`
--

LOCK TABLES `trade_account_owners` WRITE;
/*!40000 ALTER TABLE `trade_account_owners` DISABLE KEYS */;
/*!40000 ALTER TABLE `trade_account_owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trade_agents`
--

DROP TABLE IF EXISTS `trade_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trade_agents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trade_id` int NOT NULL,
  `agent_id` int NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trade_agents`
--

LOCK TABLES `trade_agents` WRITE;
/*!40000 ALTER TABLE `trade_agents` DISABLE KEYS */;
/*!40000 ALTER TABLE `trade_agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trades`
--

DROP TABLE IF EXISTS `trades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trade_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '平台交易单号（唯一标识）',
  `out_trade_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户订单号（外部系统传入）',
  `amount` decimal(12,2) NOT NULL COMMENT '应付金额',
  `amount_real` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `merchant_id` int NOT NULL COMMENT '商户ID',
  `account_id` int NOT NULL DEFAULT '0' COMMENT '收款码ID',
  `account_owner_id` int NOT NULL DEFAULT '0' COMMENT '收款码所属码商ID',
  `merchant_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '商户费率（百分比）',
  `status` enum('等待支付','支付完成','支付失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付状态',
  `notify_status` enum('等待通知','通知失败','通知成功') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '异步通知状态',
  `channel_id` int NOT NULL COMMENT '支付通道ID',
  `pay_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '支付跳转链接/二维码地址',
  `notify_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商户异步通知回调地址',
  `return_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付成功后跳转地址',
  `client_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '客户端IP',
  `success_at` timestamp NULL DEFAULT NULL COMMENT '支付成功时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payer` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `payer_note` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `trades_trade_id_unique` (`trade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=447 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trades`
--

LOCK TABLES `trades` WRITE;
/*!40000 ALTER TABLE `trades` DISABLE KEYS */;
/*!40000 ALTER TABLE `trades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pid` int NOT NULL DEFAULT '0',
  `agent_id` int NOT NULL DEFAULT '0',
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `realname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `daifu_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `daifu_balance_lock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `balance_lock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secure_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `google_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secure_ips` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `admin_secure_ips` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `role_id` int NOT NULL,
  `status` enum('启用','冻结') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jiedan_status` enum('开启','关闭') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_id` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `merchant_key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,0,'admin','admin_0',0.00,0.00,0.00,0.00,'$2y$12$OWauzK91n/PHoP1TwsbQzusQs9RbB8w2OPp9JmzB0BHxmTNILefDi','','2CMQWZDL2KKFA5QG','TGGH40fPxh1F','','',1,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(2,0,0,'merchant','merchant_0',0.00,0.00,100310.69,0.00,'$2y$12$rexJipmsluiQ4IENPl3dNeRU2Tbh.2RP0z2rPLB63mQI17Om6zxSy','','','5BdRDVNy780I','','',2,'启用','开启','2024010101','a2e8sdEDtenwer3E',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(3,0,0,'agent','agent_0',0.00,0.00,0.00,0.00,'$2y$12$.L8lDIdEu5C./NPA97WcTOcEH5XzP7sztQItWjTW2dCHrmXKfC3CK','','','khAU9L3XLGyj','','',3,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(9,9,0,'ceshi','DD总台',0.00,0.00,987365.25,5.88,'$2y$12$MafWEeYKUr0YKSIsXvfwbOzSewJBDK1hDvmHBKmKt0rDPq7bbi7xe','','','ThWKGMttMNwL','','',4,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(10,0,0,'疾风','疾风',0.00,0.00,0.00,0.00,'$2y$12$dD8fCzYY4blLiHHoMCnUN.yKmX0I1eGpNq7A7L8Dig2gRUspmTrvG','','','PUpg4cg6t3pm','','',2,'启用','开启','MCI2318485417969','KlfgB9BE8iSEVAH5qiGxnMndziXR9OMa',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(11,0,0,'jifeng ','疾风',0.00,0.00,10000665.00,0.00,'$2y$12$yorVI6ZRnIMJwlbGoBZSNOtqtdF09kAbcaafSH6dBz25MCEIntnPi','','','8Zbn9EmVz3cz','','',2,'启用','开启','MCI2318485417968','I8uSdks7fAfddwmdlUALjT5mdu7bkD2F',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(12,0,0,'天涯','天涯',0.00,0.00,19183.65,0.00,'$2y$12$Z3A1uWCF1j79msp9VrBYTe4tP4di21D/h7v7JHu0A4jvx34L7rTEC','','','Ts5vuTNXPp07','','',2,'启用','开启','MCI2719171169372','T21kxq2EsfK7MRB8FtYvxx8G20vNdUW8',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(13,9,0,'yiwu888','义乌',0.00,0.00,0.00,0.00,'$2y$12$s.tScSCC0t33g/WK0Pnor.T7kQPyNtMfeIUY7H6XfkDjGxIIiUCcS','','','R4OHtMurpR5H','','',4,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(14,9,0,'fu888','福鼎',0.00,0.00,99604.40,0.00,'$2y$12$yQtxjLPyZJdgTlRDf7IzMO0Tt1h1kjVNq48h8ISj/9iNALRmuHBtu','','UB2D44QZ3QMDXVAO','9uAUA4Zpcxx4','','',4,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(15,0,0,'admin2','admin2',0.00,0.00,0.00,0.00,'$2y$12$a5JdvftyHNF2DLnFjQo6feOh4fb9gyob8zjZc6U2xbJhId6jOBtYi','','BQIALC6GYPM2VXRY','Rz4IAiKSBO8I','','',1,'启用','开启','','',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02'),(16,0,0,'风云','风云',0.00,0.00,2141.67,0.00,'$2y$12$3WRcYo2WthZBCCAOjjeyhOrcTMfhfuVPNUYtxTBv6Y53VPFL4GXbC','','','lhiwYXMb8Guf','','',2,'冻结','开启','MCI0914304845868','Q93rP0cYsEEwYadnlm8u3VUp7wwW1MxM',NULL,'2025-11-18 16:00:00','2025-11-21 21:00:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraws`
--

DROP TABLE IF EXISTS `withdraws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraws` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_owner_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('等待处理','处理成功','处理失败') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` int NOT NULL DEFAULT '0',
  `daozhang_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraws`
--

LOCK TABLES `withdraws` WRITE;
/*!40000 ALTER TABLE `withdraws` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdraws` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-22  8:58:24
