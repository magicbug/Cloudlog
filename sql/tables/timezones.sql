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
INSERT INTO `timezones` VALUES (1,'-12.0','(GMT-12:00)-International Date Line West'),(4,'-9.0','(GMT-09:00)-Alaska'),(5,'-8.0','(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),(6,'-7.0','(GMT-07:00)-Arizona'),(8,'-7.0','(GMT-07:00)-Mountain Time (US & Canada)'),(13,'-5.0','(GMT-05:00)-Bogota, Lima, Quito'),(15,'-5.0','(GMT-05:00)-Indiana (East)'),(17,'-4.0','(GMT-04:00)-La Paz'),(19,'-3.5','(GMT-03:30)-Newfoundland'),(22,'-3.0','(GMT-03:00)-Greenland'),(23,'-2.0','(GMT-02:00)-Mid-Atlantic'),(0,'0.0','(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),(30,'1.0','(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),(31,'1.0','(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),(35,'2.0','(GMT+02:00)-Cairo'),(36,'2.0','(GMT+02:00)-Harare, Pretoria'),(38,'2.0','(GMT+02:00)-Jerusalem'),(39,'3.0','(GMT+03:00)-Baghdad'),(41,'3.0','(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),(43,'3.5','(GMT+03:30)-Tehran'),(44,'4.0','(GMT+04:00)-Abu Dhabi, Muscat'),(45,'4.0','(GMT+04:00)-Baku, Tbilisi, Yerevan'),(46,'4.5','(GMT+04:30)-Kabul'),(51,'6.0','(GMT+06:00)-Almaty, Novosibirsk'),(54,'6.5','(GMT+06:30)-Rangoon'),(55,'7.0','(GMT+07:00)-Bangkok, Hanoi, Jakarta'),(56,'7.0','(GMT+07:00)-Krasnoyarsk'),(58,'8.0','(GMT+08:00)-Irkutsk, Ulaan Bataar'),(59,'8.0','(GMT+08:00)-Kuala Lumpur, Singapore'),(60,'8.0','(GMT+08:00)-Perth'),(63,'9.0','(GMT+09:00)-Seoul'),(64,'9.0','(GMT+09:00)-Vakutsk'),(66,'9.5','(GMT+09:30)-Darwin'),(69,'10.0','(GMT+10:00)-Guam, Port Moresby'),(71,'10.0','(GMT+10:00)-Vladivostok'),(74,'12.0','(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),(76,'-11.0','(GMT-11:00)-Midway Island, Samoa'),(77,'-10.0','(GMT-10:00)-Hawaii'),(81,'-7.0','(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),(83,'-6.0','(GMT-06:00)-Central America'),(84,'-6.0','(GMT-06:00)-Central Time (US & Canada)'),(85,'-6.0','(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),(86,'-6.0','(GMT-06:00)-Saskatchewan'),(88,'-5.0','(GMT-05:00)-Eastern Time (US & Canada)'),(90,'-4.0','(GMT-04:00)-Atlantic Time (Canada)'),(91,'-4.0','(GMT-04:00)-Caracas, La Paz'),(92,'-4.0','(GMT-04:00)-Santiago'),(94,'-3.0','(GMT-03:00)-Brasilia'),(95,'-3.0','(GMT-03:00)-Buenos Aires, Georgetown'),(98,'-1.0','(GMT-01:00)-Azores'),(99,'-1.0','(GMT-01:00)-Cape Verde Is.'),(100,'0.0','(GMT)-Casablanca, Monrovia'),(102,'1.0','(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),(103,'1.0','(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),(106,'1.0','(GMT+01:00)-West Central Africa'),(107,'2.0','(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),(108,'2.0','(GMT+02:00)-Bucharest'),(111,'2.0','(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),(114,'3.0','(GMT+03:00)-Kuwait, Riyadh'),(116,'3.0','(GMT+03:00)-Nairobi'),(121,'5.0','(GMT+05:00)-Ekaterinburg'),(122,'5.0','(GMT+05:00)-Islamabad, Karachi, Tashkent'),(123,'5.5','(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),(124,'5.8','(GMT+05:45)-Kathmandu'),(126,'6.0','(GMT+06:00)-Astana, Dhaka'),(127,'6.0','(GMT+06:00)-Sri Jayawardenepura'),(129,'7.0','(GMT+07:00)-Bangkok, Hanoi, Jakarta'),(131,'8.0','(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),(135,'8.0','(GMT+08:00)-Taipei'),(136,'9.0','(GMT+09:00)-Osaka, Sapporo, Tokyo'),(139,'9.5','(GMT+09:30)-Adelaide'),(141,'10.0','(GMT+10:00)-Brisbane'),(142,'10.0','(GMT+10:00)-Canberra, Melbourne, Sydney'),(144,'10.0','(GMT+10:00)-Hobart'),(146,'11.0','(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),(147,'12.0','(GMT+12:00)-Auckland, Wellington'),(149,'13.0','(GMT+13:00)-Nuku\'alofa '),(150,'-4.5','(GMT-04:30)-Caracas');
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

-- Dump completed on 2011-09-30 13:32:18
