-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2018 at 04:29 PM
-- Server version: 10.2.15-MariaDB-10.2.15+maria~xenial-log
-- PHP Version: 7.2.6-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2m0sql`
--

-- --------------------------------------------------------

--
-- Table structure for table `station_profile`
--

CREATE TABLE `station_profile` (
  `station_id` int(11) NOT NULL,
  `station_profile_name` varchar(200) NOT NULL,
  `station_gridsquare` varchar(100) NOT NULL,
  `station_city` varchar(100) NOT NULL,
  `station_iota` varchar(100) NOT NULL,
  `station_sota` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `station_profile`
--
ALTER TABLE `station_profile`
  ADD PRIMARY KEY (`station_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `station_profile`
--
ALTER TABLE `station_profile`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `station_profile` ADD `station_callsign` VARCHAR(50) NULL DEFAULT NULL AFTER `station_sota`, ADD `station_dxcc` INT(10) NULL DEFAULT NULL AFTER `station_callsign`, ADD `station_country` VARCHAR(255) NULL DEFAULT NULL AFTER `station_dxcc`, ADD `station_cnty` VARCHAR(200) NULL DEFAULT NULL AFTER `station_country`, ADD `station_cq` INT(5) NULL DEFAULT NULL AFTER `station_cnty`, ADD `station_itu` INT(5) NULL DEFAULT NULL AFTER `station_cq`;