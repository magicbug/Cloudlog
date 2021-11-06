<?php

class DXCC_Entities extends CI_Model {
/*
CREATE TABLE IF NOT EXISTS `dxcc_entities` (                                                                                                          
  `adif` smallint NOT NULL,
  `name` varchar(150) DEFAULT NULL,                                                                                                                   
  `prefix` varchar(10) NOT NULL,
  `cqz` smallint NOT NULL,                                                                                                                               
  `ituz` smallint NOT NULL,
  `cont` varchar(5) NOT NULL,                                                                                                                         
  `long` float NOT NULL,
  `lat` float NOT NULL,                                                                                                                               
  `end` date DEFAULT NULL,
  PRIMARY KEY (`adif`),                                                                                                                               
  KEY `adif` (`adif`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;          

*/
    function empty_table($table){
        $this->db->empty_table($table);
    }


}
