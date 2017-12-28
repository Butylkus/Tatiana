-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: tatiana
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
-- Table structure for table `button_block`
--

DROP TABLE IF EXISTS `button_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `button_block` (
  `inpin` tinyint(2) NOT NULL,
  `outpin` tinyint(2) NOT NULL,
  PRIMARY KEY (`outpin`),
  UNIQUE KEY `outpin` (`outpin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Привязки кнопка-блок';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `button_block`
--

LOCK TABLES `button_block` WRITE;
/*!40000 ALTER TABLE `button_block` DISABLE KEYS */;
INSERT INTO `button_block` VALUES (20,25),(20,26);
/*!40000 ALTER TABLE `button_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `button_device`
--

DROP TABLE IF EXISTS `button_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `button_device` (
  `inpin` tinyint(2) NOT NULL COMMENT 'номер входного пина',
  `outpin` tinyint(2) NOT NULL COMMENT 'номер выходного пина',
  PRIMARY KEY (`inpin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='Привязки кнопка = ОДИН ДЕВАЙС';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `button_device`
--

LOCK TABLES `button_device` WRITE;
/*!40000 ALTER TABLE `button_device` DISABLE KEYS */;
INSERT INTO `button_device` VALUES (1,18),(8,5),(24,9);
/*!40000 ALTER TABLE `button_device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_stat`
--

DROP TABLE IF EXISTS `device_stat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_stat` (
  `pin` tinyint(2) NOT NULL COMMENT 'Пин подвешенного устройства',
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Имя устройства',
  `activity` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'архивный/активный девайс',
  `lastmodified` int(255) NOT NULL COMMENT 'Метка последнего изменения статуса',
  `operationtime` int(255) NOT NULL COMMENT 'Время работы устройства'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Статистика использования устройств';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_stat`
--

LOCK TABLES `device_stat` WRITE;
/*!40000 ALTER TABLE `device_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dht_data`
--

DROP TABLE IF EXISTS `dht_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dht_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pin` int(2) NOT NULL,
  `temperature` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `humidity` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Данные DHT-сенсоров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dht_data`
--

LOCK TABLES `dht_data` WRITE;
/*!40000 ALTER TABLE `dht_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `dht_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dht_sensors`
--

DROP TABLE IF EXISTS `dht_sensors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dht_sensors` (
  `pin` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  UNIQUE KEY `pin` (`pin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='Список датчиков температуры и влажности';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dht_sensors`
--

LOCK TABLES `dht_sensors` WRITE;
/*!40000 ALTER TABLE `dht_sensors` DISABLE KEYS */;
INSERT INTO `dht_sensors` VALUES (3,11),(15,22);
/*!40000 ALTER TABLE `dht_sensors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pins`
--

DROP TABLE IF EXISTS `pins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pins` (
  `pin` tinyint(2) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`pin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='Номера пинов, их имена, направление (ввод-вывод) и статусы';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pins`
--

LOCK TABLES `pins` WRITE;
/*!40000 ALTER TABLE `pins` DISABLE KEYS */;
INSERT INTO `pins` VALUES (1,'НЕ СУЩЕСТВУЕТ!','none',0),(2,'Второй','output',1),(3,'Третий внутри','dht',1),(4,'Пятый пир','pir',1),(5,'GzПятая','block',1),(6,'Шестой ненужный','none',1),(7,'Седьмой тоже','none',0),(8,'Восьмая кнопка','input',0),(9,'Девятый','output',0),(10,'Десятый','input',1),(11,'Одиннадцатый','none',1),(12,'Дюжина','none',1),(13,'Чёртова дюжина','output',1),(14,'Четырнадцатый','output',0),(15,'xffff','dht',0),(16,'Шесть-на-дцать','output',1),(17,'Семнадцатый','block',0),(18,'Осьмнадцатый','none',0),(19,'Девять и ещё 10','pir',1),(20,'Два чирика','block',1),(21,'Очковая кнопка','input',1),(22,'Два-два!','none',1),(23,'Двадцать третий','output',0),(24,'Двадцать четвёртый','input',0),(25,'Двадцать пятый','output',0),(26,'Предпоследний','output',1),(27,'Последний','none',1);
/*!40000 ALTER TABLE `pins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pir_data`
--

DROP TABLE IF EXISTS `pir_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pir_data` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Данные PIR-сенсоров и камер';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pir_data`
--

LOCK TABLES `pir_data` WRITE;
/*!40000 ALTER TABLE `pir_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `pir_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan`
--

DROP TABLE IF EXISTS `plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id строки плана',
  `pin` tinyint(2) NOT NULL COMMENT 'выходой пин',
  `ontime` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'время включения (hh:mm:ss)',
  `offtime` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'время выключения (hh:mm:ss)',
  `calendar` int(1) NOT NULL DEFAULT '3' COMMENT '1-будни, 2-выхи, 3-ежедневно',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='таблица планировщика';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan`
--

LOCK TABLES `plan` WRITE;
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;
INSERT INTO `plan` VALUES (2,25,'10:48:45','12:50:32',1),(3,24,'20:15:55','20:20:40',1),(4,15,'06:00:00','07:20:00',3);
/*!40000 ALTER TABLE `plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` tinyint(2) NOT NULL AUTO_INCREMENT COMMENT 'номер пользователя',
  `login` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'логин',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'мд5 пароля',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'нормальное имя (Вася)',
  `last_login` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'последняя авторизация',
  `user_sid` varchar(32) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица пользователей: лоuины, пароли и тд';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','63a9f0ea7bb98050796b649e85481845','Админ','04.10.2017 22:24:32','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-19  2:18:09
