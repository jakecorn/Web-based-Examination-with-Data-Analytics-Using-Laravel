-- MySQL dump 10.16  Distrib 10.1.25-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: lmsDB4
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB

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
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sy` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_record_announcements`
--

DROP TABLE IF EXISTS `class_record_announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_record_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` int(11) NOT NULL,
  `class_record_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_record_announcements`
--

LOCK TABLES `class_record_announcements` WRITE;
/*!40000 ALTER TABLE `class_record_announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_record_announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_record_exams`
--

DROP TABLE IF EXISTS `class_record_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_record_exams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_record_id` int(11) NOT NULL,
  `examination_id` int(11) NOT NULL,
  `visibility` tinyint(1) DEFAULT '0',
  `lock_exam` tinyint(1) DEFAULT '0',
  `done_checking` tinyint(1) NOT NULL,
  `pause_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `custom_long_exam_total_score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_record_exams`
--

LOCK TABLES `class_record_exams` WRITE;
/*!40000 ALTER TABLE `class_record_exams` DISABLE KEYS */;
INSERT INTO `class_record_exams` VALUES (1,1,1,1,0,0,NULL,'2020-06-02 05:40:07','2020-06-02 05:40:07',0),(2,1,2,1,0,0,NULL,'2020-06-02 06:14:10','2020-06-02 06:14:10',0),(3,3,3,0,0,0,NULL,'2020-06-02 12:53:51','2020-06-02 12:53:51',0),(4,5,4,0,0,0,NULL,'2020-06-02 12:59:54','2020-06-02 12:59:54',0),(5,1,5,1,0,0,NULL,'2020-06-03 06:41:12','2020-06-03 06:41:12',0),(6,1,6,1,0,0,NULL,'2020-06-09 15:17:36','2020-06-09 15:17:36',0),(7,7,7,1,0,0,NULL,'2020-07-02 02:35:37','2020-07-02 02:35:37',0),(8,7,8,1,0,0,NULL,'2020-07-02 15:06:37','2020-07-02 15:06:37',0),(9,8,9,1,0,0,NULL,'2020-07-02 15:48:25','2020-07-02 15:48:25',0),(10,9,10,0,0,0,NULL,'2020-07-14 14:33:23','2020-07-14 14:33:23',0);
/*!40000 ALTER TABLE `class_record_exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_record_files`
--

DROP TABLE IF EXISTS `class_record_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_record_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `class_record_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_record_files`
--

LOCK TABLES `class_record_files` WRITE;
/*!40000 ALTER TABLE `class_record_files` DISABLE KEYS */;
INSERT INTO `class_record_files` VALUES (1,1,1),(2,2,1),(3,3,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1),(9,9,7);
