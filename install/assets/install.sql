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
INSERT INTO `config` VALUES ('1', 'https://p1k.arrl.org/lotwuser/lotwreport.adi', 'https://p1k.arrl.org/lotwuser/upload', 'Y', 'https://p1k.arrl.org/lotwuser/default', 'http://www.eqsl.cc/qslcard/DownloadInBox.cfm', 'Y');

-- ----------------------------
-- Table structure for contest_template
-- ----------------------------
DROP TABLE IF EXISTS `contest_template`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of contest_template
-- ----------------------------

-- ----------------------------
-- Table structure for contests
-- ----------------------------
DROP TABLE IF EXISTS `contests`;
CREATE TABLE `contests` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `start` datetime NOT NULL,
                          `end` datetime NOT NULL,
                          `template` int(11) NOT NULL,
                          `serial_num` tinyint(11) NOT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of contests
-- ----------------------------

-- ----------------------------
-- Table structure for dxcc
-- ----------------------------
DROP TABLE IF EXISTS `dxcc`;
CREATE TABLE `dxcc` (
                      `prefix` varchar(10) NOT NULL,
                      `name` varchar(150) DEFAULT NULL,
                      `cqz` float NOT NULL,
                      `ituz` float NOT NULL,
                      `cont` varchar(5) NOT NULL,
                      `long` float NOT NULL,
                      `lat` float NOT NULL,
                      PRIMARY KEY (`prefix`),
                      KEY `prefix` (`prefix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of dxcc
-- ----------------------------
INSERT INTO `dxcc` VALUES ('0A', 'SOV MIL ORDER OF MALTA', '15', '28', 'EU', '12.4', '41.9');
INSERT INTO `dxcc` VALUES ('1A', 'SOV MIL ORDER OF MALTA', '15', '28', 'EU', '12.4', '41.9');
INSERT INTO `dxcc` VALUES ('1S', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8');
INSERT INTO `dxcc` VALUES ('9M0', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8');
INSERT INTO `dxcc` VALUES ('BV9S', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8');
INSERT INTO `dxcc` VALUES ('3A', 'MONACO', '14', '27', 'EU', '7.4', '43.7');
INSERT INTO `dxcc` VALUES ('3B6', 'AGALEGA & ST. BRANDON', '39', '53', 'AF', '56.6', '-10.4');
INSERT INTO `dxcc` VALUES ('3B7', 'AGALEGA & ST. BRANDON', '39', '53', 'AF', '56.6', '-10.4');
INSERT INTO `dxcc` VALUES ('3B8', 'MAURITIUS', '39', '53', 'AF', '57.5', '-20.3');
INSERT INTO `dxcc` VALUES ('3B9', 'RODRIGUEZ I.', '39', '53', 'AF', '63.4', '-19.7');
INSERT INTO `dxcc` VALUES ('3C', 'EQUATORIAL GUINEA', '36', '47', 'AF', '9.8', '1.8');
INSERT INTO `dxcc` VALUES ('3C0', 'ANNOBON', '36', '52', 'AF', '5.6', '-1.5');
INSERT INTO `dxcc` VALUES ('3D2', 'FIJI', '32', '56', 'OC', '178.4', '-18.1');
INSERT INTO `dxcc` VALUES ('3D2/c', 'CONWAY REEF', '32', '56', 'OC', '174.4', '-21.4');
INSERT INTO `dxcc` VALUES ('3D2CI', 'CONWAY REEF', '32', '56', 'OC', '174.4', '-21.4');
INSERT INTO `dxcc` VALUES ('3D2CY', 'CONWAY REEF', '32', '56', 'OC', '174.4', '-21.4');
INSERT INTO `dxcc` VALUES ('3D2/r', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3');
INSERT INTO `dxcc` VALUES ('3D2AG/P', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3');
INSERT INTO `dxcc` VALUES ('3DA', 'SWAZILAND', '38', '57', 'AF', '31.1', '-26.3');
INSERT INTO `dxcc` VALUES ('3V', 'TUNISIA', '33', '37', 'AF', '10.2', '36.8');
INSERT INTO `dxcc` VALUES ('TS', 'TUNISIA', '33', '37', 'AF', '10.2', '36.8');
INSERT INTO `dxcc` VALUES ('3W', 'VIETNAM', '26', '49', 'AS', '106.7', '10.8');
INSERT INTO `dxcc` VALUES ('XV', 'VIETNAM', '26', '49', 'AS', '106.7', '10.8');
INSERT INTO `dxcc` VALUES ('3X', 'GUINEA', '35', '46', 'AF', '-13.7', '9.5');
INSERT INTO `dxcc` VALUES ('3Y/b', 'BOUVET', '38', '67', 'AF', '3.4', '-54.5');
INSERT INTO `dxcc` VALUES ('3Y', 'BOUVET', '38', '67', 'AF', '3.4', '-54.5');
INSERT INTO `dxcc` VALUES ('3Y/p', 'PETER I I.', '12', '72', 'SA', '-90.6', '-68.8');
INSERT INTO `dxcc` VALUES ('3Y0X', 'PETER I I.', '12', '72', 'SA', '-90.6', '-68.8');
INSERT INTO `dxcc` VALUES ('4J', 'AZERBAIJAN', '21', '29', 'AS', '49.9', '40.4');
INSERT INTO `dxcc` VALUES ('4K', 'AZERBAIJAN', '21', '29', 'AS', '49.9', '40.4');
INSERT INTO `dxcc` VALUES ('4L', 'GEORGIA', '21', '29', 'AS', '44.8', '41.7');
INSERT INTO `dxcc` VALUES ('4O', 'MONTENEGRO', '15', '28', 'EU', '19.3', '42.5');
INSERT INTO `dxcc` VALUES ('4S', 'SRI LANKA', '22', '41', 'AS', '79.9', '7');
INSERT INTO `dxcc` VALUES ('4P', 'SRI LANKA', '22', '41', 'AS', '79.9', '7');
INSERT INTO `dxcc` VALUES ('4Q', 'SRI LANKA', '22', '41', 'AS', '79.9', '7');
INSERT INTO `dxcc` VALUES ('4R', 'SRI LANKA', '22', '41', 'AS', '79.9', '7');
INSERT INTO `dxcc` VALUES ('4U1I', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U1ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U0ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U1WRC', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U2ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U3ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U4ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U5ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U6ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U7ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U8ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U9ITU', 'ITU HQ GENEVA', '14', '28', 'EU', '6.2', '46.2');
INSERT INTO `dxcc` VALUES ('4U1U', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U0UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U1UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U2UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U3UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U4UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U5UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U6UN', 'UNITED NATIONS HQ', '5', '8', 'NA', '-74', '40.8');
INSERT INTO `dxcc` VALUES ('4U1V', 'AUSTRIA', '15', '28', 'EU', '16.3', '48.2');
INSERT INTO `dxcc` VALUES ('4U1VIC', 'AUSTRIA', '15', '28', 'EU', '16.3', '48.2');
INSERT INTO `dxcc` VALUES ('4U1WED', 'AUSTRIA', '15', '28', 'EU', '16.3', '48.2');
INSERT INTO `dxcc` VALUES ('4W', 'EAST TIMOR', '28', '54', 'OC', '125.5', '-8.6');
INSERT INTO `dxcc` VALUES ('4X', 'ISRAEL', '20', '39', 'AS', '35.2', '31.8');
INSERT INTO `dxcc` VALUES ('4Z', 'ISRAEL', '20', '39', 'AS', '35.2', '31.8');
INSERT INTO `dxcc` VALUES ('5A', 'LIBYA', '34', '38', 'AF', '12.5', '32.5');
INSERT INTO `dxcc` VALUES ('5B', 'CYPRUS', '20', '39', 'AS', '33.4', '35.2');
INSERT INTO `dxcc` VALUES ('C4', 'CYPRUS', '20', '39', 'AS', '33.4', '35.2');
INSERT INTO `dxcc` VALUES ('H2', 'CYPRUS', '20', '39', 'AS', '33.4', '35.2');
INSERT INTO `dxcc` VALUES ('P3', 'CYPRUS', '20', '39', 'AS', '33.4', '35.2');
INSERT INTO `dxcc` VALUES ('5H', 'TANZANIA', '37', '53', 'AF', '39.5', '-7');
INSERT INTO `dxcc` VALUES ('5I', 'TANZANIA', '37', '53', 'AF', '39.5', '-7');
INSERT INTO `dxcc` VALUES ('5N', 'NIGERIA', '35', '46', 'AF', '3.4', '6.5');
INSERT INTO `dxcc` VALUES ('5O', 'NIGERIA', '35', '46', 'AF', '3.4', '6.5');
INSERT INTO `dxcc` VALUES ('5R', 'MADAGASCAR', '39', '53', 'AF', '47.5', '-18.9');
INSERT INTO `dxcc` VALUES ('5S', 'MADAGASCAR', '39', '53', 'AF', '47.5', '-18.9');
INSERT INTO `dxcc` VALUES ('6X', 'MADAGASCAR', '39', '53', 'AF', '47.5', '-18.9');
INSERT INTO `dxcc` VALUES ('5T', 'MAURITANIA', '35', '46', 'AF', '-16', '18.1');
INSERT INTO `dxcc` VALUES ('5U', 'NIGER', '35', '46', 'AF', '2', '13.5');
INSERT INTO `dxcc` VALUES ('5V', 'TOGO', '35', '46', 'AF', '1.4', '6.2');
INSERT INTO `dxcc` VALUES ('5W', 'SAMOA', '32', '62', 'OC', '-171.8', '-13.5');
INSERT INTO `dxcc` VALUES ('5X', 'UGANDA', '37', '48', 'AF', '32.5', '0.3');
INSERT INTO `dxcc` VALUES ('5Z', 'KENYA', '37', '48', 'AF', '37.5', '-1.3');
INSERT INTO `dxcc` VALUES ('5Y', 'KENYA', '37', '48', 'AF', '37.5', '-1.3');
INSERT INTO `dxcc` VALUES ('6W', 'SENEGAL', '35', '46', 'AF', '-17.5', '14.7');
INSERT INTO `dxcc` VALUES ('6V', 'SENEGAL', '35', '46', 'AF', '-17.5', '14.7');
INSERT INTO `dxcc` VALUES ('6Y', 'JAMAICA', '8', '11', 'NA', '-76.8', '18');
INSERT INTO `dxcc` VALUES ('7O', 'YEMEN', '21', '39', 'AS', '45', '12.8');
INSERT INTO `dxcc` VALUES ('7P', 'LESOTHO', '38', '57', 'AF', '27.5', '-29.3');
INSERT INTO `dxcc` VALUES ('7Q', 'MALAWI', '37', '53', 'AF', '34.4', '-14.9');
INSERT INTO `dxcc` VALUES ('7X', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7R', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7T', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7U', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7V', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7W', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('7Y', 'ALGERIA', '33', '37', 'AF', '3', '36.7');
INSERT INTO `dxcc` VALUES ('8P', 'BARBADOS', '8', '11', 'NA', '-59.6', '13.1');
INSERT INTO `dxcc` VALUES ('8Q', 'MALDIVES', '22', '41', 'AS', '73.4', '4.4');
INSERT INTO `dxcc` VALUES ('8R', 'GUYANA', '9', '12', 'SA', '-58.2', '6.8');
INSERT INTO `dxcc` VALUES ('9A', 'CROATIA', '15', '28', 'EU', '15.6', '45.5');
INSERT INTO `dxcc` VALUES ('9G', 'GHANA', '35', '46', 'AF', '-0.2', '5.5');
INSERT INTO `dxcc` VALUES ('9H', 'MALTA', '15', '28', 'EU', '14.4', '36');
INSERT INTO `dxcc` VALUES ('9J', 'ZAMBIA', '36', '53', 'AF', '28.3', '-15.4');
INSERT INTO `dxcc` VALUES ('9I', 'ZAMBIA', '36', '53', 'AF', '28.3', '-15.4');
INSERT INTO `dxcc` VALUES ('9K', 'KUWAIT', '21', '39', 'AS', '47.8', '29.5');
INSERT INTO `dxcc` VALUES ('9L', 'SIERRA LEONE', '35', '46', 'AF', '-13.2', '8.5');
INSERT INTO `dxcc` VALUES ('9M2', 'WEST MALAYSIA', '28', '54', 'AS', '101.6', '3.2');
INSERT INTO `dxcc` VALUES ('9M1', 'WEST MALAYSIA', '28', '54', 'AS', '101.6', '3.2');
INSERT INTO `dxcc` VALUES ('9M4', 'WEST MALAYSIA', '28', '54', 'AS', '101.6', '3.2');
INSERT INTO `dxcc` VALUES ('9W2', 'WEST MALAYSIA', '28', '54', 'AS', '101.6', '3.2');
INSERT INTO `dxcc` VALUES ('9W4', 'WEST MALAYSIA', '28', '54', 'AS', '101.6', '3.2');
INSERT INTO `dxcc` VALUES ('9M6', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9M8', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9W6', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9W8', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9M1CSQ', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9M1CSS', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9M4SEA', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9M4SMO', 'EAST MALAYSIA', '28', '54', 'OC', '118.1', '5.8');
INSERT INTO `dxcc` VALUES ('9N', 'NEPAL', '22', '42', 'AS', '85.3', '27.7');
INSERT INTO `dxcc` VALUES ('9Q', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9O', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9P', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9R', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9S', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9T', 'DEM. REPUBLIC OF THE CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('9U', 'BURUNDI', '36', '52', 'AF', '29.3', '-3.3');
INSERT INTO `dxcc` VALUES ('9V', 'SINGAPORE', '28', '54', 'AS', '103.8', '1.3');
INSERT INTO `dxcc` VALUES ('S6', 'SINGAPORE', '28', '54', 'AS', '103.8', '1.3');
INSERT INTO `dxcc` VALUES ('9X', 'RWANDA', '36', '52', 'AF', '30.1', '-2');
INSERT INTO `dxcc` VALUES ('9Y', 'TRINIDAD & TOBAGO', '9', '11', 'SA', '-61.3', '10.5');
INSERT INTO `dxcc` VALUES ('9Z', 'TRINIDAD & TOBAGO', '9', '11', 'SA', '-61.3', '10.5');
INSERT INTO `dxcc` VALUES ('A2', 'BOTSWANA', '38', '57', 'AF', '24', '-22');
INSERT INTO `dxcc` VALUES ('8O', 'BOTSWANA', '38', '57', 'AF', '24', '-22');
INSERT INTO `dxcc` VALUES ('A3', 'TONGA', '32', '62', 'OC', '-175.2', '-21.1');
INSERT INTO `dxcc` VALUES ('A4', 'OMAN', '21', '39', 'AS', '58.6', '23.6');
INSERT INTO `dxcc` VALUES ('A5', 'BHUTAN', '22', '41', 'AS', '89.4', '27.3');
INSERT INTO `dxcc` VALUES ('A6', 'UNITED ARAB EMIRATES', '21', '39', 'AS', '54.2', '24.5');
INSERT INTO `dxcc` VALUES ('A7', 'QATAR', '21', '39', 'AS', '51.5', '25.3');
INSERT INTO `dxcc` VALUES ('A9', 'BAHRAIN', '21', '39', 'AS', '50.6', '26.2');
INSERT INTO `dxcc` VALUES ('AP', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('6P', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('6Q', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('6R', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('6S', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('AQ', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('AR', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('AS', 'PAKISTAN', '21', '41', 'AS', '70', '30');
INSERT INTO `dxcc` VALUES ('BS7', 'SCARBOROUGH REEF', '27', '50', 'AS', '117.5', '15.1');
INSERT INTO `dxcc` VALUES ('BV', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BM', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BN', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BO', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BP', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BQ', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BU', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BW', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BX', 'TAIWAN', '24', '44', 'AS', '121', '23.8');
INSERT INTO `dxcc` VALUES ('BV9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BM9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BN9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BO9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BP9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BQ9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BU9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BW9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BX9P', 'PRATAS ISLAND', '24', '44', 'AS', '116.4', '20.4');
INSERT INTO `dxcc` VALUES ('BY', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3T', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('3U', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B1', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B2', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B4', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B5', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B6', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B7', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B8', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('B9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BA9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BD9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BG9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BH9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BI', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BL9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BT9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BY9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3G', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3H', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3I', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3J', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3K', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ3L', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9M', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9N', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9O', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9P', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9Q', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9R', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('BZ9S', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('XS', 'CHINA', '24', '44', 'AS', '116.4', '40');
INSERT INTO `dxcc` VALUES ('C2', 'NAURU', '31', '65', 'OC', '166.9', '-0.5');
INSERT INTO `dxcc` VALUES ('C3', 'ANDORRA', '14', '27', 'EU', '1.5', '42.5');
INSERT INTO `dxcc` VALUES ('C5', 'GAMBIA', '35', '46', 'AF', '-16.7', '13.5');
INSERT INTO `dxcc` VALUES ('C6', 'BAHAMAS', '8', '11', 'NA', '-76', '24.25');
INSERT INTO `dxcc` VALUES ('C9', 'MOZAMBIQUE', '37', '53', 'AF', '35', '-18.25');
INSERT INTO `dxcc` VALUES ('C8', 'MOZAMBIQUE', '37', '53', 'AF', '35', '-18.25');
INSERT INTO `dxcc` VALUES ('CE', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('3G', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('CA', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('CB', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('CC', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('CD', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('XQ', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('XR', 'CHILE', '12', '14', 'SA', '-71', '-30');
INSERT INTO `dxcc` VALUES ('CE0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('3G0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('CA0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('CB0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('CC0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('CD0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('XQ0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('XR0X', 'SAN FELIX I.', '12', '14', 'SA', '-80.1', '-26.3');
INSERT INTO `dxcc` VALUES ('CE0Y', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('3G0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CA0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CB0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CC0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CD0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CE0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('XQ0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('XR0', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1');
INSERT INTO `dxcc` VALUES ('CE0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('3G0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CA0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CB0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CC0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CD0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CE0I', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('XQ0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('XR0Z', 'JUAN FERNANDEZ IS.', '12', '14', 'SA', '-78.8', '-33.6');
INSERT INTO `dxcc` VALUES ('CE9', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('ANT', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('AX0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('FT0Y', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('FT2Y', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('FT4Y', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('FT5Y', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('FT8Y', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('LU1Z', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('R1AN', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VH0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VI0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VJ0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VK0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VL0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VM0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VN0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VZ0', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('ZL5', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('ZM5', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('ZS7', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('8J1RF', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('8J1RL', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('CE9/K2ARB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('DP0GVN', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('DP1POL', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('KC4/K2ARB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('KC4AAA', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('KC4AAC', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('KC4USB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('KC4USV', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('LU4ZS', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('OP0LE', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('OP0OL', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('R1ANR', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VP8DJB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VP8DKF', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VP8DLJ', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VP8PJ', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('VP8ROT', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65');
INSERT INTO `dxcc` VALUES ('CM', 'CUBA', '8', '11', 'NA', '-80', '21.5');
INSERT INTO `dxcc` VALUES ('CL', 'CUBA', '8', '11', 'NA', '-80', '21.5');
INSERT INTO `dxcc` VALUES ('CO', 'CUBA', '8', '11', 'NA', '-80', '21.5');
INSERT INTO `dxcc` VALUES ('T4', 'CUBA', '8', '11', 'NA', '-80', '21.5');
INSERT INTO `dxcc` VALUES ('CN', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('5C', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('5D', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('5E', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('5F', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('5G', 'MOROCCO', '33', '37', 'AF', '-5', '32');
INSERT INTO `dxcc` VALUES ('CP', 'BOLIVIA', '10', '12', 'SA', '-65', '-17');
INSERT INTO `dxcc` VALUES ('CT', 'PORTUGAL', '14', '37', 'EU', '-8', '39.5');
INSERT INTO `dxcc` VALUES ('CQ', 'PORTUGAL', '14', '37', 'EU', '-8', '39.5');
INSERT INTO `dxcc` VALUES ('CR', 'PORTUGAL', '14', '37', 'EU', '-8', '39.5');
INSERT INTO `dxcc` VALUES ('CS', 'PORTUGAL', '14', '37', 'EU', '-8', '39.5');
INSERT INTO `dxcc` VALUES ('CT3', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CQ3', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CQ9', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CR3', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CR9', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CS3', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CS9', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CT9', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('XX', 'MADEIRA IS.', '33', '36', 'AF', '-16.9', '32.6');
INSERT INTO `dxcc` VALUES ('CU', 'AZORES', '14', '36', 'EU', '-25.7', '37.7');
INSERT INTO `dxcc` VALUES ('CX', 'URUGUAY', '13', '14', 'SA', '-56', '-33');
INSERT INTO `dxcc` VALUES ('CV', 'URUGUAY', '13', '14', 'SA', '-56', '-33');
INSERT INTO `dxcc` VALUES ('CW', 'URUGUAY', '13', '14', 'SA', '-56', '-33');
INSERT INTO `dxcc` VALUES ('CY0', 'SABLE I.', '5', '9', 'NA', '-60', '43.8');
INSERT INTO `dxcc` VALUES ('CY9', 'ST. PAUL I.', '5', '9', 'NA', '-60.1', '47.2');
INSERT INTO `dxcc` VALUES ('D2', 'ANGOLA', '36', '52', 'AF', '18.5', '-12.5');
INSERT INTO `dxcc` VALUES ('D3', 'ANGOLA', '36', '52', 'AF', '18.5', '-12.5');
INSERT INTO `dxcc` VALUES ('D4', 'CAPE VERDE', '35', '46', 'AF', '-24', '16');
INSERT INTO `dxcc` VALUES ('D6', 'COMOROS', '39', '53', 'AF', '43.7', '-11.8');
INSERT INTO `dxcc` VALUES ('DL', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DA', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DB', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DC', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DD', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DE', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DF', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DG', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DH', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DI', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DJ', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DK', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DM', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DN', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DO', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DP', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DQ', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DR', 'GERMANY', '14', '28', 'EU', '10', '51');
INSERT INTO `dxcc` VALUES ('DU', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4D', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4E', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4F', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4G', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4H', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('4I', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('DV', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('DW', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('DX', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('DY', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('DZ', 'PHILIPPINES', '27', '50', 'OC', '122', '13');
INSERT INTO `dxcc` VALUES ('E3', 'ERITREA', '37', '48', 'AF', '38.9', '15.3');
INSERT INTO `dxcc` VALUES ('E4', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4');
INSERT INTO `dxcc` VALUES ('E5/n', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4');
INSERT INTO `dxcc` VALUES ('E51WL', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4');
INSERT INTO `dxcc` VALUES ('E5/s', 'SOUTH COOK IS.', '32', '62', 'OC', '-159.8', '-21.2');
INSERT INTO `dxcc` VALUES ('E5', 'SOUTH COOK IS.', '32', '62', 'OC', '-159.8', '-21.2');
INSERT INTO `dxcc` VALUES ('E7', 'BOSNIA-HERZEGOVINA', '15', '28', 'EU', '18.3', '43.5');
INSERT INTO `dxcc` VALUES ('T9', 'BOSNIA-HERZEGOVINA', '15', '28', 'EU', '18.3', '43.5');
INSERT INTO `dxcc` VALUES ('EA', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('AM', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('AN', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('AO', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EB', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EC', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('ED', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EE', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EF', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EG', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EH', 'SPAIN', '14', '37', 'EU', '-3.7', '40.4');
INSERT INTO `dxcc` VALUES ('EA6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('AM6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('AN6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('AO6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EB6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EC6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('ED6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EE6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EF6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EG6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EH6', 'BALEARIC IS.', '14', '37', 'EU', '2.6', '39.5');
INSERT INTO `dxcc` VALUES ('EA8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('AM8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('AN8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('AO8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EB8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EC8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('ED8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EE8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EF8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EG8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EH8', 'CANARY IS.', '33', '36', 'AF', '-15.3', '28.4');
INSERT INTO `dxcc` VALUES ('EA9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('AM9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('AN9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('AO9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EB9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EC9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('ED9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EE9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EF9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EG9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EH9', 'CEUTA AND MELILLA', '33', '37', 'AF', '-3', '35.6');
INSERT INTO `dxcc` VALUES ('EI', 'IRELAND', '14', '27', 'EU', '-6.3', '53.3');
INSERT INTO `dxcc` VALUES ('EJ', 'IRELAND', '14', '27', 'EU', '-6.3', '53.3');
INSERT INTO `dxcc` VALUES ('EK', 'ARMENIA', '21', '29', 'AS', '44.5', '40.3');
INSERT INTO `dxcc` VALUES ('EL', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('5L', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('5M', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('6Z', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('A8', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('D5', 'LIBERIA', '35', '46', 'AF', '-9.5', '6.5');
INSERT INTO `dxcc` VALUES ('EP', 'IRAN', '21', '40', 'AS', '53', '32');
INSERT INTO `dxcc` VALUES ('9B', 'IRAN', '21', '40', 'AS', '53', '32');
INSERT INTO `dxcc` VALUES ('9C', 'IRAN', '21', '40', 'AS', '53', '32');
INSERT INTO `dxcc` VALUES ('9D', 'IRAN', '21', '40', 'AS', '53', '32');
INSERT INTO `dxcc` VALUES ('EQ', 'IRAN', '21', '40', 'AS', '53', '32');
INSERT INTO `dxcc` VALUES ('ER', 'MOLDOVA', '16', '29', 'EU', '28.8', '47');
INSERT INTO `dxcc` VALUES ('ES', 'ESTONIA', '15', '29', 'EU', '24.8', '59.4');
INSERT INTO `dxcc` VALUES ('ET', 'ETHIOPIA', '37', '48', 'AF', '38.7', '9');
INSERT INTO `dxcc` VALUES ('9E', 'ETHIOPIA', '37', '48', 'AF', '38.7', '9');
INSERT INTO `dxcc` VALUES ('9F', 'ETHIOPIA', '37', '48', 'AF', '38.7', '9');
INSERT INTO `dxcc` VALUES ('EU', 'BELARUS', '16', '29', 'EU', '27.6', '53.9');
INSERT INTO `dxcc` VALUES ('EV', 'BELARUS', '16', '29', 'EU', '27.6', '53.9');
INSERT INTO `dxcc` VALUES ('EW', 'BELARUS', '16', '29', 'EU', '27.6', '53.9');
INSERT INTO `dxcc` VALUES ('EX', 'KYRGYZSTAN', '17', '31', 'AS', '74.6', '42.9');
INSERT INTO `dxcc` VALUES ('EY', 'TAJIKISTAN', '17', '30', 'AS', '66.8', '39.7');
INSERT INTO `dxcc` VALUES ('EZ', 'TURKMENISTAN', '17', '30', 'AS', '58.4', '38');
INSERT INTO `dxcc` VALUES ('F', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('HW', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('HX', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('HY', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TH', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TM', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TP', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TQ', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TV', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('TW', 'FRANCE', '14', '27', 'EU', '2', '46');
INSERT INTO `dxcc` VALUES ('FG', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO1T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO1USB', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO2ANT', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO2FG', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO2HI', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO4T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO5BG', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO5C', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO5G', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO5GI', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO5S', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO6T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO7T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO8RR', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO8S', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('TO9T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16');
INSERT INTO `dxcc` VALUES ('FH', 'MAYOTTE', '39', '53', 'AF', '45.3', '-13');
INSERT INTO `dxcc` VALUES ('TX7LX', 'MAYOTTE', '39', '53', 'AF', '45.3', '-13');
INSERT INTO `dxcc` VALUES ('FJ', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9');
INSERT INTO `dxcc` VALUES ('TO5DX', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9');
INSERT INTO `dxcc` VALUES ('TO5E', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9');
INSERT INTO `dxcc` VALUES ('TO5FJ', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9');
INSERT INTO `dxcc` VALUES ('TO5RZ', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9');
INSERT INTO `dxcc` VALUES ('FK', 'NEW CALEDONIA', '32', '56', 'OC', '165.5', '-21.5');
INSERT INTO `dxcc` VALUES ('TX8', 'NEW CALEDONIA', '32', '56', 'OC', '165.5', '-21.5');
INSERT INTO `dxcc` VALUES ('TX1A', 'NEW CALEDONIA', '32', '56', 'OC', '165.5', '-21.5');
INSERT INTO `dxcc` VALUES ('TX3SAM', 'NEW CALEDONIA', '32', '56', 'OC', '165.5', '-21.5');
INSERT INTO `dxcc` VALUES ('TX5CW', 'NEW CALEDONIA', '32', '56', 'OC', '165.5', '-21.5');
INSERT INTO `dxcc` VALUES ('FK/c', 'CHESTERFIELD IS.', '30', '56', 'OC', '158.3', '-19.9');
INSERT INTO `dxcc` VALUES ('TX0AT', 'CHESTERFIELD IS.', '30', '56', 'OC', '158.3', '-19.9');
INSERT INTO `dxcc` VALUES ('TX0C', 'CHESTERFIELD IS.', '30', '56', 'OC', '158.3', '-19.9');
INSERT INTO `dxcc` VALUES ('TX0DX', 'CHESTERFIELD IS.', '30', '56', 'OC', '158.3', '-19.9');
INSERT INTO `dxcc` VALUES ('TX9', 'CHESTERFIELD IS.', '30', '56', 'OC', '158.3', '-19.9');
INSERT INTO `dxcc` VALUES ('FM', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO0O', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO0P', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO1A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO1C', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO1YR', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO2DX', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO3M', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO3T', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO3W', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO4A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5AA', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5J', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5MM', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5T', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO5X', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO6M', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO7HAM', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO7X', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO8B', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO8Z', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TO9A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('TX4B', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6');
INSERT INTO `dxcc` VALUES ('FO', 'FRENCH POLYNESIA', '32', '63', 'OC', '-149.5', '-17.6');
INSERT INTO `dxcc` VALUES ('FO/a', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5');
INSERT INTO `dxcc` VALUES ('FO/DL1AWI', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5');
INSERT INTO `dxcc` VALUES ('FO/DL5XU', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5');
INSERT INTO `dxcc` VALUES ('FO/DL9AWI', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5');
INSERT INTO `dxcc` VALUES ('FO/c', 'CLIPPERTON IS.', '7', '10', 'NA', '-109.2', '10.3');
INSERT INTO `dxcc` VALUES ('TX5C', 'CLIPPERTON IS.', '7', '10', 'NA', '-109.2', '10.3');
INSERT INTO `dxcc` VALUES ('FO/m', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9');
INSERT INTO `dxcc` VALUES ('FO/DJ7RJ', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9');
INSERT INTO `dxcc` VALUES ('FP', 'ST. PIERRE & MIQUELON', '5', '9', 'NA', '-56', '46.7');
INSERT INTO `dxcc` VALUES ('FR', 'REUNION', '39', '53', 'AF', '55.6', '-21.1');
INSERT INTO `dxcc` VALUES ('TO3R', 'REUNION', '39', '53', 'AF', '55.6', '-21.1');
INSERT INTO `dxcc` VALUES ('FR/g', 'GLORIOSO', '39', '53', 'AF', '47.3', '-11.5');
INSERT INTO `dxcc` VALUES ('TO4G', 'GLORIOSO', '39', '53', 'AF', '47.3', '-11.5');
INSERT INTO `dxcc` VALUES ('FR/j', 'JUAN DE NOVA & EUROPA', '39', '53', 'AF', '41.6', '-19.6');
INSERT INTO `dxcc` VALUES ('TO4E', 'JUAN DE NOVA & EUROPA', '39', '53', 'AF', '41.6', '-19.6');
INSERT INTO `dxcc` VALUES ('FR/t', 'TROMELIN', '39', '53', 'AF', '54.4', '-15.9');
INSERT INTO `dxcc` VALUES ('FR5ZU/T', 'TROMELIN', '39', '53', 'AF', '54.4', '-15.9');
INSERT INTO `dxcc` VALUES ('FS', 'FRENCH ST. MARTIN', '8', '11', 'NA', '-63.1', '18.1');
INSERT INTO `dxcc` VALUES ('TO4X', 'FRENCH ST. MARTIN', '8', '11', 'NA', '-63.1', '18.1');
INSERT INTO `dxcc` VALUES ('TO5D', 'FRENCH ST. MARTIN', '8', '11', 'NA', '-63.1', '18.1');
INSERT INTO `dxcc` VALUES ('FT5W', 'CROZET', '39', '68', 'AF', '52', '-46');
INSERT INTO `dxcc` VALUES ('FT0W', 'CROZET', '39', '68', 'AF', '52', '-46');
INSERT INTO `dxcc` VALUES ('FT2W', 'CROZET', '39', '68', 'AF', '52', '-46');
INSERT INTO `dxcc` VALUES ('FT4W', 'CROZET', '39', '68', 'AF', '52', '-46');
INSERT INTO `dxcc` VALUES ('FT8W', 'CROZET', '39', '68', 'AF', '52', '-46');
INSERT INTO `dxcc` VALUES ('FT5X', 'KERGUELEN', '39', '68', 'AF', '69.2', '-49.3');
INSERT INTO `dxcc` VALUES ('FT0X', 'KERGUELEN', '39', '68', 'AF', '69.2', '-49.3');
INSERT INTO `dxcc` VALUES ('FT2X', 'KERGUELEN', '39', '68', 'AF', '69.2', '-49.3');
INSERT INTO `dxcc` VALUES ('FT4X', 'KERGUELEN', '39', '68', 'AF', '69.2', '-49.3');
INSERT INTO `dxcc` VALUES ('FT8X', 'KERGUELEN', '39', '68', 'AF', '69.2', '-49.3');
INSERT INTO `dxcc` VALUES ('FT5Z', 'AMSTERDAM & ST. PAUL', '39', '68', 'AF', '77.6', '-37.7');
INSERT INTO `dxcc` VALUES ('FT0Z', 'AMSTERDAM & ST. PAUL', '39', '68', 'AF', '77.6', '-37.7');
INSERT INTO `dxcc` VALUES ('FT2Z', 'AMSTERDAM & ST. PAUL', '39', '68', 'AF', '77.6', '-37.7');
INSERT INTO `dxcc` VALUES ('FT4Z', 'AMSTERDAM & ST. PAUL', '39', '68', 'AF', '77.6', '-37.7');
INSERT INTO `dxcc` VALUES ('FT8Z', 'AMSTERDAM & ST. PAUL', '39', '68', 'AF', '77.6', '-37.7');
INSERT INTO `dxcc` VALUES ('FW', 'WALLIS & FUTUNA IS.', '32', '62', 'OC', '-176.3', '-13.3');
INSERT INTO `dxcc` VALUES ('FY', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9');
INSERT INTO `dxcc` VALUES ('TO7C', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9');
INSERT INTO `dxcc` VALUES ('TO7IR', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9');
INSERT INTO `dxcc` VALUES ('TO7R', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9');
INSERT INTO `dxcc` VALUES ('TX0A', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9');
INSERT INTO `dxcc` VALUES ('G', 'ENGLAND', '14', '27', 'EU', '-0.1', '51.5');
INSERT INTO `dxcc` VALUES ('2E', 'ENGLAND', '14', '27', 'EU', '-0.1', '51.5');
INSERT INTO `dxcc` VALUES ('M', 'ENGLAND', '14', '27', 'EU', '-0.1', '51.5');
INSERT INTO `dxcc` VALUES ('M/N2WKS', 'ENGLAND', '14', '27', 'EU', '-0.1', '51.5');
INSERT INTO `dxcc` VALUES ('GD', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('2D', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('2T', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GT', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('MD', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('MT', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB0MST', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB0WCY', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB100MER', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB100TT', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB125SR', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB2IOM', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB2MAD', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB2WB', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB3GD', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB4IOM', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB4MNH', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB4WXM/P', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB50UN', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB5MOB', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GB6SPC', 'ISLE OF MAN', '14', '27', 'EU', '-4.5', '54.3');
INSERT INTO `dxcc` VALUES ('GI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('2I', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('2N', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GN', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('MI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('MN', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0BTC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0BVC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0CI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0CSC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0DDF', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0GPF', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0MFD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0PSM', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0REL', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0SHC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0SIC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0SPD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0TCH', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB0WOA', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB1AFP', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB1SPD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB1SRI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2IL', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2LL', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2MGY', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2MRI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2NIC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2NTU', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2STI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB2TCA', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB3MNI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB4CSC', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB4ES', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB4SPD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB50AAD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB5BIG', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB5BL', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB5SPD', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GB90SOM', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6');
INSERT INTO `dxcc` VALUES ('GJ', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('2H', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('2J', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GH', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('MH', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('MJ', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB0CLR', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB0GUD', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB0JSA', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB0SHL', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB2BYL', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB2JSA', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB4BHF', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB50JSA', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GB8LMI', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3');
INSERT INTO `dxcc` VALUES ('GM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2A', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2M', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2S', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0AC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0BNC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0BWT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0DGL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0FFS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0FLA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0GDS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0GEI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0GHD', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0GKR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0GNE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0HHW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0KGS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0KTC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0LCS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0LTM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0MFG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0MLM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0MOL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0NHL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0OS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0OYT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0PPE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0QWM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0RBS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SHP', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SK', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SKY', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0SSF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB0TI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB100MAS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB125BRC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB150NRL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB1EPC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB1FS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB1FVT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB1OL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2AGG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2AST', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2AYR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2CHG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2DHS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2DTM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2FBM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2FIO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2FSM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2GEO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2GNL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2GTM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2HI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2HRH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2HST', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2HSW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IAS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IGB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IGS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IMM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IOC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IOG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2IOT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2JUNO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2KDS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2KHL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LAY', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LBN', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LCL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LCP', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LGB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LHI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LMG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LNM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LP', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LSS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LTH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2LTN', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MAS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MDG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MOD', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MOF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MSL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2MUL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NAG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NBC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NCL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NEF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2NTS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2OWM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2OYC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2PBF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2PS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2RB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2RRL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2SKG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2SLH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2SPD', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2SSF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2STB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2TDS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2TI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2WBB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB3GM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB400CA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4AAS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4CGW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4DAS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4GM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4LNM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4NFE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4PMS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4RAF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4SLH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4TSR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB4ZBS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB50ATC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB50JS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB50SWL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5AST', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5BBS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5CO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5FHC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5JS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5OL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5RO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5SI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB5TI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB60BBC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB60CRB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB60NTS', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB6MI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB6SA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB6SM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB6TAA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB6WW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB700BSB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB75GD', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB75SCP', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB75STT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8AYR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CN', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CSL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8CY', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8FF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8OO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB8RU', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB93AM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM/s', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GZ', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MZ', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2M0BDR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2M0BDT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('2M0ZET', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GB2ELH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0AVR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0CXQ', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0CYJ', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0DJI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0EKM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0ILB', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM0ULK', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM1KKI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM1ZNR', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM3KLA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM3WHT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM3ZET', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM3ZNM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4GPP', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4GQM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4IPK', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4LBE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4LER', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4PXG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4SLV', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4SSA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4SWU', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4WXQ', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM4ZHL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM6RQW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM7AFE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM7GWW', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM7RKD', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM8IHT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM8LNH', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM8MMA', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GM8YEC', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM0LSM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM0XAU', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM0ZAL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM0ZCG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM1FJM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM3CPE', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM3VQO', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM3ZET', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MM5PSL', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MS0ZCG', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('MS0ZET', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8');
INSERT INTO `dxcc` VALUES ('GU', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('2P', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('2U', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GP', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('MP', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('MU', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB0GUC', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB0JAG', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB0ON', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB0U', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB2ECG', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB2FG', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB2GU', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GB50LIB', 'GUERNSEY', '14', '27', 'EU', '-2.7', '49.5');
INSERT INTO `dxcc` VALUES ('GW', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('2C', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('2W', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('2X', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('2Y', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('MC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('MW', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0CCE', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0CLC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0CVA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0GCR', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0GIW', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0GLV', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0HEL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0HMT', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0ML', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0MPA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0MWL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0NEW', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0PSG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0RPO', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0RSC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0SDD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0SH', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0SOA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0SPS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0SRH', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0TD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0TTT', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0VK', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB0WRC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB100BD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB100FI', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB100LP', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB1CCC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB1LSG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB1SL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB1SSL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB1TDS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2000SET', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB200A', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB200HNT', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2ANG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2CI', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2CPC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2GGM', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2GLS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2GOL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2GSG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2GSS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2HDG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2IMD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2LNP', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2LSA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2MHL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2MIL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2MLM', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2MOP', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2RFS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2RSC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2RTB', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2SAC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2SDD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2SIP', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2TD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2TTA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2VK', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2WDS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2WFF', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2WHO', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB2WSF', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4BPL', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4CI', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4DPS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4HMD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4HMM', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4LRG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4LSG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4MD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4MDI', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4MUU', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4NDG', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4SA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4SDD', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4SMM', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4SNF', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4TMS', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB4XXX', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB5BS/J', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB5FI', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB5SIP', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB60VLY', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB6AR', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB6GW', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB6OQA', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB750CC', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('GB8OQE', 'WALES', '14', '27', 'EU', '-3.2', '51.5');
INSERT INTO `dxcc` VALUES ('H4', 'SOLOMON ISLANDS', '28', '51', 'OC', '160', '-9.4');
INSERT INTO `dxcc` VALUES ('H40', 'TEMOTU', '32', '51', 'OC', '165.8', '-10.7');
INSERT INTO `dxcc` VALUES ('HA', 'HUNGARY', '15', '28', 'EU', '19.1', '47.5');
INSERT INTO `dxcc` VALUES ('HG', 'HUNGARY', '15', '28', 'EU', '19.1', '47.5');
INSERT INTO `dxcc` VALUES ('HB', 'SWITZERLAND', '14', '28', 'EU', '7.5', '47');
INSERT INTO `dxcc` VALUES ('HE', 'SWITZERLAND', '14', '28', 'EU', '7.5', '47');
INSERT INTO `dxcc` VALUES ('HB0', 'LIECHTENSTEIN', '14', '28', 'EU', '9.6', '47.2');
INSERT INTO `dxcc` VALUES ('HE0', 'LIECHTENSTEIN', '14', '28', 'EU', '9.6', '47.2');
INSERT INTO `dxcc` VALUES ('HC', 'ECUADOR', '10', '12', 'SA', '-78', '-0.2');
INSERT INTO `dxcc` VALUES ('HD', 'ECUADOR', '10', '12', 'SA', '-78', '-0.2');
INSERT INTO `dxcc` VALUES ('HC8', 'GALAPAGOS IS.', '10', '12', 'SA', '-90.5', '-0.5');
INSERT INTO `dxcc` VALUES ('HD8', 'GALAPAGOS IS.', '10', '12', 'SA', '-90.5', '-0.5');
INSERT INTO `dxcc` VALUES ('HH', 'HAITI', '8', '11', 'NA', '-72.3', '18.5');
INSERT INTO `dxcc` VALUES ('4V', 'HAITI', '8', '11', 'NA', '-72.3', '18.5');
INSERT INTO `dxcc` VALUES ('HI', 'DOMINICAN REPUBLIC', '8', '11', 'NA', '-70', '18.5');
INSERT INTO `dxcc` VALUES ('HK', 'COLOMBIA', '9', '12', 'SA', '-74.1', '4.6');
INSERT INTO `dxcc` VALUES ('5J', 'COLOMBIA', '9', '12', 'SA', '-74.1', '4.6');
INSERT INTO `dxcc` VALUES ('5K', 'COLOMBIA', '9', '12', 'SA', '-74.1', '4.6');
INSERT INTO `dxcc` VALUES ('HJ', 'COLOMBIA', '9', '12', 'SA', '-74.1', '4.6');
INSERT INTO `dxcc` VALUES ('HK0/a', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('5J0', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('5K0', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('HJ0', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('HK0', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('5K0T', 'SAN ANDRES/PROVIDENCIA', '7', '11', 'NA', '-81.7', '12.5');
INSERT INTO `dxcc` VALUES ('HK0/m', 'MALPELO I.', '9', '12', 'SA', '-81.1', '4');
INSERT INTO `dxcc` VALUES ('HK0TU', 'MALPELO I.', '9', '12', 'SA', '-81.1', '4');
INSERT INTO `dxcc` VALUES ('HL', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('6K', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('6L', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('6M', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('6N', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('D7', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('D8', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('D9', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('DS', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('DT', 'SOUTH KOREA', '25', '44', 'AS', '127', '37.5');
INSERT INTO `dxcc` VALUES ('HM', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('P5', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('P6', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('P7', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('P8', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('P9', 'NORTH KOREA', '25', '44', 'AS', '126', '39');
INSERT INTO `dxcc` VALUES ('HP', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('3E', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('3F', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('H3', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('H8', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('H9', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('HO', 'PANAMA', '7', '11', 'NA', '-79.5', '9');
INSERT INTO `dxcc` VALUES ('HR', 'HONDURAS', '7', '11', 'NA', '-87.2', '14.1');
INSERT INTO `dxcc` VALUES ('HQ', 'HONDURAS', '7', '11', 'NA', '-87.2', '14.1');
INSERT INTO `dxcc` VALUES ('HS', 'THAILAND', '26', '49', 'AS', '100.5', '13.8');
INSERT INTO `dxcc` VALUES ('E2', 'THAILAND', '26', '49', 'AS', '100.5', '13.8');
INSERT INTO `dxcc` VALUES ('HV', 'VATICAN CITY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('HZ', 'SAUDI ARABIA', '21', '39', 'AS', '50', '26.3');
INSERT INTO `dxcc` VALUES ('7Z', 'SAUDI ARABIA', '21', '39', 'AS', '50', '26.3');
INSERT INTO `dxcc` VALUES ('8Z', 'SAUDI ARABIA', '21', '39', 'AS', '50', '26.3');
INSERT INTO `dxcc` VALUES ('I', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IG9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IH9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IS', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IM0', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0U', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0V', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0W', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0X', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0Y', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IW0Z', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AG', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AH', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AI', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AK', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AL', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0AM', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0EH', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0HO', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0QP', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IQ0SS', 'SARDINIA', '15', '28', 'EU', '9.1', '39.2');
INSERT INTO `dxcc` VALUES ('IT9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IB9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('ID9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IE9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IF9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('II9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IJ9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IO9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IQ9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IR9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IT', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IU9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IW9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('IZ9', 'ITALY', '15', '28', 'EU', '12.5', '41.9');
INSERT INTO `dxcc` VALUES ('J2', 'DJIBOUTI', '37', '48', 'AF', '43.2', '11.6');
INSERT INTO `dxcc` VALUES ('J3', 'GRENADA', '8', '11', 'NA', '-61.8', '12');
INSERT INTO `dxcc` VALUES ('J5', 'GUINEA-BISSAU', '35', '46', 'AF', '-15.6', '11.9');
INSERT INTO `dxcc` VALUES ('J6', 'ST. LUCIA', '8', '11', 'NA', '-61', '13.9');
INSERT INTO `dxcc` VALUES ('J7', 'DOMINICA', '8', '11', 'NA', '-61.3', '15.4');
INSERT INTO `dxcc` VALUES ('J8', 'ST. VINCENT', '8', '11', 'NA', '-61.3', '13.3');
INSERT INTO `dxcc` VALUES ('JA', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('7J', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('7K', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('7L', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('7M', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('7N', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('8J', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('8K', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('8L', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('8M', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('8N', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JB', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JC', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JE', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JF', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JG', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JH', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JI', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JJ', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JK', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JL', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JM', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JN', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JO', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JP', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JQ', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JR', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JS', 'JAPAN', '25', '45', 'AS', '139.8', '35.7');
INSERT INTO `dxcc` VALUES ('JD/m', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3');
INSERT INTO `dxcc` VALUES ('JD1BME', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3');
INSERT INTO `dxcc` VALUES ('JD1BMM', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3');
INSERT INTO `dxcc` VALUES ('JD1YAA', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3');
INSERT INTO `dxcc` VALUES ('JD1YBJ', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3');
INSERT INTO `dxcc` VALUES ('JD/o', 'OGASAWARA', '27', '45', 'AS', '141', '27.5');
INSERT INTO `dxcc` VALUES ('JD1', 'OGASAWARA', '27', '45', 'AS', '141', '27.5');
INSERT INTO `dxcc` VALUES ('JT', 'MONGOLIA', '23', '32', 'AS', '106.9', '47.9');
INSERT INTO `dxcc` VALUES ('JU', 'MONGOLIA', '23', '32', 'AS', '106.9', '47.9');
INSERT INTO `dxcc` VALUES ('JV', 'MONGOLIA', '23', '32', 'AS', '106.9', '47.9');
INSERT INTO `dxcc` VALUES ('JW', 'SVALBARD', '40', '18', 'EU', '16', '78.8');
INSERT INTO `dxcc` VALUES ('JW/b', 'SVALBARD', '40', '18', 'EU', '16', '78.8');
INSERT INTO `dxcc` VALUES ('JW2FL', 'SVALBARD', '40', '18', 'EU', '16', '78.8');
INSERT INTO `dxcc` VALUES ('JW5RIA', 'SVALBARD', '40', '18', 'EU', '16', '78.8');
INSERT INTO `dxcc` VALUES ('JW7FD', 'SVALBARD', '40', '18', 'EU', '16', '78.8');
INSERT INTO `dxcc` VALUES ('JX', 'JAN MAYEN', '40', '18', 'EU', '-8.3', '71');
INSERT INTO `dxcc` VALUES ('JY', 'JORDAN', '20', '39', 'AS', '35.9', '32');
INSERT INTO `dxcc` VALUES ('K', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('4U1WB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AA6DY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AA7CP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AA7JV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB1HZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB1R', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB4EJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB4GG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AB4IQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AC4PY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AC4TT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AC5ZS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AC8Y', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AD1C', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AD4CJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AD4EB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AD8J', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AF3X', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AG3V', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AG4W', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AJ4F', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AK4Z', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AL4T', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('AL7QQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0COP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0IP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0JJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0LUZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0TV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0XP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K0ZR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K1GU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K1KD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K1LT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K1NG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K1OU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2AAW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2BA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2HT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2RD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2RP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2VCO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K2VV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3CQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3FH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3IE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3TD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3UD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K3WT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4AB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4AGT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4AMC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4APG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4BP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4BX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4CX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4DZR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4EDI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4EJQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4HAL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4IE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4IQJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4IU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4KO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4LTA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4NO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4NP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4NVJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4RO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4SAV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4SKY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4SX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4TD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4TWJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4UY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4VX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4WI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4WW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4XG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4XU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K4ZGB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5EK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5KG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5MA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5ML', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5RQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5RR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5VIP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K5ZD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K6EID', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K6JRY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K6MJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K6VWE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K6XT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7ABV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7BG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7CMZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7CS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7IA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7OM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7RE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7SV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7TD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K7VU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8AC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8BN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8IA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8JQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8MN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8NYG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8NZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8OQL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8PO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8QM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8WV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K8YC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9AW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9ES', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9EZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9HUY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9JDV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9JM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9OM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9RS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9RX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9VV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9WZB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('K9YC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KA2EYH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KA4OTB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KA8Q', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KB4AMA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KB7Q', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KC4HW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KC4SAW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KC6R', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KC7UP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KD4HXT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KD4SN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KD5M', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KE3D', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KE4KWE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KE4KY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KE4MBP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KE7NO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KF7NN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KG4CUY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KG4NOZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KG7HF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KH6DX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KH7WW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KL1SE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KL7OO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KL7WP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KN4Q', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KN5H', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KN6RO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KO7X', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KP2F', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KP3M', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KR4F', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KR4TI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KS4X', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KS5A', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KS7T', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KT2Z', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KU1CW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KU8E', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KV6O', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KV9R', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KY4F', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KZ4V', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KZ5OH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N1CC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N1QXV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N1WQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N2BJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N2BZP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N2IC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N2NS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N2WN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3BB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3HE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3KCJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3PV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3ZI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N3ZZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4ARO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4AU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4BCB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4CB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4CBK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4CYV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4DW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4ECJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4GK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4GN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4HID', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4IJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4IR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4JF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4KC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4KG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4KZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4LS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4LW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4NM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4NO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4OGW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4PF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4PT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4QS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4RR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4SL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4TN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4TZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4UC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4VN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4VV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4XM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4ZI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N4ZZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N5RA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N5VI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N6AR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N6CY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N6DT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N6RFM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N6ZO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N7DF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N7IV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N7KA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N7NG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N7VR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8GZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8II', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8NA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8PR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8RA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8RR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N8WXQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N9ADG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('N9JRZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NA4C', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NA4K', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NA4M', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NB7V', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('ND2T', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NE4M', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NE8J', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NH0Y', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NH6CN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NI9K', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NJ2P', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NJ4I', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NL7FK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NN7A', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NO9E', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NP2CB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NP3D', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NQ4U', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NS0I', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NS2X', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NT4TT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NU4B', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NU4N', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NV4B', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NW7O', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NW8U', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NX9T', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('NY4N', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0AH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0BR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0ID', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0JLC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0QQG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0UCE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0YK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W0YR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1ESE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1NN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1RET', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1RH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1SKU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W1YY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W2OO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W2PK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W2VJN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W2WB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W3CP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W3FAF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W3HDH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W3HKK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W3IQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4BCG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4CID', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4CKD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4DAN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4DEC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4DIM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4DVG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4EEH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4FIN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4GHD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4GKM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4HRC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4JSI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4KW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4LC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4LSC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NBS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NJK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NTI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4NZ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4PA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4PV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4RJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4RK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4RYW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4SK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4UAT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4UDX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4UHF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4UR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4WL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W4YOK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W5JBV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W5JR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W5REA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6AAN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6IHG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6IZT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6LFB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6NRJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6NWS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6PU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6TER', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6UB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W6XR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7DO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7ED', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7FG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7HJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7IMP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7IY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7IZL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7JI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7JW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7KF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7KZO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7LPF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7LR', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7OT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7QF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W7ZQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8AEF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8AKS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8FJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8HC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8HGH', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8JA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8JI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8KJP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8OHT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8OP', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8PC', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8RJL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8UDX', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8WEJ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8WVM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W8ZA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9CF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9GE', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9MAK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9NGA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9PL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9RUK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('W9UAL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA0KDS', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA0WWW', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA1FCN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA1PMA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA1UJU', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA2MNO', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA3C', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA3JAT', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA4JA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA4OSD', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA4SM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA5VGI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA8CNN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA8KAN', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WA8WV', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WB4FWQ', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WB4YDL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WB4ZBI', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WB8YYY', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WC4D', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WC4V', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WC7WB', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WD4KTF', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WG7Y', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WJ9B', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WK5X', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WO4O', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WO5D', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WP4AQK', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WP4JBG', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WR4F', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WR5G', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WS4Y', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WT5L', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WU9B', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WV8AA', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WW2Y', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WX4TM', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WY7I', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('WY7LL', 'UNITED STATES', '5', '8', 'NA', '-87.9', '43');
INSERT INTO `dxcc` VALUES ('KG4', 'GUANTANAMO BAY', '8', '11', 'NA', '-75.2', '19.9');
INSERT INTO `dxcc` VALUES ('KH0', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('AH0', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('NH0', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('WH0', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('KG6SL', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('VERSION', 'MARIANA IS.', '27', '64', 'OC', '145.8', '15.2');
INSERT INTO `dxcc` VALUES ('KH1', 'BAKER & HOWLAND IS.', '31', '61', 'OC', '-176', '0.5');
INSERT INTO `dxcc` VALUES ('AH1', 'BAKER & HOWLAND IS.', '31', '61', 'OC', '-176', '0.5');
INSERT INTO `dxcc` VALUES ('NH1', 'BAKER & HOWLAND IS.', '31', '61', 'OC', '-176', '0.5');
INSERT INTO `dxcc` VALUES ('WH1', 'BAKER & HOWLAND IS.', '31', '61', 'OC', '-176', '0.5');
INSERT INTO `dxcc` VALUES ('KH2', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('AH2', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('NH2', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('WH2', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('KG6ASO', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('KG6DX', 'GUAM', '27', '64', 'OC', '144.8', '13.5');
INSERT INTO `dxcc` VALUES ('KH3', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8');
INSERT INTO `dxcc` VALUES ('AH3', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8');
INSERT INTO `dxcc` VALUES ('NH3', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8');
INSERT INTO `dxcc` VALUES ('WH3', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8');
INSERT INTO `dxcc` VALUES ('KJ6BZ', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8');
INSERT INTO `dxcc` VALUES ('KH4', 'MIDWAY I.', '31', '61', 'OC', '-177.4', '28.2');
INSERT INTO `dxcc` VALUES ('AH4', 'MIDWAY I.', '31', '61', 'OC', '-177.4', '28.2');
INSERT INTO `dxcc` VALUES ('NH4', 'MIDWAY I.', '31', '61', 'OC', '-177.4', '28.2');
INSERT INTO `dxcc` VALUES ('WH4', 'MIDWAY I.', '31', '61', 'OC', '-177.4', '28.2');
INSERT INTO `dxcc` VALUES ('KH5', 'PALMYRA & JARVIS IS.', '31', '61', 'OC', '-162.1', '5.9');
INSERT INTO `dxcc` VALUES ('AH5', 'PALMYRA & JARVIS IS.', '31', '61', 'OC', '-162.1', '5.9');
INSERT INTO `dxcc` VALUES ('NH5', 'PALMYRA & JARVIS IS.', '31', '61', 'OC', '-162.1', '5.9');
INSERT INTO `dxcc` VALUES ('WH5', 'PALMYRA & JARVIS IS.', '31', '61', 'OC', '-162.1', '5.9');
INSERT INTO `dxcc` VALUES ('KH5K', 'KINGMAN REEF', '31', '61', 'OC', '-162.4', '6.4');
INSERT INTO `dxcc` VALUES ('AH5K', 'KINGMAN REEF', '31', '61', 'OC', '-162.4', '6.4');
INSERT INTO `dxcc` VALUES ('NH5K', 'KINGMAN REEF', '31', '61', 'OC', '-162.4', '6.4');
INSERT INTO `dxcc` VALUES ('WH5K', 'KINGMAN REEF', '31', '61', 'OC', '-162.4', '6.4');
INSERT INTO `dxcc` VALUES ('KH6', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('AH6', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('AH7', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('KH7', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('N6KB', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('NH6', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('NH7', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('WH6', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('WH7', 'HAWAII', '31', '61', 'OC', '-157.9', '21.3');
INSERT INTO `dxcc` VALUES ('KH7K', 'KURE I.', '31', '61', 'OC', '-178.4', '28.4');
INSERT INTO `dxcc` VALUES ('AH7K', 'KURE I.', '31', '61', 'OC', '-178.4', '28.4');
INSERT INTO `dxcc` VALUES ('NH7K', 'KURE I.', '31', '61', 'OC', '-178.4', '28.4');
INSERT INTO `dxcc` VALUES ('WH7K', 'KURE I.', '31', '61', 'OC', '-178.4', '28.4');
INSERT INTO `dxcc` VALUES ('KH8', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3');
INSERT INTO `dxcc` VALUES ('AH8', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3');
INSERT INTO `dxcc` VALUES ('NH8', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3');
INSERT INTO `dxcc` VALUES ('WH8', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3');
INSERT INTO `dxcc` VALUES ('KH8/s', 'SWAINS ISLAND', '32', '62', 'OC', '-171.25', '-11.05');
INSERT INTO `dxcc` VALUES ('KH8SI', 'SWAINS ISLAND', '32', '62', 'OC', '-171.25', '-11.05');
INSERT INTO `dxcc` VALUES ('KH9', 'WAKE I.', '31', '65', 'OC', '166.6', '19.3');
INSERT INTO `dxcc` VALUES ('AH9', 'WAKE I.', '31', '65', 'OC', '166.6', '19.3');
INSERT INTO `dxcc` VALUES ('NH9', 'WAKE I.', '31', '65', 'OC', '166.6', '19.3');
INSERT INTO `dxcc` VALUES ('WH9', 'WAKE I.', '31', '65', 'OC', '166.6', '19.3');
INSERT INTO `dxcc` VALUES ('KL', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('AL', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('NL', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('WL', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('AH0AH', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('KW1W', 'ALASKA', '1', '1', 'NA', '-150', '61.2');
INSERT INTO `dxcc` VALUES ('KP1', 'NAVASSA I.', '8', '11', 'NA', '-75', '18.4');
INSERT INTO `dxcc` VALUES ('NP1', 'NAVASSA I.', '8', '11', 'NA', '-75', '18.4');
INSERT INTO `dxcc` VALUES ('WP1', 'NAVASSA I.', '8', '11', 'NA', '-75', '18.4');
INSERT INTO `dxcc` VALUES ('KP2', 'VIRGIN IS.', '8', '11', 'NA', '-64.9', '18.3');
INSERT INTO `dxcc` VALUES ('NP2', 'VIRGIN IS.', '8', '11', 'NA', '-64.9', '18.3');
INSERT INTO `dxcc` VALUES ('WP2', 'VIRGIN IS.', '8', '11', 'NA', '-64.9', '18.3');
INSERT INTO `dxcc` VALUES ('KV4FZ', 'VIRGIN IS.', '8', '11', 'NA', '-64.9', '18.3');
INSERT INTO `dxcc` VALUES ('KP4', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('KP3', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('NP3', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('NP4', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('WP3', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('WP4', 'PUERTO RICO', '8', '11', 'NA', '-66.2', '18.5');
INSERT INTO `dxcc` VALUES ('KP5', 'DESECHEO I.', '8', '11', 'NA', '-67.5', '18.3');
INSERT INTO `dxcc` VALUES ('NP5', 'DESECHEO I.', '8', '11', 'NA', '-67.5', '18.3');
INSERT INTO `dxcc` VALUES ('WP5', 'DESECHEO I.', '8', '11', 'NA', '-67.5', '18.3');
INSERT INTO `dxcc` VALUES ('LA', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LB', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LC', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LD', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LE', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LF', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LG', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LH', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LI', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LJ', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LK', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LL', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LM', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LN', 'NORWAY', '14', '18', 'EU', '10.7', '60');
INSERT INTO `dxcc` VALUES ('LU', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AZ', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L2', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L3', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L4', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L5', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L6', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L7', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L8', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L9', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LO', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LP', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LQ', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LR', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LS', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LT', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LV', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY0N/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY3DR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY4EJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY5E/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('AY7DSY/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('DJ4SN/LU/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L20ARC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L21ESC/LH', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L25E/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L30EY/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L30EY/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L40E/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L44D/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L80AA/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L84VI/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('L8D/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LO0D/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LO7E/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU/DH4PB/R', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU/DH4PB/S', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1AEE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1AF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1CDP/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DHO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DK/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DMA/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/P', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/R', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/S', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1DZ/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1EJ/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1EQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1EUU/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1EYW/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1OFN/I', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1VOF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1VZ/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1XAW/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1XWC/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1XY/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1YU/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU1YY/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2CRM/XA', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2DT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2DT/LH', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2DVI/H', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2EE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2EE/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2EJB/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2VC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2VDV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2WV/O', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU2XX/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3CQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DJI/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DJI/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DOC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DR/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DXG/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3DZO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3EOU/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3ES/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3ES/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3ES/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3HKA/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU3HKA/H', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4AAO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DA/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DBP/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DBT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DRC/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DRH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4DRH/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4EHP/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4EJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4ELE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4ESP/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4ETN/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4ETN/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4EV/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4UZW/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU4WG/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5BE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5BOJ/O', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DEM/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DEM/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DEM/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DIT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DIT/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DIT/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DRV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DRV/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5DWS/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5EAO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5EFX/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5EJL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5EWO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5FZ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5VAT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU5XC/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DBL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DBL/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DKT/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DRD/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DRD/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DRN/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6DRR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EC/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EJJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EPE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EPR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EPR/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EU/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6EYK/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6JJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UAL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/P', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/R', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/S', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6UO/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU6XAH/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7AC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7BTO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DBL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DID/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DID/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DIR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DJJ/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DP/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DSY/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DSY/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DSY/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DW/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DZL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7DZL/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EGH/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EGY/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EHL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EPC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7EPC/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7HW/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7VCH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7WFM/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU7WW/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8ADX/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DCH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DCH/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DIP/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DRA/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DRH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DSJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DWR/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8DWR/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EBJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EBJ/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EBK/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EBK/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8ECF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8ECF/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EEM/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EFF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EGS/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EHQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EHQ/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EHQ/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EKB/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EKC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EOT/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EOT/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8ERH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EXJ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8EXN/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8FOZ/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8VCC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8WFT/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8XC/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8XW/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU8XW/XD', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ARB/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9AUC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9DBK/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9DKX/X', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9DPD/XA', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9EI/F', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9EJS/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ESD/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ESD/F', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ESD/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ESD/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9ESD/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9EV/LH', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LU9JMG/J', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW1DAL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW1EXU/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW1EXU/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/P', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/Q', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/R', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/S', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2DX/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2EFS/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW2ENB/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW3DKC/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW3DKC/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW3DKO/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW3DKO/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW3HAQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4DRH/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4DRH/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4DRV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4ECV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4EM/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW4EM/LH', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW5DR/LH', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW5DWX/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW5EE/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW5EE/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW5EOL/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW6DTM/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7DAF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7DAF/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7DLY/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7DNS/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7EJV/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW7WFM/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8DMK/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8DMK/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8EAG/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8ECQ/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8EU/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW8EXF/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9DCF/Y', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9DX/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9EAG/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9EAG/V', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9EAG/W', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9EVA/D', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LW9EVA/E', 'ARGENTINA', '13', '14', 'SA', '-58.4', '-34.6');
INSERT INTO `dxcc` VALUES ('LX', 'LUXEMBOURG', '14', '27', 'EU', '6.2', '49.6');
INSERT INTO `dxcc` VALUES ('LY', 'LITHUANIA', '15', '29', 'EU', '25.5', '54.5');
INSERT INTO `dxcc` VALUES ('LZ', 'BULGARIA', '20', '28', 'EU', '23.3', '42.7');
INSERT INTO `dxcc` VALUES ('OA', 'PERU', '10', '12', 'SA', '-76', '-10');
INSERT INTO `dxcc` VALUES ('4T', 'PERU', '10', '12', 'SA', '-76', '-10');
INSERT INTO `dxcc` VALUES ('OB', 'PERU', '10', '12', 'SA', '-76', '-10');
INSERT INTO `dxcc` VALUES ('OC', 'PERU', '10', '12', 'SA', '-76', '-10');
INSERT INTO `dxcc` VALUES ('OD', 'LEBANON', '20', '39', 'AS', '35.8', '33.8');
INSERT INTO `dxcc` VALUES ('OE', 'AUSTRIA', '15', '28', 'EU', '16.3', '48.2');
INSERT INTO `dxcc` VALUES ('OH', 'FINLAND', '15', '18', 'EU', '25', '60.2');
INSERT INTO `dxcc` VALUES ('OF', 'FINLAND', '15', '18', 'EU', '25', '60.2');
INSERT INTO `dxcc` VALUES ('OG', 'FINLAND', '15', '18', 'EU', '25', '60.2');
INSERT INTO `dxcc` VALUES ('OI', 'FINLAND', '15', '18', 'EU', '25', '60.2');
INSERT INTO `dxcc` VALUES ('OJ', 'FINLAND', '15', '18', 'EU', '25', '60.2');
INSERT INTO `dxcc` VALUES ('OH0', 'ALAND IS.', '15', '18', 'EU', '20', '60.2');
INSERT INTO `dxcc` VALUES ('OF0', 'ALAND IS.', '15', '18', 'EU', '20', '60.2');
INSERT INTO `dxcc` VALUES ('OG0', 'ALAND IS.', '15', '18', 'EU', '20', '60.2');
INSERT INTO `dxcc` VALUES ('OI0', 'ALAND IS.', '15', '18', 'EU', '20', '60.2');
INSERT INTO `dxcc` VALUES ('OJ0', 'MARKET REEF', '15', '18', 'EU', '19', '60.3');
INSERT INTO `dxcc` VALUES ('OK', 'CZECH REPUBLIC', '15', '28', 'EU', '15.5', '50.1');
INSERT INTO `dxcc` VALUES ('OL', 'CZECH REPUBLIC', '15', '28', 'EU', '15.5', '50.1');
INSERT INTO `dxcc` VALUES ('OM', 'SLOVAKIA', '15', '28', 'EU', '17.1', '48.1');
INSERT INTO `dxcc` VALUES ('ON', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OO', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OP', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OQ', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OR', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OS', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OT', 'BELGIUM', '14', '27', 'EU', '4.4', '50.9');
INSERT INTO `dxcc` VALUES ('OX', 'GREENLAND', '40', '5', 'NA', '-45', '62.5');
INSERT INTO `dxcc` VALUES ('XP', 'GREENLAND', '40', '5', 'NA', '-45', '62.5');
INSERT INTO `dxcc` VALUES ('OY', 'FAROE IS.', '14', '18', 'EU', '-6.8', '62');
INSERT INTO `dxcc` VALUES ('OW', 'FAROE IS.', '14', '18', 'EU', '-6.8', '62');
INSERT INTO `dxcc` VALUES ('OZ', 'DENMARK', '14', '18', 'EU', '10', '56');
INSERT INTO `dxcc` VALUES ('5P', 'DENMARK', '14', '18', 'EU', '10', '56');
INSERT INTO `dxcc` VALUES ('5Q', 'DENMARK', '14', '18', 'EU', '10', '56');
INSERT INTO `dxcc` VALUES ('OU', 'DENMARK', '14', '18', 'EU', '10', '56');
INSERT INTO `dxcc` VALUES ('OV', 'DENMARK', '14', '18', 'EU', '10', '56');
INSERT INTO `dxcc` VALUES ('P2', 'PAPUA NEW GUINEA', '28', '51', 'OC', '147.1', '-9.4');
INSERT INTO `dxcc` VALUES ('P4', 'ARUBA', '9', '11', 'SA', '-70', '12.5');
INSERT INTO `dxcc` VALUES ('PA', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PB', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PC', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PD', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PE', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PF', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PG', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PH', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PI', 'NETHERLANDS', '14', '27', 'EU', '4.9', '52.4');
INSERT INTO `dxcc` VALUES ('PJ2', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ0', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ1', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ3', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ4', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ9', 'NETHERLANDS ANTILLES', '9', '11', 'SA', '-69', '12.1');
INSERT INTO `dxcc` VALUES ('PJ7', 'SINT MAARTEN', '8', '11', 'NA', '-63.2', '17.7');
INSERT INTO `dxcc` VALUES ('PJ5', 'SINT MAARTEN', '8', '11', 'NA', '-63.2', '17.7');
INSERT INTO `dxcc` VALUES ('PJ6', 'SINT MAARTEN', '8', '11', 'NA', '-63.2', '17.7');
INSERT INTO `dxcc` VALUES ('PJ8', 'SINT MAARTEN', '8', '11', 'NA', '-63.2', '17.7');
INSERT INTO `dxcc` VALUES ('PY', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PP', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PQ', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PR', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PS', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PT', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PU', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PV', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PW', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PX', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('ZV', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('ZW', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('ZX', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('ZY', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('ZZ', 'BRAZIL', '11', '15', 'SA', '-43.2', '-23');
INSERT INTO `dxcc` VALUES ('PY0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PP0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PP0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PP0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PP0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PQ0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PQ0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PQ0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PQ0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PR0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PR0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PR0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PR0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PS0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PS0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PS0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PS0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PT0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PT0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PT0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PT0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PU0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PU0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PU0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PU0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PV0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PV0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PV0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PV0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PW0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PW0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PW0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PW0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PX0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PX0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PX0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PX0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PY0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PY0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PY0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZV0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZV0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZV0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZV0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZW0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZW0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZW0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZW0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZX0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZX0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZX0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZX0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZY0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZY0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZY0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZY0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZZ0F', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZZ0R', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZZ0ZF', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('ZZ0ZR', 'FERNANDO DE NORONHA', '11', '13', 'SA', '-32.4', '-3.9');
INSERT INTO `dxcc` VALUES ('PY0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PP0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PP0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PQ0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PQ0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PR0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PR0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PS0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PS0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PT0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PT0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PU0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PU0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PV0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PV0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PW0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PW0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PX0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PX0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PY0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZV0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZV0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZW0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZW0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZX0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZX0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZY0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZY0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZZ0S', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('ZZ0ZS', 'ST. PETER & ST. PAUL', '11', '13', 'SA', '-29.4', '1');
INSERT INTO `dxcc` VALUES ('PY0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PP0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PP0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PQ0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PQ0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PR0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PR0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PS0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PS0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PT0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PT0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PU0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PU0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PV0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PV0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PW0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PW0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PX0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PX0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PY0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZV0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZV0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZW0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZW0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZX0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZX0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZY0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZY0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZZ0T', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('ZZ0ZT', 'TRINDADE & MARTIM VAZ', '11', '15', 'SA', '-29.3', '-20.5');
INSERT INTO `dxcc` VALUES ('PZ', 'SURINAME', '9', '12', 'SA', '-56', '4');
INSERT INTO `dxcc` VALUES ('R1FJ', 'FRANZ JOSEF LAND', '40', '75', 'EU', '53', '80');
INSERT INTO `dxcc` VALUES ('FJL', 'FRANZ JOSEF LAND', '40', '75', 'EU', '53', '80');
INSERT INTO `dxcc` VALUES ('R1MV', 'MALYJ VYSOTSKIJ', '16', '29', 'EU', '28.4', '60.4');
INSERT INTO `dxcc` VALUES ('MVI', 'MALYJ VYSOTSKIJ', '16', '29', 'EU', '28.4', '60.4');
INSERT INTO `dxcc` VALUES ('S0', 'WESTERN SAHARA', '33', '46', 'AF', '-15', '22');
INSERT INTO `dxcc` VALUES ('S2', 'BANGLADESH', '22', '41', 'AS', '90.4', '23.7');
INSERT INTO `dxcc` VALUES ('S3', 'BANGLADESH', '22', '41', 'AS', '90.4', '23.7');
INSERT INTO `dxcc` VALUES ('S5', 'SLOVENIA', '15', '28', 'EU', '14.5', '46');
INSERT INTO `dxcc` VALUES ('S7', 'SEYCHELLES', '39', '53', 'AF', '55.5', '-4.6');
INSERT INTO `dxcc` VALUES ('S9', 'SAO TOME & PRINCIPE', '36', '47', 'AF', '6.7', '0.3');
INSERT INTO `dxcc` VALUES ('SM', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('7S', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('8S', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SA', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SB', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SC', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SD', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SE', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SF', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SG', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SH', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SI', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SJ', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SK', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SL', 'SWEDEN', '14', '18', 'EU', '18.1', '59.3');
INSERT INTO `dxcc` VALUES ('SP', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('3Z', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('HF', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('SN', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('SO', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('SQ', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('SR', 'POLAND', '15', '28', 'EU', '21', '52.2');
INSERT INTO `dxcc` VALUES ('ST', 'SUDAN', '34', '48', 'AF', '32.5', '15.6');
INSERT INTO `dxcc` VALUES ('6T', 'SUDAN', '34', '48', 'AF', '32.5', '15.6');
INSERT INTO `dxcc` VALUES ('6U', 'SUDAN', '34', '48', 'AF', '32.5', '15.6');
INSERT INTO `dxcc` VALUES ('SU', 'EGYPT', '34', '38', 'AF', '31.4', '30');
INSERT INTO `dxcc` VALUES ('6A', 'EGYPT', '34', '38', 'AF', '31.4', '30');
INSERT INTO `dxcc` VALUES ('6B', 'EGYPT', '34', '38', 'AF', '31.4', '30');
INSERT INTO `dxcc` VALUES ('SS', 'EGYPT', '34', '38', 'AF', '31.4', '30');
INSERT INTO `dxcc` VALUES ('SV', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('J4', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('SW', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('SX', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('SY', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('SZ', 'GREECE', '20', '28', 'EU', '23.7', '38');
INSERT INTO `dxcc` VALUES ('SV/a', 'MOUNT ATHOS', '20', '28', 'EU', '24.3', '40.2');
INSERT INTO `dxcc` VALUES ('SV2ASP/A', 'MOUNT ATHOS', '20', '28', 'EU', '24.3', '40.2');
INSERT INTO `dxcc` VALUES ('SV5', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('J45', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('SW5', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('SX5', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('SY5', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('SZ5', 'DODECANESE', '20', '28', 'EU', '28.2', '36.4');
INSERT INTO `dxcc` VALUES ('SV9', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('J49', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SW9', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SX9', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SY9', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SZ9', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SV0LB', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('SV0XAZ', 'CRETE', '20', '28', 'EU', '25.2', '35.4');
INSERT INTO `dxcc` VALUES ('T2', 'TUVALU', '31', '65', 'OC', '179.2', '-8.7');
INSERT INTO `dxcc` VALUES ('T30', 'WESTERN KIRIBATI', '31', '65', 'OC', '173', '1.4');
INSERT INTO `dxcc` VALUES ('T31', 'CENTRAL KIRIBATI', '31', '62', 'OC', '-171.7', '-2.8');
INSERT INTO `dxcc` VALUES ('T32', 'EASTERN KIRIBATI', '31', '61', 'OC', '-157.4', '1.9');
INSERT INTO `dxcc` VALUES ('T33', 'BANABA', '31', '65', 'OC', '169.5', '-0.9');
INSERT INTO `dxcc` VALUES ('T5', 'SOMALIA', '37', '48', 'AF', '45.4', '2.1');
INSERT INTO `dxcc` VALUES ('6O', 'SOMALIA', '37', '48', 'AF', '45.4', '2.1');
INSERT INTO `dxcc` VALUES ('T7', 'SAN MARINO', '15', '28', 'EU', '12.3', '43.9');
INSERT INTO `dxcc` VALUES ('T8', 'PALAU', '27', '64', 'OC', '138.2', '9.5');
INSERT INTO `dxcc` VALUES ('TA', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TB', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TC', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('YM', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TA1', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TB1', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TC1', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('YM1', 'TURKEY', '20', '39', 'AS', '33', '40');
INSERT INTO `dxcc` VALUES ('TF', 'ICELAND', '40', '17', 'EU', '-22', '64.1');
INSERT INTO `dxcc` VALUES ('TG', 'GUATEMALA', '7', '11', 'NA', '-90.5', '14.6');
INSERT INTO `dxcc` VALUES ('TD', 'GUATEMALA', '7', '11', 'NA', '-90.5', '14.6');
INSERT INTO `dxcc` VALUES ('TI', 'COSTA RICA', '7', '11', 'NA', '-84', '9.9');
INSERT INTO `dxcc` VALUES ('TE', 'COSTA RICA', '7', '11', 'NA', '-84', '9.9');
INSERT INTO `dxcc` VALUES ('TI9', 'COCOS I.', '7', '11', 'NA', '-87', '5.6');
INSERT INTO `dxcc` VALUES ('TE9', 'COCOS I.', '7', '11', 'NA', '-87', '5.6');
INSERT INTO `dxcc` VALUES ('TJ', 'CAMEROON', '36', '47', 'AF', '11.5', '3.9');
INSERT INTO `dxcc` VALUES ('TK', 'CORSICA', '15', '28', 'EU', '9', '42');
INSERT INTO `dxcc` VALUES ('TL', 'CENTRAL AFRICAN REP', '36', '47', 'AF', '18.6', '4.4');
INSERT INTO `dxcc` VALUES ('TN', 'REP. OF CONGO', '36', '52', 'AF', '15.3', '-4.3');
INSERT INTO `dxcc` VALUES ('TR', 'GABON', '36', '52', 'AF', '9.5', '0.4');
INSERT INTO `dxcc` VALUES ('TT', 'CHAD', '36', '47', 'AF', '15', '12.1');
INSERT INTO `dxcc` VALUES ('TY', 'BENIN', '35', '46', 'AF', '2.6', '6.5');
INSERT INTO `dxcc` VALUES ('TZ', 'MALI', '35', '46', 'AF', '-8', '12.7');
INSERT INTO `dxcc` VALUES ('UA', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('R', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RD4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RK4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RM4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RN4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RU4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RV4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('RW4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('U', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('UA4W', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('R7C', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('R7C/1', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('R7C/3', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('R7C/4', 'EUROPEAN RUSSIA', '16', '29', 'EU', '37.6', '55.8');
INSERT INTO `dxcc` VALUES ('UA2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('R2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RA2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RB2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RC2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RD2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RE2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RF2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RG2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RH2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RI2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RJ2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RK2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RL2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RM2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RN2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RO2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RP2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RQ2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RR2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RS2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RT2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RU2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RV2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RW2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RX2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RY2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('RZ2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('U2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UB2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UC2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UD2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UE2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UF2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UG2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UH2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UI2', 'KALININGRAD', '15', '29', 'EU', '20.5', '55');
INSERT INTO `dxcc` VALUES ('UA9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RA9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RB9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RC9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RD9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RE9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RF9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RG9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RH9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RI9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RJ9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RK9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RL9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RM9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RN9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RO9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RP9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RQ9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RR9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RS9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RT9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RU9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RV9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RW9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RX9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RY9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('RZ9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('U9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UA9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UB9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UC9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UD9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UE9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UF9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UG9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UH9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI0', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI7', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI8', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI8T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI8V', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9I', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9M', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9P', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9S', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9T', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UI9W', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R35NP', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R3F/9', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R70B', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('R9HQ', 'ASIATIC RUSSIA', '17', '30', 'AS', '83', '55');
INSERT INTO `dxcc` VALUES ('UK', 'UZBEKISTAN', '17', '30', 'AS', '69.3', '41.2');
INSERT INTO `dxcc` VALUES ('UJ', 'UZBEKISTAN', '17', '30', 'AS', '69.3', '41.2');
INSERT INTO `dxcc` VALUES ('UL', 'UZBEKISTAN', '17', '30', 'AS', '69.3', '41.2');
INSERT INTO `dxcc` VALUES ('UM', 'UZBEKISTAN', '17', '30', 'AS', '69.3', '41.2');
INSERT INTO `dxcc` VALUES ('UN', 'KAZAKHSTAN', '17', '30', 'AS', '76.9', '43.3');
INSERT INTO `dxcc` VALUES ('UO', 'KAZAKHSTAN', '17', '30', 'AS', '76.9', '43.3');
INSERT INTO `dxcc` VALUES ('UP', 'KAZAKHSTAN', '17', '30', 'AS', '76.9', '43.3');
INSERT INTO `dxcc` VALUES ('UQ', 'KAZAKHSTAN', '17', '30', 'AS', '76.9', '43.3');
INSERT INTO `dxcc` VALUES ('UR', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('EM', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('EN', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('EO', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('U5', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('US', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UT', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UU', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UV', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UW', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UX', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UY', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('UZ', 'UKRAINE', '16', '29', 'EU', '30.5', '50.4');
INSERT INTO `dxcc` VALUES ('V2', 'ANTIGUA & BARBUDA', '8', '11', 'NA', '-61.8', '17.1');
INSERT INTO `dxcc` VALUES ('V3', 'BELIZE', '7', '11', 'NA', '-88.8', '17.3');
INSERT INTO `dxcc` VALUES ('V4', 'ST. KITTS & NEVIS', '8', '11', 'NA', '-62.6', '17.3');
INSERT INTO `dxcc` VALUES ('V5', 'NAMIBIA', '38', '57', 'AF', '17.1', '-22.6');
INSERT INTO `dxcc` VALUES ('V6', 'MICRONESIA', '27', '65', 'OC', '158.3', '6.9');
INSERT INTO `dxcc` VALUES ('V7', 'MARSHALL IS.', '31', '65', 'OC', '167.3', '9.1');
INSERT INTO `dxcc` VALUES ('V8', 'BRUNEI', '28', '54', 'OC', '114.9', '4.9');
INSERT INTO `dxcc` VALUES ('VE', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CF', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CG', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CH1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CH2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CI0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CI1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CI2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CJ', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CK', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CY1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CY2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CZ0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CZ1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CZ2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VB', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VC', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VD1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VD2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VF0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VF1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VF2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VG', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VO1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VO2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VX', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XJ1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XJ2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XK0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XK1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XK2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XL', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XM', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XN1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XN2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XO0', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XO1', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('XO2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('CY2ZT/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('K3FMQ/VE2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('KD3RF/VE2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('KD3TB/VE2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2BY', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2CT', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2DO', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2DXE', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2KCE', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2RHJ', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2UA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2VFT', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA2ZM', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VA3NA/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VB2C', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VB2R', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VB2V', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VC2C', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2/K3FMQ', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ACP', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2AE', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2AG', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2AOF', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2AQS', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2AS', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2BQB', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2CSI', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2CVI', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DMG', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DS', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DWU', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DXY', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DYW', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2DYX', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2EAK', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2EDL', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2EDX', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ELL', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ENB', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2END', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ENR', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ERU', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2FCV', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2GSA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2GSO', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2III', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2IM', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2KK', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2MTA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2MTB', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2NN', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2NRK', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2PR', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2QRZ', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2RB', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2TVU', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2UA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2VH', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2WDX', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2WT', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2XAA/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2XY', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2YM', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2Z', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ZC', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ZM', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE2ZV', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE3EY/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE3NE/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE3RHJ/2', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE8AJ', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE8PW', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VE8RCS', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VER2008120', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY0AA', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY0PW', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VY2MGY/3', 'CANADA', '5', '9', 'NA', '-80', '45');
INSERT INTO `dxcc` VALUES ('VK', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('AX', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VH', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VI', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VJ', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VL', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VM', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VN', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VZ', 'AUSTRALIA', '30', '59', 'OC', '135', '-22');
INSERT INTO `dxcc` VALUES ('VK0H', 'HEARD I.', '39', '68', 'AF', '73.4', '-53');
INSERT INTO `dxcc` VALUES ('VK0HI', 'HEARD I.', '39', '68', 'AF', '73.4', '-53');
INSERT INTO `dxcc` VALUES ('VK0IR', 'HEARD I.', '39', '68', 'AF', '73.4', '-53');
INSERT INTO `dxcc` VALUES ('VK0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('AX0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VH0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VI0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VJ0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VL0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VM0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VN0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VZ0M', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7');
INSERT INTO `dxcc` VALUES ('VK9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('AX9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('AX9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VH9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VH9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VI9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VI9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VJ9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VJ9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9FC', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9KC', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9KY', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VL9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VL9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VM9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VM9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VN9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VN9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VZ9C', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VZ9Y', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9AA', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2');
INSERT INTO `dxcc` VALUES ('VK9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('AX9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VH9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VI9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VJ9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9AL', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9CL', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9FL', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9GL', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9KL', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VL9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VM9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VN9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VZ9L', 'LORD HOWE I.', '30', '60', 'OC', '159.1', '-31.6');
INSERT INTO `dxcc` VALUES ('VK9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('AX9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VH9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VI9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VJ9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VK9FM', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VK9KM', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VL9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VM9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VN9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VZ9M', 'MELLISH REEF', '30', '56', 'OC', '155.8', '-17.6');
INSERT INTO `dxcc` VALUES ('VK9N', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('AX9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VH9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VI9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VJ9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VK9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VK9CN', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VL9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VM9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VN9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VZ9', 'NORFOLK I.', '32', '60', 'OC', '168', '-29');
INSERT INTO `dxcc` VALUES ('VK9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('AX9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VH9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VI9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VJ9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VK9FW', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VK9KW', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VL9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VM9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VN9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VZ9W', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VK9DWX', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2');
INSERT INTO `dxcc` VALUES ('VK9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('AX9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VH9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VI9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VJ9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VK9FX', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VK9KX', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VL9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VM9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VN9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VZ9X', 'CHRISTMAS I.', '29', '54', 'OC', '105.7', '-10.5');
INSERT INTO `dxcc` VALUES ('VP2E', 'ANGUILLA', '8', '11', 'NA', '-63', '18.3');
INSERT INTO `dxcc` VALUES ('VP2M', 'MONTSERRAT', '8', '11', 'NA', '-62.2', '16.8');
INSERT INTO `dxcc` VALUES ('VP2V', 'BRITISH VIRGIN IS.', '8', '11', 'NA', '-64.6', '18.4');
INSERT INTO `dxcc` VALUES ('VP5', 'TURKS & CAICOS', '8', '11', 'NA', '-72.4', '21.8');
INSERT INTO `dxcc` VALUES ('VQ5', 'TURKS & CAICOS', '8', '11', 'NA', '-72.4', '21.8');
INSERT INTO `dxcc` VALUES ('VP6', 'PITCAIRN I.', '32', '63', 'OC', '-130.1', '-25.1');
INSERT INTO `dxcc` VALUES ('VP6/d', 'DUCIE I.', '32', '63', 'OC', '-124.79', '-24.67');
INSERT INTO `dxcc` VALUES ('VP6DX', 'DUCIE I.', '32', '63', 'OC', '-124.79', '-24.67');
INSERT INTO `dxcc` VALUES ('VP8', 'FALKLAND IS.', '13', '16', 'SA', '-57.9', '-51.7');
INSERT INTO `dxcc` VALUES ('VP8/g', 'SOUTH GEORGIA', '13', '73', 'SA', '-36.8', '-54.3');
INSERT INTO `dxcc` VALUES ('VP8DIF', 'SOUTH GEORGIA', '13', '73', 'SA', '-36.8', '-54.3');
INSERT INTO `dxcc` VALUES ('VP8SGK', 'SOUTH GEORGIA', '13', '73', 'SA', '-36.8', '-54.3');
INSERT INTO `dxcc` VALUES ('VP8/h', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('DT8A', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('ED3RKL', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('HF0APAS', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('HF0POL', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('HL8KSJ', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('LU1ZC', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('LZ0A', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('R1ANF', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('VP8/LZ1UQ', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('VP8DJK', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62');
INSERT INTO `dxcc` VALUES ('VP8/o', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60');
INSERT INTO `dxcc` VALUES ('AY1ZA', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60');
INSERT INTO `dxcc` VALUES ('LU1ZA', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60');
INSERT INTO `dxcc` VALUES ('VP8/s', 'SOUTH SANDWICH', '13', '73', 'SA', '-26.7', '-57');
INSERT INTO `dxcc` VALUES ('VP8SSI', 'SOUTH SANDWICH', '13', '73', 'SA', '-26.7', '-57');
INSERT INTO `dxcc` VALUES ('VP8THU', 'SOUTH SANDWICH', '13', '73', 'SA', '-26.7', '-57');
INSERT INTO `dxcc` VALUES ('VP9', 'BERMUDA', '5', '11', 'NA', '-64.7', '32.3');
INSERT INTO `dxcc` VALUES ('VQ9', 'CHAGOS IS.', '39', '41', 'AF', '72.4', '-7.3');
INSERT INTO `dxcc` VALUES ('VR', 'HONG KONG', '24', '44', 'AS', '114.3', '22.3');
INSERT INTO `dxcc` VALUES ('VU', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8T', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8U', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8V', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8W', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8X', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('8Y', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('AT', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('AU', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('AV', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('AW', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('VT', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('VV', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('VW', 'INDIA', '22', '41', 'AS', '80', '22');
INSERT INTO `dxcc` VALUES ('VU4', 'ANDAMAN & NICOBAR', '26', '49', 'AS', '92.8', '11.7');
INSERT INTO `dxcc` VALUES ('VU3VPX', 'ANDAMAN & NICOBAR', '26', '49', 'AS', '92.8', '11.7');
INSERT INTO `dxcc` VALUES ('VU3VPY', 'ANDAMAN & NICOBAR', '26', '49', 'AS', '92.8', '11.7');
INSERT INTO `dxcc` VALUES ('VU7', 'LAKSHADWEEP ISLANDS', '22', '41', 'AS', '73', '10');
INSERT INTO `dxcc` VALUES ('XE', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('4A', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('4B', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('4C', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6D', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6E', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6F', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6G', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6H', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6I', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('6J', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XA', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XB', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XC', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XD', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XF', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XG', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XH', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XI', 'MEXICO', '6', '10', 'NA', '-99.1', '19.4');
INSERT INTO `dxcc` VALUES ('XF4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('4A4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('4B4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('4C4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6D4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6E4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6F4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6G4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6H4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6I4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('6J4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XA4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XB4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XC4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XD4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XE4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XG4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XH4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XI4', 'REVILLA GIGEDO', '6', '10', 'NA', '-111.5', '19');
INSERT INTO `dxcc` VALUES ('XT', 'BURKINA FASO', '35', '46', 'AF', '-1.6', '12.4');
INSERT INTO `dxcc` VALUES ('XU', 'CAMBODIA', '26', '49', 'AS', '104.8', '11.7');
INSERT INTO `dxcc` VALUES ('XW', 'LAOS', '26', '49', 'AS', '102.6', '18');
INSERT INTO `dxcc` VALUES ('XX9', 'MACAU', '24', '44', 'AS', '113.6', '22.2');
INSERT INTO `dxcc` VALUES ('XZ', 'MYANMAR', '26', '49', 'AS', '96', '16.8');
INSERT INTO `dxcc` VALUES ('1Z', 'MYANMAR', '26', '49', 'AS', '96', '16.8');
INSERT INTO `dxcc` VALUES ('XY', 'MYANMAR', '26', '49', 'AS', '96', '16.8');
INSERT INTO `dxcc` VALUES ('YA', 'AFGHANISTAN', '21', '40', 'AS', '69.2', '34.4');
INSERT INTO `dxcc` VALUES ('T6', 'AFGHANISTAN', '21', '40', 'AS', '69.2', '34.4');
INSERT INTO `dxcc` VALUES ('YB', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7A', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7B', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7C', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7D', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7E', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7F', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7G', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7H', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('7I', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8A', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8B', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8C', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8D', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8E', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8F', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8G', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8H', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('8I', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('JZ', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('PK', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('PL', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('PM', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('PN', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('PO', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YC', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YD', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YE', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YF', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YG', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YH', 'INDONESIA', '28', '54', 'OC', '106.8', '-6.2');
INSERT INTO `dxcc` VALUES ('YI', 'IRAQ', '21', '39', 'AS', '44.5', '33');
INSERT INTO `dxcc` VALUES ('HN', 'IRAQ', '21', '39', 'AS', '44.5', '33');
INSERT INTO `dxcc` VALUES ('YJ', 'VANUATU', '32', '56', 'OC', '168.3', '-17.7');
INSERT INTO `dxcc` VALUES ('YK', 'SYRIA', '20', '39', 'AS', '36.3', '33.5');
INSERT INTO `dxcc` VALUES ('6C', 'SYRIA', '20', '39', 'AS', '36.3', '33.5');
INSERT INTO `dxcc` VALUES ('YL', 'LATVIA', '15', '29', 'EU', '24.1', '57');
INSERT INTO `dxcc` VALUES ('YN', 'NICARAGUA', '7', '11', 'NA', '-86', '12');
INSERT INTO `dxcc` VALUES ('H6', 'NICARAGUA', '7', '11', 'NA', '-86', '12');
INSERT INTO `dxcc` VALUES ('H7', 'NICARAGUA', '7', '11', 'NA', '-86', '12');
INSERT INTO `dxcc` VALUES ('HT', 'NICARAGUA', '7', '11', 'NA', '-86', '12');
INSERT INTO `dxcc` VALUES ('YO', 'ROMANIA', '20', '28', 'EU', '26.1', '44.4');
INSERT INTO `dxcc` VALUES ('YP', 'ROMANIA', '20', '28', 'EU', '26.1', '44.4');
INSERT INTO `dxcc` VALUES ('YQ', 'ROMANIA', '20', '28', 'EU', '26.1', '44.4');
INSERT INTO `dxcc` VALUES ('YR', 'ROMANIA', '20', '28', 'EU', '26.1', '44.4');
INSERT INTO `dxcc` VALUES ('YS', 'EL SALVADOR', '7', '11', 'NA', '-89.2', '13.7');
INSERT INTO `dxcc` VALUES ('HU', 'EL SALVADOR', '7', '11', 'NA', '-89.2', '13.7');
INSERT INTO `dxcc` VALUES ('YU', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('4N', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YT', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YZ', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/CT1FKN', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/HB4FG', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/IW0HEU', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/LZ1BJ', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/OH2R', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YU8/S56M', 'SERBIA', '15', '28', 'EU', '20.5', '44.9');
INSERT INTO `dxcc` VALUES ('YV', 'VENEZUELA', '9', '12', 'SA', '-67', '10.5');
INSERT INTO `dxcc` VALUES ('4M', 'VENEZUELA', '9', '12', 'SA', '-67', '10.5');
INSERT INTO `dxcc` VALUES ('YW', 'VENEZUELA', '9', '12', 'SA', '-67', '10.5');
INSERT INTO `dxcc` VALUES ('YX', 'VENEZUELA', '9', '12', 'SA', '-67', '10.5');
INSERT INTO `dxcc` VALUES ('YY', 'VENEZUELA', '9', '12', 'SA', '-67', '10.5');
INSERT INTO `dxcc` VALUES ('YV0', 'AVES I.', '8', '11', 'NA', '-63.7', '15.7');
INSERT INTO `dxcc` VALUES ('4M0', 'AVES I.', '8', '11', 'NA', '-63.7', '15.7');
INSERT INTO `dxcc` VALUES ('YW0', 'AVES I.', '8', '11', 'NA', '-63.7', '15.7');
INSERT INTO `dxcc` VALUES ('YX0', 'AVES I.', '8', '11', 'NA', '-63.7', '15.7');
INSERT INTO `dxcc` VALUES ('YY0', 'AVES I.', '8', '11', 'NA', '-63.7', '15.7');
INSERT INTO `dxcc` VALUES ('Z2', 'ZIMBABWE', '38', '53', 'AF', '31', '-17.8');
INSERT INTO `dxcc` VALUES ('Z3', 'MACEDONIA', '15', '28', 'EU', '21.4', '41.8');
INSERT INTO `dxcc` VALUES ('ZA', 'ALBANIA', '15', '28', 'EU', '19.8', '41.3');
INSERT INTO `dxcc` VALUES ('ZB', 'GIBRALTAR', '14', '37', 'EU', '-5.4', '36.1');
INSERT INTO `dxcc` VALUES ('ZG', 'GIBRALTAR', '14', '37', 'EU', '-5.4', '36.1');
INSERT INTO `dxcc` VALUES ('ZC4', 'UK BASES ON CYPRUS', '20', '39', 'AS', '33', '34.6');
INSERT INTO `dxcc` VALUES ('ZD7', 'SAINT HELENA', '36', '66', 'AF', '-5.9', '-16');
INSERT INTO `dxcc` VALUES ('ZD8', 'ASCENSION I.', '36', '66', 'AF', '-14.4', '-8');
INSERT INTO `dxcc` VALUES ('ZD9', 'TRISTAN DA CUNHA', '38', '66', 'AF', '-12.3', '-37.1');
INSERT INTO `dxcc` VALUES ('ZF', 'CAYMAN IS.', '8', '11', 'NA', '-81.2', '19.5');
INSERT INTO `dxcc` VALUES ('ZK2', 'NIUE', '32', '62', 'OC', '-169.9', '-19');
INSERT INTO `dxcc` VALUES ('ZK3', 'TOKELAU', '31', '62', 'OC', '-172.7', '-8.4');
INSERT INTO `dxcc` VALUES ('ZL', 'NEW ZEALAND', '32', '60', 'OC', '174.8', '-36.9');
INSERT INTO `dxcc` VALUES ('ZK', 'NEW ZEALAND', '32', '60', 'OC', '174.8', '-36.9');
INSERT INTO `dxcc` VALUES ('ZM', 'NEW ZEALAND', '32', '60', 'OC', '174.8', '-36.9');
INSERT INTO `dxcc` VALUES ('ZL7', 'CHATHAM IS.', '32', '60', 'OC', '-176.5', '-44');
INSERT INTO `dxcc` VALUES ('ZM7', 'CHATHAM IS.', '32', '60', 'OC', '-176.5', '-44');
INSERT INTO `dxcc` VALUES ('ZL8', 'KERMADEC IS.', '32', '60', 'OC', '-177.9', '-30');
INSERT INTO `dxcc` VALUES ('ZM8', 'KERMADEC IS.', '32', '60', 'OC', '-177.9', '-30');
INSERT INTO `dxcc` VALUES ('ZL9', 'AUCKLAND & CAMPBELL', '32', '60', 'OC', '166.5', '-50.7');
INSERT INTO `dxcc` VALUES ('ZM9', 'AUCKLAND & CAMPBELL', '32', '60', 'OC', '166.5', '-50.7');
INSERT INTO `dxcc` VALUES ('ZP', 'PARAGUAY', '11', '14', 'SA', '-57.7', '-25.3');
INSERT INTO `dxcc` VALUES ('ZS', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('H5', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('S4', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('S8', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('V9', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('ZR', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('ZT', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('ZU', 'SOUTH AFRICA', '38', '57', 'AF', '28.1', '-26.2');
INSERT INTO `dxcc` VALUES ('ZS8', 'MARION I.', '38', '57', 'AF', '37.8', '-46.8');
INSERT INTO `dxcc` VALUES ('ZR8', 'MARION I.', '38', '57', 'AF', '37.8', '-46.8');
INSERT INTO `dxcc` VALUES ('ZT8', 'MARION I.', '38', '57', 'AF', '37.8', '-46.8');
INSERT INTO `dxcc` VALUES ('ZU8', 'MARION I.', '38', '57', 'AF', '37.8', '-46.8');
INSERT INTO `dxcc` VALUES ('ZK1', 'SOUTH COOK IS.', '32', '62', 'OC', '-159.8', '-21.2');

-- ----------------------------
-- Table structure for dxccexceptions
-- ----------------------------
DROP TABLE IF EXISTS `dxccexceptions`;
CREATE TABLE `dxccexceptions` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `prefix` varchar(255) NOT NULL,
                                `name` varchar(255) NOT NULL,
                                `cqz` int(11) NOT NULL,
                                `ituz` int(11) NOT NULL,
                                `cont` varchar(255) NOT NULL,
                                `long` float NOT NULL,
                                `lat` float NOT NULL,
                                `start` datetime NOT NULL,
                                `end` datetime NOT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of dxccexceptions
-- ----------------------------
INSERT INTO `dxccexceptions` VALUES ('1', 'HF0QF', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('2', '3XDQZ/P', 'GUINEA', '35', '46', 'AF', '-13.7', '9.5', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('3', 'N8S', 'SWAINS ISLAND', '32', '62', 'OC', '-171.25', '-11.05', '2007-03-29 00:00:00', '2007-04-30 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('4', '4U1VIC', 'AUSTRIA', '15', '28', 'EU', '16.3', '48.2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('5', 'K7C', 'KURE I.', '31', '61', 'OC', '-178.4', '28.4', '2005-09-01 00:00:00', '2005-10-01 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('6', 'TX5C', 'CLIPPERTON IS.', '7', '10', 'NA', '-109.2', '10.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('7', 'VP6DX', 'DUCIE I.', '32', '63', 'OC', '-124.79', '-24.67', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('8', 'FO/HA9G', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('9', '9M6/N1UR', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('10', '9M4SDX', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('11', 'DX0JP', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('12', '9M0C', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('13', '9M0F', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('14', '9M0M', 'SPRATLY IS.', '26', '50', 'AS', '111.9', '8.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('15', 'D2AG/R', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('16', '3D2RR', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('17', '3D2VB', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('18', 'FO5RJ', 'FRENCH POLYNESIA', '32', '63', 'OC', '-149.5', '-17.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('19', '9UXEV', 'BURUNDI', '36', '52', 'AF', '29.3', '-3.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('20', 'FO/SP9FIH', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('21', 'FO0POM', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('22', 'A35WE', 'TONGA', '32', '62', 'OC', '-175.2', '-21.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('23', 'VP8GEO', 'SOUTH GEORGIA', '13', '73', 'SA', '-36.8', '-54.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('24', '3D2CT/CU', 'CONWAY REEF', '32', '56', 'OC', '174.4', '-21.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('25', '3D2DX', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('26', '3Y0PI', 'PETER I I.', '12', '72', 'SA', '-90.6', '-68.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('27', '8N1OGA', 'OGASAWARA', '27', '45', 'AS', '141', '27.5', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('28', 'FO0MIZ', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('29', 'FO0SUC', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('30', 'JG8NQJ/JD1', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('31', 'JL1KFR/JD1', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('32', 'JD1/JL1KFR', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('33', 'JD1BCK', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('34', 'JA9IAX/JD1', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('35', 'JD1BAT', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('36', 'KA2CC/JD1M', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('37', 'JD1BFQ/JD1', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('38', 'JH1MAO/JD1', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('39', 'JD1BIY', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('40', 'JD1/JD1BIC', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('41', 'JD1/JR8XXQ', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('42', 'JD1BMM', 'MINAMI TORISHIMA', '27', '90', 'OC', '154', '24.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('43', 'K3J', 'JOHNSTON I.', '31', '61', 'OC', '-169.5', '16.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('44', 'K5K', 'KINGMAN REEF', '31', '61', 'OC', '-162.4', '6.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('45', 'K8T', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('46', 'K8O', 'AMERICAN SAMOA', '32', '62', 'OC', '-170.8', '-14.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('47', 'N1V', 'NAVASSA I.', '8', '11', 'NA', '-75', '18.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('48', 'TO150', 'REUNION', '39', '53', 'AF', '55.6', '-21.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('49', 'VK0CW', 'HEARD I.', '39', '68', 'AF', '73.4', '-53', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('50', 'XR0Y/Z', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('51', 'ZK1XXP', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('52', 'ZK1HCC', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('53', 'ZK1ETW', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('54', 'ZK1KDN', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('55', 'ZK1AXU', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('56', 'ZK1XXC', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('57', 'E51PEN', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('58', 'E51PDX', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('59', 'ZK1AKX', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('60', 'ZK1QMA', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('61', 'ZK1NDK', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('62', 'ZK1NJC', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('63', 'E51QMA', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('64', 'ZK1NFK', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('65', 'ZK1NDS', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('66', 'E51TUG', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('67', 'ZK1BY', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('68', 'ZK1RS', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('69', 'ZK1AM', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('70', 'ZK1CF', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('71', 'ZK1XV', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('72', 'ZK1QC', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('73', 'ZK1CQ', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('74', 'ZK1XY', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('75', 'ZK1XO', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('76', 'ZK1OQ', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('77', 'ZK1SCQ', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('78', 'ZK1AAN', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('79', 'ZK1VVV', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('80', 'FO8AT', 'CLIPPERTON IS.', '7', '10', 'NA', '-109.2', '10.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('81', 'LU6Z', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('82', 'AY1ZA', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('83', 'VP8BXK', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('84', 'VP8CFM', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('85', 'VP8SIG', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('86', 'VP8SO', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('87', 'VP8CKC', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('88', 'VP8PL', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('89', 'VP8AJM', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('90', 'VP8ALD', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('91', 'VP8AOB', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('92', 'VP8BRT', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('93', 'TO5S', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('94', 'TX7LX', 'MAYOTTE', '39', '53', 'AF', '45.3', '-13', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('95', '4W6R', 'EAST TIMOR', '28', '54', 'OC', '125.5', '-8.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('96', 'TO5E', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('97', 'K7A', 'ALASKA', '1', '1', 'NA', '-150', '61.2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('98', 'GB0SI', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('99', 'VP8GQ', 'SOUTH ORKNEY', '13', '73', 'SA', '-45.5', '-60', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('100', '8J1RL', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('101', 'GB8LMI', 'JERSEY', '14', '27', 'EU', '-2.2', '49.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('102', 'VP8THU', 'SOUTH SANDWICH', '13', '73', 'SA', '-26.7', '-57', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('103', 'TO8S', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('104', 'GB2MOF', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('105', 'FJ/G3TXF', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('106', 'GB2OWM', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('107', 'GB2LT', 'SCOTLAND', '14', '27', 'EU', '-4.3', '55.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('108', 'GB5FI', 'WALES', '14', '27', 'EU', '-3.2', '51.5', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('109', 'GB0REL', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('110', 'GB2STI', 'NORTHERN IRELAND', '14', '27', 'EU', '-5.9', '54.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('111', 'FO/DJ7RJ', 'MARQUESAS IS.', '31', '63', 'OC', '-139.5', '-9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('112', 'VK0LD', 'MACQUARIE I.', '30', '60', 'OC', '158.8', '-54.7', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('113', 'VK9DWX', 'WILLIS I.', '30', '55', 'OC', '150', '-16.2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('114', 'ZK1SDE', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '2002-11-01 00:00:00', '2002-11-06 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('115', 'HF0POL', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('116', 'HF0POL/LH', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('117', 'TO5DX', 'SAINT BARTHELEMY', '8', '11', 'NA', '-62.9', '17.9', '2008-10-01 00:00:00', '2008-10-31 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('118', 'TO3R', 'REUNION', '39', '53', 'AF', '55.6', '-21.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('119', 'TO8Z', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('120', 'TO4X', 'FRENCH ST. MARTIN', '8', '11', 'NA', '-63.1', '18.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('121', 'VP8DIF', 'SOUTH GEORGIA', '13', '73', 'SA', '-36.8', '-54.3', '2008-10-20 00:00:00', '2008-11-10 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('122', 'VK9AA', 'COCOS-KEELING', '29', '54', 'OC', '96.8', '-12.2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('123', 'TO5X', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('124', '3Y0X', 'PETER I I.', '12', '72', 'SA', '-90.6', '-68.8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('125', 'R1ANF', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('126', 'R1ANF/p', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('127', 'CE0Y/N6NO', 'EASTER ISLAND', '12', '63', 'SA', '-109.4', '-27.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('128', 'TO2HI', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('129', 'E51QQQ', 'NORTH COOK IS.', '32', '62', 'OC', '-161', '-10.4', '2008-11-19 00:00:00', '2008-12-08 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('130', 'KC4AAA', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('131', 'HF0APAS', 'SOUTH SHETLAND', '13', '73', 'SA', '-58.3', '-62', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('132', 'KC4/K2ARB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('133', 'CE9/K2ARB', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('134', 'OP0LE', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('135', 'VK0BP', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('136', 'LU1ZA', 'ANTARCTICA', '13', '74', 'SA', '-64', '-65', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('137', 'FO/DL3GA', 'AUSTRAL IS.', '32', '63', 'OC', '-152', '-22.5', '2003-09-19 00:00:00', '2003-10-03 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('138', 'KH8SI', 'SWAINS ISLAND', '32', '62', 'OC', '-171.25', '-11.05', '2006-07-28 00:00:00', '2006-08-02 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('139', '3D2AG/R', 'ROTUMA', '32', '56', 'OC', '177.7', '-12.3', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('140', 'TO5A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('141', 'TO4IPA', 'REUNION', '39', '53', 'AF', '55.6', '-21.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('142', 'TO4E', 'JUAN DE NOVA & EUROPA', '39', '53', 'AF', '41.6', '-19.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('143', 'TO4T', 'GUADELOUPE', '8', '11', 'NA', '-61.7', '16', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('144', 'TO5M', 'ST. PIERRE & MIQUELON', '5', '9', 'NA', '-56', '46.7', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('145', 'TO5MM', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('146', 'TO7C', 'FRENCH GUIANA', '9', '12', 'SA', '-52.3', '4.9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('147', 'TO8T', 'FRANCE', '14', '27', 'EU', '2', '46', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('148', 'TO1A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('149', 'TO4A', 'MARTINIQUE', '8', '11', 'NA', '-61', '14.6', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('150', 'K5D', 'DESECHEO I.', '8', '11', 'NA', '-67.5', '18.3', '2009-02-07 00:00:00', '2009-02-28 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('151', 'JA1UT/GAZA', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1994-12-12 00:00:00', '1994-12-20 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('152', 'JA3UB/GAZA', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1994-12-12 00:00:00', '1994-12-20 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('153', 'JK1KHT/GAZA', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1994-12-12 00:00:00', '1994-12-20 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('154', 'JO3XEQ/GAZA', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1994-12-12 00:00:00', '1994-12-20 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('155', 'JA1UT/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('156', 'JA1UPA/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('157', 'JA3UB/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('158', 'JO3XEQ/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('159', 'JO3XER/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('160', 'JH7DHS/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('161', 'JR0CGJ/ZC6', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '1995-05-14 00:00:00', '1995-05-19 23:59:59');
INSERT INTO `dxccexceptions` VALUES ('162', 'F5PFP/GAZA', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('163', 'ZC6A', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('164', 'ZC6B', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `dxccexceptions` VALUES ('165', 'ZC6C', 'PALESTINE', '20', '39', 'AS', '35.1', '31.4', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
INSERT INTO `migrations` VALUES ('14');

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
                                        `COL_QSL_VIA` varchar(64) DEFAULT NULL,
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
  `prefix` varchar(10) NOT NULL,
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
  `call` varchar(10) DEFAULT NULL,
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
  `call` varchar(10) DEFAULT NULL,
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