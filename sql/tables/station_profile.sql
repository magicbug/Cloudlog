CREATE TABLE `station_profile` (
  `station_id` int(11) NOT NULL,
  `station_profile_name` varchar(200) NOT NULL,
  `station_gridsquare` varchar(100) NOT NULL,
  `station_city` varchar(100) NOT NULL,
  `station_iota` varchar(100) NOT NULL,
  `station_sota` varchar(10) NOT NULL,
  `station_callsign` varchar(50) DEFAULT NULL,
  `station_dxcc` int(10) DEFAULT NULL,
  `station_country` varchar(255) DEFAULT NULL,
  `station_cnty` varchar(200) DEFAULT NULL,
  `station_cq` int(5) DEFAULT NULL,
  `station_itu` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utfmb4;

ALTER TABLE `station_profile`
MODIFY COLUMN `station_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY (`station_id`);
