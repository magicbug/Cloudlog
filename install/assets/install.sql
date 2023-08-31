SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api
-- ----------------------------
DROP TABLE IF EXISTS `api`;
CREATE TABLE `api` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `key` varchar(255) NOT NULL,
                     `rights` varchar(10) NOT NULL,
                     `status` varchar(10) NOT NULL,
                     `last_change` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of api
-- ----------------------------

-- ----------------------------
-- Table structure for cat
-- ----------------------------
DROP TABLE IF EXISTS `cat`;
CREATE TABLE `cat` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `radio` varchar(250) NOT NULL,
                     `frequency` bigint(13) NOT NULL,
                     `mode` varchar(10) NOT NULL,
                     `downlink_freq` bigint(13) DEFAULT NULL,
                     `uplink_freq` bigint(13) DEFAULT NULL,
                     `downlink_mode` varchar(255) DEFAULT NULL,
                     `uplink_mode` varchar(255) DEFAULT NULL,
                     `sat_name` varchar(255) DEFAULT NULL,
                     `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of cat
-- ----------------------------

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
                        `id` int(9) NOT NULL AUTO_INCREMENT,
                        `lotw_download_url` varchar(255) DEFAULT NULL,
                        `lotw_upload_url` varchar(255) DEFAULT NULL,
                        `lotw_rcvd_mark` varchar(1) DEFAULT NULL,
                        `lotw_login_url` varchar(244) DEFAULT NULL,
                        `eqsl_download_url` varchar(244) DEFAULT NULL,
                        `eqsl_rcvd_mark` varchar(1) DEFAULT NULL,
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', 'https://lotw.arrl.org/lotwuser/lotwreport.adi', 'https://lotw.arrl.org/lotwuser/upload', 'Y', 'https://lotw.arrl.org/lotwuser/default', 'http://www.eqsl.cc/qslcard/DownloadInBox.cfm', 'Y');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('15');

-- ----------------------------
-- Table structure for notes
-- ----------------------------
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                       `cat` varchar(255) NOT NULL,
                       `title` varchar(255) NOT NULL,
                       `note` text NOT NULL,
                       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of notes
-- ----------------------------

-- ----------------------------
-- Table structure for station_profile
-- ----------------------------
DROP TABLE IF EXISTS `station_profile`;
CREATE TABLE `station_profile` (
                                 `station_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
                                 `station_itu` int(5) DEFAULT NULL,
                                 PRIMARY KEY (`station_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of station_profile
-- ----------------------------

-- ----------------------------
-- Table structure for TABLE_HRD_CONTACTS_V01
-- ----------------------------
DROP TABLE IF EXISTS `TABLE_HRD_CONTACTS_V01`;
CREATE TABLE `TABLE_HRD_CONTACTS_V01` (
                                        `COL_PRIMARY_KEY` int(11) NOT NULL AUTO_INCREMENT,
                                        `COL_ADDRESS` varchar(255) DEFAULT NULL,
                                        `COL_AGE` int(11) DEFAULT NULL,
                                        `COL_A_INDEX` double DEFAULT NULL,
                                        `COL_ANT_AZ` double DEFAULT NULL,
                                        `COL_ANT_EL` double DEFAULT NULL,
                                        `COL_ANT_PATH` varchar(2) DEFAULT NULL,
                                        `COL_ARRL_SECT` varchar(10) DEFAULT NULL,
                                        `COL_BAND` varchar(10) DEFAULT NULL,
                                        `COL_BAND_RX` varchar(10) DEFAULT NULL,
                                        `COL_BIOGRAPHY` longtext DEFAULT NULL,
                                        `COL_CALL` varchar(32) DEFAULT NULL,
                                        `COL_CHECK` varchar(8) DEFAULT NULL,
                                        `COL_CLASS` varchar(8) DEFAULT NULL,
                                        `COL_CNTY` varchar(32) DEFAULT NULL,
                                        `COL_COMMENT` longtext DEFAULT NULL,
                                        `COL_CONT` varchar(6) DEFAULT NULL,
                                        `COL_CONTACTED_OP` varchar(32) DEFAULT NULL,
                                        `COL_CONTEST_ID` varchar(32) DEFAULT NULL,
                                        `COL_COUNTRY` varchar(64) DEFAULT NULL,
                                        `COL_CQZ` int(11) DEFAULT NULL,
                                        `COL_DISTANCE` double DEFAULT NULL,
                                        `COL_DXCC` varchar(6) DEFAULT NULL,
                                        `COL_EMAIL` varchar(32) DEFAULT NULL,
                                        `COL_EQ_CALL` varchar(32) DEFAULT NULL,
                                        `COL_EQSL_QSLRDATE` datetime DEFAULT NULL,
                                        `COL_EQSL_QSLSDATE` datetime DEFAULT NULL,
                                        `COL_EQSL_QSL_RCVD` varchar(2) DEFAULT NULL,
                                        `COL_EQSL_QSL_SENT` varchar(2) DEFAULT NULL,
                                        `COL_EQSL_STATUS` varchar(255) DEFAULT NULL,
                                        `COL_FORCE_INIT` int(11) DEFAULT NULL,
                                        `COL_FREQ` bigint(13) DEFAULT NULL,
                                        `COL_FREQ_RX` bigint(13) DEFAULT NULL,
                                        `COL_GRIDSQUARE` varchar(12) DEFAULT NULL,
                                        `COL_HEADING` double DEFAULT NULL,
                                        `COL_IOTA` varchar(10) DEFAULT NULL,
                                        `COL_ITUZ` int(11) DEFAULT NULL,
                                        `COL_K_INDEX` double DEFAULT NULL,
                                        `COL_LAT` double DEFAULT NULL,
                                        `COL_LON` double DEFAULT NULL,
                                        `COL_LOTW_QSLRDATE` datetime DEFAULT NULL,
                                        `COL_LOTW_QSLSDATE` datetime DEFAULT NULL,
                                        `COL_LOTW_QSL_RCVD` varchar(2) DEFAULT NULL,
                                        `COL_LOTW_QSL_SENT` varchar(2) DEFAULT NULL,
                                        `COL_LOTW_STATUS` varchar(255) DEFAULT NULL,
                                        `COL_MAX_BURSTS` int(11) DEFAULT NULL,
                                        `COL_MODE` varchar(10) DEFAULT NULL,
                                        `COL_MS_SHOWER` varchar(32) DEFAULT NULL,
                                        `COL_MY_CITY` varchar(32) DEFAULT NULL,
                                        `COL_MY_CNTY` varchar(32) DEFAULT NULL,
                                        `COL_MY_COUNTRY` varchar(64) DEFAULT NULL,
                                        `COL_MY_CQ_ZONE` int(11) DEFAULT NULL,
                                        `COL_MY_GRIDSQUARE` varchar(12) DEFAULT NULL,
                                        `COL_MY_IOTA` varchar(10) DEFAULT NULL,
                                        `COL_MY_ITU_ZONE` int(11) DEFAULT NULL,
                                        `COL_MY_LAT` double DEFAULT NULL,
                                        `COL_MY_LON` double DEFAULT NULL,
                                        `COL_MY_NAME` varchar(64) DEFAULT NULL,
                                        `COL_MY_POSTAL_CODE` varchar(24) DEFAULT NULL,
                                        `COL_MY_RIG` varchar(255) DEFAULT NULL,
                                        `COL_MY_SIG` varchar(32) DEFAULT NULL,
                                        `COL_MY_SIG_INFO` varchar(64) DEFAULT NULL,
                                        `COL_MY_STATE` varchar(32) DEFAULT NULL,
                                        `COL_MY_STREET` varchar(64) DEFAULT NULL,
                                        `COL_NAME` varchar(128) DEFAULT NULL,
                                        `COL_NOTES` longtext DEFAULT NULL,
                                        `COL_NR_BURSTS` int(11) DEFAULT NULL,
                                        `COL_NR_PINGS` int(11) DEFAULT NULL,
                                        `COL_OPERATOR` varchar(32) DEFAULT NULL,
                                        `COL_OWNER_CALLSIGN` varchar(32) DEFAULT NULL,
                                        `COL_PFX` varchar(32) DEFAULT NULL,
                                        `COL_PRECEDENCE` varchar(32) DEFAULT NULL,
                                        `COL_PROP_MODE` varchar(8) DEFAULT NULL,
                                        `COL_PUBLIC_KEY` varchar(255) DEFAULT NULL,
                                        `COL_QSLMSG` varchar(255) DEFAULT NULL,
                                        `COL_QSLRDATE` datetime DEFAULT NULL,
                                        `COL_QSLSDATE` datetime DEFAULT NULL,
                                        `COL_QSL_RCVD` varchar(2) DEFAULT NULL,
                                        `COL_QSL_RCVD_VIA` varchar(2) DEFAULT NULL,
                                        `COL_QSL_SENT` varchar(2) DEFAULT NULL,
                                        `COL_QSL_SENT_VIA` varchar(2) DEFAULT NULL,
                                        `COL_QSL_VIA` varchar(255) DEFAULT NULL,
                                        `COL_QSO_COMPLETE` varchar(6) DEFAULT NULL,
                                        `COL_QSO_RANDOM` int(11) DEFAULT NULL,
                                        `COL_QTH` varchar(64) DEFAULT NULL,
                                        `COL_RIG` varchar(255) DEFAULT NULL,
                                        `COL_RST_RCVD` varchar(32) DEFAULT NULL,
                                        `COL_RST_SENT` varchar(32) DEFAULT NULL,
                                        `COL_RX_PWR` double DEFAULT NULL,
                                        `COL_SAT_MODE` varchar(32) DEFAULT NULL,
                                        `COL_SAT_NAME` varchar(32) DEFAULT NULL,
                                        `COL_SFI` double DEFAULT NULL,
                                        `COL_SIG` varchar(32) DEFAULT NULL,
                                        `COL_SIG_INFO` varchar(64) DEFAULT NULL,
                                        `COL_SRX` int(11) DEFAULT NULL,
                                        `COL_SRX_STRING` varchar(32) DEFAULT NULL,
                                        `COL_STATE` varchar(32) DEFAULT NULL,
                                        `COL_STATION_CALLSIGN` varchar(32) DEFAULT NULL,
                                        `COL_STX` int(11) DEFAULT NULL,
                                        `COL_STX_STRING` varchar(32) DEFAULT NULL,
                                        `COL_SWL` int(11) DEFAULT NULL,
                                        `COL_TEN_TEN` int(11) DEFAULT NULL,
                                        `COL_TIME_OFF` datetime DEFAULT NULL,
                                        `COL_TIME_ON` datetime DEFAULT NULL,
                                        `COL_TX_PWR` double DEFAULT NULL,
                                        `COL_WEB` varchar(128) DEFAULT NULL,
                                        `COL_USER_DEFINED_0` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_1` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_2` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_3` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_4` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_5` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_6` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_7` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_8` varchar(64) DEFAULT NULL,
                                        `COL_USER_DEFINED_9` varchar(64) DEFAULT NULL,
                                        `COL_CREDIT_GRANTED` varchar(64) DEFAULT NULL,
                                        `COL_CREDIT_SUBMITTED` varchar(64) DEFAULT NULL,
                                        `COL_ADDRESS_INTL` varchar(255) DEFAULT NULL,
                                        `COL_AWARD_GRANTED` varchar(255) DEFAULT NULL,
                                        `COL_AWARD_SUMMITED` varchar(255) DEFAULT NULL,
                                        `COL_CLUBLOG_QSO_UPLOAD_DATE` datetime DEFAULT NULL,
                                        `COL_CLUBLOG_QSO_UPLOAD_STATUS` varchar(20) DEFAULT NULL,
                                        `COL_COMMENT_INTL` varchar(255) DEFAULT NULL,
                                        `COL_COUNTRY_INTL` varchar(255) DEFAULT NULL,
                                        `COL_SILENT_KEY` varchar(2) DEFAULT NULL,
                                        `COL_SKCC` varchar(255) DEFAULT NULL,
                                        `COL_DARC_DOK` varchar(10) DEFAULT NULL,
                                        `COL_FISTS` int(10) DEFAULT NULL,
                                        `COL_FISTS_CC` int(10) DEFAULT NULL,
                                        `COL_HRDLOG_QSO_UPLOAD_DATE` datetime DEFAULT NULL,
                                        `COL_HRDLOG_QSO_UPLOAD_STATUS` varchar(10) DEFAULT NULL,
                                        `COL_MY_ANTENNA` varchar(255) DEFAULT NULL,
                                        `COL_MY_ANTENNA_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_CITY_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_COUNTRY_INTL` int(6) DEFAULT NULL,
                                        `COL_MY_DXCC` int(6) DEFAULT NULL,
                                        `COL_MY_FISTS` int(10) DEFAULT NULL,
                                        `COL_MY_IOTA_ISLAND_ID` varchar(10) DEFAULT NULL,
                                        `COL_MY_NAME_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_POSTCODE_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_RIG_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_SIG_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_SIG_INFO_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_SOTA_REF` varchar(50) DEFAULT NULL,
                                        `COL_MY_STREET_INTL` varchar(255) DEFAULT NULL,
                                        `COL_MY_USACA_COUNTIES` varchar(255) DEFAULT NULL,
                                        `COL_MY_VUCC_GRIDS` varchar(50) DEFAULT NULL,
                                        `COL_NAME_INTL` varchar(255) DEFAULT NULL,
                                        `COL_NOTES_INTL` longtext DEFAULT NULL,
                                        `COL_QRZCOM_QSO_UPLOAD_DATE` datetime DEFAULT NULL,
                                        `COL_QRZCOM_QSO_UPLOAD_STATUS` varchar(10) DEFAULT NULL,
                                        `COL_QSO_DATE` date DEFAULT NULL,
                                        `COL_QSO_DATE_OFF` date DEFAULT NULL,
                                        `COL_QTH_INTL` varchar(255) DEFAULT NULL,
                                        `COL_REGION` varchar(25) DEFAULT NULL,
                                        `COL_RIG_INTL` varchar(255) DEFAULT NULL,
                                        `COL_SIG_INTL` varchar(255) DEFAULT NULL,
                                        `COL_SIG_INFO_INTL` varchar(255) DEFAULT NULL,
                                        `COL_SOTA_REF` varchar(30) DEFAULT NULL,
                                        `COL_SUBMODE` varchar(25) DEFAULT NULL,
                                        `COL_UKSMG` varchar(64) DEFAULT NULL,
                                        `COL_USACA_COUNTIES` varchar(255) DEFAULT NULL,
                                        `COL_VUCC_GRIDS` varchar(255) DEFAULT NULL,
                                        PRIMARY KEY (`COL_PRIMARY_KEY`),
                                        KEY `HRD_IDX_COL_BAND` (`COL_BAND`),
                                        KEY `HRD_IDX_COL_CALL` (`COL_CALL`),
                                        KEY `HRD_IDX_COL_CONT` (`COL_CONT`),
                                        KEY `HRD_IDX_COL_DXCC` (`COL_DXCC`),
                                        KEY `HRD_IDX_COL_IOTA` (`COL_IOTA`),
                                        KEY `HRD_IDX_COL_MODE` (`COL_MODE`),
                                        KEY `HRD_IDX_COL_PFX` (`COL_PFX`),
                                        KEY `HRD_IDX_COL_TIME_ON` (`COL_TIME_ON`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of TABLE_HRD_CONTACTS_V01
-- ----------------------------


CREATE TABLE `options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) DEFAULT NULL,
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

ALTER TABLE `options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;


-- 
-- Dumping data for table `options` that stops the version2 trigger from firing
INSERT INTO `options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES (NULL, 'version2_trigger', 'true', 'yes');

-- ----------------------------
-- Table structure for timezones
-- ----------------------------
DROP TABLE IF EXISTS `timezones`;
CREATE TABLE `timezones` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                           `offset` decimal(3,1) NOT NULL,
                           `name` varchar(120) COLLATE utf8mb4_bin NOT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of timezones
-- ----------------------------
INSERT INTO `timezones` VALUES ('1', '-12.0', '(GMT-12:00)-International Date Line West');
INSERT INTO `timezones` VALUES ('4', '-9.0', '(GMT-09:00)-Alaska');
INSERT INTO `timezones` VALUES ('5', '-8.0', '(GMT-08:00)-Pacific Time (US & Canada); Tijuana');
INSERT INTO `timezones` VALUES ('6', '-7.0', '(GMT-07:00)-Arizona');
INSERT INTO `timezones` VALUES ('8', '-7.0', '(GMT-07:00)-Mountain Time (US & Canada)');
INSERT INTO `timezones` VALUES ('13', '-5.0', '(GMT-05:00)-Bogota, Lima, Quito');
INSERT INTO `timezones` VALUES ('15', '-5.0', '(GMT-05:00)-Indiana (East)');
INSERT INTO `timezones` VALUES ('17', '-4.0', '(GMT-04:00)-La Paz');
INSERT INTO `timezones` VALUES ('19', '-3.5', '(GMT-03:30)-Newfoundland');
INSERT INTO `timezones` VALUES ('22', '-3.0', '(GMT-03:00)-Greenland');
INSERT INTO `timezones` VALUES ('23', '-2.0', '(GMT-02:00)-Mid-Atlantic');
INSERT INTO `timezones` VALUES ('0', '0.0', '(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London');
INSERT INTO `timezones` VALUES ('30', '1.0', '(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris');
INSERT INTO `timezones` VALUES ('31', '1.0', '(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb');
INSERT INTO `timezones` VALUES ('35', '2.0', '(GMT+02:00)-Cairo');
INSERT INTO `timezones` VALUES ('36', '2.0', '(GMT+02:00)-Harare, Pretoria');
INSERT INTO `timezones` VALUES ('38', '2.0', '(GMT+02:00)-Jerusalem');
INSERT INTO `timezones` VALUES ('39', '3.0', '(GMT+03:00)-Baghdad');
INSERT INTO `timezones` VALUES ('41', '3.0', '(GMT+03:00)-Moscow, St. Petersburg, Volgograd');
INSERT INTO `timezones` VALUES ('43', '3.5', '(GMT+03:30)-Tehran');
INSERT INTO `timezones` VALUES ('44', '4.0', '(GMT+04:00)-Abu Dhabi, Muscat');
INSERT INTO `timezones` VALUES ('45', '4.0', '(GMT+04:00)-Baku, Tbilisi, Yerevan');
INSERT INTO `timezones` VALUES ('46', '4.5', '(GMT+04:30)-Kabul');
INSERT INTO `timezones` VALUES ('51', '6.0', '(GMT+06:00)-Almaty, Novosibirsk');
INSERT INTO `timezones` VALUES ('54', '6.5', '(GMT+06:30)-Rangoon');
INSERT INTO `timezones` VALUES ('55', '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta');
INSERT INTO `timezones` VALUES ('56', '7.0', '(GMT+07:00)-Krasnoyarsk');
INSERT INTO `timezones` VALUES ('58', '8.0', '(GMT+08:00)-Irkutsk, Ulaan Bataar');
INSERT INTO `timezones` VALUES ('59', '8.0', '(GMT+08:00)-Kuala Lumpur, Singapore');
INSERT INTO `timezones` VALUES ('60', '8.0', '(GMT+08:00)-Perth');
INSERT INTO `timezones` VALUES ('63', '9.0', '(GMT+09:00)-Seoul');
INSERT INTO `timezones` VALUES ('64', '9.0', '(GMT+09:00)-Vakutsk');
INSERT INTO `timezones` VALUES ('66', '9.5', '(GMT+09:30)-Darwin');
INSERT INTO `timezones` VALUES ('69', '10.0', '(GMT+10:00)-Guam, Port Moresby');
INSERT INTO `timezones` VALUES ('71', '10.0', '(GMT+10:00)-Vladivostok');
INSERT INTO `timezones` VALUES ('74', '12.0', '(GMT+12:00)-Fiji, Kamchatka, Marshall Is.');
INSERT INTO `timezones` VALUES ('76', '-11.0', '(GMT-11:00)-Midway Island, Samoa');
INSERT INTO `timezones` VALUES ('77', '-10.0', '(GMT-10:00)-Hawaii');
INSERT INTO `timezones` VALUES ('81', '-7.0', '(GMT-07:00)-Chihuahua, La Paz, Mazatlan');
INSERT INTO `timezones` VALUES ('83', '-6.0', '(GMT-06:00)-Central America');
INSERT INTO `timezones` VALUES ('84', '-6.0', '(GMT-06:00)-Central Time (US & Canada)');
INSERT INTO `timezones` VALUES ('85', '-6.0', '(GMT-06:00)-Guadalajara, Mexico City, Monterrey');
INSERT INTO `timezones` VALUES ('86', '-6.0', '(GMT-06:00)-Saskatchewan');
INSERT INTO `timezones` VALUES ('88', '-5.0', '(GMT-05:00)-Eastern Time (US & Canada)');
INSERT INTO `timezones` VALUES ('90', '-4.0', '(GMT-04:00)-Atlantic Time (Canada)');
INSERT INTO `timezones` VALUES ('91', '-4.0', '(GMT-04:00)-Caracas, La Paz');
INSERT INTO `timezones` VALUES ('92', '-4.0', '(GMT-04:00)-Santiago');
INSERT INTO `timezones` VALUES ('94', '-3.0', '(GMT-03:00)-Brasilia');
INSERT INTO `timezones` VALUES ('95', '-3.0', '(GMT-03:00)-Buenos Aires, Georgetown');
INSERT INTO `timezones` VALUES ('98', '-1.0', '(GMT-01:00)-Azores');
INSERT INTO `timezones` VALUES ('99', '-1.0', '(GMT-01:00)-Cape Verde Is.');
INSERT INTO `timezones` VALUES ('100', '0.0', '(GMT)-Casablanca, Monrovia');
INSERT INTO `timezones` VALUES ('102', '1.0', '(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna');
INSERT INTO `timezones` VALUES ('103', '1.0', '(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague');
INSERT INTO `timezones` VALUES ('106', '1.0', '(GMT+01:00)-West Central Africa');
INSERT INTO `timezones` VALUES ('107', '2.0', '(GMT+02:00)-Athens, Beirut, Istanbul, Minsk');
INSERT INTO `timezones` VALUES ('108', '2.0', '(GMT+02:00)-Bucharest');
INSERT INTO `timezones` VALUES ('111', '2.0', '(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius');
INSERT INTO `timezones` VALUES ('114', '3.0', '(GMT+03:00)-Kuwait, Riyadh');
INSERT INTO `timezones` VALUES ('116', '3.0', '(GMT+03:00)-Nairobi');
INSERT INTO `timezones` VALUES ('121', '5.0', '(GMT+05:00)-Ekaterinburg');
INSERT INTO `timezones` VALUES ('122', '5.0', '(GMT+05:00)-Islamabad, Karachi, Tashkent');
INSERT INTO `timezones` VALUES ('123', '5.5', '(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi');
INSERT INTO `timezones` VALUES ('124', '5.8', '(GMT+05:45)-Kathmandu');
INSERT INTO `timezones` VALUES ('126', '6.0', '(GMT+06:00)-Astana, Dhaka');
INSERT INTO `timezones` VALUES ('127', '6.0', '(GMT+06:00)-Sri Jayawardenepura');
INSERT INTO `timezones` VALUES ('129', '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta');
INSERT INTO `timezones` VALUES ('131', '8.0', '(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi');
INSERT INTO `timezones` VALUES ('135', '8.0', '(GMT+08:00)-Taipei');
INSERT INTO `timezones` VALUES ('136', '9.0', '(GMT+09:00)-Osaka, Sapporo, Tokyo');
INSERT INTO `timezones` VALUES ('139', '9.5', '(GMT+09:30)-Adelaide');
INSERT INTO `timezones` VALUES ('141', '10.0', '(GMT+10:00)-Brisbane');
INSERT INTO `timezones` VALUES ('142', '10.0', '(GMT+10:00)-Canberra, Melbourne, Sydney');
INSERT INTO `timezones` VALUES ('144', '10.0', '(GMT+10:00)-Hobart');
INSERT INTO `timezones` VALUES ('146', '11.0', '(GMT+11:00)-Magadan, Solomon Is., New Caledonia');
INSERT INTO `timezones` VALUES ('147', '12.0', '(GMT+12:00)-Auckland, Wellington');
INSERT INTO `timezones` VALUES ('149', '13.0', '(GMT+13:00)-Nuku\'alofa ');
INSERT INTO `timezones` VALUES ('150', '-4.5', '(GMT-04:30)-Caracas');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
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
                       `user_timezone` int(3) NOT NULL DEFAULT 0,
                       `user_lotw_name` varchar(32) DEFAULT NULL,
                       `user_lotw_password` varchar(64) DEFAULT NULL,
                       `user_eqsl_name` varchar(32) DEFAULT NULL,
                       `user_eqsl_password` varchar(64) DEFAULT NULL,
                       `user_eqsl_qth_nickname` varchar(32) DEFAULT NULL,
                       PRIMARY KEY (`user_id`),
                       UNIQUE KEY `user_name` (`user_name`),
                       UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('4', 'm0abc', '$2a$08$r9UF3YhipAY6htSQoZRjeOFDx3Yuh7Zjuh45vKyUN4gO8g5l.saES', 'demo@demo.com', '99', 'M0ABC', 'io91js', 'Demo', 'Account', '0', null, null, null, null, null);



CREATE TABLE `dxcc_entities` (
  `adif` smallint(6) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `prefix` varchar(32) NOT NULL,
  `cqz` smallint(6) NOT NULL,
  `ituz` smallint(6) NOT NULL,
  `cont` varchar(5) NOT NULL,
  `long` float NOT NULL,
  `lat` float NOT NULL,
  `end` date DEFAULT NULL,
  `start` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dxcc_exceptions` (
  `record` int(11) NOT NULL,
  `call` varchar(32) DEFAULT NULL,
  `entity` varchar(255) NOT NULL,
  `adif` smallint(6) NOT NULL,
  `cqz` smallint(6) NOT NULL,
  `cont` varchar(5) NOT NULL,
  `long` float NOT NULL,
  `lat` float NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dxcc_prefixes` (
  `record` int(11) NOT NULL,
  `call` varchar(32) DEFAULT NULL,
  `entity` varchar(255) NOT NULL,
  `adif` smallint(6) NOT NULL,
  `cqz` smallint(6) NOT NULL,
  `cont` varchar(5) NOT NULL,
  `long` float NOT NULL,
  `lat` float NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `dxcc_entities`
--
ALTER TABLE `dxcc_entities`
  ADD PRIMARY KEY (`adif`);

--
-- Indexes for table `dxcc_exceptions`
--
ALTER TABLE `dxcc_exceptions`
  ADD PRIMARY KEY (`record`);

--
-- Indexes for table `dxcc_prefixes`
--
ALTER TABLE `dxcc_prefixes`
  ADD PRIMARY KEY (`record`);
