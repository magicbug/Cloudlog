CREATE TABLE IF NOT EXISTS `cat` (
  `id` int(11) NOT NULL auto_increment,
  `radio` varchar(250) NOT NULL,
  `frequency` bigint(13) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;

ALTER TABLE `cat` ADD `downlink_freq` bigint(13) DEFAULT NULL AFTER `mode`, ADD `uplink_freq` bigint(13) DEFAULT NULL AFTER `downlink_freq`, ADD `downlink_mode` VARCHAR(255) DEFAULT NULL AFTER `uplink_freq`, ADD `uplink_mode` VARCHAR(255) DEFAULT NULL AFTER `downlink_mode`, ADD `sat_name` VARCHAR(255) DEFAULT NULL AFTER `uplink_mode`;