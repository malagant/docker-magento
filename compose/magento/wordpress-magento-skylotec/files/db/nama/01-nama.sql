-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: nama
-- ------------------------------------------------------
-- Server version	5.5.38-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `conskylotec_images`
--

DROP TABLE IF EXISTS `conskylotec_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conskylotec_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modified` int(11) NOT NULL,
  `imageName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conskylotec_images`
--

LOCK TABLES `conskylotec_images` WRITE;
/*!40000 ALTER TABLE `conskylotec_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `conskylotec_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `constats_jobs`
--

DROP TABLE IF EXISTS `constats_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constats_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `started` datetime NOT NULL,
  `finished` datetime NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `constats_jobs`
--

LOCK TABLES `constats_jobs` WRITE;
/*!40000 ALTER TABLE `constats_jobs` DISABLE KEYS */;
INSERT INTO `constats_jobs` VALUES (1,1449,'2014-11-09 15:51:13','2014-11-09 15:52:47','update'),(2,20,'2014-11-15 14:52:57','2014-11-15 14:52:57','update'),(3,20,'2014-12-15 14:53:24','2014-12-15 14:53:47','update'),(4,20,'2014-12-15 14:54:20','2014-12-15 14:54:43','update'),(5,20,'2014-12-15 14:56:34','2014-12-15 14:56:58','update'),(6,20,'2014-12-15 14:57:51','2014-12-15 14:58:15','update'),(7,1,'2014-12-16 10:52:40','2014-12-16 10:53:15','delete'),(8,1442,'2014-12-16 10:53:41','2014-12-16 11:09:00','update'),(9,7,'2014-12-16 11:09:39','2014-12-16 11:11:12','create'),(10,5133,'2014-12-16 11:11:40','2014-12-16 11:13:34','language'),(11,1,'2014-12-16 11:35:22','2014-12-16 11:35:32','delete'),(12,1,'2014-12-16 11:43:02','2014-12-16 11:43:03','delete'),(13,1,'2014-12-16 11:45:28','2014-12-16 11:45:28','delete'),(14,1449,'2014-12-16 11:45:28','2014-12-16 11:56:23','update'),(15,0,'2014-12-16 11:56:23','2014-12-16 11:56:23','create'),(16,5133,'2014-12-16 11:56:23','2014-12-16 11:57:05','language'),(17,1,'2014-12-16 12:28:28','2014-12-16 12:28:28','delete'),(18,1449,'2014-12-16 12:28:28','2014-12-16 12:37:12','update'),(19,0,'2014-12-16 12:37:12','2014-12-16 12:37:12','create'),(20,5133,'2014-12-16 12:37:12','2014-12-16 12:37:48','language'),(21,1,'2014-12-16 13:03:45','2014-12-16 13:03:46','delete'),(22,1,'2014-12-16 13:10:01','2014-12-16 13:10:02','delete'),(23,1,'2014-12-16 13:12:04','2014-12-16 13:12:04','delete'),(24,1449,'2014-12-16 13:12:04','2014-12-16 13:17:11','update'),(25,0,'2014-12-16 13:17:11','2014-12-16 13:17:11','create'),(26,5133,'2014-12-16 13:17:11','2014-12-16 13:18:31','language'),(27,1,'2014-12-16 13:25:56','2014-12-16 13:25:56','delete'),(28,1,'2014-12-16 13:31:29','2014-12-16 13:31:30','delete'),(29,1,'2014-12-16 13:34:24','2014-12-16 13:34:25','delete'),(30,1449,'2014-12-16 13:34:25','2014-12-16 13:43:05','update'),(31,0,'2014-12-16 13:43:05','2014-12-16 13:43:05','create'),(32,5133,'2014-12-16 13:43:05','2014-12-16 13:43:47','language'),(33,1,'2014-12-16 14:53:33','2014-12-16 14:53:33','delete'),(34,1449,'2014-12-16 14:53:34','2014-12-16 15:02:56','update'),(35,0,'2014-12-16 15:02:56','2014-12-16 15:02:56','create'),(36,5133,'2014-12-16 15:02:56','2014-12-16 15:04:06','language'),(37,1,'2014-12-16 15:47:55','2014-12-16 15:47:55','delete'),(38,1,'2014-12-16 15:48:53','2014-12-16 15:48:54','create'),(39,5133,'2014-12-16 15:48:54','2014-12-16 15:49:32','language'),(40,1,'2014-12-17 08:23:49','2014-12-17 08:23:49','update'),(41,1,'2014-12-17 08:32:47','2014-12-17 08:32:48','images'),(42,1,'2014-12-17 08:34:46','2014-12-17 08:34:46','delete'),(43,1449,'2014-12-17 08:34:46','2014-12-17 08:43:23','update'),(44,1,'2014-12-17 08:43:23','2014-12-17 08:43:24','create'),(45,5137,'2014-12-17 08:43:24','2014-12-17 08:44:02','language'),(46,1,'2014-12-17 08:51:24','2014-12-17 08:51:25','images'),(47,1,'2014-12-17 08:52:16','2014-12-17 08:52:17','images'),(48,1,'2014-12-17 09:19:17','2014-12-17 09:19:17','delete'),(49,1,'2014-12-17 09:23:03','2014-12-17 09:23:03','delete'),(50,1450,'2014-12-17 09:23:03','2014-12-17 09:32:04','update'),(51,0,'2014-12-17 09:32:04','2014-12-17 09:32:05','create'),(52,1,'2014-12-17 09:42:51','2014-12-17 09:42:51','delete'),(53,1,'2014-12-17 09:54:20','2014-12-17 09:54:20','delete'),(54,1450,'2014-12-17 09:54:20','2014-12-17 09:54:24','update'),(55,0,'2014-12-17 09:54:24','2014-12-17 09:54:24','create'),(56,1,'2014-12-17 09:55:34','2014-12-17 09:55:34','delete'),(57,1450,'2014-12-17 09:55:34','2014-12-17 10:05:53','update'),(58,0,'2014-12-17 10:05:53','2014-12-17 10:05:53','create'),(59,5137,'2014-12-17 10:05:53','2014-12-17 10:06:30','language'),(60,1235,'2014-12-17 11:05:41','2014-12-17 11:05:56','stock'),(61,1235,'2014-12-17 11:38:22','2014-12-17 11:38:33','stock'),(62,1,'2014-12-17 13:46:36','2014-12-17 13:46:36','delete'),(63,1450,'2014-12-17 13:46:36','2014-12-17 13:58:08','update'),(64,0,'2014-12-17 13:58:08','2014-12-17 13:58:08','create'),(65,5137,'2014-12-17 13:58:08','2014-12-17 13:58:53','language'),(66,0,'2014-12-17 14:32:26','2014-12-17 14:32:26','stock'),(67,0,'2014-12-17 14:33:14','2014-12-17 14:33:14','stock'),(68,1,'2014-12-19 15:21:27','2014-12-19 15:21:27','delete'),(69,1,'2014-12-19 15:24:37','2014-12-19 15:24:37','delete'),(70,1,'2015-01-26 14:22:15','2015-01-26 14:22:15','delete'),(71,1,'2015-01-26 14:23:48','2015-01-26 14:23:48','delete'),(72,1438,'2015-01-26 14:23:48','2015-01-26 14:37:54','update'),(73,1,'2015-01-26 14:37:54','2015-01-26 14:37:54','create'),(74,1,'2015-01-26 14:39:17','2015-01-26 14:39:17','delete'),(75,1,'2015-01-26 14:45:56','2015-01-26 14:45:56','delete'),(76,1,'2015-01-26 14:53:43','2015-01-26 14:53:43','delete'),(77,1,'2015-01-26 14:54:16','2015-01-26 14:54:16','delete'),(78,1438,'2015-01-26 14:54:16','2015-01-26 15:05:00','update'),(79,1,'2015-01-26 15:05:00','2015-01-26 15:05:00','create'),(80,5093,'2015-01-26 15:05:00','2015-01-26 15:05:42','language'),(81,1,'2015-01-27 11:04:17','2015-01-27 11:04:17','delete'),(82,1438,'2015-01-27 11:04:17','2015-01-27 11:17:25','update'),(83,0,'2015-01-27 11:17:26','2015-01-27 11:17:26','create'),(84,5089,'2015-01-27 11:17:26','2015-01-27 11:18:08','language'),(85,1,'2015-01-27 11:19:59','2015-01-27 11:19:59','delete'),(86,1438,'2015-01-27 11:19:59','2015-01-27 11:32:57','update'),(87,0,'2015-01-27 11:32:57','2015-01-27 11:32:57','create'),(88,5089,'2015-01-27 11:32:57','2015-01-27 11:33:40','language');
/*!40000 ALTER TABLE `constats_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `constats_products`
--

DROP TABLE IF EXISTS `constats_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constats_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `sku` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `toGo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `constats_products`
--

LOCK TABLES `constats_products` WRITE;
/*!40000 ALTER TABLE `constats_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `constats_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conuser_roles`
--

DROP TABLE IF EXISTS `conuser_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conuser_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `roleId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_535F03BCB8C2FD88` (`roleId`),
  KEY `IDX_535F03BC727ACA70` (`parent_id`),
  CONSTRAINT `FK_535F03BC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `conuser_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conuser_roles`
--

LOCK TABLES `conuser_roles` WRITE;
/*!40000 ALTER TABLE `conuser_roles` DISABLE KEYS */;
INSERT INTO `conuser_roles` VALUES (1,NULL,'guest'),(2,1,'user'),(3,2,'admin');
/*!40000 ALTER TABLE `conuser_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conuser_user_role`
--

DROP TABLE IF EXISTS `conuser_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conuser_user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_DC5789CA76ED395` (`user_id`),
  KEY `IDX_DC5789CD60322AC` (`role_id`),
  CONSTRAINT `FK_DC5789CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `conuser_users` (`id`),
  CONSTRAINT `FK_DC5789CD60322AC` FOREIGN KEY (`role_id`) REFERENCES `conuser_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conuser_user_role`
--

LOCK TABLES `conuser_user_role` WRITE;
/*!40000 ALTER TABLE `conuser_user_role` DISABLE KEYS */;
INSERT INTO `conuser_user_role` VALUES (3,3);
/*!40000 ALTER TABLE `conuser_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conuser_users`
--

DROP TABLE IF EXISTS `conuser_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conuser_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `displayName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F1E28892E7927C74` (`email`),
  UNIQUE KEY `UNIQ_F1E28892F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conuser_users`
--

LOCK TABLES `conuser_users` WRITE;
/*!40000 ALTER TABLE `conuser_users` DISABLE KEYS */;
INSERT INTO `conuser_users` VALUES (3,NULL,'shopmaster@conlabz.com',NULL,'$2y$14$c5MysBQnUPSnaLNWFWDQ.eN9BgrNS/1o.B38eAAqE4iE7.DMrEeFu');
/*!40000 ALTER TABLE `conuser_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-28  3:23:40
