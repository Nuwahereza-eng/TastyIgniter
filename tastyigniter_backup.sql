-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: tastyigniter
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.2

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
-- Table structure for table `ti_addresses`
--

DROP TABLE IF EXISTS `ti_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_addresses` (
  `address_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_addresses`
--

LOCK TABLES `ti_addresses` WRITE;
/*!40000 ALTER TABLE `ti_addresses` DISABLE KEYS */;
INSERT INTO `ti_addresses` VALUES (1,1,'Bukoto Kisaasi road Kampala',NULL,'kampala',NULL,'00000',219,'2026-01-19 12:16:06','2026-01-19 12:16:06'),(2,2,'kawempe','kisaasi','kampala','Central','00000',219,'2026-01-21 09:09:01','2026-01-21 09:09:01');
/*!40000 ALTER TABLE `ti_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_admin_user_groups`
--

DROP TABLE IF EXISTS `ti_admin_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_admin_user_groups` (
  `user_group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_group_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_assign` tinyint(1) DEFAULT '0',
  `auto_assign_mode` tinyint DEFAULT '1',
  `auto_assign_limit` int DEFAULT '20',
  `auto_assign_availability` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_admin_user_groups`
--

LOCK TABLES `ti_admin_user_groups` WRITE;
/*!40000 ALTER TABLE `ti_admin_user_groups` DISABLE KEYS */;
INSERT INTO `ti_admin_user_groups` VALUES (1,'Owners','Default group for owners',0,1,20,1,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Managers','Default group for managers',0,1,20,1,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Waiters','Default group for waiters.',0,1,20,1,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Delivery','Default group for delivery drivers.',0,1,20,1,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_admin_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_admin_user_preferences`
--

DROP TABLE IF EXISTS `ti_admin_user_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_admin_user_preferences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_admin_user_preferences`
--

LOCK TABLES `ti_admin_user_preferences` WRITE;
/*!40000 ALTER TABLE `ti_admin_user_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_admin_user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_admin_user_roles`
--

DROP TABLE IF EXISTS `ti_admin_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_admin_user_roles` (
  `user_role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_admin_user_roles`
--

LOCK TABLES `ti_admin_user_roles` WRITE;
/*!40000 ALTER TABLE `ti_admin_user_roles` DISABLE KEYS */;
INSERT INTO `ti_admin_user_roles` VALUES (1,'Owner','owner','Default role for restaurant owners',NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Manager','manager','Default role for restaurant managers.','a:16:{s:15:\"Admin.Dashboard\";s:1:\"1\";s:16:\"Admin.Categories\";s:1:\"1\";s:14:\"Admin.Statuses\";s:1:\"1\";s:12:\"Admin.Staffs\";s:1:\"1\";s:17:\"Admin.StaffGroups\";s:1:\"1\";s:15:\"Admin.Customers\";s:1:\"1\";s:20:\"Admin.CustomerGroups\";s:1:\"1\";s:14:\"Admin.Payments\";s:1:\"1\";s:18:\"Admin.Reservations\";s:1:\"1\";s:12:\"Admin.Orders\";s:1:\"1\";s:12:\"Admin.Tables\";s:1:\"1\";s:15:\"Admin.Locations\";s:1:\"1\";s:15:\"Admin.Mealtimes\";s:1:\"1\";s:11:\"Admin.Menus\";s:1:\"1\";s:11:\"Site.Themes\";s:1:\"1\";s:18:\"Admin.MediaManager\";s:1:\"1\";}','2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Waiter','waiter','Default role for restaurant waiters.','a:4:{s:16:\"Admin.Categories\";s:1:\"1\";s:18:\"Admin.Reservations\";s:1:\"1\";s:12:\"Admin.Orders\";s:1:\"1\";s:11:\"Admin.Menus\";s:1:\"1\";}','2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Delivery','delivery','Default role for restaurant delivery.','a:3:{s:14:\"Admin.Statuses\";s:1:\"1\";s:18:\"Admin.Reservations\";s:1:\"1\";s:12:\"Admin.Orders\";s:1:\"1\";}','2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_admin_users`
--

DROP TABLE IF EXISTS `ti_admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_admin_users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role_id` bigint unsigned DEFAULT NULL,
  `language_id` bigint unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sale_permission` tinyint NOT NULL DEFAULT '0',
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `super_user` tinyint(1) DEFAULT NULL,
  `reset_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_time` datetime DEFAULT NULL,
  `activation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invited_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `ti_admin_users_username_unique` (`username`),
  UNIQUE KEY `ti_admin_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_admin_users`
--

LOCK TABLES `ti_admin_users` WRITE;
/*!40000 ALTER TABLE `ti_admin_users` DISABLE KEYS */;
INSERT INTO `ti_admin_users` VALUES (1,'Nuwahereza Peter','nuwaherezapeter34@gmail.com',NULL,1,NULL,1,0,'admin','$2y$10$wY64AsnwXolzxJ1AKT7Ot.alaYbjYODnPFuRC/3jtHIPygeg3KrhO',1,NULL,NULL,NULL,'XyXwaxHXe0aIruTe02jmU7ORWGufM5gDM7vM6rNcc60BHcjtI7TK5XEhM5gs',1,'2026-01-19 11:41:10','2026-01-21 14:51:26','2026-01-24 12:45:58','2026-01-19 08:41:10','2026-01-24 09:45:58',NULL),(2,'Nyanja Joseph','nyanjajoseph9@gmail.com','0754866975',4,1,1,0,'mendes',NULL,0,'03Vs7YkSAvgLybdw5VUGqhbUKzLgNQm7fyX7FL8esQ','2026-01-22 13:34:19',NULL,NULL,1,'2026-01-22 13:34:19','2026-01-23 10:18:47','2026-01-23 10:18:47','2026-01-22 10:34:19','2026-01-23 07:18:47','2026-01-22 10:34:19');
/*!40000 ALTER TABLE `ti_admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_admin_users_groups`
--

DROP TABLE IF EXISTS `ti_admin_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_admin_users_groups` (
  `user_id` int unsigned NOT NULL,
  `user_group_id` int unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_admin_users_groups`
--

LOCK TABLES `ti_admin_users_groups` WRITE;
/*!40000 ALTER TABLE `ti_admin_users_groups` DISABLE KEYS */;
INSERT INTO `ti_admin_users_groups` VALUES (1,1),(2,4);
/*!40000 ALTER TABLE `ti_admin_users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_assignable_logs`
--

DROP TABLE IF EXISTS `ti_assignable_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_assignable_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assignable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignable_id` bigint unsigned NOT NULL,
  `assignee_id` int unsigned DEFAULT NULL,
  `assignee_group_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `status_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignable_logs_assignable` (`assignable_type`,`assignable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_assignable_logs`
--

LOCK TABLES `ti_assignable_logs` WRITE;
/*!40000 ALTER TABLE `ti_assignable_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_assignable_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_categories`
--

DROP TABLE IF EXISTS `ti_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_categories` (
  `category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `nest_left` int DEFAULT NULL,
  `nest_right` int DEFAULT NULL,
  `permalink_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `idx_categories_status` (`status`),
  KEY `idx_categories_status_priority` (`status`,`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_categories`
--

LOCK TABLES `ti_categories` WRITE;
/*!40000 ALTER TABLE `ti_categories` DISABLE KEYS */;
INSERT INTO `ti_categories` VALUES (1,'Appetizer','Sed consequat, sapien in scelerisque egestas, neque nisi dapibus magna, non malesuada lectus ligula vel justo. Vestibulum felis nisi, tincidunt eu est quis, faucibus tincidunt ante.',NULL,1,1,NULL,NULL,'appetizer','2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Main Course','',NULL,6,1,NULL,NULL,'main-course','2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Salads','Etiam tristique pretium enim, vel convallis sem fermentum eget. Donec porta risus vestibulum elit gravida ornare. Quisque neque mi, tincidunt quis leo eget, ornare aliquam nulla. Morbi at lacinia lorem. Aenean at accumsan turpis.',NULL,3,1,NULL,NULL,'salads','2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Seafoods','Morbi blandit massa et massa ornare, sed aliquam risus suscipit. Suspendisse et felis vitae ex pulvinar dictum et non dui. Suspendisse ullamcorper diam ac aliquet malesuada. Duis auctor nisi turpis, a ornare nisi auctor sit amet. Suspendisse imperdiet magna accumsan libero laoreet, consectetur sollicitudin sem maximus.',NULL,4,1,NULL,NULL,'seafoods','2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,'Traditional','Authentic Ugandan dishes made with love and traditional recipes passed down through generations.',NULL,5,1,NULL,NULL,'traditional','2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,'Desserts','',NULL,8,1,NULL,NULL,'desserts','2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,'Drinks','',NULL,9,1,NULL,NULL,'drinks','2026-01-19 08:07:51','2026-01-19 08:07:51'),(8,'Specials','Praesent nec velit faucibus, consequat justo eu, malesuada est. Aenean leo ipsum, venenatis nec dapibus ullamcorper, volutpat eget leo. Phasellus nec ipsum lorem. Etiam nec ullamcorper augue. Phasellus mauris turpis, consequat et rutrum at, bibendum eu mi.',NULL,2,1,NULL,NULL,'specials','2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_countries`
--

DROP TABLE IF EXISTS `ti_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_countries` (
  `country_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code_2` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_code_3` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_countries`
--

LOCK TABLES `ti_countries` WRITE;
/*!40000 ALTER TABLE `ti_countries` DISABLE KEYS */;
INSERT INTO `ti_countries` VALUES (1,'Afghanistan','AF','AFG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(2,'Albania','AL','ALB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(3,'Algeria','DZ','DZA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(4,'American Samoa','AS','ASM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(5,'Andorra','AD','AND',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(6,'Angola','AO','AGO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(7,'Anguilla','AI','AIA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(8,'Antarctica','AQ','ATA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(9,'Antigua and Barbuda','AG','ATG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(10,'Argentina','AR','ARG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(11,'Armenia','AM','ARM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(12,'Aruba','AW','ABW',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(13,'Australia','AU','AUS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(14,'Austria','AT','AUT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(15,'Azerbaijan','AZ','AZE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(16,'Bahamas','BS','BHS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(17,'Bahrain','BH','BHR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(18,'Bangladesh','BD','BGD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(19,'Barbados','BB','BRB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(20,'Belarus','BY','BLR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(21,'Belgium','BE','BEL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(22,'Belize','BZ','BLZ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(23,'Benin','BJ','BEN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(24,'Bermuda','BM','BMU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(25,'Bhutan','BT','BTN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(26,'Bolivia','BO','BOL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(27,'Bosnia and Herzegowina','BA','BIH',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(28,'Botswana','BW','BWA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(29,'Bouvet Island','BV','BVT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(30,'Brazil','BR','BRA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(31,'British Indian Ocean Territory','IO','IOT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(32,'Brunei Darussalam','BN','BRN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(33,'Bulgaria','BG','BGR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(34,'Burkina Faso','BF','BFA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(35,'Burundi','BI','BDI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(36,'Cambodia','KH','KHM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(37,'Cameroon','CM','CMR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(38,'Canada','CA','CAN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(39,'Cape Verde','CV','CPV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(40,'Cayman Islands','KY','CYM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(41,'Central African Republic','CF','CAF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(42,'Chad','TD','TCD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(43,'Chile','CL','CHL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(44,'China','CN','CHN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(45,'Christmas Island','CX','CXR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(46,'Cocos (Keeling) Islands','CC','CCK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(47,'Colombia','CO','COL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(48,'Comoros','KM','COM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(49,'Congo','CG','COG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(50,'Cook Islands','CK','COK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(51,'Costa Rica','CR','CRI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(52,'Cote D\'Ivoire','CI','CIV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(53,'Croatia','HR','HRV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(54,'Cuba','CU','CUB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(55,'Cyprus','CY','CYP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(56,'Czech Republic','CZ','CZE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(57,'Denmark','DK','DNK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(58,'Djibouti','DJ','DJI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(59,'Dominica','DM','DMA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(60,'Dominican Republic','DO','DOM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(61,'East Timor','TP','TMP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(62,'Ecuador','EC','ECU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(63,'Egypt','EG','EGY',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(64,'El Salvador','SV','SLV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(65,'Equatorial Guinea','GQ','GNQ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(66,'Eritrea','ER','ERI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(67,'Estonia','EE','EST',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(68,'Ethiopia','ET','ETH',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(69,'Falkland Islands (Malvinas)','FK','FLK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(70,'Faroe Islands','FO','FRO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(71,'Fiji','FJ','FJI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(72,'Finland','FI','FIN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(73,'France','FR','FRA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(74,'France, Metropolitan','FX','FXX',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(75,'French Guiana','GF','GUF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(76,'French Polynesia','PF','PYF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(77,'French Southern Territories','TF','ATF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(78,'Gabon','GA','GAB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(79,'Gambia','GM','GMB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(80,'Georgia','GE','GEO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(81,'Germany','DE','DEU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(82,'Ghana','GH','GHA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(83,'Gibraltar','GI','GIB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(84,'Greece','GR','GRC',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(85,'Greenland','GL','GRL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(86,'Grenada','GD','GRD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(87,'Guadeloupe','GP','GLP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(88,'Guam','GU','GUM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(89,'Guatemala','GT','GTM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(90,'Guinea','GN','GIN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(91,'Guinea-bissau','GW','GNB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(92,'Guyana','GY','GUY',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(93,'Haiti','HT','HTI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(94,'Heard and Mc Donald Islands','HM','HMD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(95,'Honduras','HN','HND',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(96,'Hong Kong','HK','HKG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(97,'Hungary','HU','HUN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(98,'Iceland','IS','ISL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(99,'India','IN','IND',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(100,'Indonesia','ID','IDN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(101,'Iran (Islamic Republic of)','IR','IRN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(102,'Iraq','IQ','IRQ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(103,'Ireland','IE','IRL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(104,'Israel','IL','ISR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(105,'Italy','IT','ITA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(106,'Jamaica','JM','JAM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(107,'Japan','JP','JPN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(108,'Jordan','JO','JOR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(109,'Kazakhstan','KZ','KAZ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(110,'Kenya','KE','KEN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(111,'Kiribati','KI','KIR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(112,'North Korea','KP','PRK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(113,'Korea, Republic of','KR','KOR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(114,'Kuwait','KW','KWT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(115,'Kyrgyzstan','KG','KGZ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(116,'Lao People\'s Democratic Republic','LA','LAO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(117,'Latvia','LV','LVA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(118,'Lebanon','LB','LBN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(119,'Lesotho','LS','LSO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(120,'Liberia','LR','LBR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(121,'Libyan Arab Jamahiriya','LY','LBY',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(122,'Liechtenstein','LI','LIE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(123,'Lithuania','LT','LTU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(124,'Luxembourg','LU','LUX',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(125,'Macau','MO','MAC',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(126,'FYROM','MK','MKD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(127,'Madagascar','MG','MDG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(128,'Malawi','MW','MWI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(129,'Malaysia','MY','MYS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(130,'Maldives','MV','MDV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(131,'Mali','ML','MLI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(132,'Malta','MT','MLT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(133,'Marshall Islands','MH','MHL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(134,'Martinique','MQ','MTQ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(135,'Mauritania','MR','MRT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(136,'Mauritius','MU','MUS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(137,'Mayotte','YT','MYT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(138,'Mexico','MX','MEX',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(139,'Micronesia, Federated States of','FM','FSM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(140,'Moldova, Republic of','MD','MDA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(141,'Monaco','MC','MCO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(142,'Mongolia','MN','MNG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(143,'Montserrat','MS','MSR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(144,'Morocco','MA','MAR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(145,'Mozambique','MZ','MOZ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(146,'Myanmar','MM','MMR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(147,'Namibia','NA','NAM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(148,'Nauru','NR','NRU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(149,'Nepal','NP','NPL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(150,'Netherlands','NL','NLD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(151,'Netherlands Antilles','AN','ANT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(152,'New Caledonia','NC','NCL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(153,'New Zealand','NZ','NZL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(154,'Nicaragua','NI','NIC',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(155,'Niger','NE','NER',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(156,'Nigeria','NG','NGA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(157,'Niue','NU','NIU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(158,'Norfolk Island','NF','NFK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(159,'Northern Mariana Islands','MP','MNP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(160,'Norway','NO','NOR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(161,'Oman','OM','OMN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(162,'Pakistan','PK','PAK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(163,'Palau','PW','PLW',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(164,'Panama','PA','PAN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(165,'Papua New Guinea','PG','PNG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(166,'Paraguay','PY','PRY',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(167,'Peru','PE','PER',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(168,'Philippines','PH','PHL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(169,'Pitcairn','PN','PCN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(170,'Poland','PL','POL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(171,'Portugal','PT','PRT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(172,'Puerto Rico','PR','PRI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(173,'Qatar','QA','QAT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(174,'Reunion','RE','REU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(175,'Romania','RO','ROM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(176,'Russian Federation','RU','RUS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(177,'Rwanda','RW','RWA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(178,'Saint Kitts and Nevis','KN','KNA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(179,'Saint Lucia','LC','LCA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(180,'Saint Vincent and the Grenadines','VC','VCT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(181,'Samoa','WS','WSM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(182,'San Marino','SM','SMR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(183,'Sao Tome and Principe','ST','STP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(184,'Saudi Arabia','SA','SAU',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(185,'Senegal','SN','SEN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(186,'Seychelles','SC','SYC',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(187,'Sierra Leone','SL','SLE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(188,'Singapore','SG','SGP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(189,'Slovak Republic','SK','SVK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(190,'Slovenia','SI','SVN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(191,'Solomon Islands','SB','SLB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(192,'Somalia','SO','SOM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(193,'South Africa','ZA','ZAF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(194,'South Georgia &amp; South Sandwich Islands','GS','SGS',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(195,'Spain','ES','ESP',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(196,'Sri Lanka','LK','LKA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(197,'St. Helena','SH','SHN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(198,'St. Pierre and Miquelon','PM','SPM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(199,'Sudan','SD','SDN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(200,'Suriname','SR','SUR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(201,'Svalbard and Jan Mayen Islands','SJ','SJM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(202,'Swaziland','SZ','SWZ',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(203,'Sweden','SE','SWE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(204,'Switzerland','CH','CHE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(205,'Syrian Arab Republic','SY','SYR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(206,'Taiwan','TW','TWN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(207,'Tajikistan','TJ','TJK',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(208,'Tanzania, United Republic of','TZ','TZA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(209,'Thailand','TH','THA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(210,'Togo','TG','TGO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(211,'Tokelau','TK','TKL',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(212,'Tonga','TO','TON',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(213,'Trinidad and Tobago','TT','TTO',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(214,'Tunisia','TN','TUN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(215,'Turkey','TR','TUR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(216,'Turkmenistan','TM','TKM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(217,'Turks and Caicos Islands','TC','TCA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(218,'Tuvalu','TV','TUV',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(219,'Uganda','UG','UGA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',1),(220,'Ukraine','UA','UKR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(221,'United Arab Emirates','AE','ARE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(222,'United Kingdom','GB','GBR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(223,'United States','US','USA',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(224,'United States Minor Outlying Islands','UM','UMI',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(225,'Uruguay','UY','URY',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(226,'Uzbekistan','UZ','UZB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(227,'Vanuatu','VU','VUT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(228,'Vatican City State (Holy See)','VA','VAT',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(229,'Venezuela','VE','VEN',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(230,'Viet Nam','VN','VNM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(231,'Virgin Islands (British)','VG','VGB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(232,'Virgin Islands (U.S.)','VI','VIR',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(233,'Wallis and Futuna Islands','WF','WLF',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(234,'Western Sahara','EH','ESH',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(235,'Yemen','YE','YEM',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(236,'Yugoslavia','YU','YUG',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(237,'Democratic Republic of Congo','CD','COD',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(238,'Zambia','ZM','ZMB',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(239,'Zimbabwe','ZW','ZWE',NULL,1,999,'2026-01-19 08:07:51','2026-01-19 08:07:51',0);
/*!40000 ALTER TABLE `ti_countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_currencies`
--

DROP TABLE IF EXISTS `ti_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_currencies` (
  `currency_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int NOT NULL,
  `currency_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` decimal(15,8) NOT NULL,
  `symbol_position` tinyint(1) DEFAULT NULL,
  `thousand_sign` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_sign` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_position` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_alpha2` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_alpha3` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_numeric` int DEFAULT NULL,
  `currency_status` int DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_currencies`
--

LOCK TABLES `ti_currencies` WRITE;
/*!40000 ALTER TABLE `ti_currencies` DISABLE KEYS */;
INSERT INTO `ti_currencies` VALUES (1,222,'Pound Sterling','GBP','£',0.00000000,0,',','.','2','GB','GBR',826,1,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(2,73,'Euro','EUR','€',0.00000000,0,',','.','2','FR','FRA',0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(3,223,'US Dollar','USD','$',0.00000000,0,',','.','2','US','USA',840,0,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(4,44,'Yuan Renminbi','CNY','¥',0.00000000,0,',','.','2','CN','CHN',156,0,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(5,13,'Australian Dollar','AUD','$',0.00000000,0,',','.','2','AU','AUS',36,1,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(6,156,'Naira','NGN','₦',0.00000000,0,',','.','2','NG','NGA',566,1,'2026-01-19 08:07:51','2026-01-19 08:07:51',0),(7,219,'Ugandan Shillings','UGX','UGX',0.00000000,0,',','','0',NULL,NULL,NULL,1,'2026-01-19 11:56:10','2026-01-19 11:56:10',1);
/*!40000 ALTER TABLE `ti_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_customer_groups`
--

DROP TABLE IF EXISTS `ti_customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_customer_groups` (
  `customer_group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `approval` tinyint(1) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_customer_groups`
--

LOCK TABLES `ti_customer_groups` WRITE;
/*!40000 ALTER TABLE `ti_customer_groups` DISABLE KEYS */;
INSERT INTO `ti_customer_groups` VALUES (1,'Default group',NULL,0,1,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_customers`
--

DROP TABLE IF EXISTS `ti_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_customers` (
  `customer_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_id` int DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT NULL,
  `customer_group_id` int NOT NULL,
  `ip_address` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `status` tinyint(1) NOT NULL,
  `reset_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_time` datetime DEFAULT NULL,
  `activation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  `invited_at` timestamp NULL DEFAULT NULL,
  `last_location_area` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `ti_customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_customers`
--

LOCK TABLES `ti_customers` WRITE;
/*!40000 ALTER TABLE `ti_customers` DISABLE KEYS */;
INSERT INTO `ti_customers` VALUES (1,'Nuwahereza','Peter','nuwaherezapeter34@gmail.com','$2y$10$ZCxgV7/8xevXKld0jh.U.OdxItBoDY9eHSTzBUT52y1X.vSEg/X9O','+256779081600',NULL,0,1,NULL,'2026-01-19 08:11:31',1,'mA22ZlpcQEEsL63UjnTpgm4at9e59FxaoFqN5myjwf','2026-01-20 14:47:18',NULL,NULL,1,'2026-01-19 11:11:31','2026-01-19 11:11:31','2026-01-19 15:07:18','2026-01-21 09:18:29',NULL,'{\"query\":\"  Kampala Capital City \"}'),(2,'Nuwahereza','Peter','atwines23@gmail.com','$2y$10$4/IoUgtzWaBR/y.iO9G8FO9Re0piBuHTpTP5r6Yq/z7j/58jNAwuG','+256779081600',2,0,1,NULL,'2026-01-20 12:05:04',1,NULL,NULL,NULL,'8wW7p13oT1N2PAWK1Fwe84b8rR5q7tlfL5N0oWWRlx2xpLxKtzsMMHckZk2D',1,'2026-01-20 15:05:04','2026-01-23 19:22:37','2026-01-24 12:45:58','2026-01-24 11:24:07',NULL,'{\"areaId\":1,\"query\":\" Bahai Road Kampala Capital City \"}');
/*!40000 ALTER TABLE `ti_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_dining_areas`
--

DROP TABLE IF EXISTS `ti_dining_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_dining_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor_plan` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dining_areas_location_id` (`id`,`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_dining_areas`
--

LOCK TABLES `ti_dining_areas` WRITE;
/*!40000 ALTER TABLE `ti_dining_areas` DISABLE KEYS */;
INSERT INTO `ti_dining_areas` VALUES (1,1,'Default',NULL,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_dining_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_dining_sections`
--

DROP TABLE IF EXISTS `ti_dining_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_dining_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dining_sections_location_id_index` (`location_id`),
  KEY `idx_dining_sections_enabled` (`id`,`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_dining_sections`
--

LOCK TABLES `ti_dining_sections` WRITE;
/*!40000 ALTER TABLE `ti_dining_sections` DISABLE KEYS */;
INSERT INTO `ti_dining_sections` VALUES (1,1,'Upper Section','For meetings',NULL,7,1,NULL,NULL);
/*!40000 ALTER TABLE `ti_dining_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_dining_tables`
--

DROP TABLE IF EXISTS `ti_dining_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_dining_tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dining_area_id` bigint unsigned NOT NULL,
  `dining_section_id` bigint unsigned DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shape` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_capacity` int NOT NULL DEFAULT '0',
  `max_capacity` int NOT NULL DEFAULT '0',
  `extra_capacity` int NOT NULL DEFAULT '0',
  `is_combo` tinyint(1) NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `nest_left` int DEFAULT NULL,
  `nest_right` int DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `seat_layout` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dining_tables_dining_area_id_index` (`dining_area_id`),
  KEY `dining_tables_dining_section_id_index` (`dining_section_id`),
  KEY `dining_tables_parent_id_index` (`parent_id`),
  KEY `idx_dining_tables_booked_filter` (`parent_id`,`is_enabled`,`min_capacity`,`max_capacity`,`dining_area_id`,`dining_section_id`),
  KEY `idx_dining_tables_capacity` (`min_capacity`,`max_capacity`),
  KEY `idx_dining_tables_priority` (`id`,`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_dining_tables`
--

LOCK TABLES `ti_dining_tables` WRITE;
/*!40000 ALTER TABLE `ti_dining_tables` DISABLE KEYS */;
INSERT INTO `ti_dining_tables` VALUES (1,1,NULL,NULL,'Table 1','rectangle',3,11,0,0,1,1,2,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(2,1,NULL,NULL,'Table 2','rectangle',5,8,0,0,1,3,4,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(3,1,NULL,NULL,'Table 3','rectangle',3,11,0,0,1,5,6,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(4,1,NULL,NULL,'Table 4','rectangle',3,12,0,0,1,7,8,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(5,1,NULL,NULL,'Table 5','rectangle',5,8,0,0,1,9,10,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(6,1,NULL,NULL,'Table 6','rectangle',3,11,0,0,1,11,12,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(7,1,NULL,NULL,'Table 7','rectangle',4,8,0,0,1,13,14,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(8,1,NULL,NULL,'Table 8','rectangle',3,8,0,0,1,15,16,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(9,1,NULL,NULL,'Table 9','rectangle',2,11,0,0,1,17,18,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(10,1,NULL,NULL,'Table 10','rectangle',5,11,0,0,1,19,20,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(11,1,NULL,NULL,'Table 11','rectangle',4,7,0,0,1,21,22,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(12,1,NULL,NULL,'Table 12','rectangle',3,9,0,0,1,23,24,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(13,1,NULL,NULL,'Table 13','rectangle',2,7,0,0,1,25,26,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56'),(14,1,NULL,NULL,'Table 14','rectangle',2,6,0,0,1,27,28,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:56');
/*!40000 ALTER TABLE `ti_dining_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_extension_settings`
--

DROP TABLE IF EXISTS `ti_extension_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_extension_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_extension_settings_item_unique` (`item`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_extension_settings`
--

LOCK TABLES `ti_extension_settings` WRITE;
/*!40000 ALTER TABLE `ti_extension_settings` DISABLE KEYS */;
INSERT INTO `ti_extension_settings` VALUES (1,'igniter_review_settings','{\"ratings\": {\"ratings\": [\"Bad\", \"Worse\", \"Good\", \"Average\", \"Excellent\"]}, \"allow_reviews\": \"1\", \"approve_reviews\": \"1\"}');
/*!40000 ALTER TABLE `ti_extension_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_extensions`
--

DROP TABLE IF EXISTS `ti_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_extensions` (
  `extension_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '1.0.0',
  PRIMARY KEY (`extension_id`),
  UNIQUE KEY `ti_extensions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_extensions`
--

LOCK TABLES `ti_extensions` WRITE;
/*!40000 ALTER TABLE `ti_extensions` DISABLE KEYS */;
INSERT INTO `ti_extensions` VALUES (1,'igniter.ugandapayments','0.1.0'),(2,'igniter.api','v4.1.1'),(3,'igniter.automation','v4.0.7'),(4,'igniter.broadcast','v4.0.8'),(5,'igniter.cart','v4.1.6'),(6,'igniter.coupons','v4.1.1'),(7,'igniter.frontend','v4.0.6'),(8,'igniter.local','v4.1.0'),(9,'igniter.pages','v4.0.10'),(10,'igniter.payregister','v4.0.8'),(11,'igniter.reservation','v4.1.1'),(12,'igniter.socialite','v4.0.9'),(13,'igniter.user','v4.1.0');
/*!40000 ALTER TABLE `ti_extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_failed_jobs`
--

DROP TABLE IF EXISTS `ti_failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_failed_jobs`
--

LOCK TABLES `ti_failed_jobs` WRITE;
/*!40000 ALTER TABLE `ti_failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_api_access_tokens`
--

DROP TABLE IF EXISTS `ti_igniter_api_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_api_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_access_tokens_token_unique` (`token`),
  KEY `api_access_tokens_tokenable` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_api_access_tokens`
--

LOCK TABLES `ti_igniter_api_access_tokens` WRITE;
/*!40000 ALTER TABLE `ti_igniter_api_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_api_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_api_resources`
--

DROP TABLE IF EXISTS `ti_igniter_api_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_api_resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_api_resources`
--

LOCK TABLES `ti_igniter_api_resources` WRITE;
/*!40000 ALTER TABLE `ti_igniter_api_resources` DISABLE KEYS */;
INSERT INTO `ti_igniter_api_resources` VALUES (1,'Categories','categories','An API resource for categories','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"all\",\"show\":\"all\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(2,'Currencies','currencies','An API resource for currencies','{\"actions\":[\"index\"],\"authorization\":{\"index\":\"all\"}}',0),(3,'Customers','customers','An API resource for customers','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"users\",\"destroy\":\"admin\"}}',0),(4,'Locations','locations','An API resource for locations','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"all\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(5,'Menus','menus','An API resource for menus','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"all\",\"show\":\"all\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(6,'MenuOptions','menu_options','An API resource for Menu options','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(7,'MenuItemOptions','menu_item_options','An API resource for Menu item options','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(8,'Orders','orders','An API resource for orders','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"users\",\"show\":\"users\",\"store\":\"users\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(9,'Reservations','reservations','An API resource for reservations','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"users\",\"show\":\"users\",\"store\":\"users\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(10,'Reviews','reviews','An API resource for reviews','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"users\",\"show\":\"users\",\"store\":\"users\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(11,'Tables','tables','An API resource for dining tables','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(12,'Status','status','An API resource for status','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(13,'Staff','users','An API resource for staff','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"admin\",\"show\":\"admin\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0),(14,'Coupons','coupons','An API resource for coupons','{\"actions\":[\"index\",\"show\",\"store\",\"update\",\"destroy\"],\"authorization\":{\"index\":\"all\",\"show\":\"all\",\"store\":\"admin\",\"update\":\"admin\",\"destroy\":\"admin\"}}',0);
/*!40000 ALTER TABLE `ti_igniter_api_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_automation_logs`
--

DROP TABLE IF EXISTS `ti_igniter_automation_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_automation_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `automation_rule_id` bigint unsigned DEFAULT NULL,
  `rule_action_id` bigint unsigned DEFAULT NULL,
  `is_success` tinyint(1) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` text COLLATE utf8mb4_unicode_ci,
  `exception` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_automation_logs`
--

LOCK TABLES `ti_igniter_automation_logs` WRITE;
/*!40000 ALTER TABLE `ti_igniter_automation_logs` DISABLE KEYS */;
INSERT INTO `ti_igniter_automation_logs` VALUES (1,3,NULL,0,'Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com/domains, and change the `from` address to an email using this domain.','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"}},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com\\/domains, and change the `from` address to an email using this domain.\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/resend\\/resend-laravel\\/src\\/Transport\\/ResendTransportFactory.php\",\"line\":59,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/mailer\\/Transport\\/AbstractTransport.php(69): Resend\\\\Laravel\\\\Transport\\\\ResendTransportFactory->doSend()\\n#1 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(584): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\AbstractTransport->send()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(331): Illuminate\\\\Mail\\\\Mailer->sendSymfonyMessage()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(207): Illuminate\\\\Mail\\\\Mailer->send()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Traits\\/Localizable.php(19): Illuminate\\\\Mail\\\\Mailable->Illuminate\\\\Mail\\\\{closure}()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(200): Illuminate\\\\Mail\\\\Mailable->withLocale()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(353): Illuminate\\\\Mail\\\\Mailable->send()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(300): Illuminate\\\\Mail\\\\Mailer->sendMailable()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/MailManager.php(621): Illuminate\\\\Mail\\\\Mailer->send()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Mail\\\\MailManager->__call()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/System\\/Helpers\\/MailHelper.php(14): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Igniter\\\\System\\\\Helpers\\\\MailHelper->sendTemplate()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(91): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#13 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#15 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(35): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#58 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#59 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#60 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#61 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#62 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#63 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#64 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#65 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#66 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#67 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#68 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#69 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#70 {main}\"}','2026-01-23 11:12:31','2026-01-23 11:12:31'),(2,4,NULL,0,'SendMailTemplate: Missing a valid staff email address','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"assignee\":null},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"SendMailTemplate: Missing a valid staff email address\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php\",\"line\":179,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(82): Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->getRecipientAddress()\\n#1 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#13 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#15 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(35): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#58 {main}\"}','2026-01-23 11:12:31','2026-01-23 11:12:31'),(3,6,NULL,0,'Email \"nuwaherezapeter34@gmail.com, nyanjajoseph9@gmail.com\" does not comply with addr-spec of RFC 2822.','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"assignee\":null},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"Email \\\"nuwaherezapeter34@gmail.com, nyanjajoseph9@gmail.com\\\" does not comply with addr-spec of RFC 2822.\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/mime\\/Address.php\",\"line\":54,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Message.php(245): Symfony\\\\Component\\\\Mime\\\\Address->__construct()\\n#1 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Message.php(109): Illuminate\\\\Mail\\\\Message->addAddresses()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(454): Illuminate\\\\Mail\\\\Message->to()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(209): Illuminate\\\\Mail\\\\Mailable->buildRecipients()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(315): Illuminate\\\\Mail\\\\Mailable->Illuminate\\\\Mail\\\\{closure}()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(207): Illuminate\\\\Mail\\\\Mailer->send()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Traits\\/Localizable.php(19): Illuminate\\\\Mail\\\\Mailable->Illuminate\\\\Mail\\\\{closure}()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(200): Illuminate\\\\Mail\\\\Mailable->withLocale()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(353): Illuminate\\\\Mail\\\\Mailable->send()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(300): Illuminate\\\\Mail\\\\Mailer->sendMailable()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/MailManager.php(621): Illuminate\\\\Mail\\\\Mailer->send()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Mail\\\\MailManager->__call()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/System\\/Helpers\\/MailHelper.php(14): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#13 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Igniter\\\\System\\\\Helpers\\\\MailHelper->sendTemplate()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(91): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#15 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(35): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#58 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#59 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#60 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#61 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#62 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#63 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#64 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#65 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#66 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#67 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#68 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#69 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#70 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#71 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#72 {main}\"}','2026-01-23 11:12:31','2026-01-23 11:12:31'),(4,3,NULL,0,'Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com/domains, and change the `from` address to an email using this domain.','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"}},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com\\/domains, and change the `from` address to an email using this domain.\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/resend\\/resend-laravel\\/src\\/Transport\\/ResendTransportFactory.php\",\"line\":59,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/mailer\\/Transport\\/AbstractTransport.php(69): Resend\\\\Laravel\\\\Transport\\\\ResendTransportFactory->doSend()\\n#1 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(584): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\AbstractTransport->send()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(331): Illuminate\\\\Mail\\\\Mailer->sendSymfonyMessage()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(207): Illuminate\\\\Mail\\\\Mailer->send()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Traits\\/Localizable.php(19): Illuminate\\\\Mail\\\\Mailable->Illuminate\\\\Mail\\\\{closure}()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(200): Illuminate\\\\Mail\\\\Mailable->withLocale()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(353): Illuminate\\\\Mail\\\\Mailable->send()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(300): Illuminate\\\\Mail\\\\Mailer->sendMailable()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/MailManager.php(621): Illuminate\\\\Mail\\\\Mailer->send()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Mail\\\\MailManager->__call()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/System\\/Helpers\\/MailHelper.php(14): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Igniter\\\\System\\\\Helpers\\\\MailHelper->sendTemplate()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(91): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#13 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#15 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(12): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#58 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#59 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#60 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#61 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#62 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#63 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#64 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#65 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#66 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#67 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#68 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#69 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#70 {main}\"}','2026-01-23 11:12:54','2026-01-23 11:12:54'),(5,4,NULL,0,'SendMailTemplate: Missing a valid staff email address','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"assignee\":null},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"SendMailTemplate: Missing a valid staff email address\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php\",\"line\":179,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(82): Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->getRecipientAddress()\\n#1 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#13 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#15 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(12): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#58 {main}\"}','2026-01-23 11:12:54','2026-01-23 11:12:54'),(6,3,NULL,0,'Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com/domains, and change the `from` address to an email using this domain.','{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"Delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"03:12 pm\",\"order_date\":\"23 Jan 2026\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"23 Jan 2026\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":null,\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"},\"order\":{\"order_id\":3,\"customer_id\":null,\"first_name\":\"Test\",\"last_name\":\"Customer\",\"email\":\"nuwaherezapeter34@gmail.com\",\"telephone\":\"+256700123456\",\"location_id\":1,\"address_id\":0,\"total_items\":2,\"comment\":\"This is a test order to verify email notifications\",\"payment\":\"cod\",\"order_type\":\"delivery\",\"created_at\":\"2026-01-23T11:12:26.000000Z\",\"updated_at\":\"2026-01-23T11:12:26.000000Z\",\"order_time\":\"15:12:00\",\"order_date\":\"2026-01-22T21:00:00.000000Z\",\"order_total\":25000,\"status_id\":1,\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Symfony\",\"assignee_id\":null,\"assignee_group_id\":null,\"invoice_prefix\":\"INV-2026-00\",\"invoice_date\":\"2026-01-23T11:12:26.000000Z\",\"hash\":\"858b13e6627a314794c07b45f6686d71\",\"processed\":true,\"status_updated_at\":null,\"assignee_updated_at\":null,\"order_time_is_asap\":false,\"delivery_comment\":null,\"customer_name\":\"Test Customer\",\"order_type_name\":\"Delivery\",\"order_date_time\":\"2026-01-23T12:12:00.000000Z\",\"formatted_address\":null,\"status_name\":\"Received\",\"location\":{\"location_id\":1,\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"description\":\"<p>Come or order our tasty and delicious dishes.<\\/p>\",\"location_address_1\":\"Bukoto\",\"location_address_2\":\"kisaasi\",\"location_city\":\"kampala\",\"location_state\":null,\"location_postcode\":\"00256\",\"location_country_id\":219,\"location_telephone\":\"0779081600\",\"location_lat\":0.3556,\"location_lng\":32.592,\"location_radius\":null,\"location_status\":true,\"permalink_slug\":\"default\",\"is_default\":false,\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-21T09:49:33.000000Z\",\"is_auto_lat_lng\":0},\"address\":null,\"status\":{\"status_id\":1,\"status_name\":\"Received\",\"status_comment\":\"Your order has been received.\",\"notify_customer\":true,\"status_for\":\"order\",\"status_color\":\"#686663\",\"created_at\":\"2026-01-19T08:07:51.000000Z\",\"updated_at\":\"2026-01-19T08:07:51.000000Z\"}},\"order_number\":3,\"order_comment\":\"This is a test order to verify email notifications\",\"order_added\":\"23 January 2026 14:12\",\"invoice_id\":\"INV-2026-003\",\"invoice_number\":\"INV-2026-003\",\"order_payment\":\"Cash On Delivery\",\"order_menus\":[],\"order_totals\":[],\"order_address\":\"This is a pick-up order\",\"location_logo\":{\"id\":21,\"disk\":\"public\",\"name\":\"6971fca8479f5151606851.svg\",\"file_name\":\"tastyigniter-logo-only.svg\",\"mime_type\":\"image\\/svg+xml\",\"size\":2572,\"tag\":\"thumb\",\"custom_properties\":[],\"priority\":9,\"created_at\":\"2026-01-22T10:32:08.000000Z\",\"updated_at\":\"2026-01-22T10:32:08.000000Z\",\"path\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/1fc\\/a84\\/6971fca8479f5151606851.svg\",\"extension\":\"svg\"},\"location_name\":\"UgaEats\",\"location_email\":\"nuwaherezapeter34@gmail.com\",\"location_telephone\":\"0779081600\",\"location_address\":\"Bukoto<br \\/>kisaasi<br \\/>kampala 00256<br \\/>Uganda\",\"status_comment\":null,\"order_view_url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/order\\/858b13e6627a314794c07b45f6686d71\",\"isAdmin\":0,\"isConsole\":1,\"appLocale\":\"en\"}','{\"message\":\"Request to the Resend API failed. Reason: You can only send testing emails to your own email address (nuwaherezapeter34@gmail.com). To send emails to other recipients, please verify a domain at resend.com\\/domains, and change the `from` address to an email using this domain.\",\"code\":0,\"file\":\"\\/home\\/petercodes\\/TastyIgniter\\/vendor\\/resend\\/resend-laravel\\/src\\/Transport\\/ResendTransportFactory.php\",\"line\":59,\"trace\":\"#0 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/mailer\\/Transport\\/AbstractTransport.php(69): Resend\\\\Laravel\\\\Transport\\\\ResendTransportFactory->doSend()\\n#1 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(584): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\AbstractTransport->send()\\n#2 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(331): Illuminate\\\\Mail\\\\Mailer->sendSymfonyMessage()\\n#3 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(207): Illuminate\\\\Mail\\\\Mailer->send()\\n#4 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Traits\\/Localizable.php(19): Illuminate\\\\Mail\\\\Mailable->Illuminate\\\\Mail\\\\{closure}()\\n#5 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailable.php(200): Illuminate\\\\Mail\\\\Mailable->withLocale()\\n#6 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(353): Illuminate\\\\Mail\\\\Mailable->send()\\n#7 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/Mailer.php(300): Illuminate\\\\Mail\\\\Mailer->sendMailable()\\n#8 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Mail\\/MailManager.php(621): Illuminate\\\\Mail\\\\Mailer->send()\\n#9 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Mail\\\\MailManager->__call()\\n#10 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/System\\/Helpers\\/MailHelper.php(14): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#11 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Igniter\\\\System\\\\Helpers\\\\MailHelper->sendTemplate()\\n#12 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/AutomationRules\\/Actions\\/SendMailTemplate.php(91): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#13 [internal function]: Igniter\\\\Automation\\\\AutomationRules\\\\Actions\\\\SendMailTemplate->triggerAction()\\n#14 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Traits\\/ExtendableTrait.php(368): call_user_func_array()\\n#15 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/core\\/src\\/Flame\\/Database\\/Model.php(345): Igniter\\\\Flame\\\\Database\\\\Model->extendableCall()\\n#16 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(110): Igniter\\\\Flame\\\\Database\\\\Model->__call()\\n#17 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Models\\\\AutomationRule->Igniter\\\\Automation\\\\Models\\\\{closure}()\\n#18 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Models\\/AutomationRule.php(109): Illuminate\\\\Support\\\\Collection->each()\\n#19 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(90): Igniter\\\\Automation\\\\Models\\\\AutomationRule->triggerRule()\\n#20 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Collections\\/Traits\\/EnumeratesValues.php(271): Igniter\\\\Automation\\\\Classes\\\\EventManager->Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#21 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(88): Illuminate\\\\Support\\\\Collection->each()\\n#22 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Jobs\\/EventParams.php(34): Igniter\\\\Automation\\\\Classes\\\\EventManager->fireEvent()\\n#23 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Igniter\\\\Automation\\\\Jobs\\\\EventParams->handle()\\n#24 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#25 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#26 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#27 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#28 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(129): Illuminate\\\\Container\\\\Container->call()\\n#29 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#30 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#31 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(133): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#32 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(134): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#33 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(180): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#34 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(137): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#35 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(127): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#36 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(68): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#37 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(102): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#38 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(131): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#39 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(107): Illuminate\\\\Queue\\\\SyncQueue->executeJob()\\n#40 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Database\\/DatabaseTransactionsManager.php(211): Illuminate\\\\Queue\\\\SyncQueue->Illuminate\\\\Queue\\\\{closure}()\\n#41 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/SyncQueue.php(106): Illuminate\\\\Database\\\\DatabaseTransactionsManager->addCallback()\\n#42 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(246): Illuminate\\\\Queue\\\\SyncQueue->push()\\n#43 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(230): Illuminate\\\\Bus\\\\Dispatcher->pushCommandToQueue()\\n#44 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(80): Illuminate\\\\Bus\\\\Dispatcher->dispatchToQueue()\\n#45 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Bus\\/PendingDispatch.php(252): Illuminate\\\\Bus\\\\Dispatcher->dispatch()\\n#46 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(81): Illuminate\\\\Foundation\\\\Bus\\\\PendingDispatch->__destruct()\\n#47 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/tastyigniter\\/ti-ext-automation\\/src\\/Classes\\/EventManager.php(54): Igniter\\\\Automation\\\\Classes\\\\EventManager->queueEvent()\\n#48 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(488): Igniter\\\\Automation\\\\Classes\\\\EventManager::Igniter\\\\Automation\\\\Classes\\\\{closure}()\\n#49 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(315): Illuminate\\\\Events\\\\Dispatcher->Illuminate\\\\Events\\\\{closure}()\\n#50 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/Dispatcher.php(295): Illuminate\\\\Events\\\\Dispatcher->invokeListeners()\\n#51 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Support\\/Facades\\/Facade.php(363): Illuminate\\\\Events\\\\Dispatcher->dispatch()\\n#52 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41) : eval()\'d code(6): Illuminate\\\\Support\\\\Facades\\\\Facade::__callStatic()\\n#53 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(41): eval()\\n#54 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/ExecutionClosure.php(90): Psy\\\\{closure}()\\n#55 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/psy\\/psysh\\/src\\/Shell.php(1575): Psy\\\\ExecutionClosure->execute()\\n#56 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/tinker\\/src\\/Console\\/TinkerCommand.php(76): Psy\\\\Shell->execute()\\n#57 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Laravel\\\\Tinker\\\\Console\\\\TinkerCommand->handle()\\n#58 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(43): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#59 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(96): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#60 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(35): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#61 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(799): Illuminate\\\\Container\\\\BoundMethod::call()\\n#62 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(211): Illuminate\\\\Container\\\\Container->call()\\n#63 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Command\\/Command.php(341): Illuminate\\\\Console\\\\Command->execute()\\n#64 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(180): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#65 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(1102): Illuminate\\\\Console\\\\Command->run()\\n#66 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(356): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#67 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/symfony\\/console\\/Application.php(195): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#68 \\/home\\/petercodes\\/TastyIgniter\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(198): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#69 \\/home\\/petercodes\\/TastyIgniter\\/artisan(35): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#70 {main}\"}','2026-01-23 11:13:21','2026-01-23 11:13:21');
/*!40000 ALTER TABLE `ti_igniter_automation_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_automation_rule_actions`
--

DROP TABLE IF EXISTS `ti_igniter_automation_rule_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_automation_rule_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `automation_rule_id` bigint unsigned DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ti_igniter_actions_automation_rule_id_foreign` (`automation_rule_id`),
  CONSTRAINT `ti_igniter_actions_automation_rule_id_foreign` FOREIGN KEY (`automation_rule_id`) REFERENCES `ti_igniter_automation_rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_automation_rule_actions`
--

LOCK TABLES `ti_igniter_automation_rule_actions` WRITE;
/*!40000 ALTER TABLE `ti_igniter_automation_rule_actions` DISABLE KEYS */;
INSERT INTO `ti_igniter_automation_rule_actions` VALUES (1,1,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.local::mail.review_chase\",\"send_to\":\"customer\"}','2026-01-19 12:05:35','2026-01-19 12:05:35'),(2,2,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.reservation::mail.reservation_reminder\",\"send_to\":\"customer\"}','2026-01-19 12:05:35','2026-01-19 12:05:35'),(3,3,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.cart::mail.order\",\"send_to\":\"customer\"}','2026-01-23 11:08:01','2026-01-23 11:08:01'),(4,4,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.cart::mail.order_alert\",\"send_to\":\"staff\"}','2026-01-23 11:08:01','2026-01-23 11:10:48'),(5,5,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.cart::mail.order_update\",\"send_to\":\"customer\"}','2026-01-23 11:08:01','2026-01-23 11:08:01'),(6,6,'Igniter\\Automation\\AutomationRules\\Actions\\SendMailTemplate','{\"template\":\"igniter.cart::mail.order_alert\",\"send_to\":\"custom\",\"custom\":\"nuwaherezapeter34@gmail.com\"}','2026-01-23 11:11:20','2026-01-23 11:11:20');
/*!40000 ALTER TABLE `ti_igniter_automation_rule_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_automation_rule_conditions`
--

DROP TABLE IF EXISTS `ti_igniter_automation_rule_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_automation_rule_conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `automation_rule_id` bigint unsigned DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ti_igniter_conditions_automation_rule_id_foreign` (`automation_rule_id`),
  CONSTRAINT `ti_igniter_conditions_automation_rule_id_foreign` FOREIGN KEY (`automation_rule_id`) REFERENCES `ti_igniter_automation_rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_automation_rule_conditions`
--

LOCK TABLES `ti_igniter_automation_rule_conditions` WRITE;
/*!40000 ALTER TABLE `ti_igniter_automation_rule_conditions` DISABLE KEYS */;
INSERT INTO `ti_igniter_automation_rule_conditions` VALUES (1,1,'Igniter\\Local\\AutomationRules\\Conditions\\ReviewCount','[{\"attribute\":\"review_count\",\"value\":\"0\",\"operator\":\"is\"}]','2026-01-19 12:05:35','2026-01-19 12:05:35'),(2,1,'Igniter\\Cart\\AutomationRules\\Conditions\\OrderAttribute','[{\"attribute\":\"hours_since\",\"value\":\"24\",\"operator\":\"is\"}]','2026-01-19 12:05:35','2026-01-19 12:05:35'),(3,2,'Igniter\\Reservation\\AutomationRules\\Conditions\\ReservationAttribute','[{\"attribute\":\"days_until\",\"operator\":\"is\",\"value\":3},{\"attribute\":\"status_id\",\"operator\":\"is\",\"value\":\"6\"},{\"attribute\":\"hours_until\",\"operator\":\"is\",\"value\":15}]','2026-01-19 12:05:35','2026-01-19 12:05:35'),(4,2,'Igniter\\Reservation\\AutomationRules\\Conditions\\ReservationAttribute','','2026-01-19 12:06:16','2026-01-19 12:06:16');
/*!40000 ALTER TABLE `ti_igniter_automation_rule_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_automation_rules`
--

DROP TABLE IF EXISTS `ti_igniter_automation_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_automation_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_class` text COLLATE utf8mb4_unicode_ci,
  `config_data` text COLLATE utf8mb4_unicode_ci,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_automation_rules`
--

LOCK TABLES `ti_igniter_automation_rules` WRITE;
/*!40000 ALTER TABLE `ti_igniter_automation_rules` DISABLE KEYS */;
INSERT INTO `ti_igniter_automation_rules` VALUES (1,'Send a message to leave a review after 24 hours','chase_review_after_one_day','','Igniter\\Automation\\AutomationRules\\Events\\OrderSchedule',NULL,0,0,'2026-01-19 12:05:35','2026-01-19 12:05:35'),(2,'Send a reminder 3 days before the confirmed reservation date','remind_confirmed_reservation_3_days_before_date','','Igniter\\Automation\\AutomationRules\\Events\\ReservationSchedule',NULL,0,1,'2026-01-19 12:05:35','2026-01-19 12:06:33'),(3,'Send order confirmation to customer','order_confirmation_to_customer','Send email confirmation to customer when order is placed','Igniter\\Cart\\AutomationRules\\Events\\OrderPlaced',NULL,0,1,'2026-01-23 11:08:01','2026-01-23 11:08:01'),(4,'Send new order alert to restaurant','order_alert_to_location','Send email alert to restaurant when new order is placed','Igniter\\Cart\\AutomationRules\\Events\\OrderPlaced',NULL,0,1,'2026-01-23 11:08:01','2026-01-23 11:08:01'),(5,'Send order status update to customer','order_status_update_to_customer','Send email to customer when order status changes','Igniter\\Cart\\AutomationRules\\Events\\NewOrderStatus',NULL,0,1,'2026-01-23 11:08:01','2026-01-23 11:08:01'),(6,'Send new order alert to all staff emails','order_alert_to_custom_staff','Send email alert to specific staff emails when new order is placed','Igniter\\Cart\\AutomationRules\\Events\\OrderPlaced',NULL,0,1,'2026-01-23 11:11:20','2026-01-23 11:11:20');
/*!40000 ALTER TABLE `ti_igniter_automation_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_cart_cart`
--

DROP TABLE IF EXISTS `ti_igniter_cart_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_cart_cart` (
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`identifier`,`instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_cart_cart`
--

LOCK TABLES `ti_igniter_cart_cart` WRITE;
/*!40000 ALTER TABLE `ti_igniter_cart_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_cart_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupon_categories`
--

DROP TABLE IF EXISTS `ti_igniter_coupon_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupon_categories` (
  `coupon_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  UNIQUE KEY `coupon_category_unique` (`coupon_id`,`category_id`),
  KEY `coupon_id_index` (`coupon_id`),
  KEY `category_id_index` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupon_categories`
--

LOCK TABLES `ti_igniter_coupon_categories` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupon_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_coupon_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupon_customer_groups`
--

DROP TABLE IF EXISTS `ti_igniter_coupon_customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupon_customer_groups` (
  `coupon_id` bigint unsigned NOT NULL,
  `customer_group_id` bigint unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupon_customer_groups`
--

LOCK TABLES `ti_igniter_coupon_customer_groups` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupon_customer_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_coupon_customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupon_customers`
--

DROP TABLE IF EXISTS `ti_igniter_coupon_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupon_customers` (
  `coupon_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupon_customers`
--

LOCK TABLES `ti_igniter_coupon_customers` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupon_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_coupon_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupon_menus`
--

DROP TABLE IF EXISTS `ti_igniter_coupon_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupon_menus` (
  `coupon_id` int unsigned NOT NULL,
  `menu_id` int unsigned NOT NULL,
  UNIQUE KEY `coupon_menu_unique` (`coupon_id`,`menu_id`),
  KEY `coupon_id_index` (`coupon_id`),
  KEY `menu_id_index` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupon_menus`
--

LOCK TABLES `ti_igniter_coupon_menus` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupon_menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_coupon_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupons`
--

DROP TABLE IF EXISTS `ti_igniter_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupons` (
  `coupon_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(15,4) DEFAULT NULL,
  `min_total` decimal(15,4) DEFAULT NULL,
  `redemptions` int NOT NULL DEFAULT '0',
  `customer_redemptions` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `validity` char(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_date` date DEFAULT NULL,
  `fixed_from_time` time DEFAULT NULL,
  `fixed_to_time` time DEFAULT NULL,
  `period_start_date` date DEFAULT NULL,
  `period_end_date` date DEFAULT NULL,
  `recurring_every` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_from_time` time DEFAULT NULL,
  `recurring_to_time` time DEFAULT NULL,
  `order_restriction` text COLLATE utf8mb4_unicode_ci,
  `apply_coupon_on` enum('whole_cart','menu_items','delivery_fee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'whole_cart',
  `min_menu_quantity` int NOT NULL DEFAULT '0',
  `auto_apply` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`coupon_id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `ti_igniter_coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupons`
--

LOCK TABLES `ti_igniter_coupons` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupons` DISABLE KEYS */;
INSERT INTO `ti_igniter_coupons` VALUES (1,'Half Sundays','2222','F',100.0000,500.0000,0,0,NULL,1,'2026-01-18 21:00:00','forever',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'whole_cart',0,0,'2026-01-18 21:00:00'),(2,'Half Tuesdays','3333','P',30.0000,1000.0000,0,0,NULL,1,'2026-01-18 21:00:00','forever',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'whole_cart',0,0,'2026-01-18 21:00:00'),(3,'Full Mondays','MTo6TuTg','P',50.0000,0.0000,0,1,NULL,1,'2026-01-18 21:00:00','forever',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'whole_cart',0,0,'2026-01-18 21:00:00'),(4,'Full Tuesdays','4444','F',500.0000,5000.0000,0,0,NULL,1,'2026-01-18 21:00:00','forever',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'whole_cart',0,0,'2026-01-18 21:00:00');
/*!40000 ALTER TABLE `ti_igniter_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_coupons_history`
--

DROP TABLE IF EXISTS `ti_igniter_coupons_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_coupons_history` (
  `coupon_history_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `code` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_total` decimal(15,4) DEFAULT NULL,
  `amount` decimal(15,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `status` tinyint(1) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`coupon_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_coupons_history`
--

LOCK TABLES `ti_igniter_coupons_history` WRITE;
/*!40000 ALTER TABLE `ti_igniter_coupons_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_coupons_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_frontend_banners`
--

DROP TABLE IF EXISTS `ti_igniter_frontend_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_frontend_banners` (
  `banner_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `click_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint unsigned DEFAULT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_code` text COLLATE utf8mb4_unicode_ci,
  `custom_code` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_frontend_banners`
--

LOCK TABLES `ti_igniter_frontend_banners` WRITE;
/*!40000 ALTER TABLE `ti_igniter_frontend_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_frontend_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_frontend_sliders`
--

DROP TABLE IF EXISTS `ti_igniter_frontend_sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_frontend_sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_igniter_frontend_sliders_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_frontend_sliders`
--

LOCK TABLES `ti_igniter_frontend_sliders` WRITE;
/*!40000 ALTER TABLE `ti_igniter_frontend_sliders` DISABLE KEYS */;
INSERT INTO `ti_igniter_frontend_sliders` VALUES (1,'Homepage slider','home-slider',NULL,'2026-01-19 08:07:54','2026-01-19 08:07:54');
/*!40000 ALTER TABLE `ti_igniter_frontend_sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_frontend_subscribers`
--

DROP TABLE IF EXISTS `ti_igniter_frontend_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_frontend_subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistics` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_frontend_subscribers`
--

LOCK TABLES `ti_igniter_frontend_subscribers` WRITE;
/*!40000 ALTER TABLE `ti_igniter_frontend_subscribers` DISABLE KEYS */;
INSERT INTO `ti_igniter_frontend_subscribers` VALUES (1,NULL,'nuwaherezapeter34@gmail.com',0,NULL,NULL);
/*!40000 ALTER TABLE `ti_igniter_frontend_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_pages_menu_items`
--

DROP TABLE IF EXISTS `ti_igniter_pages_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_pages_menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci,
  `nest_left` int DEFAULT NULL,
  `nest_right` int DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ti_igniter_pages_menu_items_menu_id_index` (`menu_id`),
  KEY `ti_igniter_pages_menu_items_parent_id_index` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_pages_menu_items`
--

LOCK TABLES `ti_igniter_pages_menu_items` WRITE;
/*!40000 ALTER TABLE `ti_igniter_pages_menu_items` DISABLE KEYS */;
INSERT INTO `ti_igniter_pages_menu_items` VALUES (1,1,NULL,'igniter.orange::default.text_restaurant','',NULL,'header',NULL,NULL,'[]',1,8,1,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(2,1,1,'igniter.orange::default.menu_menu','',NULL,'theme-page',NULL,'local.menus','[]',2,3,2,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(3,1,1,'igniter.orange::default.menu_reservation','',NULL,'theme-page',NULL,'reservation.reservation','[]',4,5,3,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(4,1,1,'igniter.orange::default.menu_locations','',NULL,'theme-page',NULL,'locations','[]',6,7,4,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(5,1,NULL,'igniter.orange::default.text_information','',NULL,'header',NULL,NULL,'[]',9,16,5,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(6,1,5,'igniter.orange::default.menu_contact','',NULL,'theme-page',NULL,'contact','[]',10,11,6,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(7,1,5,'About Us','',NULL,'static-page',NULL,'1','[]',12,13,7,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(8,1,5,'Privacy Policy','',NULL,'static-page',NULL,'2','[]',14,15,8,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(9,2,NULL,'igniter.orange::default.menu_menu','view-menu',NULL,'theme-page',NULL,'local.menus','[]',17,18,9,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(10,2,NULL,'igniter.orange::default.menu_reservation','reservation',NULL,'theme-page',NULL,'reservation.reservation','[]',19,20,10,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(11,2,NULL,'igniter.orange::default.menu_login','login',NULL,'theme-page',NULL,'account.login','[]',21,22,11,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(12,2,NULL,'igniter.orange::default.menu_register','register',NULL,'theme-page',NULL,'account.register','[]',23,24,12,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(13,2,NULL,'igniter.orange::default.menu_my_account','account',NULL,'theme-page',NULL,'account.account','[]',25,36,13,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(14,2,13,'igniter.orange::default.menu_recent_order','recent-orders',NULL,'theme-page',NULL,'account.orders','[]',26,27,14,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(15,2,13,'igniter.orange::default.menu_my_account','',NULL,'theme-page',NULL,'account.account','[]',28,29,15,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(16,2,13,'igniter.orange::default.menu_address','',NULL,'theme-page',NULL,'account.address','[]',30,31,16,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(17,2,13,'igniter.orange::default.menu_recent_reservation','',NULL,'theme-page',NULL,'account.reservations','[]',32,33,17,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(18,2,13,'igniter.orange::default.menu_logout','',NULL,'url','/logout',NULL,'[]',34,35,18,'2026-01-19 08:08:16','2026-01-19 08:08:16'),(19,3,NULL,'Pages','',NULL,'all-static-pages',NULL,'','[]',37,38,19,'2026-01-19 08:08:16','2026-01-19 08:08:16');
/*!40000 ALTER TABLE `ti_igniter_pages_menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_pages_menus`
--

DROP TABLE IF EXISTS `ti_igniter_pages_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_pages_menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `theme_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ti_igniter_pages_menus_theme_code_index` (`theme_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_pages_menus`
--

LOCK TABLES `ti_igniter_pages_menus` WRITE;
/*!40000 ALTER TABLE `ti_igniter_pages_menus` DISABLE KEYS */;
INSERT INTO `ti_igniter_pages_menus` VALUES (1,'igniter-orange','Footer menu','footer-menu','2026-01-19 08:08:16','2026-01-19 08:08:16'),(2,'igniter-orange','Main menu','main-menu','2026-01-19 08:08:16','2026-01-19 08:08:16'),(3,'igniter-orange','Pages menu','pages-menu','2026-01-19 08:08:16','2026-01-19 08:08:16');
/*!40000 ALTER TABLE `ti_igniter_pages_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_reviews`
--

DROP TABLE IF EXISTS `ti_igniter_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_reviews` (
  `review_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `reviewable_id` bigint unsigned DEFAULT NULL,
  `reviewable_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `quality` int NOT NULL,
  `delivery` int NOT NULL,
  `service` int NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL,
  `review_status` tinyint(1) NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `reviews_sale_id_type_index` (`review_id`,`reviewable_type`,`reviewable_id`),
  KEY `idx_igniter_reviews_location_status` (`location_id`,`review_status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_reviews`
--

LOCK TABLES `ti_igniter_reviews` WRITE;
/*!40000 ALTER TABLE `ti_igniter_reviews` DISABLE KEYS */;
INSERT INTO `ti_igniter_reviews` VALUES (1,2,1,'orders','Nuwahereza Peter',1,4,3,5,'good food','2026-01-21 09:55:40',1,'2026-01-21 09:55:40');
/*!40000 ALTER TABLE `ti_igniter_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_igniter_socialite_providers`
--

DROP TABLE IF EXISTS `ti_igniter_socialite_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_igniter_socialite_providers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_token_index` (`provider`,`token`),
  KEY `ti_igniter_socialite_providers_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_igniter_socialite_providers`
--

LOCK TABLES `ti_igniter_socialite_providers` WRITE;
/*!40000 ALTER TABLE `ti_igniter_socialite_providers` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_igniter_socialite_providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_ingredientables`
--

DROP TABLE IF EXISTS `ti_ingredientables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_ingredientables` (
  `ingredient_id` int unsigned NOT NULL,
  `ingredientable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ingredientable_id` bigint unsigned NOT NULL,
  UNIQUE KEY `ingredientable_unique` (`ingredient_id`,`ingredientable_id`,`ingredientable_type`),
  KEY `allergenable_index` (`ingredientable_type`,`ingredientable_id`),
  KEY `ti_allergenables_allergen_id_index` (`ingredient_id`),
  KEY `idx_type_id_ingredient` (`ingredientable_type`,`ingredientable_id`,`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_ingredientables`
--

LOCK TABLES `ti_ingredientables` WRITE;
/*!40000 ALTER TABLE `ti_ingredientables` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_ingredientables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_ingredients`
--

DROP TABLE IF EXISTS `ti_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_ingredients` (
  `ingredient_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_allergen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_ingredients`
--

LOCK TABLES `ti_ingredients` WRITE;
/*!40000 ALTER TABLE `ti_ingredients` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_language_translations`
--

DROP TABLE IF EXISTS `ti_language_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_language_translations` (
  `translation_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '*',
  `group` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `unstable` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `item_unique` (`locale`,`namespace`,`group`,`item`),
  KEY `ti_language_translations_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_language_translations`
--

LOCK TABLES `ti_language_translations` WRITE;
/*!40000 ALTER TABLE `ti_language_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_language_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_languages`
--

DROP TABLE IF EXISTS `ti_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_languages` (
  `language_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idiom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `original_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `version` json DEFAULT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_languages`
--

LOCK TABLES `ti_languages` WRITE;
/*!40000 ALTER TABLE `ti_languages` DISABLE KEYS */;
INSERT INTO `ti_languages` VALUES (1,'en','English',NULL,'english',1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51',1,NULL),(2,'lg_UG','Luganda',NULL,'lg_UG',1,0,NULL,'2026-01-21 09:41:18','2026-01-21 09:41:18',0,NULL);
/*!40000 ALTER TABLE `ti_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_location_areas`
--

DROP TABLE IF EXISTS `ti_location_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_location_areas` (
  `area_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `boundaries` json NOT NULL,
  `conditions` json NOT NULL,
  `color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_location_areas`
--

LOCK TABLES `ti_location_areas` WRITE;
/*!40000 ALTER TABLE `ti_location_areas` DISABLE KEYS */;
INSERT INTO `ti_location_areas` VALUES (1,1,'Kampala','circle','{\"circle\": \"{\\\"lat\\\": 0.3556, \\\"lng\\\": 32.592, \\\"radius\\\": 20000}\", \"polygon\": null, \"distance\": {\"1\": {\"type\": \"equals_or_less\", \"charge\": \"0\", \"distance\": \"5\"}, \"2\": {\"type\": \"equals_or_less\", \"charge\": \"2000\", \"distance\": \"10\"}, \"3\": {\"type\": \"equals_or_less\", \"charge\": \"5000\", \"distance\": \"20\"}, \"4\": {\"type\": \"greater\", \"charge\": \"10000\", \"distance\": \"20\"}}, \"vertices\": null, \"components\": null}','[{\"type\": \"above\", \"total\": \"5000\", \"amount\": \"2000\"}, {\"type\": \"above\", \"total\": \"10000\", \"amount\": \"3000\"}, {\"type\": \"above\", \"total\": \"20000\", \"amount\": \"5000\"}, {\"type\": \"below\", \"total\": \"4000\", \"amount\": \"1000\"}]','#7BC8A4',1,1),(3,2,'Kampala','circle','{\"circle\": \"{\\\"lat\\\": 0.354, \\\"lng\\\": 32.59, \\\"radius\\\": 20000}\", \"polygon\": null, \"distance\": {\"1\": {\"type\": \"equals_or_less\", \"charge\": \"0\", \"distance\": \"5\"}, \"2\": {\"type\": \"equals_or_less\", \"charge\": \"2000\", \"distance\": \"10\"}, \"3\": {\"type\": \"equals_or_less\", \"charge\": \"5000\", \"distance\": \"20\"}, \"4\": {\"type\": \"greater\", \"charge\": \"10000\", \"distance\": \"20\"}}, \"vertices\": null, \"components\": null}','[]','#F16745',0,3),(4,3,'Kampala','circle','{\"circle\": \"{\\\"lat\\\": 0.315, \\\"lng\\\": 32.592, \\\"radius\\\": 20000}\", \"polygon\": null, \"distance\": {\"1\": {\"type\": \"equals_or_less\", \"charge\": \"0\", \"distance\": \"5\"}, \"2\": {\"type\": \"equals_or_less\", \"charge\": \"2000\", \"distance\": \"10\"}, \"3\": {\"type\": \"equals_or_less\", \"charge\": \"5000\", \"distance\": \"20\"}, \"4\": {\"type\": \"greater\", \"charge\": \"10000\", \"distance\": \"20\"}}, \"vertices\": null, \"components\": null}','[]','#4CC3D9',1,4);
/*!40000 ALTER TABLE `ti_location_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_location_options`
--

DROP TABLE IF EXISTS `ti_location_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_location_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_location_options_location_id_item_unique` (`location_id`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_location_options`
--

LOCK TABLES `ti_location_options` WRITE;
/*!40000 ALTER TABLE `ti_location_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_location_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_location_settings`
--

DROP TABLE IF EXISTS `ti_location_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_location_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_location_settings_location_id_item_unique` (`location_id`,`item`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_location_settings`
--

LOCK TABLES `ti_location_settings` WRITE;
/*!40000 ALTER TABLE `ti_location_settings` DISABLE KEYS */;
INSERT INTO `ti_location_settings` VALUES (1,1,'booking','{\"stay_time\": 45, \"is_enabled\": 1, \"limit_guests\": 0, \"time_interval\": 15, \"max_guest_count\": 20, \"min_guest_count\": 2, \"max_advance_time\": 30, \"min_advance_time\": 2, \"auto_allocate_table\": 1, \"cancellation_timeout\": 0}'),(2,1,'delivery','{\"lead_time\": 25, \"is_enabled\": 1, \"add_lead_time\": 0, \"future_orders\": {\"days\": 5, \"min_days\": 1, \"is_enabled\": 1}, \"time_interval\": 15, \"min_order_amount\": \"3000\", \"time_restriction\": \"2\", \"cancellation_timeout\": 5}'),(3,1,'collection','{\"lead_time\": 25, \"is_enabled\": 1, \"add_lead_time\": 1, \"future_orders\": {\"days\": 5, \"min_days\": 1, \"is_enabled\": 1}, \"time_interval\": 15, \"min_order_amount\": \"3000.00\", \"cancellation_timeout\": 5}'),(4,1,'checkout','{\"payments\": [\"cod\"], \"guest_order\": \"-1\"}'),(5,1,'hours','{\"opening\": {\"days\": [\"0\", \"1\", \"2\", \"3\", \"4\", \"5\"], \"open\": \"00:00\", \"type\": \"flexible\", \"close\": \"23:59\", \"flexible\": [{\"day\": \"0\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"1\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"2\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"3\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"4\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"5\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}, {\"day\": \"6\", \"hours\": \"00:00-23:59\", \"status\": \"1\"}], \"timesheet\": \"[{\\\"day\\\":0,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":1,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":2,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":3,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":4,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":5,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true},{\\\"day\\\":6,\\\"hours\\\":[{\\\"open\\\":\\\"00:00\\\",\\\"close\\\":\\\"23:59\\\"}],\\\"status\\\":true}]\"}}');
/*!40000 ALTER TABLE `ti_location_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_locationables`
--

DROP TABLE IF EXISTS `ti_locationables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_locationables` (
  `location_id` int NOT NULL,
  `locationable_id` int NOT NULL,
  `locationable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  KEY `idx_locationables_lookup` (`locationable_type`,`locationable_id`,`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_locationables`
--

LOCK TABLES `ti_locationables` WRITE;
/*!40000 ALTER TABLE `ti_locationables` DISABLE KEYS */;
INSERT INTO `ti_locationables` VALUES (1,1,'tables',NULL),(1,2,'tables',NULL),(1,3,'tables',NULL),(1,4,'tables',NULL),(1,5,'tables',NULL),(1,6,'tables',NULL),(1,7,'tables',NULL),(1,8,'tables',NULL),(1,9,'tables',NULL),(1,10,'tables',NULL),(1,11,'tables',NULL),(1,12,'tables',NULL),(1,13,'tables',NULL),(1,14,'tables',NULL),(1,1,'users',NULL),(1,12,'menus',NULL),(1,11,'menus',NULL),(2,10,'menus',NULL),(1,2,'users',NULL);
/*!40000 ALTER TABLE `ti_locationables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_locations`
--

DROP TABLE IF EXISTS `ti_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_locations` (
  `location_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_email` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location_address_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_country_id` int DEFAULT NULL,
  `location_telephone` text COLLATE utf8mb4_unicode_ci,
  `location_lat` decimal(10,6) DEFAULT NULL,
  `location_lng` decimal(10,6) DEFAULT NULL,
  `location_radius` int DEFAULT NULL,
  `location_status` tinyint(1) DEFAULT NULL,
  `permalink_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_auto_lat_lng` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`),
  KEY `idx_locations_name` (`location_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_locations`
--

LOCK TABLES `ti_locations` WRITE;
/*!40000 ALTER TABLE `ti_locations` DISABLE KEYS */;
INSERT INTO `ti_locations` VALUES (1,'UgaEats','nuwaherezapeter34@gmail.com','<p>Come or order our tasty and delicious dishes.</p>','Bukoto','kisaasi','kampala',NULL,'00256',219,'0779081600',0.355600,32.592000,NULL,1,'default',0,'2026-01-19 08:07:51','2026-01-21 09:49:33',0),(2,'La Cafe','nuwaherezapeter34@gmail.com','<p>At the heart of Entebbe town</p>','Bukoto','entebbe','kampala','western','000256',NULL,'0779081600',0.354000,32.590000,NULL,1,'la-cafe',0,'2026-01-21 11:11:45','2026-01-21 11:11:45',1),(3,'Cafe Javas','nuwaherezapeter34@gmail.com','<p>The home of yummy dishes</p>','Kisaasi',NULL,'kampala',NULL,NULL,NULL,'0779081600',0.315000,32.592000,NULL,1,'cafe-javas',1,'2026-01-23 10:07:40','2026-01-23 10:07:40',1);
/*!40000 ALTER TABLE `ti_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_mail_layouts`
--

DROP TABLE IF EXISTS `ti_mail_layouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_mail_layouts` (
  `layout_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `status` tinyint(1) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout` text COLLATE utf8mb4_unicode_ci,
  `plain_layout` text COLLATE utf8mb4_unicode_ci,
  `layout_css` text COLLATE utf8mb4_unicode_ci,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`),
  UNIQUE KEY `ti_mail_layouts_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_mail_layouts`
--

LOCK TABLES `ti_mail_layouts` WRITE;
/*!40000 ALTER TABLE `ti_mail_layouts` DISABLE KEYS */;
INSERT INTO `ti_mail_layouts` VALUES (1,'Default layout',1,'2026-01-19 08:43:15','2026-01-19 08:43:15',1,'default',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `ti_mail_layouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_mail_partials`
--

DROP TABLE IF EXISTS `ti_mail_partials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_mail_partials` (
  `partial_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `html` text COLLATE utf8mb4_unicode_ci,
  `text` text COLLATE utf8mb4_unicode_ci,
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`partial_id`),
  UNIQUE KEY `ti_mail_partials_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_mail_partials`
--

LOCK TABLES `ti_mail_partials` WRITE;
/*!40000 ALTER TABLE `ti_mail_partials` DISABLE KEYS */;
INSERT INTO `ti_mail_partials` VALUES (1,'Header','header',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(2,'Footer','footer',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(3,'Button','button',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(4,'Panel','panel',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(5,'Table','table',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(6,'Subcopy','subcopy',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15'),(7,'Promotion','promotion',NULL,NULL,0,'2026-01-19 08:43:15','2026-01-19 08:43:15');
/*!40000 ALTER TABLE `ti_mail_partials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_mail_templates`
--

DROP TABLE IF EXISTS `ti_mail_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_mail_templates` (
  `template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint unsigned DEFAULT NULL,
  `code` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_custom` tinyint(1) DEFAULT NULL,
  `plain_body` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `ti_mail_templates_data_template_id_code_unique` (`layout_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_mail_templates`
--

LOCK TABLES `ti_mail_templates` WRITE;
/*!40000 ALTER TABLE `ti_mail_templates` DISABLE KEYS */;
INSERT INTO `ti_mail_templates` VALUES (1,1,'igniter.user::mail.admin_password_reset','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_admin_password_reset',0,NULL),(2,1,'igniter.user::mail.admin_password_reset_request','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_admin_password_reset_request',0,NULL),(3,1,'igniter.user::mail.password_reset','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_password_reset',0,NULL),(4,1,'igniter.user::mail.password_reset_request','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_password_reset_request',0,NULL),(5,1,'igniter.user::mail.registration','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_registration',0,NULL),(6,1,'igniter.user::mail.registration_alert','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_registration_alert',0,NULL),(7,1,'igniter.user::mail.activation','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_activation',0,NULL),(8,1,'igniter.user::mail.invite','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_invite',0,NULL),(9,1,'igniter.user::mail.invite_customer','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.user::default.text_mail_invite_customer',0,NULL),(10,1,'igniter.reservation::mail.reservation','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.reservation::default.text_mail_reservation',0,NULL),(11,1,'igniter.reservation::mail.reservation_alert','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.reservation::default.text_mail_reservation_alert',0,NULL),(12,1,'igniter.reservation::mail.reservation_update','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.reservation::default.text_mail_reservation_update',0,NULL),(13,1,'igniter.reservation::mail.reservation_reminder','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.reservation::default.text_mail_reservation_reminder',0,NULL),(14,1,'igniter.local::mail.review_chase','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.local::default.reviews.text_chase_email',0,NULL),(15,1,'igniter.frontend::mail.contact','','','2026-01-19 08:43:15','2026-01-19 08:43:15','Contact form email to admin',0,NULL),(16,1,'igniter.cart::mail.order','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.cart::default.text_mail_order',0,NULL),(17,1,'igniter.cart::mail.order_alert','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.cart::default.text_mail_order_alert',0,NULL),(18,1,'igniter.cart::mail.order_update','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.cart::default.text_mail_order_update',0,NULL),(19,1,'igniter.cart::mail.low_stock_alert','','','2026-01-19 08:43:15','2026-01-19 08:43:15','lang:igniter.cart::default.text_mail_low_stock_alert',0,NULL);
/*!40000 ALTER TABLE `ti_mail_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_mealtimes`
--

DROP TABLE IF EXISTS `ti_mealtimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_mealtimes` (
  `mealtime_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mealtime_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '23:59:59',
  `mealtime_status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `validity` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `recurring_every` json DEFAULT NULL,
  `recurring_from` time DEFAULT NULL,
  `recurring_to` time DEFAULT NULL,
  PRIMARY KEY (`mealtime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_mealtimes`
--

LOCK TABLES `ti_mealtimes` WRITE;
/*!40000 ALTER TABLE `ti_mealtimes` DISABLE KEYS */;
INSERT INTO `ti_mealtimes` VALUES (1,'Breakfast','07:00:00','10:00:00',1,'2026-01-19 08:07:51','2026-01-19 08:07:51','daily',NULL,NULL,NULL,NULL,NULL),(2,'Lunch','12:00:00','14:30:00',1,'2026-01-19 08:07:51','2026-01-19 08:07:51','daily',NULL,NULL,NULL,NULL,NULL),(3,'Dinner','18:00:00','20:00:00',1,'2026-01-19 08:07:51','2026-01-19 08:07:51','daily',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ti_mealtimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_media_attachments`
--

DROP TABLE IF EXISTS `ti_media_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_media_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int unsigned NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_id` bigint unsigned DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `custom_properties` text COLLATE utf8mb4_unicode_ci,
  `priority` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_attachments_attachment` (`attachment_type`,`attachment_id`),
  KEY `ti_media_attachments_tag_index` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_media_attachments`
--

LOCK TABLES `ti_media_attachments` WRITE;
/*!40000 ALTER TABLE `ti_media_attachments` DISABLE KEYS */;
INSERT INTO `ti_media_attachments` VALUES (7,'public','6970b1e5ce3d9184205090.jpeg','kikomando.jpeg','image/jpeg',7373,'gallery','locations',1,1,'[]',6,'2026-01-21 11:00:53','2026-01-21 11:00:53'),(8,'public','6970b1e5d1b97697842805.png','Pasted image.png','image/png',144856,'gallery','locations',1,1,'[]',7,'2026-01-21 11:00:53','2026-01-21 11:00:53'),(9,'public','6970b1e5d315f122178760.jpg','Uganda-rolex-street-food.jpg','image/jpeg',79216,'gallery','locations',1,1,'[]',8,'2026-01-21 11:00:53','2026-01-21 11:00:53'),(21,'public','6971fca8479f5151606851.svg','tastyigniter-logo-only.svg','image/svg+xml',2572,'thumb','locations',1,1,'[]',9,'2026-01-22 10:32:08','2026-01-22 10:32:08'),(23,'public','6972026b4f72a530142452.png','eshabwe.png','image/png',527038,'thumb','menus',12,1,'[]',10,'2026-01-22 10:56:43','2026-01-22 10:56:43'),(24,'public','6972029e533bc061895252.jpeg','kikomando.jpeg','image/jpeg',7373,'thumb','menus',9,1,'[]',11,'2026-01-22 10:57:34','2026-01-22 10:57:34'),(25,'public','69720908b8ee8053171059.png','muchomo.png','image/png',89146,'thumb','menus',8,1,'[]',12,'2026-01-22 11:24:56','2026-01-22 11:24:56'),(26,'public','697209fcd612f889431509.png','samosa.png','image/png',103848,'thumb','menus',10,1,'[]',13,'2026-01-22 11:29:00','2026-01-22 11:29:00'),(27,'public','69720a1cea3b8942983014.png','gnuts.png','image/png',551621,'thumb','menus',7,1,'[]',14,'2026-01-22 11:29:32','2026-01-22 11:29:32'),(28,'public','69720a3c0b3df684391240.png','nile perch.png','image/png',122492,'thumb','menus',6,1,'[]',15,'2026-01-22 11:30:04','2026-01-22 11:30:04'),(29,'public','69720a5371ec0879867792.png','katogo.png','image/png',618296,'thumb','menus',5,1,'[]',16,'2026-01-22 11:30:27','2026-01-22 11:30:27'),(30,'public','69720a76141af961302043.jpg','Uganda-rolex-street-food.jpg','image/jpeg',79216,'thumb','menus',4,1,'[]',17,'2026-01-22 11:31:02','2026-01-22 11:31:02'),(31,'public','69720aa57a73e750903362.png','posho beans.png','image/png',512933,'thumb','menus',3,1,'[]',18,'2026-01-22 11:31:49','2026-01-22 11:31:49'),(33,'public','69720b21c25c8789033261.png','luwobo.png','image/png',313168,'thumb','menus',2,1,'[]',19,'2026-01-22 11:33:53','2026-01-22 11:33:53'),(34,'public','69720ded9946f995245032.png','bamboo.png','image/png',619412,'thumb','menus',11,1,'[]',20,'2026-01-22 11:45:49','2026-01-22 11:45:49'),(35,'public','69720e1f342ac855259412.png','steemed matoke.png','image/png',512119,'thumb','menus',1,1,'[]',21,'2026-01-22 11:46:39','2026-01-22 11:46:39'),(38,'public','6972572d4f7f7400264208.jpg','Uganda-rolex-street-food.jpg','image/jpeg',79216,'gallery','locations',2,1,'[]',24,'2026-01-22 16:58:21','2026-01-22 16:58:21'),(39,'public','697257345c638166837630.png','luwobo.png','image/png',313168,'gallery','locations',2,1,'[]',25,'2026-01-22 16:58:28','2026-01-22 16:58:28'),(40,'public','697342e371edd414355388.svg','tastyigniter-logo-only.svg','image/svg+xml',2572,'thumb','locations',2,1,'[]',26,'2026-01-23 09:44:03','2026-01-23 09:44:03'),(41,'public','69738d89e2661761308729.png','slider.png','image/png',975258,'images','sliders',1,1,'[]',27,'2026-01-23 15:02:33','2026-01-23 15:02:33'),(42,'public','69738da7506fb894277012.png','slider2.png','image/png',1071764,'images','sliders',1,1,'[]',28,'2026-01-23 15:03:03','2026-01-23 15:03:03'),(43,'public','69738dbca873a348413948.png','slider3.png','image/png',1102740,'images','sliders',1,1,'[]',29,'2026-01-23 15:03:24','2026-01-23 15:03:24'),(44,'public','69738dd0a81b8900463704.png','slider4.png','image/png',1059045,'images','sliders',1,1,'[]',30,'2026-01-23 15:03:44','2026-01-23 15:03:44'),(46,'public','69738e0ff31a4328717320.png','slider5.png','image/png',968184,'images','sliders',1,1,'[]',31,'2026-01-23 15:04:47','2026-01-23 15:04:47'),(47,'public','697494f3eb73b626623980.svg','tastyigniter-logo-only.svg','image/svg+xml',2572,'thumb','locations',3,1,'[]',32,'2026-01-24 09:46:27','2026-01-24 09:46:27');
/*!40000 ALTER TABLE `ti_media_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_categories`
--

DROP TABLE IF EXISTS `ti_menu_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_categories` (
  `menu_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  UNIQUE KEY `ti_menu_categories_menu_id_category_id_unique` (`menu_id`,`category_id`),
  KEY `ti_menu_categories_menu_id_index` (`menu_id`),
  KEY `ti_menu_categories_category_id_index` (`category_id`),
  KEY `idx_menu_categories_category_menu` (`category_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_categories`
--

LOCK TABLES `ti_menu_categories` WRITE;
/*!40000 ALTER TABLE `ti_menu_categories` DISABLE KEYS */;
INSERT INTO `ti_menu_categories` VALUES (1,5),(9,5),(10,5),(11,5),(12,5);
/*!40000 ALTER TABLE `ti_menu_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_item_option_values`
--

DROP TABLE IF EXISTS `ti_menu_item_option_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_item_option_values` (
  `menu_option_value_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_option_id` int NOT NULL,
  `option_value_id` int NOT NULL,
  `override_price` decimal(15,4) DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `is_default` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menu_option_value_id`),
  KEY `idx_menu_item_option_values_menu_option` (`menu_option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_item_option_values`
--

LOCK TABLES `ti_menu_item_option_values` WRITE;
/*!40000 ALTER TABLE `ti_menu_item_option_values` DISABLE KEYS */;
INSERT INTO `ti_menu_item_option_values` VALUES (1,1,9,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,1,10,0.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,2,7,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,2,8,5.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,3,4,4.9500,4,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,3,5,4.9500,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,3,6,6.9500,3,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(8,4,7,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(9,4,8,5.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(10,5,4,4.9500,4,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(11,5,5,4.9500,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(12,5,6,6.9500,3,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(13,6,7,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(14,6,8,5.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(15,7,7,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(16,7,8,5.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(20,9,9,0.0000,1,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(21,9,10,0.0000,2,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_menu_item_option_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_item_options`
--

DROP TABLE IF EXISTS `ti_menu_item_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_item_options` (
  `menu_option_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `min_selected` int NOT NULL DEFAULT '0',
  `max_selected` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menu_option_id`),
  KEY `idx_menu_item_options_menu` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_item_options`
--

LOCK TABLES `ti_menu_item_options` WRITE;
/*!40000 ALTER TABLE `ti_menu_item_options` DISABLE KEYS */;
INSERT INTO `ti_menu_item_options` VALUES (1,4,1,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,3,2,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,2,3,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,3,3,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,2,4,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,3,4,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,3,5,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(8,2,10,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(9,4,10,0,0,0,0,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_menu_item_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_mealtimes`
--

DROP TABLE IF EXISTS `ti_menu_mealtimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_mealtimes` (
  `menu_id` int unsigned NOT NULL,
  `mealtime_id` int unsigned NOT NULL,
  UNIQUE KEY `ti_menu_mealtimes_menu_id_mealtime_id_unique` (`menu_id`,`mealtime_id`),
  KEY `ti_menu_mealtimes_menu_id_index` (`menu_id`),
  KEY `ti_menu_mealtimes_mealtime_id_index` (`mealtime_id`),
  KEY `idx_menu_mealtimes_menu_mealtime` (`menu_id`,`mealtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_mealtimes`
--

LOCK TABLES `ti_menu_mealtimes` WRITE;
/*!40000 ALTER TABLE `ti_menu_mealtimes` DISABLE KEYS */;
INSERT INTO `ti_menu_mealtimes` VALUES (10,1),(10,3),(11,1),(11,2),(11,3);
/*!40000 ALTER TABLE `ti_menu_mealtimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_option_values`
--

DROP TABLE IF EXISTS `ti_menu_option_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_option_values` (
  `option_value_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,4) DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`option_value_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_option_values`
--

LOCK TABLES `ti_menu_option_values` WRITE;
/*!40000 ALTER TABLE `ti_menu_option_values` DISABLE KEYS */;
INSERT INTO `ti_menu_option_values` VALUES (1,1,'Peperoni',1.9900,2),(2,1,'Jalapenos',3.9900,1),(3,1,'Sweetcorn',1.9900,3),(4,2,'Meat',4.9500,4),(5,2,'Fish',4.9500,2),(6,2,'Beef',6.9500,3),(7,3,'Small',0.0000,1),(8,3,'Large',5.0000,2),(9,4,'Coke',0.0000,1),(10,4,'Diet Coke',0.0000,2);
/*!40000 ALTER TABLE `ti_menu_option_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menu_options`
--

DROP TABLE IF EXISTS `ti_menu_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menu_options` (
  `option_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `idx_menu_options_option` (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menu_options`
--

LOCK TABLES `ti_menu_options` WRITE;
/*!40000 ALTER TABLE `ti_menu_options` DISABLE KEYS */;
INSERT INTO `ti_menu_options` VALUES (1,'Toppings','checkbox',0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Sides','select',0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Size','radio',0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Drinks','quantity',0,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_menu_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menus`
--

DROP TABLE IF EXISTS `ti_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menus` (
  `menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_description` text COLLATE utf8mb4_unicode_ci,
  `menu_price` decimal(15,4) NOT NULL,
  `minimum_qty` int NOT NULL DEFAULT '0',
  `menu_status` tinyint(1) NOT NULL,
  `menu_priority` int NOT NULL DEFAULT '0',
  `order_restriction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `idx_menus_status` (`menu_status`),
  KEY `idx_menus_status_priority` (`menu_status`,`menu_priority`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menus`
--

LOCK TABLES `ti_menus` WRITE;
/*!40000 ALTER TABLE `ti_menus` DISABLE KEYS */;
INSERT INTO `ti_menus` VALUES (1,'Matoke (Steamed Plantains)','Traditional Ugandan dish of steamed green bananas, cooked with groundnut sauce',8000.0000,3,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Luwombo (Chicken)','Chicken stew steamed in banana leaves with vegetables and groundnuts',12000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Posho & Beans','Maize flour porridge served with red beans cooked in tomato sauce',5000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Rolex (Chapati Roll)','Popular street food: chapati rolled with eggs, tomatoes, onions and cabbage',3000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,'Katogo (Matooke & Offals)','Breakfast dish of mashed matooke mixed with offals in groundnut sauce',7000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,'Nile Perch (Empuuta)','Fresh Nile perch grilled or fried, served with steamed vegetables',15000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,'Groundnut Stew (Ebinyebwa)','Rich peanut butter stew with mushrooms, served with matooke or rice',9000.0000,1,0,0,NULL,'2026-01-19 08:07:51','2026-01-22 11:29:49'),(8,'Muchomo (Grilled Meat)','Skewered grilled goat or beef, seasoned with traditional spices',10000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(9,'Kikomando','Pieces of chapati mixed with fried beans, topped with avocado',4000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(10,'Samosas (Sambusa)','Crispy fried pastry filled with spiced minced meat or vegetables',2000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-21 10:32:47'),(11,'Malewa (Bamboo Shoots)','Steamed bamboo shoots cooked in groundnut sauce - Eastern Uganda specialty',8500.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-21 10:28:28'),(12,'Eshabwe (Ghee Sauce)','Traditional butter sauce from Western Uganda, served with millet bread',11000.0000,1,1,0,NULL,'2026-01-19 08:07:51','2026-01-21 10:19:37');
/*!40000 ALTER TABLE `ti_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_menus_specials`
--

DROP TABLE IF EXISTS `ti_menus_specials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_menus_specials` (
  `special_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `special_price` decimal(15,4) DEFAULT NULL,
  `special_status` tinyint(1) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurring_every` text COLLATE utf8mb4_unicode_ci,
  `recurring_from` time DEFAULT NULL,
  `recurring_to` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`special_id`),
  UNIQUE KEY `ti_menus_specials_special_id_menu_id_unique` (`special_id`,`menu_id`),
  KEY `idx_menus_specials_menu_special` (`menu_id`,`special_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_menus_specials`
--

LOCK TABLES `ti_menus_specials` WRITE;
/*!40000 ALTER TABLE `ti_menus_specials` DISABLE KEYS */;
INSERT INTO `ti_menus_specials` VALUES (1,12,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(2,11,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(3,10,'2026-01-31 15:01:00','2026-12-31 15:01:00',NULL,0,'F','period',NULL,NULL,NULL,NULL,NULL),(4,9,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(5,7,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(6,6,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(7,5,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(8,4,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(9,3,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(10,2,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL),(11,1,NULL,NULL,NULL,0,'F','forever',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ti_menus_specials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_migrations`
--

DROP TABLE IF EXISTS `ti_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_migrations`
--

LOCK TABLES `ti_migrations` WRITE;
/*!40000 ALTER TABLE `ti_migrations` DISABLE KEYS */;
INSERT INTO `ti_migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'igniter.system::2015_03_25_000001_create_tables',2),(6,'igniter.system::2016_11_29_000300_optimize_tables_columns',2),(7,'igniter.system::2017_04_13_000300_modify_columns_on_users_and_customers_tables',2),(8,'igniter.system::2017_05_08_000300_add_columns',2),(9,'igniter.system::2017_06_11_000300_create_payments_and_payment_logs_table',2),(10,'igniter.system::2017_08_23_000300_create_themes_table',2),(11,'igniter.system::2018_01_23_000300_create_language_translations_table',2),(12,'igniter.system::2018_03_30_000300_create_extension_settings_table',2),(13,'igniter.system::2018_06_12_000300_rename_model_class_names_to_morph_map_custom_names',2),(14,'igniter.system::2018_10_19_000300_create_media_attachments_table',2),(15,'igniter.system::2019_04_16_000300_nullify_customer_id_on_addresses_table',2),(16,'igniter.system::2019_07_01_000300_delete_unused_columns_from_activities_table',2),(17,'igniter.system::2019_07_22_000300_add_user_type_column_to_activities_table',2),(18,'igniter.system::2019_07_30_000300_create_mail_partials_table',2),(19,'igniter.system::2020_02_05_000300_delete_stale_unused_table',2),(20,'igniter.system::2020_04_16_000300_drop_stale_unused_columns',2),(21,'igniter.system::2020_05_24_000300_create_request_logs_table',2),(22,'igniter.system::2021_09_06_010000_add_timestamps_to_tables',2),(23,'igniter.system::2021_10_22_010000_make_primary_key_bigint_all_tables',2),(24,'igniter.system::2022_04_20_000300_add_version_column_to_languages_table',2),(25,'igniter.system::2022_05_14_000300_update_class_view_lang_namespaces',2),(26,'igniter.system::2022_06_30_010000_drop_foreign_key_constraints_on_all_tables',2),(27,'igniter.system::2023_02_24_000300_drop_activities_table',2),(28,'igniter.system::2023_03_05_123125_create_notifications_table',2),(29,'igniter.system::2023_04_23_000300_reduce_column_key_size_language_translations_table',2),(30,'igniter.system::2023_04_24_000300_nullable_layout_id_column_mail_layouts_table',2),(31,'igniter.system::2023_05_20_000300_add_is_default_column_locations_countries_currencies_customer_groups_languages_tables',2),(32,'igniter.system::2025_03_29_164243_remove_deprecated_code_from_mail_layouts',2),(33,'igniter.system::2025_05_04_000300_increase_version_column_length_languages_table',2),(34,'igniter.system::2025_05_11_000300_fix_renamed_mail_templates_table',2),(35,'igniter.admin::2017_08_25_000300_create_location_areas_table',3),(36,'igniter.admin::2017_08_25_000300_create_menu_categories_table',3),(37,'igniter.admin::2018_01_19_000300_add_hash_columns_on_orders_reservations_table',3),(38,'igniter.admin::2018_04_06_000300_drop_unique_on_order_totals_table',3),(39,'igniter.admin::2018_04_12_000300_modify_columns_on_orders_reservations_table',3),(40,'igniter.admin::2018_05_21_000300_drop_redundant_columns_on_kitchen_tables',3),(41,'igniter.admin::2018_05_29_000300_add_columns_on_location_areas_table',3),(42,'igniter.admin::2018_06_12_000300_create_locationables_table',3),(43,'igniter.admin::2018_07_04_000300_create_user_preferences_table',3),(44,'igniter.admin::2018_10_09_000300_auto_increment_on_order_totals_table',3),(45,'igniter.admin::2019_04_09_000300_auto_increment_on_user_preferences_table',3),(46,'igniter.admin::2019_07_02_000300_add_columns_on_menu_specials_table',3),(47,'igniter.admin::2019_07_16_000300_create_reservation_tables_table',3),(48,'igniter.admin::2019_07_21_000300_change_sort_value_ratings_to_config_on_settings_table',3),(49,'igniter.admin::2019_11_08_000300_add_selected_columns_to_menu_options_table',3),(50,'igniter.admin::2020_02_18_000400_create_staffs_groups_and_locations_table',3),(51,'igniter.admin::2020_02_21_000400_create_staff_roles_table',3),(52,'igniter.admin::2020_02_22_000300_remove_add_columns_on_staff_staff_groups_table',3),(53,'igniter.admin::2020_02_25_000300_create_assignable_logs_table',3),(54,'igniter.admin::2020_03_18_000300_add_quantity_column_to_order_menu_options_table',3),(55,'igniter.admin::2020_04_05_000300_create_payment_profiles_table',3),(56,'igniter.admin::2020_04_16_000300_drop_stale_unused_columns',3),(57,'igniter.admin::2020_05_31_000300_drop_more_unused_columns',3),(58,'igniter.admin::2020_06_11_000300_create_menu_mealtimes_table',3),(59,'igniter.admin::2020_08_16_000300_modify_columns_on_tables_reservations_table',3),(60,'igniter.admin::2020_08_18_000300_create_allergens_table',3),(61,'igniter.admin::2020_09_28_000300_add_refund_columns_to_payment_logs_table',3),(62,'igniter.admin::2020_12_13_000300_merge_staffs_locations_into_locationables_table',3),(63,'igniter.admin::2020_12_22_000300_add_priority_column_to_location_areas_table',3),(64,'igniter.admin::2021_01_04_000300_add_update_related_column_to_menu_options_table',3),(65,'igniter.admin::2021_01_04_010000_add_order_time_is_asap_on_orders_table',3),(66,'igniter.admin::2021_04_23_010000_remove_unused_columns',3),(67,'igniter.admin::2021_05_26_010000_alter_order_type_columns',3),(68,'igniter.admin::2021_05_29_010000_add_is_summable_on_order_totals_table',3),(69,'igniter.admin::2021_07_20_010000_add_columns_default_value',3),(70,'igniter.admin::2021_09_03_010000_make_serialize_columns_json',3),(71,'igniter.admin::2021_09_06_010000_add_timestamps_to_tables',3),(72,'igniter.admin::2021_10_22_010000_make_primary_key_bigint_all_tables',3),(73,'igniter.admin::2021_11_28_000300_create_stocks_table',3),(74,'igniter.admin::2022_02_03_000300_rename_allergens_to_ingredients_table',3),(75,'igniter.admin::2022_02_07_010000_add_low_stock_alerted_on_stocks_table',3),(76,'igniter.admin::2022_02_17_000300_merge_staffs_into_users_table',3),(77,'igniter.admin::2022_04_27_000300_create_location_options_table',3),(78,'igniter.admin::2022_05_10_000300_add_primary_key_to_working_hours_table',3),(79,'igniter.admin::2022_06_10_030300_prefix_users_tables_with_admin_table',3),(80,'igniter.admin::2022_06_30_010000_drop_foreign_key_constraints_on_all_tables',3),(81,'igniter.admin::2022_09_03_000300_make_location_options_fields_unique',3),(82,'igniter.admin::2022_10_26_000300_make_code_field_unique_mail_layouts_partials_table',3),(83,'igniter.admin::2022_11_03_003000_merge_menu_item_options_tables',3),(84,'igniter.admin::2023_01_10_000400_add_delivery_comment_orders_table',3),(85,'igniter.admin::2023_05_22_000400_add_invited_at_activated_at_orders_table',3),(86,'igniter.admin::2023_06_06_000400_update_dashboard_widget_properties_on_user_preferences_table',3),(87,'igniter.admin::2023_07_01_000300_create_location_settings',3),(88,'igniter.admin::2023_07_01_000400_copy_location_options_to_settings',3),(89,'igniter.api::2018_10_12_000300_create_resources_table',4),(90,'igniter.api::2020_04_27_000300_update_class_names_api_resources_table',4),(91,'igniter.api::2020_05_18_000300_create_access_tokens_table',4),(92,'igniter.api::2020_11_11_000300_alter_resources_table',4),(93,'igniter.api::2021_11_18_010000_make_primary_key_bigint_all_tables',4),(94,'igniter.api::2023_06_15_010000_drop_controller_resources_table',4),(95,'igniter.automation::2018_10_01_000100_create_all_tables',5),(96,'igniter.automation::2020_11_08_000300_create_task_log_table',5),(97,'igniter.automation::2021_11_18_010000_make_primary_key_bigint_all_tables',5),(98,'igniter.automation::2021_11_18_010300_add_foreign_key_constraints_to_tables',5),(99,'igniter.automation::2022_06_30_010000_drop_foreign_key_constraints',5),(100,'igniter.cart::2017_10_20_000100_create_conditions_settings',6),(101,'igniter.cart::2017_11_20_010000_create_cart_table',6),(102,'igniter.cart::2018_09_20_010000_rename_content_field_on_cart_table',6),(103,'igniter.cart::2025_05_22_010000_make_menu_description_nullable_table',6),(104,'igniter.cart::2025_07_08_010000_add_start_end_date_mealtimes_table',6),(105,'igniter.cart::2025_11_15_165912_add_indexes',6),(106,'igniter.coupons::2020_09_17_000300_create_coupons_table_or_rename',7),(107,'igniter.coupons::2020_09_18_000300_create_coupon_relations_tables',7),(108,'igniter.coupons::2020_10_15_000300_create_cart_restriction',7),(109,'igniter.coupons::2020_11_01_000300_add_auto_apply_field_on_coupons_table',7),(110,'igniter.coupons::2021_02_22_000300_increase_coupon_code_character_limit',7),(111,'igniter.coupons::2021_05_26_010000_alter_order_restriction_column',7),(112,'igniter.coupons::2021_09_06_010000_add_timestamps_to_coupons',7),(113,'igniter.coupons::2021_11_18_010000_make_primary_key_bigint_all_tables',7),(114,'igniter.coupons::2021_11_18_010300_add_foreign_key_constraints_to_tables',7),(115,'igniter.coupons::2022_06_30_010000_drop_foreign_key_constraints',7),(116,'igniter.coupons::2023_06_03_010000_set_nullable_columns',7),(117,'igniter.coupons::2023_09_28_010000_create_coupon_customer_groups_tables',7),(118,'igniter.coupons::2023_10_19_010000_change_is_limited_to_cart_item_to_apply_coupon_on_enum',7),(119,'igniter.coupons::2025_11_21_010000_add_min_menu_quantity',7),(120,'igniter.frontend::2018_01_28_000300_create_subscribers_table',8),(121,'igniter.frontend::2018_06_28_000300_create_banners_table',8),(122,'igniter.frontend::2019_11_02_000300_create_sliders_table',8),(123,'igniter.frontend::2021_10_20_000300_rename_banners_table',8),(124,'igniter.frontend::2021_11_18_010000_make_primary_key_bigint_all_tables',8),(125,'igniter.frontend::2021_11_18_010300_add_foreign_key_constraints_to_tables',8),(126,'igniter.frontend::2022_06_30_010000_drop_foreign_key_constraints',8),(127,'igniter.frontend::2024_02_28_010000_add_code_banners_table',8),(128,'igniter.local::2020_09_17_000300_create_reviews_table_or_rename',9),(129,'igniter.local::2020_12_10_000300_update_reviews_table',9),(130,'igniter.local::2021_01_02_000300_add_last_location_area_customers_table',9),(131,'igniter.local::2021_09_06_010000_add_timestamps_to_reviews',9),(132,'igniter.local::2021_11_18_010000_make_primary_key_bigint_all_tables',9),(133,'igniter.local::2024_06_04_010000_rename_sale_type_sale_id_reviews',9),(134,'igniter.local::2025_11_15_165912_add_indexes',9),(135,'igniter.pages::2018_06_28_000300_create_pages_table',10),(136,'igniter.pages::2019_11_28_000300_create_menus_table',10),(137,'igniter.pages::2019_11_28_000400_alter_columns_on_pages_table',10),(138,'igniter.pages::2021_03_31_000300_seed_menus_table',10),(139,'igniter.pages::2021_09_06_010000_add_timestamps_to_pages',10),(140,'igniter.pages::2021_10_20_010000_add_foreign_key_constraints_to_tables',10),(141,'igniter.pages::2022_09_16_010000_change_page_content_to_medium_text',10),(142,'igniter.pages::2023_01_28_010000_make_page_id_incremental',10),(143,'igniter.payregister::2021_05_08_000300_seed_default_payment_gateways',11),(144,'igniter.reservation::2022_09_15_000300_create_dining_areas_sections_tables_add_columns_table',12),(145,'igniter.reservation::2023_07_01_000500_copy_location_options_to_settings',12),(146,'igniter.reservation::2025_03_29_164012_remove_table_id_foreign_key',12),(147,'igniter.reservation::2025_04_03_164012_make_telephone_on_reservations_nullable',12),(148,'igniter.reservation::2025_05_15_164012_make_telephone_on_reservations_string',12),(149,'igniter.reservation::2025_08_30_164012_make_status_id_on_reservations_integer',12),(150,'igniter.reservation::2025_10_03_000000_add_indexes_to_dining_tables_and_areas',12),(151,'igniter.socialite::2018_10_11_211028_create_socialite_providers_table',13),(152,'igniter.socialite::2022_02_04_211028_add_user_type_column_socialite_providers_table',13),(153,'igniter.socialite::2022_06_14_211028_increase_string_length',13),(154,'igniter.user::2024_05_30_000400_add_user_id_assignable_logs',14),(155,'igniter.user::2025_04_04_000400_make_password_nullable_on_admin_users_customers',14),(156,'igniter.user::2025_06_01_000400_add_telephone_column_on_users',14);
/*!40000 ALTER TABLE `ti_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_notifications`
--

DROP TABLE IF EXISTS `ti_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_notifications`
--

LOCK TABLES `ti_notifications` WRITE;
/*!40000 ALTER TABLE `ti_notifications` DISABLE KEYS */;
INSERT INTO `ti_notifications` VALUES ('4178f251-e8da-48b6-a4fa-f3f4b645f297','order-created','users',1,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/1\",\"message\":\"<b>Nuwahereza Peter<\\/b> created an order.\"}',NULL,'2026-01-21 09:54:49','2026-01-21 09:54:49'),('60d9e62f-fcd6-4ccd-ba05-42d7cf1cdb84','reservation-created','users',1,'{\"title\":\"New reservation.\",\"icon\":\"fa-chair\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/reservations\\/edit\\/1\",\"message\":\"<b>Nuwahereza Peter<\\/b> created a reservation.\"}',NULL,'2026-01-20 13:11:08','2026-01-20 13:11:08'),('6712d63a-e862-46e1-8e5e-8eb94ab76b1e','order-created','users',2,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:12:31','2026-01-23 11:12:31'),('847dbcd8-9c1c-4bd3-91b5-5df6e7fb850d','order-created','users',2,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:13:22','2026-01-23 11:13:22'),('85bbe5bb-ce1d-4ef9-979a-47bcf207ec38','update-found','users',1,'{\"title\":\"No updates available.\",\"icon\":\"fa-cloud-arrow-down\",\"iconColor\":\"success\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/updates\",\"message\":\"Your app is up to date.\"}',NULL,'2026-01-22 10:22:10','2026-01-22 10:22:10'),('93a04dd1-d2b5-4f36-a8f8-62ee7308bcb2','order-created','users',2,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:12:54','2026-01-23 11:12:54'),('d87c3367-e789-441b-a1b7-d665c3000304','customer-registered','users',1,'{\"title\":\"Customer registered\",\"icon\":\"fa-user\",\"iconColor\":null,\"url\":\"http:\\/\\/0.0.0.0:8000\\/admin\\/customers\\/edit\\/2\",\"message\":\"<b>Nuwahereza Peter<\\/b> created an account.\"}',NULL,'2026-01-20 12:05:04','2026-01-20 12:05:04'),('e24715c4-f640-4555-961f-83b4f29d0972','order-created','users',1,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:12:31','2026-01-23 11:12:31'),('e47426c8-9d99-48bb-b2c6-3e42fb776229','order-created','users',1,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:13:22','2026-01-23 11:13:22'),('ffefd24d-8514-4ae8-a261-2bbcc57253a6','order-created','users',1,'{\"title\":\"New order placed\",\"icon\":\"fa-clipboard-list\",\"iconColor\":null,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/edit\\/3\",\"message\":\"<b>Test Customer<\\/b> created an order.\"}',NULL,'2026-01-23 11:12:54','2026-01-23 11:12:54');
/*!40000 ALTER TABLE `ti_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_order_menu_options`
--

DROP TABLE IF EXISTS `ti_order_menu_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_order_menu_options` (
  `order_option_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `order_option_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_option_price` decimal(15,4) DEFAULT NULL,
  `order_menu_id` int NOT NULL,
  `menu_option_id` int NOT NULL,
  `menu_option_value_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`order_option_id`),
  KEY `idx_ti_order_menu_options_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_order_menu_options`
--

LOCK TABLES `ti_order_menu_options` WRITE;
/*!40000 ALTER TABLE `ti_order_menu_options` DISABLE KEYS */;
INSERT INTO `ti_order_menu_options` VALUES (3,1,'Coke',0.0000,2,1,1,1),(4,1,'Diet Coke',0.0000,2,1,2,1);
/*!40000 ALTER TABLE `ti_order_menu_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_order_menus`
--

DROP TABLE IF EXISTS `ti_order_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_order_menus` (
  `order_menu_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,4) DEFAULT NULL,
  `subtotal` decimal(15,4) DEFAULT NULL,
  `option_values` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`order_menu_id`),
  KEY `idx_ti_order_menus_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_order_menus`
--

LOCK TABLES `ti_order_menus` WRITE;
/*!40000 ALTER TABLE `ti_order_menus` DISABLE KEYS */;
INSERT INTO `ti_order_menus` VALUES (2,1,1,'Puff-Puff',3,4.9900,14.9700,'s:531:\"O:28:\"Igniter\\Cart\\CartItemOptions\":2:{s:8:\"\0*\0items\";a:1:{i:1;O:27:\"Igniter\\Cart\\CartItemOption\":3:{s:2:\"id\";i:1;s:4:\"name\";s:6:\"Drinks\";s:6:\"values\";O:33:\"Igniter\\Cart\\CartItemOptionValues\":2:{s:8:\"\0*\0items\";a:2:{i:1;O:32:\"Igniter\\Cart\\CartItemOptionValue\":4:{s:2:\"id\";i:1;s:4:\"name\";s:4:\"Coke\";s:3:\"qty\";i:1;s:5:\"price\";d:0;}i:2;O:32:\"Igniter\\Cart\\CartItemOptionValue\":4:{s:2:\"id\";i:2;s:4:\"name\";s:9:\"Diet Coke\";s:3:\"qty\";i:1;s:5:\"price\";d:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}\";',NULL),(3,2,2,'Luwombo (Chicken)',1,12000.0000,12000.0000,'s:101:\"O:28:\"Igniter\\Cart\\CartItemOptions\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}\";',NULL);
/*!40000 ALTER TABLE `ti_order_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_order_totals`
--

DROP TABLE IF EXISTS `ti_order_totals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_order_totals` (
  `order_total_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `is_summable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_total_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_order_totals`
--

LOCK TABLES `ti_order_totals` WRITE;
/*!40000 ALTER TABLE `ti_order_totals` DISABLE KEYS */;
INSERT INTO `ti_order_totals` VALUES (1,1,'delivery','Delivery',0.0000,100,1),(2,1,'tax','VAT [3%]',0.4500,127,1),(3,1,'subtotal','Sub Total',14.9700,0,0),(4,1,'total','Order Total',15.4200,127,0),(5,2,'delivery','Delivery',2000.0000,100,1),(6,2,'tax','VAT [3%]',420.0000,127,1),(7,2,'subtotal','Sub Total',12000.0000,0,0),(8,2,'total','Order Total',14420.0000,127,0);
/*!40000 ALTER TABLE `ti_order_totals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_orders`
--

DROP TABLE IF EXISTS `ti_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_orders` (
  `order_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int NOT NULL,
  `address_id` int DEFAULT NULL,
  `cart` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_items` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `order_time` time NOT NULL,
  `order_date` date NOT NULL,
  `order_total` decimal(15,4) DEFAULT NULL,
  `status_id` int NOT NULL,
  `ip_address` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignee_id` int DEFAULT NULL,
  `assignee_group_id` int unsigned DEFAULT NULL,
  `invoice_prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed` tinyint(1) DEFAULT NULL,
  `status_updated_at` datetime DEFAULT NULL,
  `assignee_updated_at` datetime DEFAULT NULL,
  `order_time_is_asap` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_comment` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`order_id`),
  KEY `ti_orders_hash_index` (`hash`),
  KEY `idx_ti_orders_location_date_status` (`location_id`,`order_date`,`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_orders`
--

LOCK TABLES `ti_orders` WRITE;
/*!40000 ALTER TABLE `ti_orders` DISABLE KEYS */;
INSERT INTO `ti_orders` VALUES (1,2,'Nuwahereza','Peter','atwines23@gmail.com','+256779081600',1,2,'O:24:\"Igniter\\Cart\\CartContent\":2:{s:8:\"\0*\0items\";a:1:{s:32:\"0f11d0220b4da1e5f01fa37cb7c1e0cf\";O:21:\"Igniter\\Cart\\CartItem\":9:{s:5:\"rowId\";s:32:\"0f11d0220b4da1e5f01fa37cb7c1e0cf\";s:2:\"id\";i:1;s:3:\"qty\";i:3;s:4:\"name\";s:9:\"Puff-Puff\";s:5:\"price\";d:4.99;s:7:\"comment\";N;s:7:\"options\";O:28:\"Igniter\\Cart\\CartItemOptions\":2:{s:8:\"\0*\0items\";a:1:{i:1;O:27:\"Igniter\\Cart\\CartItemOption\":3:{s:2:\"id\";i:1;s:4:\"name\";s:6:\"Drinks\";s:6:\"values\";O:33:\"Igniter\\Cart\\CartItemOptionValues\":2:{s:8:\"\0*\0items\";a:2:{i:1;O:32:\"Igniter\\Cart\\CartItemOptionValue\":4:{s:2:\"id\";i:1;s:4:\"name\";s:4:\"Coke\";s:3:\"qty\";i:1;s:5:\"price\";d:0;}i:2;O:32:\"Igniter\\Cart\\CartItemOptionValue\":4:{s:2:\"id\";i:2;s:4:\"name\";s:9:\"Diet Coke\";s:3:\"qty\";i:1;s:5:\"price\";d:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"conditions\";O:31:\"Igniter\\Cart\\CartItemConditions\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"\0*\0associatedModel\";s:24:\"Igniter\\Cart\\Models\\Menu\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',3,NULL,'cod','collection','2026-01-21 09:51:56','2026-01-23 07:20:45','13:09:00','2026-01-21',15.4200,5,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,'57ab93866889451d8655fecf11b87d49',1,'2026-01-23 10:20:45',NULL,1,NULL),(2,2,'Nuwahereza','Peter','atwines23@gmail.com','+256779081600',1,2,'O:24:\"Igniter\\Cart\\CartContent\":2:{s:8:\"\0*\0items\";a:1:{s:32:\"370d08585360f5c568b18d1f2e4ca1df\";O:21:\"Igniter\\Cart\\CartItem\":9:{s:5:\"rowId\";s:32:\"370d08585360f5c568b18d1f2e4ca1df\";s:2:\"id\";i:2;s:3:\"qty\";i:1;s:4:\"name\";s:17:\"Luwombo (Chicken)\";s:5:\"price\";d:12000;s:7:\"comment\";N;s:7:\"options\";O:28:\"Igniter\\Cart\\CartItemOptions\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"conditions\";O:31:\"Igniter\\Cart\\CartItemConditions\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"\0*\0associatedModel\";s:24:\"Igniter\\Cart\\Models\\Menu\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1,NULL,'cod','delivery','2026-01-23 06:52:41','2026-01-23 06:52:42','00:00:00','2026-01-24',14420.0000,0,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,'a952f8e92e1ada0c50e85215e3a1c03a',NULL,NULL,NULL,0,NULL),(3,NULL,'Test','Customer','nuwaherezapeter34@gmail.com','+256700123456',1,0,'',2,'This is a test order to verify email notifications','cod','delivery','2026-01-23 11:12:26','2026-01-23 11:12:26','15:12:00','2026-01-23',25000.0000,1,'127.0.0.1','Symfony',NULL,NULL,'INV-2026-00','2026-01-23 14:12:26','858b13e6627a314794c07b45f6686d71',1,NULL,NULL,0,NULL);
/*!40000 ALTER TABLE `ti_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_pages`
--

DROP TABLE IF EXISTS `ti_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_pages` (
  `page_id` int NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `status` tinyint(1) NOT NULL,
  `permalink_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` mediumtext COLLATE utf8mb4_unicode_ci,
  `priority` int DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  KEY `ti_pages_language_id_foreign` (`language_id`),
  CONSTRAINT `ti_pages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `ti_languages` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_pages`
--

LOCK TABLES `ti_pages` WRITE;
/*!40000 ALTER TABLE `ti_pages` DISABLE KEYS */;
INSERT INTO `ti_pages` VALUES (1,1,'About Us','<p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><span style=\"color: rgb(46, 46, 58); font-size: 16.003px; font-weight: 600;\">ABOUT US</span></p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Welcome to UgaEats 🇺🇬</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>Connecting Ugandans to Their Favorite Local Dishes</strong></p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">UgaEats is Uganda\'s premier food delivery platform, bringing the rich flavors of Ugandan cuisine right to your doorstep. Founded in 2024 in Kampala, we are passionate about celebrating and promoting our beloved local dishes while making food ordering convenient for every Ugandan.</p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our Story</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">UgaEats was born from a simple idea:&nbsp;<strong>every Ugandan deserves easy access to delicious, authentic local food</strong>.</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We noticed that while international food delivery apps were growing, none truly understood the Ugandan palate. We asked ourselves:&nbsp;<em>Where can someone order a steaming plate of Luwombo? Who delivers fresh Rolex at midnight? How can a busy professional enjoy homemade Matoke without cooking?</em></p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">The answer became UgaEats.</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Starting with just 5 restaurant partners in Kampala, we\'ve grown to serve thousands of customers across the greater Kampala metropolitan area, Wakiso, Mukono, and Entebbe.</p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our Mission</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>To make authentic Ugandan cuisine accessible to everyone, anytime, anywhere.</strong></p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We believe that:</p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li>🍌&nbsp;<strong>Local food matters</strong>&nbsp;- Ugandan dishes deserve the same convenience as international cuisines</li><li>🏪&nbsp;<strong>Local businesses matter</strong>&nbsp;- We empower small restaurants and home cooks to reach more customers</li><li>🚴&nbsp;<strong>Local jobs matter</strong>&nbsp;- We create employment opportunities for delivery riders across Uganda</li><li>💚&nbsp;<strong>Community matters</strong>&nbsp;- We reinvest in the communities we serve</li></ul><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">What We Offer</h3><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">🍽️ Authentic Ugandan Cuisine</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">From Matoke to Muchomo, Rolex to Luwombo, we partner with restaurants that prepare genuine Ugandan dishes using traditional recipes and fresh local ingredients.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">🏠 Home Cooks Program</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We support talented home cooks (\"Maama\'s Kitchen\") by giving them a platform to share their family recipes with a wider audience.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">⚡ Fast Delivery</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our network of dedicated boda-boda riders ensures your food arrives hot and fresh, typically within 30-45 minutes.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">💳 Convenient Payment</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Pay the way that works for you:</p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li><strong>MTN Mobile Money</strong>&nbsp;📱</li><li><strong>Airtel Money</strong>&nbsp;📱</li><li><strong>Cash on Delivery</strong>&nbsp;💵</li><li><strong>Visa/Mastercard</strong>&nbsp;💳</li></ul><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">📍 Wide Coverage</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We currently serve:</p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li>Kampala Central</li><li>Nakawa</li><li>Kawempe</li><li>Makindye</li><li>Rubaga</li><li>Wakiso</li><li>Mukono</li><li>Entebbe</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><em>Expanding to more areas soon!</em></p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our Values</h3><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">🤝 Trust</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We verify all restaurant partners for food safety and hygiene. Your health is our priority.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">💯 Quality</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We work only with restaurants committed to serving fresh, delicious food made with quality ingredients.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">⏰ Reliability</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">When we say 30 minutes, we mean it. Our riders are trained to deliver your food safely and on time.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">🌍 Community</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We\'re proudly Ugandan. We hire locally, source locally, and give back locally.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">💡 Innovation</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We continuously improve our platform to make your food ordering experience seamless and enjoyable.</p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our Team</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">UgaEats is powered by a passionate team of Ugandans who love food and technology:</p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li><strong>Founders</strong>&nbsp;- Tech entrepreneurs with a passion for Ugandan cuisine</li><li><strong>Operations Team</strong>&nbsp;- Ensuring smooth deliveries across Kampala</li><li><strong>Restaurant Partners Team</strong>&nbsp;- Supporting our restaurant network</li><li><strong>Rider Support</strong>&nbsp;- Taking care of our delivery heroes</li><li><strong>Customer Support</strong>&nbsp;- Available 7 days a week to help you</li></ul><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Our Impact</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Since our launch:</p><table style=\"color: rgb(204, 204, 204); font-size: 13px; width: 307px; margin-bottom: 16px; border-color: rgba(255, 255, 255, 0.1); border-style: solid; border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; padding: 4px 6px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><thead><tr><th style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">Metric</th><th style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">Achievement</th></tr></thead><tbody><tr><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">🍽️ Orders Delivered</td><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">50,000+</td></tr><tr><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">🏪 Restaurant Partners</td><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">200+</td></tr><tr><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">🚴 Delivery Riders</td><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">500+</td></tr><tr><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">😊 Happy Customers</td><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">20,000+</td></tr><tr><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">📍 Areas Covered</td><td style=\"border-color: rgba(255, 255, 255, 0.1); border-width: 0.666667px; border-image: none 100% / 1 / 0 stretch; border-collapse: collapse; padding: 4px 6px;\">10+</td></tr></tbody></table><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Join the UgaEats Family</h3><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">For Customers</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Download our app or visit our website to start ordering your favorite Ugandan dishes today!</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">For Restaurants</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Want to reach more customers? Partner with us and grow your business with UgaEats.</p><h4 style=\"line-height: normal; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">For Riders</h4><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Looking for flexible earning opportunities? Join our rider team and earn money on your own schedule.</p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Contact Us</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">We\'d love to hear from you!</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>UgaEats Headquarters</strong><br>📍 Kampala, Uganda</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">📞&nbsp;<strong>Customer Support:</strong>&nbsp;+256 700 000 000<br>📧&nbsp;<strong>Email:</strong>&nbsp;<a href=\"vscode-file://vscode-app/usr/share/code/resources/app/out/vs/code/electron-browser/workbench/workbench.html\" title=\"\" draggable=\"false\" data-href=\"mailto:hello@ugaeats.ug\" custom-hover=\"true\" style=\"text-decoration-style: solid; text-decoration-color: rgb(77, 170, 252); color: rgb(77, 170, 252); user-select: text;\">hello@ugaeats.ug</a><br>💬&nbsp;<strong>WhatsApp:</strong>&nbsp;+256 700 000 000</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>Business Hours:</strong></p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li>Monday - Friday: 8:00 AM - 10:00 PM</li><li>Saturday - Sunday: 9:00 AM - 11:00 PM</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>Follow Us:</strong></p><ul style=\"padding-inline-start: 24px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><li>Facebook: @UgaEatsUG</li><li>Instagram: @ugaeats</li><li>Twitter: @UgaEatsUG</li><li>TikTok: @ugaeats</li></ul><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" color:=\"\" rgb(204,=\"\" 204,=\"\" 204);=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Webale Nnyo! (Thank You!)</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\">Thank you for choosing UgaEats. Every order you place supports local restaurants, creates jobs for riders, and helps grow Uganda\'s food ecosystem.</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><strong>Eat Local. Support Local. Love Local.</strong>&nbsp;🇺🇬</p><hr style=\"border-color: rgba(255, 255, 255, 0.18); color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><em>UgaEats - Bringing Uganda\'s Flavors to Your Door</em></p><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, \" droid=\"\" sans\",=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" background-color:=\"\" rgb(24,=\"\" 24,=\"\" 24);\"=\"\"><br></p>',NULL,NULL,'2026-01-19 08:07:55','2026-01-23 08:23:09',1,'about-us','static','{\"navigation_hidden\":0}',NULL),(2,1,'Policy','<h2 style=\"color: rgb(204, 204, 204); font-weight: 600; line-height: normal; margin: 16px 0px 8px; font-size: 16.003px;\">PRIVACY POLICY</h2><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">Last Updated: January 23, 2026</span></p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">1. Introduction</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">UgaEats (\"we,\" \"our,\" or \"us\") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information in compliance with Uganda\'s Data Protection and Privacy Act, 2019.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">2. Information We Collect</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">2.1 Personal Information</span></p><ul style=\"padding-inline-start: 24px;\"><li>Full name</li><li>Phone number</li><li>Email address</li><li>Delivery addresses</li><li>Payment information</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">2.2 Order Information</span></p><ul style=\"padding-inline-start: 24px;\"><li>Order history</li><li>Food preferences</li><li>Delivery instructions</li><li>Reviews and ratings</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">2.3 Device Information</span></p><ul style=\"padding-inline-start: 24px;\"><li>IP address</li><li>Browser type</li><li>Device type</li><li>Location data (with your permission)</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">2.4 Usage Data</span></p><ul style=\"padding-inline-start: 24px;\"><li>Pages visited</li><li>Time spent on Platform</li><li>Search queries</li><li>Click patterns</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">3. How We Use Your Information</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">We use your information to:</p><ul style=\"padding-inline-start: 24px;\"><li>Process and deliver orders</li><li>Communicate about your orders</li><li>Improve our services</li><li>Send promotional offers (with your consent)</li><li>Prevent fraud and abuse</li><li>Comply with legal obligations</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">4. Information Sharing</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">4.1 We share information with:</span></p><ul style=\"padding-inline-start: 24px;\"><li>Restaurant Partners (to prepare your order)</li><li>Delivery Partners (to deliver your order)</li><li>Payment processors (to process payments)</li><li>Service providers (who assist our operations)</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">4.2 We do NOT:</span></p><ul style=\"padding-inline-start: 24px;\"><li>Sell your personal information</li><li>Share data with unrelated third parties</li><li>Use your data for purposes you haven\'t consented to</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">5. Data Security</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">We implement appropriate security measures:</p><ul style=\"padding-inline-start: 24px;\"><li>Encrypted data transmission (SSL/TLS)</li><li>Secure data storage</li><li>Access controls for staff</li><li>Regular security audits</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">6. Your Rights</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">Under Uganda\'s Data Protection Act, you have the right to:</p><ul style=\"padding-inline-start: 24px;\"><li>Access your personal data</li><li>Correct inaccurate data</li><li>Delete your data (right to be forgotten)</li><li>Restrict processing of your data</li><li>Data portability</li><li>Withdraw consent at any time</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">7. Data Retention</h3><ul style=\"padding-inline-start: 24px;\"><li>Account data: Retained while account is active</li><li>Order history: Retained for 3 years</li><li>Payment data: Retained as required by law</li><li>Marketing data: Until you unsubscribe</li></ul><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">8. Cookies</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">We use cookies to:</p><ul style=\"padding-inline-start: 24px;\"><li>Remember your preferences</li><li>Keep you logged in</li><li>Analyze site usage</li><li>Improve user experience</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">You can disable cookies in your browser settings.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">9. Children\'s Privacy</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">UgaEats is not intended for users under 18 years old. We do not knowingly collect data from children.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">10. Third-Party Links</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">Our Platform may contain links to third-party websites. We are not responsible for their privacy practices.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">11. International Transfers</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">Your data is stored in Uganda. If transferred internationally, we ensure adequate protection measures are in place.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">12. Changes to This Policy</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">We may update this Privacy Policy periodically. We will notify you of significant changes via email or Platform notification.</p><h3 style=\"color: rgb(204, 204, 204); font-weight: unset; line-height: normal; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; font-size: 13px;\">13. Contact Us</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">For privacy-related inquiries:</p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">Data Protection Officer</span><br>UgaEats Uganda</p><ul style=\"padding-inline-start: 24px;\"><li>Email:&nbsp;<a href=\"vscode-file://vscode-app/usr/share/code/resources/app/out/vs/code/electron-browser/workbench/workbench.html\" title=\"\" draggable=\"false\" data-href=\"mailto:privacy@ugaeats.ug\" custom-hover=\"true\" style=\"color: rgb(77, 170, 252); user-select: text;\">privacy@ugaeats.ug</a></li><li>Phone: +256 700 000 000</li><li>Address: Kampala, Uganda</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-weight: bolder;\">National Information Technology Authority - Uganda (NITA-U)</span><br>For complaints about data protection, you may also contact:</p><ul style=\"padding-inline-start: 24px;\"><li>Website:&nbsp;<a href=\"vscode-file://vscode-app/usr/share/code/resources/app/out/vs/code/electron-browser/workbench/workbench.html\" title=\"\" draggable=\"false\" data-href=\"http://www.nita.go.ug\" custom-hover=\"true\" style=\"color: rgb(77, 170, 252); user-select: text;\">www.nita.go.ug</a></li></ul><p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px;\"><br style=\"color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, &quot;Droid Sans&quot;, sans-serif; font-size: 13px; background-color: rgb(24, 24, 24);\"></p>',NULL,NULL,'2026-01-19 08:07:55','2026-01-23 08:17:18',1,'policy','static','{\"navigation_hidden\":0}',NULL),(3,1,'Terms and Conditions','<div class=\"value\" style=\"width: 307px; overflow-wrap: anywhere; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, &quot;Droid Sans&quot;, sans-serif; font-size: 13px; background-color: rgb(24, 24, 24);\"><div class=\"chat-markdown-part rendered-markdown\" style=\"margin-bottom: 0px; line-height: 1.5em;\"><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><span style=\"font-size: 16.003px; font-weight: 600;\">TERMS AND CONDITIONS</span></p><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>Last Updated: January 23, 2026</strong></p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">1. Introduction</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">Welcome to UgaEats (\"we,\" \"our,\" or \"us\"). These Terms and Conditions govern your use of our food delivery platform and services in Uganda. By accessing or using UgaEats, you agree to be bound by these terms.</p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">2. Definitions</h3><ul style=\"padding-inline-start: 24px;\"><li><strong>\"Platform\"</strong>&nbsp;- The UgaEats website and mobile application</li><li><strong>\"User\"</strong>&nbsp;- Any person who accesses or uses our Platform</li><li><strong>\"Restaurant Partner\"</strong>&nbsp;- Food establishments listed on our Platform</li><li><strong>\"Delivery Partner\"</strong>&nbsp;- Independent contractors who deliver orders</li><li><strong>\"Order\"</strong>&nbsp;- A request for food items placed through our Platform</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">3. Eligibility</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">To use UgaEats, you must:</p><ul style=\"padding-inline-start: 24px;\"><li>Be at least 18 years old</li><li>Have a valid Ugandan phone number</li><li>Provide accurate registration information</li><li>Have the legal capacity to enter into contracts</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">4. Account Registration</h3><ul style=\"padding-inline-start: 24px;\"><li>You must register an account to place orders</li><li>Keep your login credentials confidential</li><li>You are responsible for all activities under your account</li><li>Notify us immediately of any unauthorized use</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">5. Ordering and Payment</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>5.1 Placing Orders</strong></p><ul style=\"padding-inline-start: 24px;\"><li>All orders are subject to availability</li><li>Prices are displayed in Ugandan Shillings (UGX)</li><li>Prices may vary between restaurants</li><li>We reserve the right to cancel orders due to errors in pricing</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>5.2 Payment Methods</strong><br>We accept the following payment methods:</p><ul style=\"padding-inline-start: 24px;\"><li>MTN Mobile Money</li><li>Airtel Money</li><li>Cash on Delivery</li><li>Visa/Mastercard (via Flutterwave)</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>5.3 Payment Terms</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Payment is required before or upon delivery</li><li>Mobile money transactions are processed in real-time</li><li>Cash on Delivery requires exact payment when possible</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">6. Delivery</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>6.1 Delivery Areas</strong><br>We currently deliver within:</p><ul style=\"padding-inline-start: 24px;\"><li>Kampala Metropolitan Area</li><li>Wakiso District</li><li>Mukono District</li><li>Entebbe</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>6.2 Delivery Times</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Estimated delivery times are approximations</li><li>Delays may occur due to traffic, weather, or high demand</li><li>We are not liable for delays beyond our reasonable control</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>6.3 Delivery Fees</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Delivery fees vary based on distance and location</li><li>Fees are clearly displayed before order confirmation</li><li>Minimum order amounts may apply in certain areas</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">7. Cancellations and Refunds</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>7.1 Order Cancellation</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Orders may be cancelled within 2 minutes of placement</li><li>Once preparation begins, cancellation may not be possible</li><li>Restaurant Partners reserve the right to cancel orders</li></ul><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>7.2 Refunds</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Refunds are processed within 5-7 business days</li><li>Mobile money refunds are credited to your registered number</li><li>Partial refunds may apply for incomplete orders</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">8. Quality and Safety</h3><ul style=\"padding-inline-start: 24px;\"><li>Restaurant Partners are responsible for food quality and safety</li><li>We verify that partners meet basic hygiene standards</li><li>Report any food safety concerns immediately</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">9. User Conduct</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">You agree NOT to:</p><ul style=\"padding-inline-start: 24px;\"><li>Provide false information</li><li>Use the Platform for illegal purposes</li><li>Harass delivery or restaurant staff</li><li>Attempt to manipulate pricing or promotions</li><li>Share your account with others</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">10. Intellectual Property</h3><ul style=\"padding-inline-start: 24px;\"><li>All content on UgaEats is our property</li><li>You may not copy, modify, or distribute our content</li><li>Restaurant logos and names belong to respective owners</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">11. Limitation of Liability</h3><ul style=\"padding-inline-start: 24px;\"><li>We are not liable for food quality or allergic reactions</li><li>Maximum liability is limited to the order value</li><li>We are not liable for indirect or consequential damages</li></ul><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">12. Privacy</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">Your use of UgaEats is also governed by our Privacy Policy. We collect and process personal data in accordance with Uganda\'s Data Protection and Privacy Act, 2019.</p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">13. Modifications</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">We may update these Terms at any time. Continued use of the Platform after changes constitutes acceptance of the new Terms.</p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">14. Governing Law</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\">These Terms are governed by the laws of the Republic of Uganda. Disputes shall be resolved in the courts of Kampala.</p><h3 style=\"line-height: normal; font-size: 13px; font-weight: unset; margin-right: 0px; margin-bottom: 8px; margin-left: 0px;\">15. Contact Us</h3><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px;\"><strong>UgaEats Uganda</strong></p><ul style=\"padding-inline-start: 24px;\"><li>Email:&nbsp;<a href=\"vscode-file://vscode-app/usr/share/code/resources/app/out/vs/code/electron-browser/workbench/workbench.html\" title=\"\" draggable=\"false\" data-href=\"mailto:support@ugaeats.ug\" custom-hover=\"true\" style=\"color: rgb(77, 170, 252); user-select: text;\">support@ugaeats.ug</a></li><li>Phone: +256 700 000 000</li><li>Address: Kampala, Uganda</li></ul><hr style=\"border-color: rgba(255, 255, 255, 0.18);\"><h2 style=\"line-height: normal; font-size: 16.003px; font-weight: 600; margin: 16px 0px 8px;\"><br></h2></div></div><div class=\"chat-footer-toolbar\" style=\"opacity: 1; visibility: visible; padding-top: 6px; height: 22px; color: rgb(204, 204, 204); font-family: system-ui, Ubuntu, &quot;Droid Sans&quot;, sans-serif; font-size: 13px; text-wrap-mode: nowrap; background-color: rgb(24, 24, 24);\"><div class=\"monaco-toolbar\" style=\"height: 22px; display: flex; justify-content: space-between; align-items: center;\"><div class=\"monaco-action-bar\" style=\"height: 22px;\"><ul class=\"actions-container\" role=\"toolbar\" style=\"display: flex; margin-right: auto; margin-bottom: 0px; margin-left: auto; padding: 0px; height: 22px; width: 100px; align-items: center; gap: 4px;\"><li class=\"action-item menu-entry\" role=\"presentation\" custom-hover=\"true\" style=\"display: block; align-items: center; justify-content: center; cursor: pointer; position: relative;\"></li><li class=\"action-item menu-entry\" role=\"presentation\" custom-hover=\"true\" style=\"display: block; align-items: center; justify-content: center; cursor: pointer; position: relative;\"></li><li class=\"action-item menu-entry\" role=\"presentation\" custom-hover=\"true\" style=\"display: block; align-items: center; justify-content: center; cursor: pointer; position: relative;\"></li><li class=\"action-item\" role=\"presentation\" style=\"display: block; align-items: center; justify-content: center; cursor: pointer; position: relative;\"><div class=\"monaco-dropdown\" style=\"height: 22px; padding: 0px;\"><div class=\"dropdown-label\" style=\"cursor: pointer; height: 22px; display: flex; align-items: center; justify-content: center;\"></div></div></li></ul></div><div class=\"chat-footer-details\" tabindex=\"0\" style=\"padding: 6px 0px 0px; font-size: 10.998px; opacity: 1; color: rgb(157, 157, 157); line-height: 16px; margin-left: auto; visibility: visible; height: 22px;\">Claude Opus 4.5 • 3x</div></div></div>',NULL,NULL,'2026-01-19 08:07:55','2026-01-23 08:16:33',1,'terms-and-conditions','static','{\"navigation_hidden\":0}',NULL);
/*!40000 ALTER TABLE `ti_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_password_resets`
--

DROP TABLE IF EXISTS `ti_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `ti_password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_password_resets`
--

LOCK TABLES `ti_password_resets` WRITE;
/*!40000 ALTER TABLE `ti_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_payment_logs`
--

DROP TABLE IF EXISTS `ti_payment_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_payment_logs` (
  `payment_log_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `payment_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` text COLLATE utf8mb4_unicode_ci,
  `response` text COLLATE utf8mb4_unicode_ci,
  `is_success` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `payment_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_refundable` tinyint(1) NOT NULL DEFAULT '0',
  `refunded_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_payment_logs`
--

LOCK TABLES `ti_payment_logs` WRITE;
/*!40000 ALTER TABLE `ti_payment_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_payment_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_payment_profiles`
--

DROP TABLE IF EXISTS `ti_payment_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_payment_profiles` (
  `payment_profile_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned DEFAULT NULL,
  `payment_id` int unsigned DEFAULT NULL,
  `card_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_data` text COLLATE utf8mb4_unicode_ci,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_profile_id`),
  KEY `ti_payment_profiles_customer_id_index` (`customer_id`),
  KEY `ti_payment_profiles_payment_id_index` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_payment_profiles`
--

LOCK TABLES `ti_payment_profiles` WRITE;
/*!40000 ALTER TABLE `ti_payment_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_payment_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_payments`
--

DROP TABLE IF EXISTS `ti_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_payments` (
  `payment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `data` json NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `ti_payments_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_payments`
--

LOCK TABLES `ti_payments` WRITE;
/*!40000 ALTER TABLE `ti_payments` DISABLE KEYS */;
INSERT INTO `ti_payments` VALUES (1,'Cash On Delivery','cod','Igniter\\PayRegister\\Payments\\Cod','Pay with cash when you pick up your order or when is delivered','[]',1,1,1,'2026-01-19 08:07:55','2026-01-19 08:07:55'),(2,'PayPal Express','paypalexpress','Igniter\\PayRegister\\Payments\\PaypalExpress','Securely pay using your PayPal account','[]',0,0,2,'2026-01-19 08:07:55','2026-01-19 08:07:55'),(3,'Authorize.Net (AIM)','authorizenetaim','Igniter\\PayRegister\\Payments\\AuthorizeNetAim','Pay with your credit card via Authorize.Net','[]',0,0,3,'2026-01-19 08:07:55','2026-01-19 08:07:55'),(4,'Stripe Payment','stripe','Igniter\\PayRegister\\Payments\\Stripe','Pay with your credit card using Stripe','[]',0,0,4,'2026-01-19 08:07:55','2026-01-19 08:07:55'),(5,'Mollie Payment','mollie','Igniter\\PayRegister\\Payments\\Mollie','Pay with your credit card through Mollie','[]',0,0,5,'2026-01-19 08:07:55','2026-01-19 08:07:55'),(6,'Square Payment','square','Igniter\\PayRegister\\Payments\\Square','Pay with your credit card using Square','[]',0,0,6,'2026-01-19 08:07:55','2026-01-19 08:07:55');
/*!40000 ALTER TABLE `ti_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_personal_access_tokens`
--

DROP TABLE IF EXISTS `ti_personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_personal_access_tokens_token_unique` (`token`),
  KEY `ti_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_personal_access_tokens`
--

LOCK TABLES `ti_personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `ti_personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_request_logs`
--

DROP TABLE IF EXISTS `ti_request_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_request_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_code` int DEFAULT NULL,
  `referrer` text COLLATE utf8mb4_unicode_ci,
  `count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_request_logs`
--

LOCK TABLES `ti_request_logs` WRITE;
/*!40000 ALTER TABLE `ti_request_logs` DISABLE KEYS */;
INSERT INTO `ti_request_logs` VALUES (1,'http://127.0.0.1:8001/favicon.ico',404,'[\"http:\\/\\/127.0.0.1:8001\\/uganda-preview.html\"]',1,'2026-01-19 12:26:46','2026-01-19 12:26:46'),(2,'http://127.0.0.1:8000/database',404,NULL,1,'2026-01-21 09:57:59','2026-01-21 09:57:59'),(3,'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json',404,NULL,5,'2026-01-21 12:04:13','2026-01-23 12:49:17'),(4,'http://localhost:8001/storage/media/attachments/public/pub/lic//69/public/697/0a1/0a0/6970a10a00a1c890265516.svg',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',2,'2026-01-21 12:24:59','2026-01-21 12:25:19'),(5,'http://localhost:8001/storage/media/attachments/public/697//0a/1/0/697/0a1/0a0/6970a10a00a1c890265516.svg',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',19,'2026-01-21 12:39:55','2026-01-21 14:13:21'),(6,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css',404,NULL,52,'2026-01-21 13:03:14','2026-01-22 12:12:02'),(7,'http://127.0.0.1:8001/.well-known/appspecific/com.chrome.devtools.json',404,NULL,6,'2026-01-21 13:11:00','2026-01-21 13:12:37'),(8,'http://127.0.0.1:8001',404,NULL,5,'2026-01-21 13:13:39','2026-01-21 13:24:52'),(9,'http://127.0.0.1:8000/default/menus?id=1d4ae44c-8a81-48cb-8f40-8890e953b0b4&vscodeBrowserReqId=1769001527280',404,NULL,1,'2026-01-21 13:18:47','2026-01-21 13:18:47'),(10,'http://127.0.0.1:8000/default/menus',404,NULL,5,'2026-01-21 13:19:06','2026-01-21 13:20:27'),(11,'http://127.0.0.1:8000/locations',404,NULL,1,'2026-01-21 13:20:37','2026-01-21 13:20:37'),(12,'http://127.0.0.1:8000',404,NULL,4,'2026-01-21 13:20:46','2026-01-22 12:09:42'),(13,'http://127.0.0.1:8000/ugaeats/menus',404,NULL,1,'2026-01-21 13:21:04','2026-01-21 13:21:04'),(14,'http://127.0.0.1:8000/admin]',404,NULL,1,'2026-01-22 07:57:22','2026-01-22 07:57:22'),(15,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',51,'2026-01-22 09:39:13','2026-01-22 12:12:02'),(16,'http://127.0.0.1:8000/storage/media/uploads/uploads/uganda-logo.svg',404,'[\"http:\\/\\/127.0.0.1:8000\\/admin\\/dashboard\"]',15,'2026-01-22 10:16:14','2026-01-22 10:23:30'),(17,'http://127.0.0.1:8000/favicon.ico',404,'[\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/attachments\\/public\\/697\\/0aa\\/453\\/6970aa45333e92857837684.jpg\"]',1,'2026-01-22 10:20:18','2026-01-22 10:20:18'),(18,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083531',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:05:31','2026-01-22 12:05:31'),(19,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083531',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:05:32','2026-01-22 12:05:32'),(20,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083537',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/menus\"]',1,'2026-01-22 12:05:37','2026-01-22 12:05:37'),(21,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083537',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/menus\"]',1,'2026-01-22 12:05:37','2026-01-22 12:05:37'),(22,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083539',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/reservation\"]',1,'2026-01-22 12:05:39','2026-01-22 12:05:39'),(23,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083539',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/reservation\"]',1,'2026-01-22 12:05:39','2026-01-22 12:05:39'),(24,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083541',404,'[\"http:\\/\\/127.0.0.1:8000\\/login\"]',1,'2026-01-22 12:05:41','2026-01-22 12:05:41'),(25,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083541',404,'[\"http:\\/\\/127.0.0.1:8000\\/login\"]',1,'2026-01-22 12:05:41','2026-01-22 12:05:41'),(26,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083543',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/menus\"]',1,'2026-01-22 12:05:43','2026-01-22 12:05:43'),(27,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083543',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/menus\"]',1,'2026-01-22 12:05:43','2026-01-22 12:05:43'),(28,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083547',404,'[\"http:\\/\\/127.0.0.1:8000\\/login\"]',1,'2026-01-22 12:05:47','2026-01-22 12:05:47'),(29,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083547',404,'[\"http:\\/\\/127.0.0.1:8000\\/login\"]',1,'2026-01-22 12:05:47','2026-01-22 12:05:47'),(30,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083548',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/reservation\"]',1,'2026-01-22 12:05:49','2026-01-22 12:05:49'),(31,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083548',404,'[\"http:\\/\\/127.0.0.1:8000\\/default\\/reservation\"]',1,'2026-01-22 12:05:49','2026-01-22 12:05:49'),(32,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083550',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:05:51','2026-01-22 12:05:51'),(33,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083550',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:05:51','2026-01-22 12:05:51'),(34,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083559',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:05:59','2026-01-22 12:05:59'),(35,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083559',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:06:00','2026-01-22 12:06:00'),(36,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083892',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:11:32','2026-01-22 12:11:32'),(37,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083892',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:11:33','2026-01-22 12:11:33'),(38,'http://127.0.0.1:8000/themes/demo/assets/css/uganda-custom.css?v=1769083921',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:12:01','2026-01-22 12:12:01'),(39,'http://127.0.0.1:8000/themes/demo/assets/js/uganda-enhancements.js?v=1769083921',404,'[\"http:\\/\\/127.0.0.1:8000\\/\"]',1,'2026-01-22 12:12:02','2026-01-22 12:12:02'),(40,'http://127.0.0.1:8000/default/admin',404,NULL,1,'2026-01-23 06:53:57','2026-01-23 06:53:57'),(41,'http://127.0.0.1:8000/terms',404,NULL,2,'2026-01-23 08:12:44','2026-01-23 08:13:20');
/*!40000 ALTER TABLE `ti_request_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_reservation_tables`
--

DROP TABLE IF EXISTS `ti_reservation_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_reservation_tables` (
  `reservation_id` int unsigned NOT NULL,
  `dining_table_id` bigint unsigned DEFAULT NULL,
  `table_id` int unsigned NOT NULL,
  UNIQUE KEY `reservation_dining_table_unique` (`reservation_id`,`dining_table_id`),
  KEY `reservation_id_index` (`reservation_id`),
  KEY `table_id_index` (`table_id`),
  KEY `idx_reservation_tables_res_table` (`reservation_id`,`dining_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_reservation_tables`
--

LOCK TABLES `ti_reservation_tables` WRITE;
/*!40000 ALTER TABLE `ti_reservation_tables` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_reservation_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_reservations`
--

DROP TABLE IF EXISTS `ti_reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_reservations` (
  `reservation_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `table_id` int NOT NULL,
  `guest_num` int NOT NULL,
  `occasion_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(96) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `reserve_time` time NOT NULL,
  `reserve_date` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `assignee_id` int DEFAULT NULL,
  `assignee_group_id` int unsigned DEFAULT NULL,
  `notify` tinyint(1) DEFAULT NULL,
  `ip_address` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` bigint unsigned DEFAULT NULL,
  `hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `processed` tinyint(1) DEFAULT NULL,
  `status_updated_at` datetime DEFAULT NULL,
  `assignee_updated_at` datetime DEFAULT NULL,
  `reserve_datetime` datetime GENERATED ALWAYS AS (addtime(`reserve_date`,`reserve_time`)) STORED,
  PRIMARY KEY (`reservation_id`),
  KEY `ti_reservations_location_id_table_id_index` (`location_id`,`table_id`),
  KEY `ti_reservations_hash_index` (`hash`),
  KEY `idx_reservations_datetime` (`reserve_datetime`),
  KEY `idx_reservations_time_filter` (`location_id`,`status_id`,`reserve_date`,`reserve_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_reservations`
--

LOCK TABLES `ti_reservations` WRITE;
/*!40000 ALTER TABLE `ti_reservations` DISABLE KEYS */;
INSERT INTO `ti_reservations` (`reservation_id`, `location_id`, `table_id`, `guest_num`, `occasion_id`, `customer_id`, `first_name`, `last_name`, `email`, `telephone`, `comment`, `reserve_time`, `reserve_date`, `created_at`, `updated_at`, `assignee_id`, `assignee_group_id`, `notify`, `ip_address`, `user_agent`, `status_id`, `hash`, `duration`, `processed`, `status_updated_at`, `assignee_updated_at`) VALUES (1,1,0,16,NULL,2,'Nuwahereza','Peter','atwines23@gmail.com','+256779081600',NULL,'00:30:00','2026-01-22','2026-01-19 21:00:00','2026-01-20 21:00:00',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',6,'fa473f095cf126c9c8e8c4bae15daf92',0,NULL,'2026-01-21 12:16:55',NULL);
/*!40000 ALTER TABLE `ti_reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_settings`
--

DROP TABLE IF EXISTS `ti_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_settings` (
  `setting_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sort` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `serialized` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `ti_settings_sort_item_unique` (`sort`,`item`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_settings`
--

LOCK TABLES `ti_settings` WRITE;
/*!40000 ALTER TABLE `ti_settings` DISABLE KEYS */;
INSERT INTO `ti_settings` VALUES (1,'config','site_logo','tastyigniter-logo-only.svg',NULL),(2,'config','timezone','Africa/Kampala',NULL),(3,'config','detect_language','1',NULL),(4,'prefs','supported_languages','a:2:{i:0;s:2:\"en\";i:1;s:5:\"lg_UG\";}',NULL),(5,'config','allow_registration','1',NULL),(6,'config','customer_group_id','11',NULL),(7,'config','registration_email','a:2:{i:0;s:8:\"customer\";i:1;s:5:\"admin\";}',NULL),(8,'config','order_email','a:2:{i:0;s:8:\"customer\";i:1;s:5:\"admin\";}',NULL),(9,'config','reservation_email','a:2:{i:0;s:8:\"customer\";i:1;s:5:\"admin\";}',NULL),(10,'config','maps_api_key','',NULL),(11,'config','distance_unit','km',NULL),(12,'config','location_order','0',NULL),(13,'config','location_order_email','0',NULL),(14,'config','location_reserve_email','0',NULL),(15,'config','default_order_status','1',NULL),(16,'config','processing_order_status','a:5:{i:0;s:1:\"5\";i:1;s:1:\"4\";i:2;s:1:\"2\";i:3;s:1:\"3\";i:4;s:1:\"1\";}',NULL),(17,'config','completed_order_status','a:3:{i:0;s:1:\"5\";i:1;s:1:\"4\";i:2;s:1:\"1\";}',NULL),(18,'config','guest_order','0',NULL),(19,'config','default_reservation_status','8',NULL),(20,'config','confirmed_reservation_status','6',NULL),(21,'config','canceled_order_status','1',NULL),(22,'config','canceled_reservation_status','7',NULL),(23,'config','tax_mode','1',NULL),(24,'config','invoice_prefix','INV-{year}-00',NULL),(25,'config','protocol','log',NULL),(26,'config','smtp_host','smtp.mailgun.org',NULL),(27,'config','smtp_port','587',NULL),(28,'config','smtp_user',NULL,NULL),(29,'config','smtp_pass',NULL,NULL),(30,'config','log_threshold','1',NULL),(31,'config','permalink','1',NULL),(32,'config','maintenance_mode','1',NULL),(33,'config','maintenance_message','Site is under maintenance. Please check back later.',NULL),(34,'config','cache_mode','0',NULL),(35,'config','cache_time','0',NULL),(36,'prefs','default_themes','a:1:{s:4:\"main\";s:9:\"ti-orange\";}',NULL),(37,'prefs','ti_setup','installed',NULL),(38,'config','site_name','UgaEats',NULL),(39,'config','site_email','nuwaherezapeter34@gmail.com',NULL),(40,'config','sender_name','UgaEats',NULL),(41,'config','sender_email','nuwaherezapeter34@gmail.com',NULL),(42,'config','default_geocoder','nominatim',NULL),(43,'config','currency_converter','a:4:{s:3:\"api\";s:17:\"openexchangerates\";s:3:\"oer\";a:1:{s:6:\"apiKey\";N;}s:7:\"fixerio\";a:1:{s:6:\"apiKey\";N;}s:15:\"refreshInterval\";s:2:\"24\";}',NULL),(50,'config','invoice_logo','favicon.svg',NULL),(51,'config','enable_status_workflow','1',NULL),(52,'config','accepted_order_status','1',NULL),(53,'config','limit_users',NULL,NULL),(54,'config','tax_percentage','3',NULL),(55,'config','tax_menu_price','1',NULL),(56,'config','tax_delivery_charge','1',NULL),(57,'config','rejected_reasons','a:1:{i:1;a:3:{s:4:\"code\";s:3:\"101\";s:7:\"comment\";s:21:\"out of deleivery area\";s:9:\"status_id\";s:1:\"9\";}}',NULL),(58,'config','delay_times','a:1:{i:1;a:2:{s:4:\"time\";s:2:\"30\";s:7:\"comment\";s:21:\"order process expired\";}}',NULL),(86,'config','mail_logo','favicon.svg',NULL),(87,'config','smtp_encryption','tls',NULL),(88,'config','mailgun_domain',NULL,NULL),(89,'config','mailgun_secret',NULL,NULL),(90,'config','postmark_token',NULL,NULL),(91,'config','ses_key',NULL,NULL),(92,'config','ses_secret',NULL,NULL),(93,'config','ses_region',NULL,NULL),(101,'prefs','test_mail_sent','1',NULL),(156,'config','enable_request_log','1',NULL),(157,'config','activity_log_timeout','60',NULL),(215,'0','default_location_id','s:1:\"1\";',NULL);
/*!40000 ALTER TABLE `ti_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_status_history`
--

DROP TABLE IF EXISTS `ti_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_status_history` (
  `status_history_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int NOT NULL,
  `object_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `status_id` int NOT NULL,
  `notify` tinyint(1) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`status_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_status_history`
--

LOCK TABLES `ti_status_history` WRITE;
/*!40000 ALTER TABLE `ti_status_history` DISABLE KEYS */;
INSERT INTO `ti_status_history` VALUES (1,1,'reservations',NULL,8,0,'Your table reservation is pending.','2026-01-20 13:11:07','2026-01-20 13:11:07'),(2,1,'reservations',NULL,6,0,'Your table reservation has been confirmed.','2026-01-21 09:16:55','2026-01-21 09:16:55'),(3,1,'orders',NULL,1,0,'Your order has been received.','2026-01-21 09:54:49','2026-01-21 09:54:49'),(4,1,'orders',NULL,5,0,'','2026-01-21 09:56:09','2026-01-21 09:56:09'),(5,1,'orders',NULL,1,1,'Your order has been received.','2026-01-23 07:20:40','2026-01-23 07:20:40'),(6,1,'orders',NULL,5,0,'','2026-01-23 07:20:45','2026-01-23 07:20:45');
/*!40000 ALTER TABLE `ti_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_statuses`
--

DROP TABLE IF EXISTS `ti_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_statuses` (
  `status_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_comment` text COLLATE utf8mb4_unicode_ci,
  `notify_customer` tinyint(1) DEFAULT NULL,
  `status_for` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_statuses`
--

LOCK TABLES `ti_statuses` WRITE;
/*!40000 ALTER TABLE `ti_statuses` DISABLE KEYS */;
INSERT INTO `ti_statuses` VALUES (1,'Received','Your order has been received.',1,'order','#686663','2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Pending','Your order is pending',1,'order','#f0ad4e','2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Preparation','Your order is in the kitchen',1,'order','#00c0ef','2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Delivery','Your order will be with you shortly.',0,'order','#00a65a','2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,'Completed','',0,'order','#00a65a','2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,'Confirmed','Your table reservation has been confirmed.',0,'reservation','#00a65a','2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,'Canceled','Your table reservation has been canceled.',1,'reservation','#dd4b39','2026-01-19 08:07:51','2026-01-21 09:39:39'),(8,'Pending','Your table reservation is pending.',1,'reservation','','2026-01-19 08:07:51','2026-01-19 08:07:51'),(9,'Canceled','Your order has been cancelled, please contact customer care for assistance',1,'order','#c0392b','2026-01-19 08:07:51','2026-01-21 09:39:14');
/*!40000 ALTER TABLE `ti_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_stock_history`
--

DROP TABLE IF EXISTS `ti_stock_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_stock_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ti_stock_history_stock_id_foreign` (`stock_id`),
  KEY `ti_stock_history_order_id_foreign` (`order_id`),
  CONSTRAINT `ti_stock_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `ti_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ti_stock_history_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `ti_stocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_stock_history`
--

LOCK TABLES `ti_stock_history` WRITE;
/*!40000 ALTER TABLE `ti_stock_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_stock_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_stocks`
--

DROP TABLE IF EXISTS `ti_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `stockable_id` bigint unsigned NOT NULL,
  `stockable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` bigint DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '0',
  `low_stock_threshold` int NOT NULL DEFAULT '0',
  `is_tracked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `low_stock_alert_sent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_stocks_type_id` (`stockable_type`,`stockable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_stocks`
--

LOCK TABLES `ti_stocks` WRITE;
/*!40000 ALTER TABLE `ti_stocks` DISABLE KEYS */;
INSERT INTO `ti_stocks` VALUES (1,1,1,'menus',NULL,0,0,0,'2026-01-21 09:54:49','2026-01-21 09:54:49',0),(2,1,9,'menu_option_values',NULL,0,0,0,'2026-01-21 09:54:49','2026-01-21 09:54:49',0),(3,1,10,'menu_option_values',NULL,0,0,0,'2026-01-21 09:54:49','2026-01-21 09:54:49',0),(4,1,12,'menus',NULL,0,0,0,'2026-01-21 10:13:51','2026-01-21 10:13:51',0);
/*!40000 ALTER TABLE `ti_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_tables`
--

DROP TABLE IF EXISTS `ti_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_tables` (
  `table_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_capacity` int NOT NULL,
  `max_capacity` int NOT NULL,
  `table_status` tinyint(1) NOT NULL,
  `extra_capacity` int NOT NULL DEFAULT '0',
  `is_joinable` tinyint(1) NOT NULL DEFAULT '1',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_tables`
--

LOCK TABLES `ti_tables` WRITE;
/*!40000 ALTER TABLE `ti_tables` DISABLE KEYS */;
INSERT INTO `ti_tables` VALUES (1,'Table 1',3,11,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(2,'Table 2',5,8,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(3,'Table 3',3,11,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(4,'Table 4',3,12,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(5,'Table 5',5,8,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(6,'Table 6',3,11,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(7,'Table 7',4,8,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(8,'Table 8',3,8,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(9,'Table 9',2,11,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(10,'Table 10',5,11,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(11,'Table 11',4,7,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(12,'Table 12',3,9,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(13,'Table 13',2,7,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51'),(14,'Table 14',2,6,1,0,1,0,'2026-01-19 08:07:51','2026-01-19 08:07:51');
/*!40000 ALTER TABLE `ti_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_themes`
--

DROP TABLE IF EXISTS `ti_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_themes` (
  `theme_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0.0.1',
  `data` json NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`theme_id`),
  UNIQUE KEY `ti_themes_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_themes`
--

LOCK TABLES `ti_themes` WRITE;
/*!40000 ALTER TABLE `ti_themes` DISABLE KEYS */;
INSERT INTO `ti_themes` VALUES (1,'Orange Theme','igniter-orange','Free Modern, Responsive and Clean TastyIgniter Theme based on Livewire and Bootstrap.','v4.1.3','[]',1,1,NULL,'2026-01-23 08:29:25');
/*!40000 ALTER TABLE `ti_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_users`
--

DROP TABLE IF EXISTS `ti_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ti_users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_users`
--

LOCK TABLES `ti_users` WRITE;
/*!40000 ALTER TABLE `ti_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `ti_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ti_working_hours`
--

DROP TABLE IF EXISTS `ti_working_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ti_working_hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weekday` int NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ti_working_hours`
--

LOCK TABLES `ti_working_hours` WRITE;
/*!40000 ALTER TABLE `ti_working_hours` DISABLE KEYS */;
INSERT INTO `ti_working_hours` VALUES (8,1,'delivery',0,'00:00:00','23:59:00',1,NULL,NULL),(9,1,'delivery',1,'00:00:00','23:59:00',1,NULL,NULL),(10,1,'delivery',2,'00:00:00','23:59:00',1,NULL,NULL),(11,1,'delivery',3,'00:00:00','23:59:00',1,NULL,NULL),(12,1,'delivery',4,'00:00:00','23:59:00',1,NULL,NULL),(13,1,'delivery',5,'00:00:00','23:59:00',1,NULL,NULL),(14,1,'delivery',6,'00:00:00','23:59:00',1,NULL,NULL),(15,1,'collection',0,'00:00:00','23:59:00',1,NULL,NULL),(16,1,'collection',1,'00:00:00','23:59:00',1,NULL,NULL),(17,1,'collection',2,'00:00:00','23:59:00',1,NULL,NULL),(18,1,'collection',3,'00:00:00','23:59:00',1,NULL,NULL),(19,1,'collection',4,'00:00:00','23:59:00',1,NULL,NULL),(20,1,'collection',5,'00:00:00','23:59:00',1,NULL,NULL),(21,1,'collection',6,'00:00:00','23:59:00',1,NULL,NULL),(22,1,'opening',0,'00:00:00','23:59:00',1,NULL,NULL),(23,1,'opening',1,'00:00:00','23:59:00',1,NULL,NULL),(24,1,'opening',2,'00:00:00','23:59:00',1,NULL,NULL),(25,1,'opening',3,'00:00:00','23:59:00',1,NULL,NULL),(26,1,'opening',4,'00:00:00','23:59:00',1,NULL,NULL),(27,1,'opening',5,'00:00:00','23:59:00',1,NULL,NULL),(28,1,'opening',6,'00:00:00','23:59:00',1,NULL,NULL),(29,2,'opening',0,'00:00:00','23:59:00',1,NULL,NULL),(30,2,'opening',1,'00:00:00','23:59:00',1,NULL,NULL),(31,2,'opening',2,'00:00:00','23:59:00',1,NULL,NULL),(32,2,'opening',3,'00:00:00','23:59:00',1,NULL,NULL),(33,2,'opening',4,'00:00:00','23:59:00',1,NULL,NULL),(34,2,'opening',5,'00:00:00','23:59:00',1,NULL,NULL),(35,2,'opening',6,'00:00:00','23:59:00',1,NULL,NULL),(36,2,'delivery',0,'00:00:00','23:59:00',1,NULL,NULL),(37,2,'delivery',1,'00:00:00','23:59:00',1,NULL,NULL),(38,2,'delivery',2,'00:00:00','23:59:00',1,NULL,NULL),(39,2,'delivery',3,'00:00:00','23:59:00',1,NULL,NULL),(40,2,'delivery',4,'00:00:00','23:59:00',1,NULL,NULL),(41,2,'delivery',5,'00:00:00','23:59:00',1,NULL,NULL),(42,2,'delivery',6,'00:00:00','23:59:00',1,NULL,NULL),(43,2,'collection',0,'00:00:00','23:59:00',1,NULL,NULL),(44,2,'collection',1,'00:00:00','23:59:00',1,NULL,NULL),(45,2,'collection',2,'00:00:00','23:59:00',1,NULL,NULL),(46,2,'collection',3,'00:00:00','23:59:00',1,NULL,NULL),(47,2,'collection',4,'00:00:00','23:59:00',1,NULL,NULL),(48,2,'collection',5,'00:00:00','23:59:00',1,NULL,NULL),(49,2,'collection',6,'00:00:00','23:59:00',1,NULL,NULL),(50,3,'opening',0,'00:00:00','23:59:00',1,NULL,NULL),(51,3,'opening',1,'00:00:00','23:59:00',1,NULL,NULL),(52,3,'opening',2,'00:00:00','23:59:00',1,NULL,NULL),(53,3,'opening',3,'00:00:00','23:59:00',1,NULL,NULL),(54,3,'opening',4,'00:00:00','23:59:00',1,NULL,NULL),(55,3,'opening',5,'00:00:00','23:59:00',1,NULL,NULL),(56,3,'opening',6,'00:00:00','23:59:00',1,NULL,NULL),(57,3,'delivery',0,'00:00:00','23:59:00',1,NULL,NULL),(58,3,'delivery',1,'00:00:00','23:59:00',1,NULL,NULL),(59,3,'delivery',2,'00:00:00','23:59:00',1,NULL,NULL),(60,3,'delivery',3,'00:00:00','23:59:00',1,NULL,NULL),(61,3,'delivery',4,'00:00:00','23:59:00',1,NULL,NULL),(62,3,'delivery',5,'00:00:00','23:59:00',1,NULL,NULL),(63,3,'delivery',6,'00:00:00','23:59:00',1,NULL,NULL),(64,3,'collection',0,'00:00:00','23:59:00',1,NULL,NULL),(65,3,'collection',1,'00:00:00','23:59:00',1,NULL,NULL),(66,3,'collection',2,'00:00:00','23:59:00',1,NULL,NULL),(67,3,'collection',3,'00:00:00','23:59:00',1,NULL,NULL),(68,3,'collection',4,'00:00:00','23:59:00',1,NULL,NULL),(69,3,'collection',5,'00:00:00','23:59:00',1,NULL,NULL),(70,3,'collection',6,'00:00:00','23:59:00',1,NULL,NULL);
/*!40000 ALTER TABLE `ti_working_hours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-24 16:06:35
