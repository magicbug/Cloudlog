-- MySQL dump 10.13  Distrib 5.1.45, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: hrdlog_m0vkg
-- ------------------------------------------------------
-- Server version	5.1.45-1

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
-- Table structure for table `contest_template`
--

DROP TABLE IF EXISTS `contest_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `band_160` varchar(20) NOT NULL,
  `band_80` varchar(20) NOT NULL,
  `band_40` varchar(20) NOT NULL,
  `band_20` varchar(20) NOT NULL,
  `band_15` varchar(20) NOT NULL,
  `band_10` varchar(20) NOT NULL,
  `band_6m` varchar(20) NOT NULL,
  `band_4m` varchar(20) NOT NULL,
  `band_2m` varchar(20) NOT NULL,
  `band_70cm` varchar(20) NOT NULL,
  `band_23cm` varchar(20) NOT NULL,
  `mode_ssb` varchar(20) NOT NULL,
  `mode_cw` varchar(20) NOT NULL,
  `serial` varchar(20) NOT NULL,
  `point_per_km` int(20) NOT NULL,
  `qra` varchar(20) NOT NULL,
  `other_exch` varchar(255) NOT NULL,
  `scoring` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contest_template`
--

LOCK TABLES `contest_template` WRITE;
/*!40000 ALTER TABLE `contest_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `contest_template` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-18  2:25:55
