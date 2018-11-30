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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique user ID',
  `user_name` varchar(32) NOT NULL COMMENT 'Username',
  `user_password` varchar(64) NOT NULL COMMENT 'Password',
  `user_email` varchar(64) NOT NULL COMMENT 'E-mail address',
  `user_type` varchar(8) NOT NULL COMMENT 'User type',
  `user_callsign` varchar(32) NOT NULL COMMENT 'User''s callsign',
  `user_locator` varchar(16) NOT NULL COMMENT 'User''s locator',
  `user_firstname` varchar(32) NOT NULL COMMENT 'User''s first name',
  `user_lastname` varchar(32) NOT NULL COMMENT 'User''s last name',
  `user_timezone` int(3) NOT NULL DEFAULT '0',
  `user_lotw_name` varchar(32) DEFAULT NULL,
  `user_lotw_password` varchar(64) DEFAULT NULL,
  `user_eqsl_name` varchar(32) DEFAULT NULL,
  `user_eqsl_password` varchar(64) DEFAULT NULL,
  `user_eqsl_qth_nickname` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-19 21:36:27
