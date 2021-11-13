<?php

class DXCC_Exceptions extends CI_Model {
/*
CREATE TABLE IF NOT EXISTS `dxcc_exceptions` (
  `record` int NOT NULL,
  `call` varchar(10) NOT NULL,
  `entity` varchar(255) NOT NULL,
  `adif` smallint NOT NULL,
  `cqz` smallint NOT NULL,
  `cont` varchar(5),
  `long` float,
  `lat` float,
  `start` datetime,
  `end` datetime,
  PRIMARY KEY (`record`),
  KEY `record` (`record`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;   

*/
    function empty_table($table){
        $this->db->empty_table($table);
    }
}