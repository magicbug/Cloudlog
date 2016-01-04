-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2016 at 03:52 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cloudlog`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `lotw_download_url` varchar(255) DEFAULT NULL,
  `lotw_upload_url` varchar(255) DEFAULT NULL,
  `lotw_rcvd_mark` varchar(1) DEFAULT NULL,
  `lotw_login_url` varchar(244) DEFAULT NULL,
  `eqsl_download_url` varchar(244) DEFAULT NULL,
  `eqsl_rcvd_mark` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `lotw_download_url`, `lotw_upload_url`, `lotw_rcvd_mark`, `lotw_login_url`, `eqsl_download_url`, `eqsl_rcvd_mark`) VALUES
(1, 'https://p1k.arrl.org/lotwuser/lotwreport.adi', 'https://p1k.arrl.org/lotwuser/upload', 'Y', 'https://p1k.arrl.org/lotwuser/default', 'http://www.eqsl.cc/qslcard/DownloadInBox.cfm', 'Y');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
