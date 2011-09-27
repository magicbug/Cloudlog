--
-- Based on http://www.michaelapproved.com/articles/timezone-dropdown-select-list/
--
-- MySQL dump 10.13  Distrib 5.1.58, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: hrdlog_m0vkg
-- ------------------------------------------------------
-- Server version	5.1.58-1

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
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offset` decimal(3,1) NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones`
--

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
INSERT INTO `timezones` VALUES (1,'-12.0','(GMT-12:00)-International Date Line West'),(2,'-11.0','(GMT-11:00)-Midway Island, Samoa'),(3,'-10.0','(GMT-10:00)-Hawaii'),(4,'-9.0','(GMT-09:00)-Alaska'),(5,'-8.0','(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),(6,'-7.0','(GMT-07:00)-Arizona'),(7,'-7.0','(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),(8,'-7.0','(GMT-07:00)-Mountain Time (US & Canada)'),(9,'-6.0','(GMT-06:00)-Central America'),(10,'-6.0','(GMT-06:00)-Central Time (US & Canada)'),(11,'-6.0','(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),(12,'-6.0','(GMT-06:00)-Saskatchewan'),(13,'-5.0','(GMT-05:00)-Bogota, Lima, Quito'),(14,'-5.0','(GMT-05:00)-Eastern Time (US & Canada)'),(15,'-5.0','(GMT-05:00)-Indiana (East)'),(16,'-4.0','(GMT-04:00)-Atlantic Time (Canada)'),(17,'-4.0','(GMT-04:00)-La Paz'),(18,'-4.0','(GMT-04:00)-Santiago'),(19,'-3.5','(GMT-03:30)-Newfoundland'),(20,'-3.0','(GMT-03:00)-Brasilia'),(21,'-3.0','(GMT-03:00)-Buenos Aires, Georgetown'),(22,'-3.0','(GMT-03:00)-Greenland'),(23,'-2.0','(GMT-02:00)-Mid-Atlantic'),(24,'-1.0','(GMT-01:00)-Azores'),(25,'-1.0','(GMT-01:00)-Cape Verde Is.'),(26,'0.0','(GMT)-Casablanca, Monrovia'),(0,'0.0','(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),(28,'1.0','(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),(29,'1.0','(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),(30,'1.0','(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),(31,'1.0','(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),(32,'1.0','(GMT+01:00)-West Central Africa'),(33,'2.0','(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),(34,'2.0','(GMT+02:00)-Bucharest'),(35,'2.0','(GMT+02:00)-Cairo'),(36,'2.0','(GMT+02:00)-Harare, Pretoria'),(37,'2.0','(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),(38,'2.0','(GMT+02:00)-Jerusalem'),(39,'3.0','(GMT+03:00)-Baghdad'),(40,'3.0','(GMT+03:00)-Kuwait, Riyadh'),(41,'3.0','(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),(42,'3.0','(GMT+03:00)-Nairobi'),(43,'3.5','(GMT+03:30)-Tehran'),(44,'4.0','(GMT+04:00)-Abu Dhabi, Muscat'),(45,'4.0','(GMT+04:00)-Baku, Tbilisi, Yerevan'),(46,'4.5','(GMT+04:30)-Kabul'),(47,'5.0','(GMT+05:00)-Ekaterinburg'),(48,'5.0','(GMT+05:00)-Islamabad, Karachi, Tashkent'),(49,'5.5','(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),(50,'5.8','(GMT+05:45)-Kathmandu'),(51,'6.0','(GMT+06:00)-Almaty, Novosibirsk'),(52,'6.0','(GMT+06:00)-Astana, Dhaka'),(53,'6.0','(GMT+06:00)-Sri Jayawardenepura'),(54,'6.5','(GMT+06:30)-Rangoon'),(55,'7.0','(GMT+07:00)-Bangkok, Hanoi, Jakarta'),(56,'7.0','(GMT+07:00)-Krasnoyarsk'),(57,'8.0','(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),(58,'8.0','(GMT+08:00)-Irkutsk, Ulaan Bataar'),(59,'8.0','(GMT+08:00)-Kuala Lumpur, Singapore'),(60,'8.0','(GMT+08:00)-Perth'),(61,'8.0','(GMT+08:00)-Taipei'),(62,'9.0','(GMT+09:00)-Osaka, Sapporo, Tokyo'),(63,'9.0','(GMT+09:00)-Seoul'),(64,'9.0','(GMT+09:00)-Vakutsk'),(65,'9.5','(GMT+09:30)-Adelaide'),(66,'9.5','(GMT+09:30)-Darwin'),(67,'10.0','(GMT+10:00)-Brisbane'),(68,'10.0','(GMT+10:00)-Canberra, Melbourne, Sydney'),(69,'10.0','(GMT+10:00)-Guam, Port Moresby'),(70,'10.0','(GMT+10:00)-Hobart'),(71,'10.0','(GMT+10:00)-Vladivostok'),(72,'11.0','(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),(73,'12.0','(GMT+12:00)-Auckland, Wellington'),(74,'12.0','(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),(75,'-12.0','(GMT-12:00)-International Date Line West'),(76,'-11.0','(GMT-11:00)-Midway Island, Samoa'),(77,'-10.0','(GMT-10:00)-Hawaii'),(78,'-9.0','(GMT-09:00)-Alaska'),(79,'-8.0','(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),(80,'-7.0','(GMT-07:00)-Arizona'),(81,'-7.0','(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),(82,'-7.0','(GMT-07:00)-Mountain Time (US & Canada)'),(83,'-6.0','(GMT-06:00)-Central America'),(84,'-6.0','(GMT-06:00)-Central Time (US & Canada)'),(85,'-6.0','(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),(86,'-6.0','(GMT-06:00)-Saskatchewan'),(87,'-5.0','(GMT-05:00)-Bogota, Lima, Quito'),(88,'-5.0','(GMT-05:00)-Eastern Time (US & Canada)'),(89,'-5.0','(GMT-05:00)-Indiana (East)'),(90,'-4.0','(GMT-04:00)-Atlantic Time (Canada)'),(91,'-4.0','(GMT-04:00)-Caracas, La Paz'),(92,'-4.0','(GMT-04:00)-Santiago'),(93,'-3.5','(GMT-03:30)-Newfoundland'),(94,'-3.0','(GMT-03:00)-Brasilia'),(95,'-3.0','(GMT-03:00)-Buenos Aires, Georgetown'),(96,'-3.0','(GMT-03:00)-Greenland'),(97,'-2.0','(GMT-02:00)-Mid-Atlantic'),(98,'-1.0','(GMT-01:00)-Azores'),(99,'-1.0','(GMT-01:00)-Cape Verde Is.'),(100,'0.0','(GMT)-Casablanca, Monrovia'),(101,'0.0','(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),(102,'1.0','(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),(103,'1.0','(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),(104,'1.0','(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),(105,'1.0','(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),(106,'1.0','(GMT+01:00)-West Central Africa'),(107,'2.0','(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),(108,'2.0','(GMT+02:00)-Bucharest'),(109,'2.0','(GMT+02:00)-Cairo'),(110,'2.0','(GMT+02:00)-Harare, Pretoria'),(111,'2.0','(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),(112,'2.0','(GMT+02:00)-Jerusalem'),(113,'3.0','(GMT+03:00)-Baghdad'),(114,'3.0','(GMT+03:00)-Kuwait, Riyadh'),(115,'3.0','(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),(116,'3.0','(GMT+03:00)-Nairobi'),(117,'3.5','(GMT+03:30)-Tehran'),(118,'4.0','(GMT+04:00)-Abu Dhabi, Muscat'),(119,'4.0','(GMT+04:00)-Baku, Tbilisi, Yerevan'),(120,'4.5','(GMT+04:30)-Kabul'),(121,'5.0','(GMT+05:00)-Ekaterinburg'),(122,'5.0','(GMT+05:00)-Islamabad, Karachi, Tashkent'),(123,'5.5','(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),(124,'5.8','(GMT+05:45)-Kathmandu'),(125,'6.0','(GMT+06:00)-Almaty, Novosibirsk'),(126,'6.0','(GMT+06:00)-Astana, Dhaka'),(127,'6.0','(GMT+06:00)-Sri Jayawardenepura'),(128,'6.5','(GMT+06:30)-Rangoon'),(129,'7.0','(GMT+07:00)-Bangkok, Hanoi, Jakarta'),(130,'7.0','(GMT+07:00)-Krasnoyarsk'),(131,'8.0','(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),(132,'8.0','(GMT+08:00)-Irkutsk, Ulaan Bataar'),(133,'8.0','(GMT+08:00)-Kuala Lumpur, Singapore'),(134,'8.0','(GMT+08:00)-Perth'),(135,'8.0','(GMT+08:00)-Taipei'),(136,'9.0','(GMT+09:00)-Osaka, Sapporo, Tokyo'),(137,'9.0','(GMT+09:00)-Seoul'),(138,'9.0','(GMT+09:00)-Vakutsk'),(139,'9.5','(GMT+09:30)-Adelaide'),(140,'9.5','(GMT+09:30)-Darwin'),(141,'10.0','(GMT+10:00)-Brisbane'),(142,'10.0','(GMT+10:00)-Canberra, Melbourne, Sydney'),(143,'10.0','(GMT+10:00)-Guam, Port Moresby'),(144,'10.0','(GMT+10:00)-Hobart'),(145,'10.0','(GMT+10:00)-Vladivostok'),(146,'11.0','(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),(147,'12.0','(GMT+12:00)-Auckland, Wellington'),(148,'12.0','(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),(149,'13.0','(GMT+13:00)-Nuku\'alofa '),(150,'-4.5','(GMT-04:30)-Caracas');
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-27 23:44:46
