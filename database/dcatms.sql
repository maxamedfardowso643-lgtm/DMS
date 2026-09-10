-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dcatms
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'updated',NULL,NULL,'Clinic settings updated','[]','127.0.0.1','2026-09-09 15:15:20','2026-09-09 15:15:20'),(2,1,'updated',NULL,NULL,'Clinic settings updated','[]','127.0.0.1','2026-09-09 15:15:43','2026-09-09 15:15:43'),(3,1,'created','App\\Models\\Patient',41,'Patient FU\'AAD SAID MATTAN registered','[]','127.0.0.1','2026-09-09 15:24:40','2026-09-09 15:24:40'),(4,1,'created','App\\Models\\Invoice',36,'Invoice INV-2026-00036 created','[]','127.0.0.1','2026-09-09 15:32:41','2026-09-09 15:32:41'),(5,1,'created','App\\Models\\Payment',28,'Payment PAY-2026-00036 recorded for invoice INV-2026-00036','[]','127.0.0.1','2026-09-09 15:38:48','2026-09-09 15:38:48'),(6,1,'created','App\\Models\\InventoryItem',9,'Inventory item PARACTAMOL added','[]','127.0.0.1','2026-09-09 15:49:34','2026-09-09 15:49:34'),(7,1,'created','App\\Models\\Dentist',4,'Dentist Ali  Ahmed added','[]','127.0.0.1','2026-09-09 15:52:14','2026-09-09 15:52:14'),(8,1,'created','App\\Models\\Appointment',76,'Appointment APT-2026-00076 booked','[]','127.0.0.1','2026-09-09 15:55:56','2026-09-09 15:55:56'),(9,1,'status_changed','App\\Models\\Appointment',76,'Appointment APT-2026-00076 status: booked -> completed','[]','127.0.0.1','2026-09-09 16:03:03','2026-09-09 16:03:03'),(10,1,'updated','App\\Models\\Patient',41,'Dental chart updated for FU\'AAD SAID MATTAN','[]','127.0.0.1','2026-09-09 16:03:50','2026-09-09 16:03:50'),(11,8,'created','App\\Models\\Treatment',1,'Treatment recorded for FU\'AAD SAID MATTAN','[]','127.0.0.1','2026-09-09 16:05:01','2026-09-09 16:05:01'),(12,1,'created','App\\Models\\User',9,'User Fardowso created','[]','127.0.0.1','2026-09-09 16:21:01','2026-09-09 16:21:01'),(13,9,'created','App\\Models\\Patient',42,'Patient fahiimo mahmed adam registered','[]','127.0.0.1','2026-09-09 17:33:38','2026-09-09 17:33:38'),(14,9,'updated','App\\Models\\Patient',42,'Patient fahiimo mahmed adam updated','[]','127.0.0.1','2026-09-09 17:42:45','2026-09-09 17:42:45');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointment_requests`
--

DROP TABLE IF EXISTS `appointment_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointment_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','contacted','converted','dismissed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_requests_service_id_foreign` (`service_id`),
  CONSTRAINT `appointment_requests_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_requests`
--

LOCK TABLES `appointment_requests` WRITE;
/*!40000 ALTER TABLE `appointment_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointment_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointment_status_logs`
--

DROP TABLE IF EXISTS `appointment_status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointment_status_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint(20) unsigned NOT NULL,
  `from_status` varchar(255) DEFAULT NULL,
  `to_status` varchar(255) NOT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_status_logs_changed_by_foreign` (`changed_by`),
  KEY `appointment_status_logs_appointment_id_index` (`appointment_id`),
  CONSTRAINT `appointment_status_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_status_logs`
--

LOCK TABLES `appointment_status_logs` WRITE;
/*!40000 ALTER TABLE `appointment_status_logs` DISABLE KEYS */;
INSERT INTO `appointment_status_logs` VALUES (1,1,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(2,1,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(3,2,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(4,2,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(5,3,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(6,3,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(7,4,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(8,4,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(9,5,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(10,5,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(11,6,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(12,6,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(13,7,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(14,7,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(15,8,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(16,8,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(17,9,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(18,9,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(19,10,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(20,10,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(21,11,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(22,11,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(23,12,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(24,12,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(25,13,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(26,13,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(27,14,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(28,14,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(29,15,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(30,15,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(31,16,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(32,16,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(33,17,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(34,17,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(35,18,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(36,18,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(37,19,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(38,19,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(39,20,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(40,21,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(41,21,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(42,22,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(43,22,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(44,23,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(45,23,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(46,24,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(47,24,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(48,25,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(49,25,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(50,26,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(51,26,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(52,27,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(53,28,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(54,28,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(55,29,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(56,29,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(57,30,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(58,30,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(59,31,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(60,31,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(61,32,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(62,32,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(63,33,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(64,33,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(65,34,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(66,34,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(67,35,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(68,35,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(69,36,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(70,37,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(71,37,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(72,38,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(73,38,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(74,39,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(75,39,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(76,40,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(77,40,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(78,41,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(79,41,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(80,42,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:07','2026-09-09 15:08:07'),(81,42,'booked','completed',NULL,'Status updated','2026-09-09 15:08:07','2026-09-09 15:08:07'),(82,43,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(83,43,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(84,44,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(85,44,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(86,45,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(87,45,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(88,46,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(89,46,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(90,47,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(91,47,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(92,48,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(93,48,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(94,49,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(95,49,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(96,50,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(97,50,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(98,51,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(99,51,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(100,52,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(101,52,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(102,53,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(103,53,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(104,54,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(105,54,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(106,55,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(107,55,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(108,56,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(109,56,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(110,57,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(111,57,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(112,58,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(113,58,'booked','confirmed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(114,59,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(115,59,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(116,60,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(117,60,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(118,61,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(119,61,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(120,62,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(121,62,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(122,63,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(123,63,'booked','cancelled',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(124,64,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(125,64,'booked','completed',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(126,65,NULL,'booked',NULL,'Appointment created','2026-09-09 15:08:08','2026-09-09 15:08:08'),(127,65,'booked','no_show',NULL,'Status updated','2026-09-09 15:08:08','2026-09-09 15:08:08'),(128,66,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(129,67,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(130,68,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(131,69,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(132,70,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(133,71,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(134,72,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(135,73,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(136,74,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(137,75,NULL,'booked',NULL,'Appointment created','2026-09-09 15:18:30','2026-09-09 15:18:30'),(138,76,NULL,'booked',1,'Appointment created','2026-09-09 15:55:56','2026-09-09 15:55:56'),(139,76,'booked','completed',1,'Appointment completed','2026-09-09 16:03:03','2026-09-09 16:03:03');
/*!40000 ALTER TABLE `appointment_status_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_no` varchar(255) NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `source` enum('scheduled','walk_in') NOT NULL DEFAULT 'scheduled',
  `status` enum('booked','confirmed','checked_in','in_progress','completed','cancelled','no_show') NOT NULL DEFAULT 'booked',
  `notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointments_appointment_no_unique` (`appointment_no`),
  KEY `appointments_service_id_foreign` (`service_id`),
  KEY `appointments_created_by_foreign` (`created_by`),
  KEY `appointments_dentist_id_appointment_date_start_time_index` (`dentist_id`,`appointment_date`,`start_time`),
  KEY `appointments_patient_id_index` (`patient_id`),
  KEY `appointments_status_index` (`status`),
  CONSTRAINT `appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,'APT-2026-00001',26,3,9,'2026-09-02','16:15:00','18:15:00','walk_in','cancelled','Sit repudiandae corporis magnam molestias qui repudiandae.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(2,'APT-2026-00002',14,1,4,'2026-08-22','12:00:00','12:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(3,'APT-2026-00003',18,2,7,'2026-08-08','10:30:00','11:15:00','walk_in','completed','Et quis ea pariatur illum.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(4,'APT-2026-00004',20,2,10,'2026-08-17','13:15:00','13:30:00','walk_in','no_show','Cum odit maxime labore qui.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(5,'APT-2026-00005',27,3,9,'2026-08-18','10:30:00','12:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(6,'APT-2026-00006',16,3,9,'2026-08-12','16:15:00','18:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(7,'APT-2026-00007',18,3,10,'2026-07-26','10:00:00','10:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(8,'APT-2026-00008',16,2,10,'2026-08-13','13:15:00','13:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(9,'APT-2026-00009',4,1,3,'2026-08-30','16:15:00','17:00:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(10,'APT-2026-00010',18,3,2,'2026-08-24','14:45:00','15:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(11,'APT-2026-00011',18,1,9,'2026-08-28','11:30:00','13:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(12,'APT-2026-00012',15,3,8,'2026-09-12','14:45:00','15:15:00','walk_in','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(13,'APT-2026-00013',17,3,1,'2026-09-07','13:15:00','13:35:00','scheduled','completed','Et qui quibusdam illo quisquam.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(14,'APT-2026-00014',6,1,9,'2026-08-17','13:30:00','15:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(15,'APT-2026-00015',36,3,10,'2026-07-27','11:15:00','11:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(16,'APT-2026-00016',36,3,2,'2026-07-27','11:15:00','11:45:00','scheduled','completed','Cumque ipsa quam consequatur praesentium voluptatibus.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(17,'APT-2026-00017',7,1,6,'2026-08-18','15:00:00','16:00:00','scheduled','no_show',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(18,'APT-2026-00018',28,1,8,'2026-08-06','11:00:00','11:30:00','walk_in','no_show','Distinctio itaque in reiciendis ipsa necessitatibus delectus illum tempore.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(19,'APT-2026-00019',28,2,1,'2026-08-18','13:15:00','13:35:00','walk_in','completed','Dolorum laborum labore ut provident numquam quis.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(20,'APT-2026-00020',11,2,6,'2026-09-16','13:45:00','14:45:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(21,'APT-2026-00021',1,3,4,'2026-09-18','11:30:00','12:00:00','scheduled','confirmed','Dolore quis enim quisquam corporis.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(22,'APT-2026-00022',37,2,4,'2026-07-30','14:15:00','14:45:00','scheduled','cancelled',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(23,'APT-2026-00023',21,1,5,'2026-09-11','13:30:00','15:00:00','scheduled','confirmed','Consectetur perspiciatis blanditiis quia.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(24,'APT-2026-00024',33,2,8,'2026-08-21','16:45:00','17:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(25,'APT-2026-00025',31,1,6,'2026-08-03','12:30:00','13:30:00','walk_in','cancelled',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(26,'APT-2026-00026',40,3,6,'2026-08-28','16:30:00','17:30:00','walk_in','cancelled',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(27,'APT-2026-00027',28,3,5,'2026-09-10','10:15:00','11:45:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(28,'APT-2026-00028',6,2,3,'2026-07-30','14:00:00','14:45:00','walk_in','completed','Voluptatem ea quo provident sed.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(29,'APT-2026-00029',32,3,2,'2026-08-12','11:00:00','11:30:00','scheduled','no_show','Sed et eos aut quae minus adipisci.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(30,'APT-2026-00030',5,2,9,'2026-08-11','14:00:00','16:00:00','walk_in','completed','Omnis deserunt laborum voluptas doloribus omnis et.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(31,'APT-2026-00031',40,3,9,'2026-08-03','12:00:00','14:00:00','scheduled','completed','Adipisci accusamus sit voluptatibus et aut aut atque.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(32,'APT-2026-00032',13,1,7,'2026-09-01','16:15:00','17:00:00','walk_in','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(33,'APT-2026-00033',9,3,6,'2026-09-02','09:30:00','10:30:00','scheduled','completed','Consequatur dolore quo ducimus.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(34,'APT-2026-00034',9,2,8,'2026-08-04','16:15:00','16:45:00','walk_in','no_show',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(35,'APT-2026-00035',22,1,10,'2026-09-04','12:30:00','12:45:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(36,'APT-2026-00036',16,1,3,'2026-09-15','10:45:00','11:30:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(37,'APT-2026-00037',36,2,2,'2026-09-16','10:45:00','11:15:00','scheduled','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(38,'APT-2026-00038',15,2,9,'2026-09-05','13:00:00','15:00:00','scheduled','completed','Voluptas omnis repellendus non non minus laborum.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(39,'APT-2026-00039',25,2,5,'2026-09-14','09:45:00','11:15:00','scheduled','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(40,'APT-2026-00040',9,1,3,'2026-08-08','12:45:00','13:30:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(41,'APT-2026-00041',7,2,3,'2026-09-01','15:00:00','15:45:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(42,'APT-2026-00042',15,2,10,'2026-08-11','09:15:00','09:30:00','scheduled','completed','Quod facere hic possimus libero cupiditate mollitia.',NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(43,'APT-2026-00043',7,1,10,'2026-09-08','09:45:00','10:00:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(44,'APT-2026-00044',33,1,2,'2026-09-10','09:15:00','09:45:00','walk_in','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(45,'APT-2026-00045',4,2,6,'2026-09-15','12:30:00','13:30:00','scheduled','confirmed','Quasi dolores asperiores incidunt minima voluptatem error omnis.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(46,'APT-2026-00046',5,3,6,'2026-09-18','12:15:00','13:15:00','scheduled','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(47,'APT-2026-00047',38,2,6,'2026-08-27','09:00:00','10:00:00','walk_in','completed','Enim earum accusantium et omnis.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(48,'APT-2026-00048',11,1,9,'2026-09-01','15:30:00','17:30:00','walk_in','completed','Enim quam deleniti tempora praesentium ea deserunt.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(49,'APT-2026-00049',15,1,4,'2026-08-15','15:00:00','15:30:00','scheduled','no_show',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(50,'APT-2026-00050',33,3,5,'2026-09-05','09:45:00','11:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(51,'APT-2026-00051',29,2,1,'2026-08-10','10:45:00','11:05:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(52,'APT-2026-00052',32,2,6,'2026-07-30','09:00:00','10:00:00','scheduled','completed','Et ab velit rerum nostrum.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(53,'APT-2026-00053',1,2,3,'2026-08-14','13:45:00','14:30:00','scheduled','completed','Rerum voluptatibus quia velit quaerat consequatur fugit.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(54,'APT-2026-00054',22,1,6,'2026-08-21','09:45:00','10:45:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(55,'APT-2026-00055',16,2,5,'2026-09-05','10:30:00','12:00:00','walk_in','no_show','Perspiciatis iure dolor voluptas id dolor quia harum neque.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(56,'APT-2026-00056',4,3,10,'2026-09-14','15:30:00','15:45:00','scheduled','confirmed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(57,'APT-2026-00057',2,2,6,'2026-08-20','12:15:00','13:15:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(58,'APT-2026-00058',23,2,2,'2026-09-15','10:15:00','10:45:00','scheduled','confirmed','Nisi neque eligendi ut fuga.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(59,'APT-2026-00059',24,3,9,'2026-08-20','12:30:00','14:30:00','scheduled','completed','Sint sit ut eos eligendi in id ipsam.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(60,'APT-2026-00060',34,3,8,'2026-09-04','15:15:00','15:45:00','scheduled','cancelled',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(61,'APT-2026-00061',7,2,5,'2026-08-10','15:30:00','17:00:00','walk_in','cancelled','Sit repellat recusandae tempora officiis.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(62,'APT-2026-00062',1,3,8,'2026-08-20','09:15:00','09:45:00','scheduled','completed','Ut id at possimus corrupti facere magnam optio.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(63,'APT-2026-00063',37,2,2,'2026-08-03','12:45:00','13:15:00','scheduled','cancelled','Ut ut magni maiores quia aspernatur sit.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(64,'APT-2026-00064',29,3,6,'2026-08-03','15:00:00','16:00:00','scheduled','completed',NULL,NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(65,'APT-2026-00065',17,2,9,'2026-08-04','10:00:00','12:00:00','scheduled','no_show','Praesentium minima dolor a.',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(66,'APT-2026-00066',5,1,4,'2026-09-14','15:45:00','16:15:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(67,'APT-2026-00067',20,1,5,'2026-09-16','09:30:00','11:00:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(68,'APT-2026-00068',34,3,9,'2026-09-15','10:00:00','12:00:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(69,'APT-2026-00069',25,1,5,'2026-09-14','10:30:00','12:00:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(70,'APT-2026-00070',19,3,9,'2026-09-10','13:45:00','15:45:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(71,'APT-2026-00071',39,1,3,'2026-09-16','11:15:00','12:00:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(72,'APT-2026-00072',7,1,2,'2026-09-13','11:00:00','11:30:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(73,'APT-2026-00073',2,2,7,'2026-09-20','09:45:00','10:30:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(74,'APT-2026-00074',24,1,3,'2026-09-11','15:45:00','16:30:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(75,'APT-2026-00075',12,2,2,'2026-09-18','13:15:00','13:45:00','scheduled','booked',NULL,NULL,NULL,'2026-09-09 15:18:30','2026-09-09 15:18:30',NULL),(76,'APT-2026-00076',41,4,2,'2026-09-09','16:00:00','16:30:00','scheduled','completed','ilko dhaqid',NULL,1,'2026-09-09 15:55:56','2026-09-09 16:03:03',NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `treatment_id` bigint(20) unsigned DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `uploaded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_patient_id_foreign` (`patient_id`),
  KEY `attachments_treatment_id_foreign` (`treatment_id`),
  KEY `attachments_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `attachments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attachments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('setting.appointment_prefix','s:3:\"APT\";',2104337744),('setting.clinic_address','s:18:\"Mogadishu, Somalia\";',2104337744),('setting.clinic_email','s:16:\"info@dcatms.test\";',2104337744),('setting.clinic_logo','s:53:\"branding/m6vfy4mDqhjCVDqBAd6K8nqIIy4Au31mSihnSh2u.jpg\";',2104337744),('setting.clinic_name','s:10:\"EAU DENTAL\";',2104337744),('setting.clinic_phone','s:15:\"+252 61 0000000\";',2104337744),('setting.currency','s:3:\"USD\";',2104337744),('setting.currency_symbol','s:1:\"$\";',2104337744),('setting.invoice_prefix','s:3:\"INV\";',2104337744),('setting.tax_rate','s:1:\"0\";',2104337744),('setting.working_hours','s:25:\"09:00 - 17:00 (Sun - Thu)\";',2104337744);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dental_chart_entries`
--

DROP TABLE IF EXISTS `dental_chart_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dental_chart_entries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `treatment_id` bigint(20) unsigned DEFAULT NULL,
  `tooth_number` varchar(255) NOT NULL,
  `condition` enum('healthy','decayed','filled','missing','crowned','root_canal','implant','extracted','impacted') NOT NULL DEFAULT 'healthy',
  `notes` text DEFAULT NULL,
  `recorded_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dental_chart_entries_treatment_id_foreign` (`treatment_id`),
  KEY `dental_chart_entries_patient_id_tooth_number_index` (`patient_id`,`tooth_number`),
  CONSTRAINT `dental_chart_entries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dental_chart_entries_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dental_chart_entries`
--

LOCK TABLES `dental_chart_entries` WRITE;
/*!40000 ALTER TABLE `dental_chart_entries` DISABLE KEYS */;
INSERT INTO `dental_chart_entries` VALUES (1,41,NULL,'11','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(2,41,NULL,'12','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(3,41,NULL,'13','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(4,41,NULL,'14','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(5,41,NULL,'15','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(6,41,NULL,'16','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(7,41,NULL,'17','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(8,41,NULL,'18','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(9,41,NULL,'21','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(10,41,NULL,'22','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(11,41,NULL,'23','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(12,41,NULL,'24','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(13,41,NULL,'25','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(14,41,NULL,'26','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(15,41,NULL,'27','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(16,41,NULL,'28','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(17,41,NULL,'31','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(18,41,NULL,'32','decayed',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(19,41,NULL,'33','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(20,41,NULL,'34','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(21,41,NULL,'35','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(22,41,NULL,'36','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(23,41,NULL,'37','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(24,41,NULL,'38','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(25,41,NULL,'41','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(26,41,NULL,'42','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(27,41,NULL,'43','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(28,41,NULL,'44','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(29,41,NULL,'45','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(30,41,NULL,'46','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(31,41,NULL,'47','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(32,41,NULL,'48','healthy',NULL,'2026-09-09','2026-09-09 16:03:50','2026-09-09 16:03:50'),(33,41,1,'32','decayed',NULL,'2026-09-09','2026-09-09 16:05:01','2026-09-09 16:05:01');
/*!40000 ALTER TABLE `dental_chart_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dentists`
--

DROP TABLE IF EXISTS `dentists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dentists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `dentist_code` varchar(255) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dentists_user_id_unique` (`user_id`),
  UNIQUE KEY `dentists_dentist_code_unique` (`dentist_code`),
  CONSTRAINT `dentists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dentists`
--

LOCK TABLES `dentists` WRITE;
/*!40000 ALTER TABLE `dentists` DISABLE KEYS */;
INSERT INTO `dentists` VALUES (1,5,'DEN-0001','General Dentistry','LIC-20616','Dr. Abdirahman Mohamed Ali specializes in General Dentistry, based at the clinic in Garowe, Puntland.',1,'2026-09-09 15:08:05','2026-09-09 15:08:05',NULL),(2,6,'DEN-0002','Orthodontics','LIC-76186','Dr. Hodan Yusuf Warsame specializes in Orthodontics, based at the clinic in Garowe, Puntland.',1,'2026-09-09 15:08:05','2026-09-09 15:08:05',NULL),(3,7,'DEN-0003','Oral Surgery','LIC-61297','Dr. Khalid Ahmed Farah specializes in Oral Surgery, based at the clinic in Garowe, Puntland.',1,'2026-09-09 15:08:05','2026-09-09 15:08:05',NULL),(4,8,'DEN-0004','ILKO SAARID','778554333','ALI AHMED WAA DENTISTE KU TAKHASUSAY ILKO SAARID',1,'2026-09-09 15:52:14','2026-09-09 15:52:14',NULL);
/*!40000 ALTER TABLE `dentists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_items`
--

DROP TABLE IF EXISTS `inventory_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity_on_hand` int(10) unsigned NOT NULL DEFAULT 0,
  `reorder_level` int(10) unsigned NOT NULL DEFAULT 10,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `supplier` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_items_item_code_unique` (`item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_items`
--

LOCK TABLES `inventory_items` WRITE;
/*!40000 ALTER TABLE `inventory_items` DISABLE KEYS */;
INSERT INTO `inventory_items` VALUES (1,'INV-0001','Dental Gloves (Box)','box',50,10,5.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(2,'INV-0002','Face Masks (Box)','box',40,10,4.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(3,'INV-0003','Composite Filling Material','pcs',15,5,12.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(4,'INV-0004','Local Anesthetic Cartridges','box',8,10,20.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(5,'INV-0005','Dental Bibs','pcs',200,50,0.20,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(6,'INV-0006','Sterilization Pouches','pcs',5,20,0.50,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(7,'INV-0007','X-Ray Film','box',12,5,25.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(8,'INV-0008','Suture Kits','pcs',18,10,8.00,'MedSupply Co.',1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(9,'INV-0009','PARACTAMOL','BOX',50,10,5.00,'AL NACIIM',1,'2026-09-09 15:49:34','2026-09-09 15:49:34',NULL);
/*!40000 ALTER TABLE `inventory_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `tooth_number` varchar(255) DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_service_id_foreign` (`service_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,4,'Tooth Extraction',NULL,1,35.00,0.00,0.00,35.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(2,2,7,'Teeth Whitening',NULL,1,80.00,8.00,0.00,72.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(3,3,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(4,4,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(5,5,10,'X-Ray Imaging',NULL,1,20.00,0.00,0.00,20.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(6,6,10,'X-Ray Imaging',NULL,1,20.00,2.00,0.00,18.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(7,7,3,'Tooth Filling',NULL,1,40.00,0.00,0.00,40.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(8,8,2,'Teeth Cleaning (Scaling)',NULL,1,30.00,0.00,0.00,30.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(9,9,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(10,10,1,'Dental Check-up',NULL,1,15.00,0.00,0.00,15.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(11,11,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(12,12,10,'X-Ray Imaging',NULL,1,20.00,0.00,0.00,20.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(13,13,2,'Teeth Cleaning (Scaling)',NULL,1,30.00,0.00,0.00,30.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(14,14,1,'Dental Check-up',NULL,1,15.00,0.00,0.00,15.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(15,15,8,'Braces Consultation',NULL,1,25.00,0.00,0.00,25.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(16,16,3,'Tooth Filling',NULL,1,40.00,4.00,0.00,36.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(17,17,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(18,18,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(19,19,7,'Teeth Whitening',NULL,1,80.00,0.00,0.00,80.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(20,20,6,'Dental Crown',NULL,1,150.00,0.00,0.00,150.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(21,21,10,'X-Ray Imaging',NULL,1,20.00,0.00,0.00,20.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(22,22,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(23,23,3,'Tooth Filling',NULL,1,40.00,4.00,0.00,36.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(24,24,3,'Tooth Filling',NULL,1,40.00,0.00,0.00,40.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(25,25,10,'X-Ray Imaging',NULL,1,20.00,0.00,0.00,20.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(26,26,10,'X-Ray Imaging',NULL,1,20.00,0.00,0.00,20.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(27,27,6,'Dental Crown',NULL,1,150.00,15.00,0.00,135.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(28,28,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(29,29,5,'Root Canal Treatment',NULL,1,120.00,0.00,0.00,120.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(30,30,1,'Dental Check-up',NULL,1,15.00,0.00,0.00,15.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(31,31,6,'Dental Crown',NULL,1,150.00,0.00,0.00,150.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(32,32,3,'Tooth Filling',NULL,1,40.00,0.00,0.00,40.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(33,33,6,'Dental Crown',NULL,1,150.00,15.00,0.00,135.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(34,34,6,'Dental Crown',NULL,1,150.00,0.00,0.00,150.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(35,35,9,'Dental Implant',NULL,1,400.00,0.00,0.00,400.00,'2026-09-09 15:08:08','2026-09-09 15:08:08'),(36,36,1,'Dental Check-up',NULL,1,15.00,3.00,0.00,12.00,'2026-09-09 15:32:41','2026-09-09 15:32:41'),(37,36,2,'Teeth Cleaning (Scaling)',NULL,1,30.00,2.00,0.00,28.00,'2026-09-09 15:32:41','2026-09-09 15:32:41');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(255) NOT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','unpaid','partially_paid','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`),
  KEY `invoices_appointment_id_foreign` (`appointment_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_patient_id_index` (`patient_id`),
  KEY `invoices_status_index` (`status`),
  CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'INV-2026-00001',2,14,'2026-08-22','2026-09-05',35.00,0.00,0.00,35.00,17.50,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(2,'INV-2026-00002',3,18,'2026-08-08','2026-08-22',80.00,8.00,0.00,72.00,36.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(3,'INV-2026-00003',5,27,'2026-08-18','2026-09-01',400.00,0.00,0.00,400.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(4,'INV-2026-00004',6,16,'2026-08-12','2026-08-26',400.00,0.00,0.00,400.00,400.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(5,'INV-2026-00005',7,18,'2026-07-26','2026-08-09',20.00,0.00,0.00,20.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(6,'INV-2026-00006',8,16,'2026-08-13','2026-08-27',20.00,2.00,0.00,18.00,9.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(7,'INV-2026-00007',9,4,'2026-08-30','2026-09-13',40.00,0.00,0.00,40.00,20.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(8,'INV-2026-00008',10,18,'2026-08-24','2026-09-07',30.00,0.00,0.00,30.00,30.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(9,'INV-2026-00009',11,18,'2026-08-28','2026-09-11',400.00,0.00,0.00,400.00,200.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(10,'INV-2026-00010',13,17,'2026-09-07','2026-09-21',15.00,0.00,0.00,15.00,15.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(11,'INV-2026-00011',14,6,'2026-08-17','2026-08-31',400.00,0.00,0.00,400.00,400.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(12,'INV-2026-00012',15,36,'2026-07-27','2026-08-10',20.00,0.00,0.00,20.00,10.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(13,'INV-2026-00013',16,36,'2026-07-27','2026-08-10',30.00,0.00,0.00,30.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(14,'INV-2026-00014',19,28,'2026-08-18','2026-09-01',15.00,0.00,0.00,15.00,15.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(15,'INV-2026-00015',24,33,'2026-08-21','2026-09-04',25.00,0.00,0.00,25.00,12.50,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(16,'INV-2026-00016',28,6,'2026-07-30','2026-08-13',40.00,4.00,0.00,36.00,18.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(17,'INV-2026-00017',30,5,'2026-08-11','2026-08-25',400.00,0.00,0.00,400.00,400.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(18,'INV-2026-00018',31,40,'2026-08-03','2026-08-17',400.00,0.00,0.00,400.00,400.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(19,'INV-2026-00019',32,13,'2026-09-01','2026-09-15',80.00,0.00,0.00,80.00,40.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(20,'INV-2026-00020',33,9,'2026-09-02','2026-09-16',150.00,0.00,0.00,150.00,150.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(21,'INV-2026-00021',35,22,'2026-09-04','2026-09-18',20.00,0.00,0.00,20.00,20.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(22,'INV-2026-00022',38,15,'2026-09-05','2026-09-19',400.00,0.00,0.00,400.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(23,'INV-2026-00023',40,9,'2026-08-08','2026-08-22',40.00,4.00,0.00,36.00,36.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(24,'INV-2026-00024',41,7,'2026-09-01','2026-09-15',40.00,0.00,0.00,40.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(25,'INV-2026-00025',42,15,'2026-08-11','2026-08-25',20.00,0.00,0.00,20.00,20.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(26,'INV-2026-00026',43,7,'2026-09-08','2026-09-22',20.00,0.00,0.00,20.00,20.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(27,'INV-2026-00027',47,38,'2026-08-27','2026-09-10',150.00,15.00,0.00,135.00,67.50,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(28,'INV-2026-00028',48,11,'2026-09-01','2026-09-15',400.00,0.00,0.00,400.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(29,'INV-2026-00029',50,33,'2026-09-05','2026-09-19',120.00,0.00,0.00,120.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(30,'INV-2026-00030',51,29,'2026-08-10','2026-08-24',15.00,0.00,0.00,15.00,7.50,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(31,'INV-2026-00031',52,32,'2026-07-30','2026-08-13',150.00,0.00,0.00,150.00,75.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(32,'INV-2026-00032',53,1,'2026-08-14','2026-08-28',40.00,0.00,0.00,40.00,0.00,'unpaid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(33,'INV-2026-00033',54,22,'2026-08-21','2026-09-04',150.00,15.00,0.00,135.00,135.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(34,'INV-2026-00034',57,2,'2026-08-20','2026-09-03',150.00,0.00,0.00,150.00,150.00,'paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(35,'INV-2026-00035',59,24,'2026-08-20','2026-09-03',400.00,0.00,0.00,400.00,200.00,'partially_paid',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(36,'INV-2026-00036',NULL,41,'2026-09-09','2026-09-09',45.00,5.00,0.00,40.00,40.00,'paid',NULL,1,'2026-09-09 15:32:41','2026-09-09 15:38:48',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_08_174835_create_roles_table',1),(5,'2026_09_08_174837_create_role_user_table',1),(6,'2026_09_08_174839_add_fields_to_users_table',1),(7,'2026_09_08_174841_create_settings_table',1),(8,'2026_09_08_174843_create_activity_logs_table',1),(9,'2026_09_08_174845_create_patients_table',1),(10,'2026_09_08_174847_create_dentists_table',1),(11,'2026_09_08_174848_create_schedules_table',1),(12,'2026_09_08_174850_create_services_table',1),(13,'2026_09_08_174851_create_appointments_table',1),(14,'2026_09_08_174852_create_appointment_status_logs_table',1),(15,'2026_09_08_174853_create_treatments_table',1),(16,'2026_09_08_174855_create_treatment_details_table',1),(17,'2026_09_08_174856_create_prescriptions_table',1),(18,'2026_09_08_174857_create_dental_chart_entries_table',1),(19,'2026_09_08_174858_create_attachments_table',1),(20,'2026_09_08_174859_create_payment_methods_table',1),(21,'2026_09_08_174903_create_invoices_table',1),(22,'2026_09_08_174904_create_invoice_items_table',1),(23,'2026_09_08_174905_create_payments_table',1),(24,'2026_09_08_174907_create_inventory_items_table',1),(25,'2026_09_08_174908_create_stock_movements_table',1),(26,'2026_09_08_205238_create_contact_messages_table',1),(27,'2026_09_08_205239_create_appointment_requests_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `patient_code` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_patient_code_unique` (`patient_code`),
  UNIQUE KEY `patients_user_id_unique` (`user_id`),
  KEY `patients_first_name_last_name_index` (`first_name`,`last_name`),
  CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,NULL,'PT-2026-00001','Fardowsa','Warsame','1966-10-20','female','+25268534748','fardowsa-warsame1@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Hassan Ahmed','+25266598143',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(2,NULL,'PT-2026-00002','Bashir','Osman','2008-04-02','male','+25266763918','bashir-osman2@example.com','Garowe - Iskoshuban Rd, Puntland','Ubah Hussein','+25265477213',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(3,NULL,'PT-2026-00003','Said','Adan','1964-06-30','male','+25269074178','said-adan3@example.com','Garowe, Puntland','Asli Abdi','+25265233732','Omnis totam cumque non neque.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(4,NULL,'PT-2026-00004','Deeqa','Ahmed','1999-07-07','female','+25265526409','deeqa-ahmed4@example.com','Garowe - Horseed, Puntland','Omar Yusuf','+25290814595','Qui assumenda aliquid dolore ea assumenda rerum.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(5,NULL,'PT-2026-00005','Yusuf','Ismail','1989-04-04','male','+25261676424','yusuf-ismail5@example.com','Garowe, Puntland','Deeqa Ismail','+25269736116','Perferendis eveniet maiores vel expedita.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(6,NULL,'PT-2026-00006','Said','Omar','1960-03-13','male','+25265198842','said-omar6@example.com','Garowe - Horseed, Puntland','Rahma Ahmed','+25268543370','Aspernatur voluptatem dolor facilis assumenda et.','Aspirin',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(7,NULL,'PT-2026-00007','Khadija','Farah','2004-12-13','female','+25265767554','khadija-farah7@example.com','Garowe - Bulo Watiin, Puntland','Hussein Warsame','+25266756702','Mollitia accusamus qui nemo nihil dolorum.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(8,NULL,'PT-2026-00008','Naima','Hassan','2006-06-30','female','+25269920992','naima-hassan8@example.com','Garowe - Horseed, Puntland','Mustafe Nur','+25269337464','Ratione aut in odio facere aut odio quia.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(9,NULL,'PT-2026-00009','Mohamed','Jama','1994-05-17','male','+25266843408','mohamed-jama9@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Ikran Warsame','+25265746094',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(10,NULL,'PT-2026-00010','Said','Warsame','1968-10-09','male','+25261345802','said-warsame10@example.com','Garowe - Tawakal, Puntland','Deeqa Ibrahim','+25269576217',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(11,NULL,'PT-2026-00011','Halima','Abdi','2020-05-28','female','+25290350093','halima-abdi11@example.com','Garowe - Bulo Watiin, Puntland','Abdullahi Abdi','+25261708769',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(12,NULL,'PT-2026-00012','Liban','Yusuf','1962-03-09','male','+25269605615','liban-yusuf12@example.com','Garowe - Iskoshuban Rd, Puntland','Khadija Hassan','+25269861589',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(13,NULL,'PT-2026-00013','Mohamed','Nur','1985-09-04','male','+25269273731','mohamed-nur13@example.com','Garowe - Iskoshuban Rd, Puntland','Khadija Nur','+25269716460','Id amet rem sint molestiae molestiae dolor eligendi.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(14,NULL,'PT-2026-00014','Ayan','Salah','2009-03-28','female','+25290407095','ayan-salah14@example.com','Garowe - Horseed, Puntland','Ahmed Ahmed','+25269853934',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(15,NULL,'PT-2026-00015','Mustafe','Mohamed','1988-02-08','male','+25290547578','mustafe-mohamed15@example.com','Garowe, Puntland','Faiza Nur','+25290993687',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(16,NULL,'PT-2026-00016','Hassan','Ali','2001-11-05','male','+25266072963','hassan-ali16@example.com','Garowe - Iskoshuban Rd, Puntland','Ubah Salah','+25265257652',NULL,'Aspirin',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(17,NULL,'PT-2026-00017','Mustafe','Omar','1999-02-20','male','+25268240662','mustafe-omar17@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Nasteha Ali','+25261156415',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(18,NULL,'PT-2026-00018','Khalid','Ibrahim','2023-06-17','male','+25266760569','khalid-ibrahim18@example.com','Garowe - Tawakal, Puntland','Hawa Hussein','+25266878592',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(19,NULL,'PT-2026-00019','Maryan','Omar','1987-12-04','female','+25261878168','maryan-omar19@example.com','Garowe - Horseed, Puntland','Ibrahim Mohamed','+25261433838',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(20,NULL,'PT-2026-00020','Halima','Abdi','1958-04-07','female','+25265027255','halima-abdi20@example.com','Garowe, Puntland','Mohamed Farah','+25290222877','Ipsam voluptatibus labore fugit nemo reprehenderit voluptas ut.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(21,NULL,'PT-2026-00021','Said','Elmi','2022-11-28','male','+25290522076','said-elmi21@example.com','Garowe - Bulo Watiin, Puntland','Ubah Ismail','+25290656281','Quos commodi tempore cum molestiae rerum animi fuga.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(22,NULL,'PT-2026-00022','Ali','Yusuf','1970-12-01','male','+25268347460','ali-yusuf22@example.com','Garowe - Tawakal, Puntland','Ayan Warsame','+25290556113',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(23,NULL,'PT-2026-00023','Abdullahi','Osman','2012-04-26','male','+25261547784','abdullahi-osman23@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Faiza Adan','+25265088510',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(24,NULL,'PT-2026-00024','Mustafe','Mohamed','2010-03-18','male','+25290111766','mustafe-mohamed24@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Ikran Muse','+25290016637','Earum impedit beatae repellendus hic ratione aut assumenda.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(25,NULL,'PT-2026-00025','Ahmed','Osman','1999-10-14','male','+25269110312','ahmed-osman25@example.com','Garowe - Horseed, Puntland','Hawa Warsame','+25269761974','Placeat voluptatem sint qui ratione nisi voluptatum.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(26,NULL,'PT-2026-00026','Abdifatah','Muse','1956-11-23','male','+25290049565','abdifatah-muse26@example.com','Garowe, Puntland','Halima Warsame','+25290503017','Occaecati dolores quis earum enim et.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(27,NULL,'PT-2026-00027','Fadumo','Muse','2021-06-10','female','+25290820418','fadumo-muse27@example.com','Garowe - Bulo Watiin, Puntland','Abdirahman Farah','+25265750018','Impedit nobis optio voluptatem ipsam omnis quasi ipsum.','Latex',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(28,NULL,'PT-2026-00028','Abdiqadir','Muse','2016-03-31','male','+25261562833','abdiqadir-muse28@example.com','Garowe - Iskoshuban Rd, Puntland','Faiza Ismail','+25265506671','Dolor et possimus sit.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(29,NULL,'PT-2026-00029','Nur','Yusuf','2022-02-06','male','+25265778831','nur-yusuf29@example.com','Garowe, Puntland','Rahma Aden','+25266117543','Aspernatur aperiam dolorem aut consequatur libero eaque atque.','Penicillin',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(30,NULL,'PT-2026-00030','Asli','Muse','2017-12-01','female','+25268095407','asli-muse30@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Hassan Ahmed','+25269106707',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(31,NULL,'PT-2026-00031','Abdullahi','Osman','1995-07-26','male','+25269023226','abdullahi-osman31@example.com','Garowe, Puntland','Asli Ibrahim','+25261410211',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(32,NULL,'PT-2026-00032','Ayan','Omar','2009-10-14','female','+25269292980','ayan-omar32@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Omar Aden','+25261549481','Nesciunt aut dignissimos quis qui omnis sed.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(33,NULL,'PT-2026-00033','Said','Muse','1969-07-16','male','+25261607817','said-muse33@example.com','Garowe - Horseed, Puntland','Ayan Osman','+25269347562','Aut est vitae quibusdam nihil commodi.','Penicillin',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(34,NULL,'PT-2026-00034','Said','Adan','1985-06-17','male','+25268920901','said-adan34@example.com','Garowe - Iskoshuban Rd, Puntland','Amina Mohamed','+25269531396',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(35,NULL,'PT-2026-00035','Sahra','Jama','2016-10-14','female','+25266818460','sahra-jama35@example.com','Garowe - Horseed, Puntland','Mustafe Jama','+25268317123','Rerum ea assumenda est nobis tempore voluptas cumque.',NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(36,NULL,'PT-2026-00036','Hodan','Jama','2017-02-28','female','+25290027417','hodan-jama36@example.com','Garowe - Tawakal, Puntland','Jamal Elmi','+25268406290',NULL,'Latex',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(37,NULL,'PT-2026-00037','Abdifatah','Elmi','1980-06-15','male','+25290635224','abdifatah-elmi37@example.com','Garowe - Horseed, Puntland','Maryan Adan','+25290659204',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(38,NULL,'PT-2026-00038','Fadumo','Omar','1999-01-25','female','+25261483067','fadumo-omar38@example.com','Garowe - Horseed, Puntland','Ibrahim Adan','+25265181525',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(39,NULL,'PT-2026-00039','Hawa','Ibrahim','1984-07-18','female','+25268525993','hawa-ibrahim39@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Ibrahim Jama','+25290663393',NULL,'Latex',NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(40,NULL,'PT-2026-00040','Zainab','Nur','1956-12-13','female','+25269670436','zainab-nur40@example.com','Garowe - Wadada Isgaarsiinta, Puntland','Bashir Hussein','+25266921617',NULL,NULL,NULL,1,'2026-09-09 15:08:07','2026-09-09 15:08:07',NULL),(41,NULL,'PT-2026-00041','FU\'AAD SAID','MATTAN','2003-05-05','male','907999999','maxamedfardowso643@gmail.com','X.WADAJIR','MUNO','9067878787',NULL,NULL,'patients/QUAdZU74H3EJzBcFOHhO7BHwHVi8A0vR8TuynM6Y.jpg',1,'2026-09-09 15:24:40','2026-09-09 15:24:40',NULL),(42,NULL,'PT-2026-00042','fahiimo mahmed','adam',NULL,'female','906787878','fahiimo643@gmail.com','x.wabari','haawo','9067765681',NULL,NULL,NULL,1,'2026-09-09 17:33:38','2026-09-09 17:42:45',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'Cash','cash',NULL,NULL,1,1,'2026-09-09 15:08:07','2026-09-09 15:08:07'),(2,'e-Dahab','edahab','252-65-0000000','DCATMS Dental Clinic',1,2,'2026-09-09 15:08:07','2026-09-09 15:08:07'),(3,'Sahal (EVC Plus)','sahal','252-61-0000000','DCATMS Dental Clinic',1,3,'2026-09-09 15:08:07','2026-09-09 15:08:07'),(4,'Bank Transfer','bank_transfer','SO00 0000 0000 0000','DCATMS Dental Clinic',1,4,'2026-09-09 15:08:07','2026-09-09 15:08:07'),(5,'EVC','HORMUUD','556767','EAU DENTAL',1,5,'2026-09-09 15:36:00','2026-09-09 15:36:00');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_no` varchar(255) NOT NULL,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `method` varchar(255) NOT NULL DEFAULT 'cash',
  `sender_phone` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `type` enum('payment','refund','credit_note') NOT NULL DEFAULT 'payment',
  `notes` text DEFAULT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_no_unique` (`payment_no`),
  KEY `payments_payment_method_id_foreign` (`payment_method_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  KEY `payments_invoice_id_index` (`invoice_id`),
  KEY `payments_patient_id_index` (`patient_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,'PAY-2026-00001',1,14,'2026-08-22',17.50,0.00,NULL,'card',NULL,'REF-6533CK','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(2,'PAY-2026-00002',2,18,'2026-08-08',36.00,0.00,NULL,'bank_transfer',NULL,'REF-9089LZ','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(3,'PAY-2026-00004',4,16,'2026-08-12',400.00,0.00,NULL,'cash',NULL,'REF-6900LE','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(4,'PAY-2026-00006',6,16,'2026-08-13',9.00,0.00,NULL,'card',NULL,'REF-2497MM','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(5,'PAY-2026-00007',7,4,'2026-08-30',20.00,0.00,NULL,'card',NULL,'REF-1981SJ','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(6,'PAY-2026-00008',8,18,'2026-08-24',30.00,0.00,NULL,'card',NULL,'REF-9921FC','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(7,'PAY-2026-00009',9,18,'2026-08-28',200.00,0.00,NULL,'card',NULL,'REF-0150SO','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(8,'PAY-2026-00010',10,17,'2026-09-07',15.00,0.00,NULL,'bank_transfer',NULL,'REF-1022UE','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(9,'PAY-2026-00011',11,6,'2026-08-17',400.00,0.00,NULL,'card',NULL,'REF-5287RI','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(10,'PAY-2026-00012',12,36,'2026-07-27',10.00,0.00,NULL,'card',NULL,'REF-1162MU','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(11,'PAY-2026-00014',14,28,'2026-08-18',15.00,0.00,NULL,'cash',NULL,'REF-8825FU','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(12,'PAY-2026-00015',15,33,'2026-08-21',12.50,0.00,NULL,'cash',NULL,'REF-4831BX','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(13,'PAY-2026-00016',16,6,'2026-07-30',18.00,0.00,NULL,'bank_transfer',NULL,'REF-1859RB','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(14,'PAY-2026-00017',17,5,'2026-08-11',400.00,0.00,NULL,'card',NULL,'REF-8315WG','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(15,'PAY-2026-00018',18,40,'2026-08-03',400.00,0.00,NULL,'bank_transfer',NULL,'REF-9657MK','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(16,'PAY-2026-00019',19,13,'2026-09-01',40.00,0.00,NULL,'card',NULL,'REF-1363BK','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(17,'PAY-2026-00020',20,9,'2026-09-02',150.00,0.00,NULL,'bank_transfer',NULL,'REF-4077JL','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(18,'PAY-2026-00021',21,22,'2026-09-04',20.00,0.00,NULL,'bank_transfer',NULL,'REF-0371BK','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(19,'PAY-2026-00023',23,9,'2026-08-08',36.00,0.00,NULL,'bank_transfer',NULL,'REF-5872WV','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(20,'PAY-2026-00025',25,15,'2026-08-11',20.00,0.00,NULL,'bank_transfer',NULL,'REF-9195UY','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(21,'PAY-2026-00026',26,7,'2026-09-08',20.00,0.00,NULL,'mobile_money',NULL,'REF-0037ZJ','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(22,'PAY-2026-00027',27,38,'2026-08-27',67.50,0.00,NULL,'card',NULL,'REF-5288UF','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(23,'PAY-2026-00030',30,29,'2026-08-10',7.50,0.00,NULL,'mobile_money',NULL,'REF-5138MA','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(24,'PAY-2026-00031',31,32,'2026-07-30',75.00,0.00,NULL,'card',NULL,'REF-1811LM','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(25,'PAY-2026-00033',33,22,'2026-08-21',135.00,0.00,NULL,'cash',NULL,'REF-1305EY','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(26,'PAY-2026-00034',34,2,'2026-08-20',150.00,0.00,NULL,'cash',NULL,'REF-6780OK','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(27,'PAY-2026-00035',35,24,'2026-08-20',200.00,0.00,NULL,'card',NULL,'REF-3978HS','payment',NULL,NULL,'2026-09-09 15:08:08','2026-09-09 15:08:08',NULL),(28,'PAY-2026-00036',36,41,'2026-09-09',40.00,0.00,2,'e-Dahab','907787878',NULL,'payment',NULL,1,'2026-09-09 15:38:48','2026-09-09 15:38:48',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `treatment_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescriptions_treatment_id_foreign` (`treatment_id`),
  KEY `prescriptions_patient_id_foreign` (`patient_id`),
  KEY `prescriptions_dentist_id_foreign` (`dentist_id`),
  CONSTRAINT `prescriptions_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescriptions_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES (1,1,41,4,'DAAWADA ILKAHA',NULL,'500ML','3','IN UU DAWADAN CUNO SADDEX WAQTI SUBAX,DUHUR HABEN','2026-09-09 16:06:52','2026-09-09 16:06:52');
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1,1,NULL,NULL),(2,2,3,NULL,NULL),(3,3,4,NULL,NULL),(4,4,5,NULL,NULL),(5,5,2,NULL,NULL),(6,6,2,NULL,NULL),(7,7,2,NULL,NULL),(8,8,2,NULL,NULL),(9,9,1,NULL,NULL);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','admin','Full system access','2026-09-09 15:08:03','2026-09-09 15:08:03'),(2,'Dentist','dentist','Clinical staff','2026-09-09 15:08:03','2026-09-09 15:08:03'),(3,'Receptionist','receptionist','Front desk / appointments','2026-09-09 15:08:03','2026-09-09 15:08:03'),(4,'Accountant','accountant','Billing and finance','2026-09-09 15:08:03','2026-09-09 15:08:03'),(5,'Patient','patient','Patient portal access','2026-09-09 15:08:03','2026-09-09 15:08:03');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `type` enum('weekly','leave') NOT NULL DEFAULT 'weekly',
  `day_of_week` tinyint(4) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_dentist_id_day_of_week_index` (`dentist_id`,`day_of_week`),
  KEY `schedules_dentist_id_leave_date_index` (`dentist_id`,`leave_date`),
  CONSTRAINT `schedules_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (1,1,'weekly',0,'08:00:00','14:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(2,1,'weekly',1,'08:00:00','14:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(3,1,'weekly',2,'08:00:00','14:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(4,1,'weekly',3,'08:00:00','14:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(5,1,'weekly',4,'08:00:00','14:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(6,2,'weekly',0,'14:00:00','20:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(7,2,'weekly',1,'14:00:00','20:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(8,2,'weekly',2,'14:00:00','20:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(9,2,'weekly',3,'14:00:00','20:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(10,2,'weekly',4,'14:00:00','20:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(11,3,'weekly',0,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(12,3,'weekly',1,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(13,3,'weekly',2,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(14,3,'weekly',3,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(15,3,'weekly',4,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:08:05','2026-09-09 15:08:05'),(16,4,'weekly',3,'09:00:00','17:00:00',NULL,NULL,1,'2026-09-09 15:54:55','2026-09-09 15:54:55');
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `duration_minutes` int(10) unsigned NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Dental Check-up','preventive',NULL,15.00,20,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(2,'Teeth Cleaning (Scaling)','preventive',NULL,30.00,30,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(3,'Tooth Filling','restorative',NULL,40.00,45,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(4,'Tooth Extraction','surgical',NULL,35.00,30,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(5,'Root Canal Treatment','restorative',NULL,120.00,90,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(6,'Dental Crown','restorative',NULL,150.00,60,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(7,'Teeth Whitening','cosmetic',NULL,80.00,45,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(8,'Braces Consultation','orthodontic',NULL,25.00,30,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(9,'Dental Implant','surgical',NULL,400.00,120,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(10,'X-Ray Imaging','diagnostic',NULL,20.00,15,1,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('Jte7NU9pOfEwMf8JWbjs3xQiNgM9hpdUqKFLZ0Hh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.136.2 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWxZOG1PcXVYZm5GMGs1b1c3Ukg5Y1VQaUZ0cHowQVhocVFyYjVQdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1789048062),('TH2xLztjxRfZujMY5I0dX91Kftm6Qx44qwNUksjg',9,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkgwN1RYR1Q5Y1JmRnhxeklNS0E0Smg2RDNTcVJwQVN1MUV5VHhhViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9pbnZvaWNlcy8xL3BkZiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==',1789048889);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'clinic_name','EAU DENTAL','2026-09-09 15:08:07','2026-09-09 15:15:20'),(2,'clinic_phone','+252 61 0000000','2026-09-09 15:08:07','2026-09-09 15:08:07'),(3,'clinic_email','info@dcatms.test','2026-09-09 15:08:07','2026-09-09 15:08:07'),(4,'clinic_address','Mogadishu, Somalia','2026-09-09 15:08:07','2026-09-09 15:08:07'),(5,'working_hours','09:00 - 17:00 (Sun - Thu)','2026-09-09 15:08:07','2026-09-09 15:08:07'),(6,'currency','USD','2026-09-09 15:08:07','2026-09-09 15:08:07'),(7,'currency_symbol','$','2026-09-09 15:08:07','2026-09-09 15:08:07'),(8,'tax_rate','0','2026-09-09 15:08:07','2026-09-09 15:08:07'),(9,'invoice_prefix','INV','2026-09-09 15:08:07','2026-09-09 15:08:07'),(10,'appointment_prefix','APT','2026-09-09 15:08:07','2026-09-09 15:08:07'),(11,'clinic_logo','branding/m6vfy4mDqhjCVDqBAd6K8nqIIy4Au31mSihnSh2u.jpg','2026-09-09 15:15:43','2026-09-09 15:15:43');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_item_id` bigint(20) unsigned NOT NULL,
  `type` enum('in','out') NOT NULL DEFAULT 'in',
  `quantity` int(10) unsigned NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `performed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_performed_by_foreign` (`performed_by`),
  KEY `stock_movements_inventory_item_id_index` (`inventory_item_id`),
  CONSTRAINT `stock_movements_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_details`
--

DROP TABLE IF EXISTS `treatment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `treatment_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `tooth_number` varchar(255) DEFAULT NULL,
  `procedure_notes` text DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_details_treatment_id_foreign` (`treatment_id`),
  KEY `treatment_details_service_id_foreign` (`service_id`),
  CONSTRAINT `treatment_details_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatment_details_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_details`
--

LOCK TABLES `treatment_details` WRITE;
/*!40000 ALTER TABLE `treatment_details` DISABLE KEYS */;
INSERT INTO `treatment_details` VALUES (1,1,2,NULL,NULL,1,30.00,'2026-09-09 16:05:01','2026-09-09 16:05:01');
/*!40000 ALTER TABLE `treatment_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatments`
--

DROP TABLE IF EXISTS `treatments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `treatments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dentist_id` bigint(20) unsigned NOT NULL,
  `visit_date` date NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatments_appointment_id_foreign` (`appointment_id`),
  KEY `treatments_dentist_id_foreign` (`dentist_id`),
  KEY `treatments_patient_id_index` (`patient_id`),
  CONSTRAINT `treatments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatments`
--

LOCK TABLES `treatments` WRITE;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
INSERT INTO `treatments` VALUES (1,76,41,4,'2026-09-09','ILKO DHAQID',NULL,'2026-09-09 16:05:01','2026-09-09 16:05:01',NULL);
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@dcatms.test',NULL,'avatars/XOTNF8cedPH2IXVi1CcfCI2OipjPQpzZ6Pccg5Pt.jpg',1,NULL,'$2y$12$WU/5XWFzQGOsRMx4g6c73OOE7vcWBN9NYWDUAzcQar6i7doKMqK.m',NULL,'2026-09-09 15:08:03','2026-09-09 15:16:05',NULL),(2,'Amina Yusuf','receptionist@dcatms.test',NULL,NULL,1,NULL,'$2y$12$MCDCzaFG5TYYtAN4jMmG9uvRpA9.yq9v9kInrr0z8fjjUrOY9MW2e',NULL,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(3,'Khalid Warsame','accountant@dcatms.test',NULL,NULL,1,NULL,'$2y$12$Fn8hMRjXrFyLeyNt3sr/k.wQY332BRUfWhW1nus/pwuVDRH0QwxJq',NULL,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(4,'Demo Patient','patient@dcatms.test',NULL,NULL,1,NULL,'$2y$12$Z2LuaOFikBLCvEeR7h28.eCNaIaVV5BpXZ8QRj4mgOFUfYZdIzstS',NULL,'2026-09-09 15:08:04','2026-09-09 15:08:04',NULL),(5,'Dr. Abdirahman Mohamed Ali','dentist1@dcatms.test','+2529012345','avatars/dentist1.jpg',1,NULL,'$2y$12$1spVzO9iHgeT5bVZRutsdu3Yym4k42nuHqg5cMVdZJX0smKre3Rey',NULL,'2026-09-09 15:08:05','2026-09-10 11:01:08',NULL),(6,'Dr. Hodan Yusuf Warsame','dentist2@dcatms.test','+2526112233','avatars/dentist2.jpg',1,NULL,'$2y$12$8FsEwumnyVVEo0ggMvBBUO.khPM7S8JWfUWAxesi7T3t5WpbJ1w.G',NULL,'2026-09-09 15:08:05','2026-09-10 11:01:08',NULL),(7,'Dr. Khalid Ahmed Farah','dentist3@dcatms.test','+2526898765','avatars/dentist3.jpg',1,NULL,'$2y$12$eQM0Y7GZjmgQiFeGALsFB.qbMraULZ.yylHZJdJdHBROPk6PSSLQC',NULL,'2026-09-09 15:08:05','2026-09-10 11:01:08',NULL),(8,'Ali  Ahmed','aliahmed@gmail.com','907999999',NULL,1,NULL,'$2y$12$wchcl7FDgG.T0IhxxF6Q.O1E/goT.qVXU/UYoyEEraVIPe1jpYoxa',NULL,'2026-09-09 15:52:14','2026-09-09 15:52:14',NULL),(9,'Fardowso','fardowso@gmail.com','906787878',NULL,1,NULL,'$2y$12$6W5eWVym1Sxy54DMujZbhuCKBd/SV1pZh5ntkRVATUAT7vfD2MsgG',NULL,'2026-09-09 16:21:01','2026-09-09 16:21:01',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dcatms'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-10 17:01:35
